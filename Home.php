<?php
/**
 * Shortcode for Academy Borzouie Custom Homepage
 * Shortcode usage: [bz_home_page]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'bz_home_page', 'bz_render_home_page_shortcode' );

function bz_render_home_page_shortcode() {
    ob_start();
    ?>
    <!-- Vazirmatn Font -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />

    <style>
        /* ========== RESET & VARIABLES ========== */
        #bz-luxury-home, #bz-luxury-home *, #bz-preloader, #bz-preloader * {
            font-family: 'Vazirmatn', sans-serif !important;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        :root {
            --bz-bg: #F8F7F4;
            --bz-surface: #FFFFFF;
            --bz-primary: #0B1C2E;
            --bz-accent: #EF7215;
            --bz-accent-soft: rgba(239, 114, 21, 0.08);
            --bz-text: #2C3A4B;
            --bz-muted: #7B8A9B;
            --bz-border: rgba(11, 28, 46, 0.06);
            --bz-shadow-sm: 0 2px 12px rgba(0,0,0,0.02);
            --bz-shadow-md: 0 10px 28px rgba(0,0,0,0.06);
            --bz-shadow-lg: 0 20px 40px rgba(0,0,0,0.08);
            --bz-radius: 16px;
            --bz-transition: all 0.35s cubic-bezier(0.25,0.8,0.25,1);
            --section-gap: 30px;
            --soft-transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
        }

        #bz-luxury-home {
            background-color: var(--bz-bg);
            background-image: radial-gradient(circle at 2px 2px, rgba(0,0,0,0.02) 1px, transparent 0);
            background-size: 32px 32px;
            color: var(--bz-text);
            line-height: 1.8;
            overflow-x: hidden;
            direction: rtl;
            width: 100%;
            max-width: 100vw;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ========== PRELOADER ========== */
        #bz-preloader {
            position: fixed;
            inset: 0;
            background: var(--bz-bg);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.7s, visibility 0.7s;
        }
        .bz-loader-circle {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: conic-gradient(var(--bz-accent), var(--bz-primary), var(--bz-accent));
            animation: bz-spin 1.4s linear infinite;
            position: relative;
            margin-bottom: 28px;
        }
        .bz-loader-circle::before {
            content: '';
            position: absolute;
            inset: 4px;
            background: var(--bz-bg);
            border-radius: 50%;
        }
        .bz-loader-text {
            color: var(--bz-primary);
            font-size: 15px;
            font-weight: 500;
            animation: bz-pulse 2s infinite;
        }
        @keyframes bz-spin { to { transform: rotate(360deg); } }
        @keyframes bz-pulse { 0%,100%{opacity:0.4;} 50%{opacity:1;} }

        /* ========== COMMON ========== */
        .bz-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 35px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .bz-section-spacer {
            height: var(--section-gap);
            width: 100%;
        }
        .bz-section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            color: var(--bz-primary);
            margin-bottom: 35px;
            letter-spacing: -0.5px;
        }
        .bz-section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--bz-accent), rgba(239,114,21,0.3));
            margin: 24px auto 0;
            border-radius: 2px;
        }
        .bz-img-full { width: 100%; height: auto; display: block; }
        .bz-banner-wrapper { width: 100%; max-width: 100%; line-height: 0; overflow: hidden; }

        /* ========== MODERN BANNER SLIDER ========== */
        .bz-slider-wrapper {
            position: relative;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            z-index: 1;
        }

        .bz-slider-container {
            position: relative;
            width: 100%;
            border-radius: var(--bz-radius);
            overflow: hidden;
            box-shadow: var(--bz-shadow-md), 0 0 0 1px rgba(255, 255, 255, 0.5);
            aspect-ratio: 1024 / 570;
            background: #f1f5f9;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .bz-slides-track {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.55s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .bz-slide {
            min-width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            flex-shrink: 0;
            position: relative;
            display: block;
            text-decoration: none !important;
        }

        .bz-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--bz-surface);
            border: none;
            cursor: pointer;
            color: #374151;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
            z-index: 10;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            outline: none;
            -webkit-tap-highlight-color: transparent;
        }
        .bz-nav-btn:hover {
            background: #f8fafc;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.18);
            color: var(--bz-accent);
            transform: translateY(-50%) scale(1.07);
        }
        .bz-nav-btn:active {
            transform: translateY(-50%) scale(0.94);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.1s ease;
        }
        .bz-nav-btn.prev {
            right: 15px;
        }
        .bz-nav-btn.next {
            left: 15px;
        }
        .bz-nav-btn svg {
            width: 20px;
            height: 20px;
            pointer-events: none;
        }

        .bz-dots-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
            z-index: 5;
        }
        .bz-slider-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.35s ease;
            border: none;
            outline: none;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }
        .bz-slider-dot.active {
            background: var(--bz-accent);
            width: 30px;
            border-radius: 20px;
            box-shadow: 0 0 0 4px rgba(239, 114, 21, 0.2);
        }
        .bz-slider-dot:hover:not(.active) {
            background: #94a3b8;
        }

        .bz-counter-badge {
            position: absolute;
            bottom: 14px;
            left: 14px;
            z-index: 10;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .bz-slider-container {
                aspect-ratio: 1024 / 570;
                border-radius: 16px;
            }
            .bz-nav-btn {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }
            .bz-nav-btn.prev {
                right: 8px;
            }
            .bz-nav-btn.next {
                left: 8px;
            }
            .bz-nav-btn svg {
                width: 16px;
                height: 16px;
            }
            .bz-counter-badge {
                bottom: 8px;
                left: 8px;
                font-size: 10px;
                padding: 4px 10px;
            }
        }
        @media (max-width: 400px) {
            .bz-slider-container {
                aspect-ratio: 1024 / 570;
                border-radius: 14px;
            }
            .bz-nav-btn {
                width: 30px;
                height: 30px;
            }
            .bz-nav-btn.prev {
                right: 4px;
            }
            .bz-nav-btn.next {
                left: 4px;
            }
        }

        /* ========== APPS GRID ========== */
        .bz-apps-wrapper {
            width: 100%;
            background-color: rgba(11,28,46,0.02);
            border-top: 1px solid rgba(11,28,46,0.05);
            border-bottom: 1px solid rgba(11,28,46,0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .bz-apps-grid {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
            width: 100%;
            margin: 0 auto;
        }
        .bz-app-gate {
            flex: 1 1 calc(25% - 24px);
            max-width: 270px;
            min-width: 220px;
            background: var(--bz-surface);
            border: 1px solid var(--bz-border);
            padding: 30px 20px;
            text-align: center;
            border-radius: var(--bz-radius);
            transition: var(--bz-transition);
            box-shadow: var(--bz-shadow-sm);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            isolation: isolate;
        }
        .bz-app-gate::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 80px; height: 80px;
            background: var(--bz-accent-soft);
            border-radius: 50%;
            transition: transform 0.6s;
            transform: scale(0);
        }
        .bz-app-gate:hover::before {
            transform: scale(3.5);
        }
        .bz-app-gate:hover {
            transform: translateY(-5px);
            border-color: var(--bz-accent);
            box-shadow: var(--bz-shadow-md);
        }
        .bz-app-icon-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            z-index: 1;
        }
        .bz-app-icon { width: 64px; height: 64px; position: relative; z-index: 2; }
        .bz-app-title { color: var(--bz-primary); font-weight: 700; font-size: 15px; z-index: 1; }

        /* ====== BLUR OVERLAY ====== */
        .bz-loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: var(--bz-radius);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: var(--soft-transition);
        }
        .bz-app-gate.loading .bz-loading-overlay { opacity: 1; visibility: visible; }
        .bz-loading-spinner {
            width: 48px; height: 48px;
            border: 3px solid rgba(239,114,21,0.15);
            border-top-color: var(--bz-accent);
            border-radius: 50%;
            animation: bz-spin 0.8s linear infinite;
        }
        .bz-loading-text { color: var(--bz-primary); font-size: 15px; font-weight: 700; text-align: center; line-height: 1.5; }

        /* ========== HORIZONTAL ARTICLES SCROLL (REFINED ELONGATED) ========== */
        #bz-luxury-home .article-horizontal-section {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            direction: rtl;
            padding: 0;
        }

        #bz-luxury-home .article-h-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding: 0 5px;
        }

        #bz-luxury-home .article-h-title-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #bz-luxury-home .article-h-icon {
            font-size: 22px;
        }

        #bz-luxury-home .article-h-heading {
            margin: 0;
            font-size: 1.2rem;
            color: #0B1C2E;
            font-weight: 700;
            line-height: 1.2;
        }

        #bz-luxury-home .article-h-scroll {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            gap: 18px;
            padding: 16px 16px 20px 16px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #EF7215 #f1f1f1;
            align-items: stretch;
        }

        /* اسپيسر انتهاي اسکرول */
        #bz-luxury-home .article-h-scroll::after {
            content: '';
            flex: 0 0 1px;
            width: 1px;
        }

        #bz-luxury-home .article-h-scroll::-webkit-scrollbar {
            height: 5px;
        }
        #bz-luxury-home .article-h-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        #bz-luxury-home .article-h-scroll::-webkit-scrollbar-thumb {
            background: #EF7215;
            border-radius: 10px;
        }

        /* کارت‌ها */
        #bz-luxury-home .article-h-item {
            flex: 0 0 auto;
            width: 280px;
            min-height: 385px;
            background: #ffffff;
            border: 1px solid rgba(11, 28, 46, 0.1);
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none !important;
            transition: all 0.35s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
            position: relative;
        }

        #bz-luxury-home .article-h-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.09);
            border-color: #EF7215;
        }

        /* عکس کارت - نسبت ۱:۱ و وسط‌چین دقیق */
        #bz-luxury-home .article-h-image {
            width: calc(100% - 40px);
            aspect-ratio: 1 / 1;
            height: auto;
            margin: 20px auto 14px auto; /* حاشیه اتوماتیک در چپ و راست برای وسط قرار گرفتن */
            border-radius: 14px;
            overflow: hidden;
            background: #f8f9fa;
            border: 1px solid rgba(11, 28, 46, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        #bz-luxury-home .article-h-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            border-radius: 12px;
            transition: transform 0.4s ease;
        }

        #bz-luxury-home .article-h-item:hover .article-h-image img {
            transform: scale(1.05);
        }

        /* بدنه متني با فاصله‌گذاري متناسب و وسط‌چین */
        #bz-luxury-home .article-h-body {
            padding: 0 20px 14px 20px; 
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1 1 auto;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        #bz-luxury-home .article-h-title {
            margin: 0;
            font-size: 0.98rem;
            color: #0B1C2E;
            font-weight: 700;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }

        #bz-luxury-home .article-h-item:hover .article-h-title {
            color: #EF7215;
        }

        #bz-luxury-home .article-h-excerpt {
            margin: 0;
            font-size: 0.82rem;
            color: #7B8A9B;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* فوتر دکمه چسبيده به پايين و وسط‌چین */
        #bz-luxury-home .article-h-footer {
            width: 100%;
            border-top: 1px dashed rgba(11, 28, 46, 0.08);
            padding: 12px 20px 16px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            margin-top: auto;
        }

        #bz-luxury-home .article-h-btn {
            color: #EF7215;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.2s ease;
            margin: 0;
            padding: 0;
        }

        #bz-luxury-home .article-h-item:hover .article-h-btn {
            color: #EA580C;
            transform: translateY(-2px);
        }

        /* ريسپانسيو */
        @media (max-width: 768px) {
            #bz-luxury-home .article-h-item {
                width: 250px;
                min-height: 350px;
            }
            #bz-luxury-home .article-h-image {
                width: calc(100% - 32px);
                aspect-ratio: 1 / 1;
                height: auto;
                margin: 16px auto 12px auto;
            }
            #bz-luxury-home .article-h-body {
                padding: 0 16px 12px 16px;
            }
            #bz-luxury-home .article-h-footer {
                padding: 10px 16px 14px 16px;
            }
        }

        /* ========== DUAL BANNERS ========== */
        .bz-dual-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 28px; width: 100%; }
        .bz-banner-clean {
            display: block; border-radius: var(--bz-radius); overflow: hidden;
            transition: var(--bz-transition); box-shadow: var(--bz-shadow-sm);
        }
        .bz-banner-clean:hover { transform: translateY(-5px); box-shadow: var(--bz-shadow-lg); }

        /* ========== REVEAL ANIMATION ========== */
        .bz-reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.7s, transform 0.7s; }
        .bz-reveal.visible { opacity: 1; transform: translateY(0); }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) { .bz-container { padding: 30px 18px; } }
        @media (max-width: 768px) {
            .bz-container { padding: 25px 16px; }
            .bz-apps-grid { gap: 16px; }
            .bz-app-gate { flex: 1 1 calc(50% - 16px); min-width: 140px; }
            .bz-dual-grid { grid-template-columns: 1fr; gap: 20px; }
            .bz-section-title { font-size: 1.6rem; }
        }
    </style>

    <!-- PRELOADER -->
    <div id="bz-preloader">
        <div class="bz-loader-circle"></div>
        <div class="bz-loader-text">در حال آماده‌سازي فضاي اختصاصي شما...</div>
    </div>

    <!-- MAIN HOMEPAGE CONTENT -->
    <main id="bz-luxury-home">
        <!-- Hero Banner -->
        <div class="bz-banner-wrapper">
            <img src="https://borzouie.com/wp-content/uploads/2026/01/%D8%A8%D9%86%D8%B1-%D8%A7%D8%B5%D9%84%DB%8C-%D8%B3%D8%A7%DB%8C%D8%AA-%D8%B3%D8%A8%DA%A9-%D8%B2%D9%86%D8%AF%DA%AF%DB%8C_result-1536x624.webp" alt="آکادمي برزويي" class="bz-img-full" fetchpriority="high" decoding="async">
        </div>

        <div class="bz-section-spacer"></div>

        <!-- Free Courses Slider -->
        <div class="bz-container bz-reveal">
            <h2 class="bz-section-title">دوره‌هاي رايگان آکادمي</h2>
            
            <div class="bz-slider-wrapper">
                <div class="bz-slider-container" id="sliderContainer">
                    <div class="bz-slides-track" id="slidesTrack">
                        <a href="https://borzouie.com/product/mezajshenasi/" class="bz-slide" style="background-image: url('https://borzouie.com/wp-content/uploads/2026/02/free2.webp');"></a>
                        <a href="https://borzouie.com/product/sabkezendegi1/" class="bz-slide" style="background-image: url('https://borzouie.com/wp-content/uploads/2026/02/free1.webp');"></a>
                    </div>

                    <button type="button" class="bz-nav-btn prev" id="prevBtn" aria-label="اسلايد قبلي" title="قبلي">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                    <button type="button" class="bz-nav-btn next" id="nextBtn" aria-label="اسلايد بعدي" title="بعدي">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>

                    <span class="bz-counter-badge" id="counterBadge">1 / 2</span>
                </div>

                <div class="bz-dots-container" id="dotsContainer">
                    <button type="button" class="bz-slider-dot active" data-index="0" aria-label="اسلايد ۱"></button>
                    <button type="button" class="bz-slider-dot" data-index="1" aria-label="اسلايد ۲"></button>
                </div>
            </div>
        </div>

        <div class="bz-section-spacer"></div>

        <!-- Application Downloads -->
        <div class="bz-apps-wrapper">
            <div class="bz-container bz-reveal" style="padding-top: 35px; padding-bottom: 35px;">
                <h2 class="bz-section-title">دريافت اپليکيشن‌هاي آکادمي</h2>
                <div class="bz-apps-grid" id="appsGrid">
                    <div class="bz-app-gate" data-href="https://my.uupload.ir/dl/mbAvW9EN" data-type="download">
                        <div class="bz-app-icon-wrap">
                            <img src="https://borzouie.com/wp-content/uploads/2026/04/win.png" alt="ويندوز" class="bz-app-icon" loading="lazy" decoding="async">
                        </div>
                        <span class="bz-app-title">نسخه ويندوز</span>
                        <div class="bz-loading-overlay">
                            <div class="bz-loading-spinner"></div>
                            <div class="bz-loading-text">آماده‌سازي دانلود...</div>
                        </div>
                    </div>
                    <div class="bz-app-gate" data-href="https://my.uupload.ir/dl/NdQpmaN0" data-type="download">
                        <div class="bz-app-icon-wrap">
                            <img src="https://borzouie.com/wp-content/uploads/2026/04/mac.png" alt="مک" class="bz-app-icon" loading="lazy" decoding="async">
                        </div>
                        <span class="bz-app-title">نسخه مک‌بوک</span>
                        <div class="bz-loading-overlay">
                            <div class="bz-loading-spinner"></div>
                            <div class="bz-loading-text">آماده‌سازي دانلود...</div>
                        </div>
                    </div>
                    <div class="bz-app-gate" data-href="https://my.uupload.ir/dl/kjXEKeRX" data-type="download">
                        <div class="bz-app-icon-wrap">
                            <img src="https://borzouie.com/wp-content/uploads/2026/04/android.png" alt="اندرويد" class="bz-app-icon" loading="lazy" decoding="async">
                        </div>
                        <span class="bz-app-title">نسخه اندرويد</span>
                        <div class="bz-loading-overlay">
                            <div class="bz-loading-spinner"></div>
                            <div class="bz-loading-text">آماده‌سازي دانلود...</div>
                        </div>
                    </div>
                    <div class="bz-app-gate" data-href="https://app.borzouie.com" data-type="web">
                        <div class="bz-app-icon-wrap">
                            <img src="https://borzouie.com/wp-content/uploads/2026/04/ios.png" alt="تحت وب" class="bz-app-icon" loading="lazy" decoding="async">
                        </div>
                        <span class="bz-app-title">نسخه وب / iOS</span>
                        <div class="bz-loading-overlay">
                            <div class="bz-loading-spinner"></div>
                            <div class="bz-loading-text">در حال انتقال...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bz-section-spacer"></div>

        <!-- Dynamic Latest Articles Section -->
        <div class="bz-container bz-reveal">
            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 10,
                'post_status'    => 'publish',
            );
            $latest_posts = new WP_Query( $args );
            ?>
            <div class="article-horizontal-section">
                <div class="article-h-header">
                    <div class="article-h-title-group">
                        <span class="article-h-icon">📚</span>
                        <h2 class="article-h-heading">مقالات سايت</h2>
                    </div>
                </div>

                <div class="article-h-scroll">
                    <?php if ( $latest_posts->have_posts() ) : ?>
                        <?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
                            $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
                            if ( ! $thumb_url ) {
                                $thumb_url = 'https://borzouie.com/wp-content/uploads/2026/02/free1.webp';
                            }
                        ?>
                        <a href="<?php the_permalink(); ?>" class="article-h-item">
                            <div class="article-h-image">
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" decoding="async">
                            </div>
                            <div class="article-h-body">
                                <h4 class="article-h-title"><?php the_title(); ?></h4>
                                <p class="article-h-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?></p>
                            </div>
                            <div class="article-h-footer">
                                <span class="article-h-btn">مشاهده مقاله 💡</span>
                            </div>
                        </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php else : ?>
                        <p style="color:var(--bz-muted);">هنوز مقاله‌اي منتشر نشده است.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bz-section-spacer"></div>

        <!-- Dual Banners -->
        <div class="bz-container bz-reveal">
            <div class="bz-dual-grid">
                <a href="<?php echo esc_url( home_url( '/consultation' ) ); ?>" class="bz-banner-clean">
                    <img src="https://borzouie.com/wp-content/uploads/2026/02/consultaion3.webp" alt="مشاوره" class="bz-img-full" loading="lazy" decoding="async">
                </a>
                <a href="<?php echo esc_url( home_url( '/form' ) ); ?>" class="bz-banner-clean">
                    <img src="https://borzouie.com/wp-content/uploads/2026/02/form-banne2r.webp" alt="فرم" class="bz-img-full" loading="lazy" decoding="async">
                </a>
            </div>
        </div>

        <div class="bz-section-spacer"></div>

        <!-- Full Width Banners -->
        <div class="bz-container bz-reveal">
            <div class="bz-dual-grid">
                <a href="https://borzouie-business.ir/" target="_blank" rel="noopener" class="bz-banner-clean">
                    <img src="https://borzouie.com/wp-content/uploads/2026/08/1000191383.webp" alt="بيزينس" class="bz-img-full" loading="lazy" decoding="async">
                </a>
                <a href="https://borzouie.ir/" target="_blank" rel="noopener" class="bz-banner-clean">
                    <img src="https://borzouie.com/wp-content/uploads/2026/02/organic-products-main-page.webp" alt="ارگانيک" class="bz-img-full" loading="lazy" decoding="async">
                </a>
            </div>
        </div>
        
        <div class="bz-section-spacer"></div>

    </main>

    <!-- JS SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== PRELOADER =====
            const preloader = document.getElementById('bz-preloader');
            if (preloader) {
                const hidePreloader = () => {
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';
                    setTimeout(() => preloader.style.display = 'none', 700);
                };
                setTimeout(hidePreloader, 300);
            }

            // ===== REVEAL ANIMATION =====
            const revealEls = document.querySelectorAll('#bz-luxury-home .bz-reveal');
            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
                }, { threshold: 0.12 });
                revealEls.forEach(el => revealObserver.observe(el));
            } else {
                revealEls.forEach(el => el.classList.add('visible'));
            }

            // ===== COURSES SLIDER =====
            (function() {
                const slidesTrack = document.getElementById('slidesTrack');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const counterBadge = document.getElementById('counterBadge');
                const dotsContainer = document.getElementById('dotsContainer');
                const sliderContainer = document.getElementById('sliderContainer');
                
                if (!slidesTrack || !sliderContainer) return;

                const dots = dotsContainer ? dotsContainer.querySelectorAll('.bz-slider-dot') : [];
                const totalSlides = 2;
                let currentIndex = 0;
                let autoPlayInterval = null;
                const autoPlayDelay = 5000;
                let isTransitioning = false;

                function goToSlide(index) {
                    if (isTransitioning) return;
                    if (index < 0 || index >= totalSlides) return;
                    if (index === currentIndex) return;

                    isTransitioning = true;
                    currentIndex = index;

                    slidesTrack.style.transform = `translateX(${currentIndex * 100}%)`;

                    updateDots();
                    updateCounter();

                    setTimeout(() => {
                        isTransitioning = false;
                    }, 580);
                }

                function nextSlide() { goToSlide((currentIndex + 1) % totalSlides); }
                function prevSlide() { goToSlide((currentIndex - 1 + totalSlides) % totalSlides); }

                function updateDots() {
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === currentIndex);
                        if (i === currentIndex) dot.setAttribute('aria-current', 'true');
                        else dot.removeAttribute('aria-current');
                    });
                }

                function updateCounter() {
                    if (counterBadge) {
                        counterBadge.textContent = `${currentIndex + 1} / ${totalSlides}`;
                    }
                }

                function startAutoPlay() {
                    stopAutoPlay();
                    autoPlayInterval = setInterval(nextSlide, autoPlayDelay);
                }

                function stopAutoPlay() {
                    if (autoPlayInterval) {
                        clearInterval(autoPlayInterval);
                        autoPlayInterval = null;
                    }
                }

                function resetAutoPlay() {
                    stopAutoPlay();
                    startAutoPlay();
                }

                if (prevBtn) { prevBtn.addEventListener('click', (e) => { e.preventDefault(); prevSlide(); resetAutoPlay(); }); }
                if (nextBtn) { nextBtn.addEventListener('click', (e) => { e.preventDefault(); nextSlide(); resetAutoPlay(); }); }

                if (dotsContainer) {
                    dotsContainer.addEventListener('click', (e) => {
                        const dot = e.target.closest('.bz-slider-dot');
                        if (!dot) return;
                        const index = parseInt(dot.getAttribute('data-index'), 10);
                        if (!isNaN(index) && index !== currentIndex) {
                            goToSlide(index);
                            resetAutoPlay();
                        }
                    });
                }

                sliderContainer.addEventListener('mouseenter', stopAutoPlay);
                sliderContainer.addEventListener('mouseleave', startAutoPlay);
                sliderContainer.addEventListener('touchstart', stopAutoPlay, { passive: true });
                sliderContainer.addEventListener('touchend', () => { setTimeout(startAutoPlay, 500); });

                let touchStartX = 0;
                sliderContainer.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                
                sliderContainer.addEventListener('touchend', (e) => {
                    const diff = touchStartX - e.changedTouches[0].screenX;
                    if (Math.abs(diff) > 50) {
                        if (diff > 0) nextSlide();
                        else prevSlide();
                        resetAutoPlay();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowRight') { e.preventDefault(); nextSlide(); resetAutoPlay(); }
                    else if (e.key === 'ArrowLeft') { e.preventDefault(); prevSlide(); resetAutoPlay(); }
                });

                function init() {
                    slidesTrack.style.transform = 'translateX(0%)';
                    currentIndex = 0;
                    updateDots();
                    updateCounter();
                    isTransitioning = false;
                    startAutoPlay();
                }

                init();
                window.addEventListener('beforeunload', () => stopAutoPlay());
            })();

            // ===== APPS BLUR OVERLAY =====
            const appGates = document.querySelectorAll('#bz-luxury-home .bz-app-gate');
            appGates.forEach(gate => {
                gate.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (this.classList.contains('loading')) return;

                    const href = this.dataset.href;
                    const type = this.dataset.type;

                    this.classList.add('loading');

                    setTimeout(() => {
                        if (type === 'web') {
                            window.location.href = href;
                        } else {
                            window.open(href, '_blank');
                        }
                        setTimeout(() => {
                            this.classList.remove('loading');
                        }, 800);
                    }, 2500);
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
