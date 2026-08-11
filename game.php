<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
date_default_timezone_set('Asia/Tehran');

const TOKEN = '180102226:-SM4Hhb11bMZu-XYvLDffGQCWajfuRrmEbA';
const API   = 'https://tapi.bale.ai/bot' . TOKEN . '/';
const DATA_DIR = __DIR__ . '/data';

function ensureDataDir(): void
{
    if (!is_dir(DATA_DIR)) {
        @mkdir(DATA_DIR, 0775, true);
    }
}

function chatFile($chat_id): string
{
    ensureDataDir();
    return DATA_DIR . '/' . $chat_id . '.json';
}

function defaultState(): array
{
    return [
        'enabled' => true,
        'started' => false,
        'players' => [],
        'turn' => 0,
        'last_bot_message_id' => null,
    ];
}

function loadState($chat_id): array
{
    $file = chatFile($chat_id);
    if (!is_file($file)) {
        return defaultState();
    }

    $raw = file_get_contents($file);
    $data = json_decode($raw ?: '', true);
    if (!is_array($data)) {
        $data = [];
    }

    return array_merge(defaultState(), $data);
}

function saveState($chat_id, array $state): void
{
    ensureDataDir();
    file_put_contents(
        chatFile($chat_id),
        json_encode($state, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        LOCK_EX
    );
}

function resetGameState($chat_id): array
{
    $state = defaultState();
    saveState($chat_id, $state);
    return $state;
}

function apiRequest(string $method, array $data = []): ?array
{
    $ch = curl_init(API . $method);
    if ($ch === false) {
        error_log('curl_init failed for method: ' . $method);
        return null;
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
    ]);

    $res = curl_exec($ch);
    if ($res === false) {
        error_log('cURL error on ' . $method . ': ' . curl_error($ch));
        curl_close($ch);
        return null;
    }

    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($res, true);
    if (!is_array($decoded)) {
        error_log('Invalid JSON from API on ' . $method . ': ' . $res);
        return null;
    }

    if ($http >= 400) {
        error_log('HTTP ' . $http . ' from API on ' . $method . ': ' . $res);
    }

    return $decoded;
}

function inGroup($chat_type): bool
{
    return in_array($chat_type, ['group', 'supergroup'], true);
}

function getUserName(array $user): string
{
    $first = trim((string)($user['first_name'] ?? ''));
    $last  = trim((string)($user['last_name'] ?? ''));
    $name  = trim($first . ' ' . $last);

    if ($name !== '') {
        return $name;
    }

    if (!empty($user['username'])) {
        return '@' . $user['username'];
    }

    return 'بدون نام';
}

function isAdmin($chat_id, int $user_id): bool
{
    $res = apiRequest('getChatMember', [
        'chat_id' => $chat_id,
        'user_id' => $user_id,
    ]);

    $status = (string)($res['result']['status'] ?? '');
    return in_array($status, ['administrator', 'creator'], true);
}

function answerCallback(string $callback_id, string $text = ''): ?array
{
    return apiRequest('answerCallbackQuery', [
        'callback_query_id' => $callback_id,
        'text' => $text,
        'show_alert' => false,
    ]);
}

function deleteLastBotMessage($chat_id, array &$state): void
{
    if (!empty($state['last_bot_message_id'])) {
        apiRequest('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $state['last_bot_message_id'],
        ]);
        $state['last_bot_message_id'] = null;
        saveState($chat_id, $state);
    }
}

function privateHelpText(): string
{
    return "سلام دوست عزیز! خوش آمدید 🌹\n\n"
        . "این ربات برای بازی گروهی «دوست‌های صمیمی» طراحی شده است و در چت شخصی (پیوی) قابل بازی نیست.\n\n"
        . "🎮 **چگونه بازی را شروع کنیم؟**\n"
        . "1. ابتدا ربات را به گروه خود اضافه کنید.\n"
        . "2. حتماً ربات را در گروه **مدیر (ادمین کل)** کنید تا بتواند پیام‌ها را دریافت و مدیریت کند.\n"
        . "3. در گروه دستور `/start` یا `/help` را بفرستید تا دکمه‌های بازی و راهنما برای شما نمایش داده شوند.\n\n"
        . "موفق باشید\n"
        . "سجاد برزویی\n"
        . "@sabkezendegi14";
}

function helpText(): string
{
    return "سلام دوست های عزیزم🌹\n\n"
        . "بازی به این شکل طراحی شده که همه بچه ها یک ویژگی مثبتی 💚 از دوست خوبشون رو میگن\n\n"
        . "و خود شخص هم یک نقطه ضعف خودش 🌹 صادقانه رو میگه\n\n"
        . "اینطوری باعث میشه که هم فرد بدونه چقد از نگاه دوستاش ارزشمنده 😍 هم دچار غرور نمیشه و هممون به تعادل شخصیتی برسیم و دوستی هامونم قوی تر بشه 😍\n\n"
        . "برای شروع فقط کافیه روی دکمه منم بازی بزنید\n\n"
        . "موفق باشید\n"
        . "سجاد برزویی\n"
        . "@sabkezendegi14\n\n"
        . "🎮 راهنمای بازی 🎮 «دوست های صمیمی»:\n\n"
        . "1) همه اعضا با دکمه منم بازی 😍 وارد بازی می شوند\n"
        . "2) مدیر گروه با دکمه شروع بازی 🎮 بازی را آغاز می کند\n"
        . "3) به ترتیب دکمه دوست بعدی 🌹 را بزنید";
}

function playersText(array $state): string
{
    if (empty($state['players'])) {
        return "هنوز کسی وارد بازی نشده.\n\nبرای راهنما:\n/help";
    }

    $txt = "بازیکنان بازی:\n";
    foreach ($state['players'] as $i => $p) {
        $txt .= ($i + 1) . '. ' . ($p['name'] ?? 'بدون نام') . "\n";
    }

    $txt .= "\nتعداد: " . count($state['players']) . " نفر";
    return $txt;
}

function turnText(array $state): string
{
    if (empty($state['players'])) {
        return "هیچ بازیکنی در بازی نیست.";
    }

    $count = count($state['players']);
    $turn = ((int)($state['turn'] ?? 0)) % $count;
    $current = (string)($state['players'][$turn]['name'] ?? 'بدون نام');

    return "نوبت: {$current}\n\n"
        . "💚 دوستای خوب {$current}:\n"
        . "یک ویژگی مثبت از دوست خوبتون بگید 😍\n\n"
        . "🪞 {$current} عزیز:\n"
        . "خودت یک نقطه ضعفت رو که اذیتت میکنه صادقانه بگو 🌹\n\n"
        . "برای رفتن به نفر بعدی دکمه «دوست بعدی 🌹» را بزنید.\n\n"
        . "برای دیدن بازیکنان:\n/players";
}

function inlineButtons(array $state): array
{
    $buttons = [];

    if (empty($state['started'])) {
        $buttons[] = [
            ['text' => 'منم بازی 😍', 'callback_data' => 'join'],
            ['text' => 'شروع بازی 🎮', 'callback_data' => 'start_game'],
        ];
    } else {
        $buttons[] = [
            ['text' => 'دوست بعدی 🌹', 'callback_data' => 'next'],
            ['text' => 'پایان بازی 🛑', 'callback_data' => 'end_game'],
        ];
    }

    $buttons[] = [
        ['text' => 'روشن ✅', 'callback_data' => 'enable'],
        ['text' => 'خاموش ⛔', 'callback_data' => 'disable'],
    ];

    return $buttons;
}

function sendTrackedMessage($chat_id, string $text, array &$state): ?array
{
    deleteLastBotMessage($chat_id, $state);

    $res = apiRequest('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => [
            'inline_keyboard' => inlineButtons($state),
        ],
    ]);

    if (isset($res['result']['message_id'])) {
        $state['last_bot_message_id'] = $res['result']['message_id'];
        saveState($chat_id, $state);
    }

    return $res;
}

function addPlayer(array $state, array $user): array
{
    foreach ($state['players'] as $p) {
        if (($p['id'] ?? null) == ($user['id'] ?? null)) {
            return [false, $state];
        }
    }

    $state['players'][] = [
        'id' => $user['id'] ?? 0,
        'name' => getUserName($user),
    ];

    return [true, $state];
}

function denyAdminAction($callback_id = null, $chat_id = null): void
{
    if ($callback_id) {
        answerCallback($callback_id, 'فقط مدیر گروه می‌تواند این کار را انجام دهد.');
    } elseif ($chat_id !== null) {
        apiRequest('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'فقط مدیر گروه می‌تواند این کار را انجام دهد.',
        ]);
    }
}

// Auto Webhook Setup via GET Request
if (isset($_GET['set_webhook'])) {
    header('Content-Type: text/html; charset=utf-8');

    // Determine the current URL of the script automatically
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    // Strip query string
    $uri_clean = explode('?', $uri)[0];
    $script_url = $protocol . $host . $uri_clean;

    echo "<h2>تنظیم خودکار وب‌هووک ربات بله (Auto Webhook Setup)</h2>";
    echo "<p>در حال تلاش برای تنظیم وب‌هووک روی آدرس زیر:</p>";
    echo "<code>" . htmlspecialchars($script_url) . "</code><br><br>";

    $res = apiRequest('setWebhook', [
        'url' => $script_url
    ]);

    if ($res && isset($res['ok']) && $res['ok'] === true) {
        echo "<h3 style='color: green;'>✅ وب‌هووک با موفقیت تنظیم شد!</h3>";
        echo "<pre>" . htmlspecialchars(json_encode($res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) . "</pre>";
    } else {
        echo "<h3 style='color: red;'>❌ خطا در تنظیم وب‌هووک!</h3>";
        echo "<p>پاسخ دریافتی از سرور بله:</p>";
        echo "<pre>" . htmlspecialchars(json_encode($res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) . "</pre>";
        echo "<p>نکته: مطمئن شوید توکن ربات (TOKEN) در فایل <code>game.php</code> درست وارد شده است و آدرس سایت شما دارای گواهینامه SSL (https) معتبر می‌باشد.</p>";
    }
    exit;
}

$raw = file_get_contents('php://input');
$UPDATE = json_decode($raw ?: '', true);

if (!is_array($UPDATE)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Bale bot is running';
    exit;
}

$message = $UPDATE['message'] ?? null;
$callback = $UPDATE['callback_query'] ?? null;

$chat_id = null;
$chat_type = null;
$user = [];
$user_id = 0;
$text = null;
$callback_id = null;
$data = null;

if (is_array($message)) {
    $chat_id = $message['chat']['id'] ?? null;
    $chat_type = $message['chat']['type'] ?? '';
    $user = $message['from'] ?? [];
    $user_id = (int)($user['id'] ?? 0);
    $text = trim((string)($message['text'] ?? ''));
} elseif (is_array($callback)) {
    $chat_id = $callback['message']['chat']['id'] ?? null;
    $chat_type = $callback['message']['chat']['type'] ?? '';
    $user = $callback['from'] ?? [];
    $user_id = (int)($user['id'] ?? 0);
    $callback_id = (string)($callback['id'] ?? '');
    $data = (string)($callback['data'] ?? '');
} else {
    exit;
}

if ($chat_id === null) {
    exit;
}

if (!inGroup($chat_type)) {
    apiRequest('sendMessage', [
        'chat_id' => $chat_id,
        'text' => privateHelpText(),
    ]);
    exit;
}

$state = loadState($chat_id);
$is_admin = isAdmin($chat_id, $user_id);

if ($callback_id !== null && $callback_id !== '') {
    if ($data === 'join') {
        $text = '/join';
    } elseif ($data === 'next') {
        $text = '/next';
    } elseif ($data === 'start_game') {
        $text = '/startgame';
    } elseif ($data === 'end_game') {
        $text = '/endgame';
    } elseif ($data === 'enable') {
        $text = '/enable';
    } elseif ($data === 'disable') {
        $text = '/disable';
    }
}

if (is_string($text) && $text !== '') {
    $text = trim($text);
    if (preg_match('/^\/[A-Za-z0-9_]+(?:@\w+)?$/', $text)) {
        $text = strtolower(preg_replace('/@\w+$/', '', $text));
    }

    if ($text === 'منم بازی 😍') $text = '/join';
    if ($text === 'دوست بعدی 🌹') $text = '/next';
    if ($text === 'شروع بازی 🎮') $text = '/startgame';
    if ($text === 'پایان بازی 🛑') $text = '/endgame';
    if ($text === 'روشن ✅') $text = '/enable';
    if ($text === 'خاموش ⛔') $text = '/disable';
}

if (!$state['enabled'] && !in_array($text, ['/enable', '/start', '/help'], true)) {
    if ($callback_id) {
        answerCallback($callback_id, 'ربات خاموش است.');
    }
    exit;
}

if ($text === '/start' || $text === '/help') {
    apiRequest('sendMessage', [
        'chat_id' => $chat_id,
        'text' => helpText(),
        'reply_markup' => [
            'inline_keyboard' => inlineButtons($state),
        ],
    ]);
    exit;
}

if ($text === '/enable') {
    if (!$is_admin) {
        denyAdminAction($callback_id, $chat_id);
        exit;
    }

    $state['enabled'] = true;
    saveState($chat_id, $state);

    if ($callback_id) {
        answerCallback($callback_id, 'ربات روشن شد.');
    }
    sendTrackedMessage($chat_id, 'ربات روشن شد ✅', $state);
    exit;
}

if ($text === '/disable') {
    if (!$is_admin) {
        denyAdminAction($callback_id, $chat_id);
        exit;
    }

    $state['enabled'] = false;
    saveState($chat_id, $state);

    if ($callback_id) {
        answerCallback($callback_id, 'ربات خاموش شد.');
    }
    sendTrackedMessage($chat_id, 'ربات خاموش شد ⛔', $state);
    exit;
}

if ($text === '/join') {
    if (!empty($state['started'])) {
        sendTrackedMessage($chat_id, 'بازی شروع شده و دیگر امکان ورود بازیکن جدید وجود ندارد.', $state);
        exit;
    }

    [$added, $state] = addPlayer($state, $user);
    saveState($chat_id, $state);

    if ($added) {
        $msg = getUserName($user) . " وارد بازی شد 😍\n\nتعداد بازیکنان: " . count($state['players']);
    } else {
        $msg = getUserName($user) . ' عزیز، شما قبلاً وارد بازی شده‌اید 🌹';
    }

    if ($callback_id) {
        answerCallback($callback_id, $added ? 'وارد بازی شدی 😍' : 'قبلاً وارد بازی شدی 🌹');
    }

    sendTrackedMessage($chat_id, $msg, $state);
    exit;
}

if ($text === '/players') {
    sendTrackedMessage($chat_id, playersText($state), $state);
    exit;
}

if ($text === '/startgame') {
    if (!$is_admin) {
        denyAdminAction($callback_id, $chat_id);
        exit;
    }

    if (!empty($state['started'])) {
        sendTrackedMessage($chat_id, 'بازی از قبل شروع شده است.', $state);
        exit;
    }

    if (count($state['players']) === 0) {
        sendTrackedMessage($chat_id, "هیچ بازیکنی وارد بازی نشده.\n\nابتدا بازیکن‌ها با دکمه «منم بازی 😍» وارد شوند.", $state);
        exit;
    }

    $state['started'] = true;
    $state['turn'] = 0;
    saveState($chat_id, $state);

    if ($callback_id) {
        answerCallback($callback_id, 'بازی شروع شد.');
    }
    sendTrackedMessage($chat_id, turnText($state), $state);
    exit;
}

if ($text === '/next') {
    if (!$is_admin) {
        denyAdminAction($callback_id, $chat_id);
        exit;
    }

    if (empty($state['started'])) {
        sendTrackedMessage($chat_id, "بازی هنوز شروع نشده.\n\nابتدا دکمه «شروع بازی 🎮» را بزنید.", $state);
        exit;
    }

    if (count($state['players']) === 0) {
        sendTrackedMessage($chat_id, 'هیچ بازیکنی در بازی نیست.', $state);
        exit;
    }

    $state['turn'] = ((int)$state['turn'] + 1) % count($state['players']);
    saveState($chat_id, $state);

    if ($callback_id) {
        answerCallback($callback_id, 'نفر بعدی انتخاب شد.');
    }
    sendTrackedMessage($chat_id, turnText($state), $state);
    exit;
}

if ($text === '/endgame') {
    if (!$is_admin) {
        denyAdminAction($callback_id, $chat_id);
        exit;
    }

    if (empty($state['started']) && empty($state['players'])) {
        sendTrackedMessage($chat_id, 'بازی فعالی وجود ندارد.', $state);
        exit;
    }

    deleteLastBotMessage($chat_id, $state);
    $state = resetGameState($chat_id);

    if ($callback_id) {
        answerCallback($callback_id, 'بازی پایان یافت.');
    }

    $res = apiRequest('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "بازی پایان یافت 🛑\n\nهمه بازیکنان حذف شدند و ربات به حالت اولیه برگشت.\n\nبرای راهنما:\n/help",
        'reply_markup' => [
            'inline_keyboard' => inlineButtons($state),
        ],
    ]);

    if (isset($res['result']['message_id'])) {
        $state['last_bot_message_id'] = $res['result']['message_id'];
        saveState($chat_id, $state);
    }
    exit;
}

exit;