<!DOCTYPE html>
<html lang="id" x-data="appShell()" :class="{ 'dark': darkMode }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIP-Pelangi')</title>

    {{-- Fonts: preconnect + preload critical weight only --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- NProgress --}}
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Geist', 'system-ui', 'sans-serif'],
                        mono: ['Geist Mono', 'ui-monospace', 'monospace'],
                    },
                }
            }
        }
    </script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ═══════════════════════════════════════════════════
           DESIGN TOKENS — SIP-Pelangi v2
           Semua warna dalam OKLCH. Tidak ada #000 atau #fff murni.
           Strategi warna: Restrained — satu aksen biru ≤10% permukaan.
        ═══════════════════════════════════════════════════ */
        :root {
            /* ── Chrome dimensions ── */
            --sidebar-w:      240px;
            --sidebar-coll:   56px;
            --topbar-h:       54px;
            --nav-h:          60px;

            /* ── Spacing scale (4pt base) ── */
            --sp-1:   4px;
            --sp-2:   8px;
            --sp-3:   10px;
            --sp-4:   12px;
            --sp-5:   14px;
            --sp-6:   16px;
            --sp-7:   18px;
            --sp-8:   20px;
            --sp-9:   24px;
            --sp-10:  32px;

            /* ── Aksen: biru hangat ── */
            --accent:        oklch(52% 0.190 260);
            --accent-h:      oklch(46% 0.200 262);
            --accent-soft:   oklch(96% 0.025 260);
            --accent-muted:  oklch(94% 0.040 260);
            --accent-ring:   oklch(88% 0.055 260);

            /* ── Netral (di-tint ke biru, chroma 0.006–0.010) ── */
            --bg:            oklch(96.0% 0.006 250);
            --bg-2:          oklch(93.5% 0.008 250);
            --surface:       oklch(99.5% 0.003 250);
            --surface-2:     oklch(98.0% 0.005 250);
            --border:        oklch(90.0% 0.007 250);
            --border-2:      oklch(85.0% 0.009 250);

            /* ── Teks ── */
            --text-1:        oklch(14% 0.008 260);
            --text-2:        oklch(46% 0.012 255);
            --text-3:        oklch(68% 0.008 255);
            --text-inv:      oklch(99% 0.003 250);

            /* ── Semantik ── */
            --success:       oklch(46% 0.150 155);
            --success-bg:    oklch(98% 0.025 155);
            --success-border:oklch(88% 0.060 155);
            --danger:        oklch(50% 0.210 27);
            --danger-bg:     oklch(98% 0.025 27);
            --danger-border: oklch(88% 0.075 27);
            --warning:       oklch(60% 0.180 70);
            --warning-bg:    oklch(98% 0.030 70);

            /* ── Elevasi (OKLCH opacity) ── */
            --shadow-xs: 0 1px 2px oklch(0% 0 0 / 5%);
            --shadow-sm: 0 1px 3px oklch(0% 0 0 / 8%), 0 1px 2px oklch(0% 0 0 / 4%);
            --shadow-md: 0 4px 8px oklch(0% 0 0 / 6%), 0 2px 4px oklch(0% 0 0 / 4%);
            --shadow-lg: 0 10px 24px oklch(0% 0 0 / 8%), 0 4px 8px oklch(0% 0 0 / 4%);

            /* ── Radius ── */
            --r-sm:  6px;
            --r:    10px;
            --r-lg: 14px;
            --r-xl: 20px;

            /* ── Motion ── */
            --ease-out:    cubic-bezier(0.22, 1, 0.36, 1);
            --ease-in:     cubic-bezier(0.55, 0, 0.85, 0);
            --ease-io:     cubic-bezier(0.65, 0, 0.35, 1);
            /* spring sangat terbatas: hanya untuk icon translateY ≤4px */
            --ease-spring: cubic-bezier(0.34, 1.42, 0.64, 1);
            --dur-micro:   0.10s;
            --dur-fast:    0.14s;
            --dur-mid:     0.22s;
            --dur-page:    0.30s;
            --dur-panel:   0.32s;

            /* ── Tipografi ── */
            --fs-2xs: 9px;
            --fs-xs:  11px;
            --fs-sm:  13px;
            --fs-base:14px;
            --fs-md:  15px;
            --fs-lg:  18px;
        }

        /* ── Dark mode ── */
        .dark {
            --accent:        oklch(63% 0.185 260);
            --accent-h:      oklch(58% 0.195 262);
            --accent-soft:   oklch(20% 0.030 260);
            --accent-muted:  oklch(23% 0.042 260);
            --accent-ring:   oklch(30% 0.060 260);

            --bg:            oklch(10.5% 0.010 255);
            --bg-2:          oklch(13.5% 0.012 255);
            --surface:       oklch(15.5% 0.012 255);
            --surface-2:     oklch(18.5% 0.013 255);
            --border:        oklch(25% 0.010 255 / 70%);
            --border-2:      oklch(32% 0.012 255 / 80%);

            --text-1:        oklch(94% 0.012 260);
            --text-2:        oklch(62% 0.018 255);
            --text-3:        oklch(40% 0.012 255);
            --text-inv:      oklch(99% 0.003 250);

            --success:       oklch(70% 0.165 155);
            --success-bg:    oklch(18% 0.040 155);
            --success-border:oklch(32% 0.080 155);
            --danger:        oklch(68% 0.200 27);
            --danger-bg:     oklch(18% 0.045 27);
            --danger-border: oklch(32% 0.090 27);

            --shadow-xs: 0 1px 2px oklch(0% 0 0 / 22%);
            --shadow-sm: 0 1px 3px oklch(0% 0 0 / 30%), 0 1px 2px oklch(0% 0 0 / 18%);
            --shadow-md: 0 4px 8px oklch(0% 0 0 / 28%), 0 2px 4px oklch(0% 0 0 / 18%);
            --shadow-lg: 0 10px 24px oklch(0% 0 0 / 36%), 0 4px 8px oklch(0% 0 0 / 22%);
        }

        /* ── Reduced motion (WAJIB — WCAG) ── */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ── Reset ── */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ── NProgress ── */
        #nprogress .bar {
            background: var(--accent) !important;
            height: 2px !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 6px var(--accent) !important;
        }
        #nprogress .spinner { display: none !important; }

        /* ── Base ── */
        html {
            height: 100%;
            height: 100dvh;
        }
        body {
            height: 100%;
            overflow: hidden;
            font-family: 'Geist', system-ui, sans-serif;
            font-size: var(--fs-base);
            font-kerning: normal;
            font-optical-sizing: auto;
            line-height: 1.5;
            background: var(--bg);
            color: var(--text-1);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* dark mode: kompensasi kontras — sedikit lebih terang dan lebih renggang */
        .dark body {
            letter-spacing: 0.008em;
        }

        /* ── Tabular nums untuk semua angka nominal ── */
        .tabular { font-variant-numeric: tabular-nums; }

        /* ═══════════════════════════════════════════════════
           APP SHELL
        ═══════════════════════════════════════════════════ */
        .app-shell {
            display: flex;
            height: 100%;
            height: 100dvh;
            overflow: hidden;
            background: var(--bg);
        }

        /* ═══════════════════════════════════════════════════
           SIDEBAR — desktop only
        ═══════════════════════════════════════════════════ */
        .sidebar { display: none; }

        @media (min-width: 1024px) {
            .sidebar {
                display: flex;
                flex-direction: column;
                width: var(--sidebar-w);
                flex-shrink: 0;
                height: 100%;
                background: var(--surface);
                border-right: 1px solid var(--border);
                transition:
                    width var(--dur-mid) var(--ease-out),
                    background var(--dur-mid) ease;
                overflow: visible;
                position: relative;
                z-index: 20;
            }

            .sidebar.collapsed {
                width: var(--sidebar-coll);
            }
        }

        .sidebar-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        /* ── Brand bar ── */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            padding: env(safe-area-inset-top, 0px) var(--sp-5) 0;
            height: calc(var(--topbar-h) + env(safe-area-inset-top, 0px));
            min-height: calc(var(--topbar-h) + env(safe-area-inset-top, 0px));
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            overflow: hidden;
        }

        .brand-mark {
            width: 32px;
            height: 32px;
            border-radius: var(--r-sm);
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px color-mix(in oklch, var(--accent), transparent 60%);
            /* micro-interaction: scale on hover pai brand link */
            transition: transform var(--dur-fast) var(--ease-out),
                        box-shadow var(--dur-fast) ease;
        }

        .sidebar-brand:hover .brand-mark {
            transform: scale(1.06);
            box-shadow: 0 4px 12px color-mix(in oklch, var(--accent), transparent 50%);
        }

        .brand-text {
            overflow: hidden;
            transition:
                opacity var(--dur-fast) ease,
                width var(--dur-mid) var(--ease-out);
            white-space: nowrap;
        }
        .sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
            pointer-events: none;
        }

        .brand-name {
            font-size: var(--fs-base);
            font-weight: 700;
            color: var(--text-1);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        /* DIPERBAIKI: hapus gradient text — ganti warna solid */
        .brand-name-accent { color: var(--accent); }

        .brand-sub {
            font-size: var(--fs-2xs);
            color: var(--text-3);
            font-family: 'Geist Mono', monospace;
            font-weight: 400;
            margin-top: 1px;
            letter-spacing: 0.03em;
        }

        /* ── Collapse button ── */
        .collapse-btn {
            margin-left: auto;
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-3);
            background: transparent;
            border: 1px solid var(--border);
            cursor: pointer;
            transition:
                background var(--dur-fast) ease,
                border-color var(--dur-fast) ease,
                color var(--dur-fast) ease,
                transform var(--dur-mid) var(--ease-out);
        }
        .collapse-btn:hover {
            background: var(--accent-soft);
            border-color: var(--accent-ring);
            color: var(--accent);
        }
        .collapse-btn:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }
        .collapse-btn:active { transform: scale(0.92); }
        .sidebar.collapsed .collapse-btn { transform: rotate(180deg); }
        .sidebar.collapsed .collapse-btn:active { transform: rotate(180deg) scale(0.92); }

        /* ── User card ── */
        .user-card-wrap {
            padding: var(--sp-3) var(--sp-3) 0;
            flex-shrink: 0;
        }

        .user-card {
            padding: 9px var(--sp-3);
            border-radius: var(--r);
            background: var(--surface-2);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 9px;
            overflow: hidden;
            transition:
                background var(--dur-fast) ease,
                border-color var(--dur-fast) ease;
        }
        .user-card:hover { background: var(--bg-2); border-color: var(--border-2); }

        .sidebar.collapsed .user-card {
            padding: 9px;
            justify-content: center;
        }
        .sidebar.collapsed .user-card-info { display: none; }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-weight: 700;
            font-size: var(--fs-2xs);
            /* DIPERBAIKI: --text-inv bukan #fff */
            color: var(--text-inv);
            letter-spacing: 0.02em;
            transition: transform var(--dur-fast) var(--ease-out);
        }
        .user-card:hover .user-avatar {
            transform: scale(1.08);
        }

        .user-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .role-pill {
            display: inline-block;
            margin-top: 2px;
            padding: 1px 7px;
            border-radius: 9999px;
            font-size: var(--fs-2xs);
            font-weight: 600;
            font-family: 'Geist Mono', monospace;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* ── Status dot ── */
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            /* DIPERBAIKI: gunakan --success bukan #22c55e hardcoded */
            background: var(--success);
            flex-shrink: 0;
            box-shadow: 0 0 0 2px color-mix(in oklch, var(--success), transparent 78%);
            animation: dot-pulse 3s ease-in-out infinite;
        }
        @keyframes dot-pulse {
            0%, 100% { box-shadow: 0 0 0 2px color-mix(in oklch, var(--success), transparent 78%); }
            50%       { box-shadow: 0 0 0 4px color-mix(in oklch, var(--success), transparent 90%); }
        }

        /* ── Section label ── */
        .sb-section {
            padding: var(--sp-6) var(--sp-4) var(--sp-1);
            font-size: var(--fs-2xs);
            font-weight: 600;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            color: var(--text-3);
            font-family: 'Geist Mono', monospace;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--dur-fast) ease;
            /* text-wrap: balance belum support di semua browser, jadi skip untuk elemen kecil */
        }
        .sidebar.collapsed .sb-section { opacity: 0; pointer-events: none; }

        /* ── Nav link ── */
        .sb-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: var(--sp-2) var(--sp-3);
            /* custom scrollbar */
            scrollbar-width: thin;
            scrollbar-color: var(--border-2) transparent;
        }
        .sb-nav::-webkit-scrollbar { width: 3px; }
        .sb-nav::-webkit-scrollbar-track { background: transparent; }
        .sb-nav::-webkit-scrollbar-thumb {
            background: var(--border-2);
            border-radius: 9999px;
        }
        .sb-nav::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px var(--sp-3);
            border-radius: var(--r-sm);
            font-size: var(--fs-sm);
            font-weight: 500;
            color: var(--text-2);
            text-decoration: none;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
            transition:
                background var(--dur-fast) ease,
                color var(--dur-fast) ease;
            /* margin antara link: 1px (space-y-px) */
            margin-bottom: 1px;
        }

        .sb-link svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            opacity: 0.45;
            transition:
                opacity var(--dur-fast) ease,
                transform var(--dur-fast) var(--ease-out);
        }

        .sb-link:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }
        .sb-link:hover svg {
            opacity: 0.80;
            transform: translateX(1px);
        }

        .sb-link:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: -2px;
        }

        /* Active state */
        .sb-link.active {
            background: var(--accent-muted);
            color: var(--accent);
            font-weight: 600;
        }
        .sb-link.active svg { opacity: 1; }

        /* Left indicator — bukan side-stripe dekoratif:
           tinggi 60%, width 2.5px, radius 0 di kiri */
        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 2.5px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
            /* fade in saat active */
            animation: indicator-in var(--dur-fast) var(--ease-out) both;
        }
        @keyframes indicator-in {
            from { opacity: 0; transform: scaleY(0.4); }
            to   { opacity: 1; transform: scaleY(1); }
        }

        /* Collapsed state */
        .sidebar.collapsed .sb-link {
            justify-content: center;
            padding: 9px;
        }
        .sidebar.collapsed .sb-link-label { display: none; }
        .sidebar.collapsed .sb-link:hover svg { transform: none; }

        /* Tooltip collapsed — menggunakan ::after bukan position:fixed */
        .sidebar.collapsed .sb-link[data-tip]:hover::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(var(--sidebar-coll) + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-1);
            padding: 5px 10px;
            border-radius: var(--r-sm);
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            pointer-events: none;
            z-index: 200;
            box-shadow: var(--shadow-md);
            /* fade in */
            animation: tooltip-in var(--dur-fast) var(--ease-out) both;
        }
        @keyframes tooltip-in {
            from { opacity: 0; transform: translateY(-50%) translateX(-4px); }
            to   { opacity: 1; transform: translateY(-50%) translateX(0); }
        }

        /* Danger variant */
        .sb-link.danger { color: var(--danger); }
        .sb-link.danger svg { opacity: 0.65; }
        .sb-link.danger:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }
        .sb-link.danger:hover svg {
            opacity: 1;
            transform: none; /* jangan geser icon danger */
        }

        /* ── Divider ── */
        .sb-divider {
            height: 1px;
            background: var(--border);
            margin: var(--sp-1) 0;
        }

        /* ── Sidebar footer ── */
        .sidebar-footer {
            padding: var(--sp-3) var(--sp-3) var(--sp-4);
            border-top: 1px solid var(--border);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* ═══════════════════════════════════════════════════
           MAIN AREA
        ═══════════════════════════════════════════════════ */
        .main-area {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
            min-height: 0;
            overflow: hidden;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: var(--border-2) transparent;
        }
        .main-content::-webkit-scrollbar { width: 3px; }
        .main-content::-webkit-scrollbar-track { background: transparent; }
        .main-content::-webkit-scrollbar-thumb {
            background: var(--border-2);
            border-radius: 9999px;
        }
        .main-content::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* Mobile: padding bawah untuk bottom nav */
        @media (max-width: 1023px) {
            .main-content {
                padding-bottom: calc(var(--nav-h) + env(safe-area-inset-bottom, 0px));
            }
        }

        /* ── Content inner — asimetri vertikal/horizontal disengaja ── */
        .content-inner {
            padding: var(--sp-8) calc(var(--sp-8) + 2px);
        }
        @media (max-width: 640px) {
            .content-inner { padding: var(--sp-5) var(--sp-5); }
        }

        /* Page entrance — opacity + translateY, tidak melibatkan layout property */
        .content-inner {
            animation: page-enter var(--dur-page) var(--ease-out) both;
        }
        @keyframes page-enter {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Stagger children on page load — cap 0.04s per item */
        .content-inner > * {
            animation: page-enter var(--dur-page) var(--ease-out) both;
            animation-delay: calc(var(--i, 0) * 0.04s + 0.05s);
        }

        /* ═══════════════════════════════════════════════════
           TOPBAR
        ═══════════════════════════════════════════════════ */
        .topbar {
            display: flex;
            align-items: center;
            height: calc(var(--topbar-h) + env(safe-area-inset-top, 0px));
            padding: env(safe-area-inset-top, 0px) var(--sp-7) 0;
            flex-shrink: 0;
            position: relative;
            z-index: 10;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            gap: var(--sp-4);
            transition: background var(--dur-mid) ease;
        }

        .topbar-left { flex: 1; min-width: 0; }

        .topbar-title {
            font-size: var(--fs-md);
            font-weight: 700;
            color: var(--text-1);
            letter-spacing: -0.025em;
            line-height: 1.2;
            text-wrap: balance;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: var(--sp-1);
            font-size: var(--fs-2xs);
            color: var(--text-3);
            margin-top: 2px;
            font-family: 'Geist Mono', monospace;
            letter-spacing: 0.02em;
        }
        .topbar-breadcrumb a {
            color: var(--text-3);
            text-decoration: none;
            transition: color var(--dur-fast) ease;
        }
        .topbar-breadcrumb a:hover { color: var(--accent); }
        .topbar-breadcrumb a:focus-visible {
            outline: 2px solid var(--accent);
            border-radius: 2px;
        }
        .topbar-breadcrumb .sep { opacity: 0.4; }
        .topbar-breadcrumb .current { color: var(--accent); font-weight: 500; }

        /* ── Date chip ── */
        .date-chip {
            font-size: var(--fs-xs);
            font-weight: 500;
            color: var(--text-2);
            background: var(--surface-2);
            border: 1px solid var(--border);
            padding: 4px var(--sp-3);
            border-radius: var(--r-sm);
            white-space: nowrap;
            font-family: 'Geist Mono', monospace;
            letter-spacing: 0.02em;
            transition:
                border-color var(--dur-fast) ease,
                background var(--dur-fast) ease;
        }
        .date-chip:hover {
            border-color: var(--border-2);
            background: var(--bg-2);
        }

        /* ── Icon button ── */
        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-2);
            background: transparent;
            border: 1px solid var(--border);
            cursor: pointer;
            flex-shrink: 0;
            transition:
                background var(--dur-fast) ease,
                border-color var(--dur-fast) ease,
                color var(--dur-fast) ease,
                transform var(--dur-micro) ease;
        }
        .icon-btn:hover {
            background: var(--accent-soft);
            border-color: var(--accent-ring);
            color: var(--accent);
        }
        .icon-btn:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }
        .icon-btn:active {
            transform: scale(0.92);
        }
        /* Sun icon bounce saat dark toggle */
        .icon-btn.dark-toggling {
            animation: spin-half var(--dur-mid) var(--ease-out) both;
        }
        @keyframes spin-half {
            from { transform: rotate(-30deg) scale(0.85); }
            to   { transform: rotate(0deg) scale(1); }
        }

        /* ── Topbar user chip ── */
        .topbar-user {
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            padding: 4px var(--sp-3) 4px 4px;
            border-radius: var(--r);
            background: var(--surface-2);
            border: 1px solid var(--border);
            cursor: default;
            transition:
                border-color var(--dur-fast) ease,
                background var(--dur-fast) ease;
        }
        .topbar-user:hover {
            border-color: var(--border-2);
            background: var(--bg-2);
        }

        .topbar-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--fs-2xs);
            font-weight: 700;
            /* DIPERBAIKI: --text-inv bukan #fff */
            color: var(--text-inv);
            flex-shrink: 0;
            letter-spacing: 0.02em;
        }

        /* ── Topbar logo (mobile) ── */
        .topbar-logo {
            display: inline-flex;
            align-items: baseline;
            gap: 1px;
            font-size: var(--fs-md);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
        }
        .topbar-logo-sip { color: var(--accent); }
        .topbar-logo-sep {
            color: var(--text-3);
            font-weight: 400;
            font-size: 13px;
            margin: 0 1px;
        }
        /* DIPERBAIKI: hapus gradient text — solid text-1 */
        .topbar-logo-pelangi { color: var(--text-1); }

        .topbar-sep {
            width: 1px;
            height: 20px;
            background: var(--border);
            margin: 0 2px;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════════════
           FLASH MESSAGES
        ═══════════════════════════════════════════════════ */
        .flash {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            padding: var(--sp-3) var(--sp-4);
            border-radius: var(--r);
            font-size: var(--fs-sm);
            font-weight: 500;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            animation: flash-in var(--dur-mid) var(--ease-out) both;
        }
        @keyframes flash-in {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .flash-success {
            background: var(--success-bg);
            border-color: var(--success-border);
            color: var(--success);
        }
        .flash-error {
            background: var(--danger-bg);
            border-color: var(--danger-border);
            color: var(--danger);
        }

        .flash-auto::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 1.5px;
            background: currentColor;
            opacity: 0.28;
            animation: flash-shrink 4.5s linear forwards;
        }
        @keyframes flash-shrink {
            from { width: 100%; }
            to   { width: 0%; }
        }

        .flash-icon {
            width: 20px;
            height: 20px;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .flash-success .flash-icon { background: color-mix(in oklch, var(--success), transparent 82%); }
        .flash-error   .flash-icon { background: color-mix(in oklch, var(--danger),  transparent 82%); }

        .flash-close {
            margin-left: auto;
            opacity: 0.45;
            cursor: pointer;
            background: none;
            border: none;
            color: inherit;
            padding: var(--sp-1);
            flex-shrink: 0;
            border-radius: var(--r-sm);
            transition:
                opacity var(--dur-fast) ease,
                background var(--dur-fast) ease;
        }
        .flash-close:hover {
            opacity: 1;
            background: color-mix(in oklch, currentColor, transparent 88%);
        }
        .flash-close:focus-visible {
            outline: 2px solid currentColor;
        }

        /* ═══════════════════════════════════════════════════
           BOTTOM NAV — mobile only
        ═══════════════════════════════════════════════════ */
        .bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 50;
            display: flex;
            align-items: stretch;
            border-top: 1px solid var(--border);
            height: calc(var(--nav-h) + env(safe-area-inset-bottom, 0px));
            padding-bottom: env(safe-area-inset-bottom, 0px);
            padding-left: env(safe-area-inset-left, 0px);
            padding-right: env(safe-area-inset-right, 0px);
            /* subtle backdrop blur untuk depth — purposeful, bukan dekoratif */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            /* overlay bg agar blur ada substansi */
            background: color-mix(in oklch, var(--surface), transparent 8%);
        }
        @media (min-width: 1024px) { .bottom-nav { display: none; } }

        .bn-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            gap: 3px;
            text-decoration: none;
            color: var(--text-3);
            font-size: var(--fs-2xs);
            font-weight: 600;
            font-family: 'Geist Mono', monospace;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            min-height: var(--nav-h);
            /* touch target WCAG: min 44px */
            min-width: 44px;
            -webkit-tap-highlight-color: transparent;
            cursor: pointer;
            border: none;
            background: transparent;
            position: relative;
            transition: color var(--dur-fast) ease;
        }

        .bn-item svg {
            width: 20px;
            height: 20px;
            transition:
                transform var(--dur-mid) var(--ease-spring),
                opacity var(--dur-fast) ease;
        }

        /* hover hanya untuk pointer:fine (mouse), bukan touch */
        @media (hover: hover) and (pointer: fine) {
            .bn-item:hover { color: var(--accent); }
            .bn-item:hover svg { transform: translateY(-2px); }
        }

        .bn-item.active { color: var(--accent); }
        .bn-item.active svg { transform: translateY(-2px); }

        /* active dot */
        .bn-item.active::after {
            content: '';
            position: absolute;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--accent);
            animation: dot-appear var(--dur-fast) var(--ease-out) both;
        }
        @keyframes dot-appear {
            from { opacity: 0; transform: translateX(-50%) scale(0); }
            to   { opacity: 1; transform: translateX(-50%) scale(1); }
        }

        /* press feedback untuk touch */
        .bn-item:active {
            opacity: 0.65;
            transform: scale(0.94);
            transition-duration: var(--dur-micro);
        }

        .bn-more-badge {
            position: absolute;
            top: 8px;
            right: 50%;
            transform: translateX(12px);
            min-width: 14px;
            height: 14px;
            border-radius: 9999px;
            background: var(--accent);
            /* DIPERBAIKI: --text-inv bukan #fff */
            color: var(--text-inv);
            font-size: 8px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            border: 1.5px solid var(--surface);
            font-variant-numeric: tabular-nums;
        }

        /* ═══════════════════════════════════════════════════
           DRAWER — mobile
        ═══════════════════════════════════════════════════ */
        .drawer-backdrop {
            position: fixed;
            inset: 0;
            z-index: 55;
            background: oklch(0% 0 0 / 40%);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            animation: backdrop-in var(--dur-panel) var(--ease-out) both;
        }
        @keyframes backdrop-in {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .drawer-panel {
            position: fixed;
            left: 0; right: 0;
            bottom: calc(var(--nav-h) + env(safe-area-inset-bottom, 0px));
            z-index: 56;
            background: var(--surface);
            border: 1px solid var(--border);
            border-bottom: none;
            border-radius: var(--r-xl) var(--r-xl) 0 0;
            padding: 6px var(--sp-4) var(--sp-5);
            box-shadow: var(--shadow-lg);
            animation: drawer-in var(--dur-panel) var(--ease-out) both;
        }
        @keyframes drawer-in {
            from { transform: translateY(100%); }
            to   { transform: translateY(0); }
        }
        /* exit via Alpine x-transition */

        .drawer-handle {
            width: 32px;
            height: 3px;
            background: var(--border-2);
            border-radius: 9999px;
            margin: 6px auto var(--sp-5);
        }

        /* Drawer links memakai .sb-link — konsisten dengan sidebar */
        .drawer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: var(--sp-1);
        }

        /* ═══════════════════════════════════════════════════
           ACCESSIBILITY — focus visible global
        ═══════════════════════════════════════════════════ */
        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* ── Skip to content ── */
        .skip-link {
            position: absolute;
            top: -100%;
            left: var(--sp-4);
            background: var(--surface);
            color: var(--accent);
            padding: var(--sp-2) var(--sp-4);
            border-radius: var(--r-sm);
            font-size: var(--fs-sm);
            font-weight: 600;
            border: 1px solid var(--accent-ring);
            box-shadow: var(--shadow-md);
            z-index: 9999;
            text-decoration: none;
            transition: top 0.15s ease;
        }
        .skip-link:focus { top: var(--sp-4); }

        /* ═══════════════════════════════════════════════════
           MOBILE WRAPPER
        ═══════════════════════════════════════════════════ */
        .mobile-wrapper {
            display: contents;
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- Skip to content (a11y) --}}
<a href="#main-content" class="skip-link">Langsung ke konten</a>

{{-- APP SHELL --}}
<div class="app-shell">

    {{-- ═══════════ SIDEBAR (desktop) ═══════════ --}}
    <aside
        class="sidebar"
        :class="{ 'collapsed': sidebarCollapsed }"
        aria-label="Navigasi utama"
        role="navigation"
    >
        <div class="sidebar-inner">

            {{-- Brand --}}
            <div class="sidebar-brand" aria-label="SIP-Pelangi beranda">
                <div class="brand-mark" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 22 22" fill="none" aria-hidden="true">
                        <path d="M4 15 Q4 8.5 11 8.5 Q18 8.5 18 15"
                              stroke="rgba(255,255,255,0.35)" stroke-width="1.5"
                              fill="none" stroke-linecap="round"/>
                        <path d="M6.5 15 Q6.5 10.5 11 10.5 Q15.5 10.5 15.5 15"
                              stroke="rgba(255,255,255,0.72)" stroke-width="1.6"
                              fill="none" stroke-linecap="round"/>
                        <path d="M9 15 Q9 12.5 11 12.5 Q13 12.5 13 15"
                              stroke="white" stroke-width="2"
                              fill="none" stroke-linecap="round"/>
                        <line x1="2.5" y1="16" x2="19.5" y2="16"
                              stroke="rgba(255,255,255,0.30)" stroke-width="1.2"
                              stroke-linecap="round"/>
                        <circle cx="11" cy="5.5" r="1" fill="white" opacity="0.82"/>
                    </svg>
                </div>
                <div class="brand-text" aria-hidden="true">
                    {{-- DIPERBAIKI: hapus gradient text, gunakan warna solid --}}
                    <p class="brand-name">
                        <span class="brand-name-accent">SIP</span>-Pelangi
                    </p>
                    <p class="brand-sub">Sistem Informasi PAUD</p>
                </div>

            </div>

            {{-- User card → link ke profil --}}
            <div class="user-card-wrap">
                <a href="{{ route('profile.edit') }}"
                   class="user-card {{ request()->routeIs('profile.*') ? 'border-[--accent-ring] bg-[--accent-soft]' : '' }}"
                   aria-label="Lihat profil saya"
                   title="Kelola Profil">
                    <div class="user-avatar" aria-hidden="true"
                         style="{{ Auth::user()->foto ? 'background:transparent;padding:0;overflow:hidden;' : '' }}">
                        @if(Auth::user()->foto)
                            <img src="{{ Storage::url(Auth::user()->foto) }}"
                                 alt="{{ Auth::user()->nama_lengkap }}"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        @else
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 2)) }}
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 user-card-info">
                        <p class="user-name">{{ Auth::user()->nama_lengkap }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <div class="status-dot" aria-hidden="true"></div>
                            <span class="role-pill
                                @if(Auth::user()->role === 'admin')
                                    bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300
                                @elseif(Auth::user()->role === 'bendahara')
                                    bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300
                                @elseif(Auth::user()->role === 'kepala_sekolah')
                                    bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300
                                @else
                                    bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </span>
                        </div>
                    </div>
                    {{-- icon panah kecil --}}
                    <svg class="user-card-info flex-shrink-0" width="12" height="12"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="opacity:0.35;" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Navigation --}}
            <nav id="sidebar-nav" class="sb-nav" aria-label="Menu navigasi">

                <a href="{{ route('dashboard') }}"
                   class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   data-tip="Dashboard"
                   aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                    </svg>
                    <span class="sb-link-label">Dashboard</span>
                </a>

                @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
                    <p class="sb-section" aria-hidden="true">Data</p>

                    <a href="{{ route('siswa.index') }}"
                       class="sb-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}"
                       data-tip="Data Siswa"
                       aria-current="{{ request()->routeIs('siswa.*') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                        <span class="sb-link-label">Data Siswa</span>
                    </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
                    <a href="{{ route('guru.index') }}"
                       class="sb-link {{ request()->routeIs('guru.*') ? 'active' : '' }}"
                       data-tip="Data Guru"
                       aria-current="{{ request()->routeIs('guru.*') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-4-3.5l4 3.5 4-3.5"/>
                        </svg>
                        <span class="sb-link-label">Data Guru</span>
                    </a>

                    <a href="{{ route('absensi.kelola') }}"
                       class="sb-link {{ request()->routeIs('absensi.kelola','absensi.laporan') ? 'active' : '' }}"
                       data-tip="Kelola Absensi"
                       aria-current="{{ request()->routeIs('absensi.kelola','absensi.laporan') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="sb-link-label">Kelola Absensi</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'guru')
                    <p class="sb-section" aria-hidden="true">Aktivitas</p>
                    <a href="{{ route('absensi.index') }}"
                       class="sb-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}"
                       data-tip="Absensi Saya"
                       aria-current="{{ request()->routeIs('absensi.index') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="sb-link-label">Absensi Saya</span>
                    </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
                    <p class="sb-section" aria-hidden="true">Keuangan</p>
                    <a href="{{ route('spp.index') }}"
                       class="sb-link {{ request()->routeIs('spp.index','spp.create','spp.store') ? 'active' : '' }}"
                       data-tip="Pembayaran SPP"
                       aria-current="{{ request()->routeIs('spp.index','spp.create','spp.store') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="sb-link-label">Pembayaran SPP</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'bendahara')
                    <a href="{{ route('spp.tunggakan') }}"
                       class="sb-link {{ request()->routeIs('spp.tunggakan') ? 'active' : '' }}"
                       data-tip="Tunggakan SPP"
                       aria-current="{{ request()->routeIs('spp.tunggakan') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <span class="sb-link-label">Tunggakan SPP</span>
                    </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
                    <p class="sb-section" aria-hidden="true">Laporan</p>
                    <a href="{{ route('absensi.laporan') }}"
                       class="sb-link {{ request()->routeIs('absensi.laporan') ? 'active' : '' }}"
                       data-tip="Laporan Absensi"
                       aria-current="{{ request()->routeIs('absensi.laporan') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sb-link-label">Lap. Absensi</span>
                    </a>

                    <a href="{{ route('laporan.spp') }}"
                       class="sb-link {{ request()->routeIs('laporan.spp') ? 'active' : '' }}"
                       data-tip="Laporan SPP"
                       aria-current="{{ request()->routeIs('laporan.spp') ? 'page' : 'false' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sb-link-label">Lap. SPP</span>
                    </a>
                @endif

            </nav>

            {{-- Footer --}}
            <div class="sidebar-footer">
                <div class="sb-divider"></div>

                <a href="{{ route('profile.edit') }}"
                   class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                   data-tip="Profil Saya"
                   aria-current="{{ request()->routeIs('profile.*') ? 'page' : 'false' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sb-link-label">Profil Saya</span>
                </a>

                <button
                    @click="$dispatch('toggle-dark')"
                    class="sb-link w-full"
                    data-tip="Ganti tema"
                    :aria-label="darkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
                >
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400"
                         fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-4 h-4"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <span class="sb-link-label">Ganti Tema</span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sb-link danger w-full" data-tip="Keluar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="sb-link-label">Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </aside>

    {{-- ═══════════ MAIN AREA ═══════════ --}}
    <div class="main-area">

        {{-- Topbar --}}
        <header class="topbar" role="banner">

            {{-- Mobile: logo --}}
            <div class="topbar-left lg:hidden">
                <span class="topbar-logo" aria-label="SIP-Pelangi">
                    <span class="topbar-logo-sip">SIP</span>
                    <span class="topbar-logo-sep">—</span>
                    {{-- DIPERBAIKI: solid text, bukan gradient --}}
                    <span class="topbar-logo-pelangi">Pelangi</span>
                </span>
            </div>

            {{-- Sidebar toggle button (desktop only, always visible) --}}
            <button
                @click="toggleSidebar()"
                class="icon-btn hidden lg:flex flex-shrink-0"
                :aria-label="sidebarCollapsed ? 'Buka sidebar' : 'Ciutkan sidebar'"
                :aria-expanded="!sidebarCollapsed"
                aria-controls="sidebar-nav"
            >
                <svg width="15" height="15" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" aria-hidden="true"
                     :style="sidebarCollapsed ? 'transform:rotate(180deg);transition:transform 0.22s cubic-bezier(0.22,1,0.36,1)' : 'transform:rotate(0deg);transition:transform 0.22s cubic-bezier(0.22,1,0.36,1)'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            {{-- Desktop: page title + breadcrumb --}}
            <div class="topbar-left hidden lg:block">
                <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                <nav class="topbar-breadcrumb" aria-label="Breadcrumb">
                    @yield('breadcrumb')
                </nav>
                @endif
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-2 flex-shrink-0">

                {{-- Date chip: mono, tabular --}}
                <time class="date-chip hidden sm:block tabular"
                      datetime="{{ now()->toDateString() }}"
                      aria-label="Tanggal hari ini">
                    {{ now()->locale('id')->isoFormat('ddd, D MMM YYYY') }}
                </time>

                <div class="topbar-sep hidden sm:block" aria-hidden="true"></div>

                {{-- Dark mode toggle --}}
                <button
                    @click="$dispatch('toggle-dark')"
                    class="icon-btn"
                    :aria-label="darkMode ? 'Mode terang' : 'Mode gelap'"
                    :aria-pressed="darkMode"
                    x-on:click="$el.classList.add('dark-toggling'); setTimeout(() => $el.classList.remove('dark-toggling'), 300)"
                >
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400"
                         fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-4 h-4"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- User chip (desktop) — dropdown profil --}}
                <div class="hidden lg:block relative" x-data="{ open: false }" @keydown.escape="open = false">
                    <button
                        @click="open = !open"
                        :aria-expanded="open"
                        aria-haspopup="true"
                        class="topbar-user"
                        style="cursor:pointer;"
                        aria-label="Menu pengguna"
                    >
                        <div class="topbar-avatar" aria-hidden="true"
                             style="{{ Auth::user()->foto ? 'background:transparent;padding:0;overflow:hidden;' : '' }}">
                            @if(Auth::user()->foto)
                                <img src="{{ Storage::url(Auth::user()->foto) }}"
                                     alt="{{ Auth::user()->nama_lengkap }}"
                                     style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            @else
                                {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 2)) }}
                            @endif
                        </div>
                        <span style="font-size: var(--fs-sm); color: var(--text-1); max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:500;">
                            {{ Auth::user()->nama_lengkap }}
                        </span>
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="opacity:0.5; flex-shrink:0; transition:transform 0.15s ease;"
                             :style="open ? 'transform:rotate(180deg)' : ''"
                             aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown panel --}}
                    <div
                        x-show="open"
                        x-cloak
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 transform scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 transform scale-95 translate-y-1"
                        style="
                            position: absolute;
                            right: 0;
                            top: calc(100% + 8px);
                            min-width: 200px;
                            background: var(--surface);
                            border: 1px solid var(--border);
                            border-radius: var(--r-lg);
                            box-shadow: var(--shadow-lg);
                            z-index: 100;
                            padding: 6px;
                            transform-origin: top right;
                        "
                        role="menu"
                        aria-label="Menu pengguna"
                    >
                        {{-- Info user di atas dropdown --}}
                        <div style="padding: 10px 12px 10px; border-bottom: 1px solid var(--border); margin-bottom: 4px;">
                            <p style="font-size: var(--fs-sm); font-weight: 600; color: var(--text-1); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ Auth::user()->nama_lengkap }}
                            </p>
                            <p style="font-size: var(--fs-xs); color: var(--text-3); margin-top:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ Auth::user()->email ?? Auth::user()->username }}
                            </p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                           @click="open = false"
                           class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                           role="menuitem"
                           style="width:100%;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="sb-link-label">Profil Saya</span>
                        </a>

                        <div style="height:1px; background:var(--border); margin: 4px 0;"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sb-link danger w-full" role="menuitem"
                                    @click="open = false">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="sb-link-label">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="px-5 pt-4" x-data="{ show: true }" x-show="show" x-cloak
             role="alert" aria-live="polite">
            <div class="flash flash-success flash-auto">
                <div class="flash-icon" aria-hidden="true">
                    <svg width="12" height="12" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
                <button class="flash-close" @click="show = false"
                        aria-label="Tutup notifikasi">
                    <svg width="14" height="14" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="px-5 pt-4" x-data="{ show: true }" x-show="show" x-cloak
             role="alert" aria-live="assertive">
            <div class="flash flash-error flash-auto">
                <div class="flash-icon" aria-hidden="true">
                    <svg width="12" height="12" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span>{{ session('error') }}</span>
                <button class="flash-close" @click="show = false"
                        aria-label="Tutup notifikasi">
                    <svg width="14" height="14" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        {{-- Main content --}}
        <main class="main-content" id="main-content" tabindex="-1">
            <div class="content-inner">
                @yield('content')
            </div>
        </main>

    </div>{{-- /main-area --}}

    {{-- ═══════════ MOBILE WRAPPER ═══════════ --}}
    <div x-data="{ moreOpen: false }">

        {{-- Drawer backdrop --}}
        <div
            x-show="moreOpen"
            x-cloak
            class="drawer-backdrop"
            @click="moreOpen = false"
            aria-hidden="true"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        {{-- Drawer panel --}}
        <div
            x-show="moreOpen"
            x-cloak
            class="drawer-panel"
            role="dialog"
            aria-modal="true"
            aria-label="Menu lainnya"
            x-transition:enter="transition ease-out duration-280"
            x-transition:enter-start="transform translate-y-full"
            x-transition:enter-end="transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="transform translate-y-0"
            x-transition:leave-end="transform translate-y-full"
        >
            <div class="drawer-handle" aria-hidden="true"></div>

            <div class="drawer-grid">

                @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
                    <a href="{{ route('guru.index') }}"
                       class="sb-link {{ request()->routeIs('guru.*') ? 'active' : '' }}"
                       @click="moreOpen = false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-4-3.5l4 3.5 4-3.5"/>
                        </svg>
                        <span class="sb-link-label">Data Guru</span>
                    </a>

                    <a href="{{ route('absensi.kelola') }}"
                       class="sb-link {{ request()->routeIs('absensi.kelola') ? 'active' : '' }}"
                       @click="moreOpen = false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="sb-link-label">Kelola Absensi</span>
                    </a>

                    <a href="{{ route('absensi.laporan') }}"
                       class="sb-link {{ request()->routeIs('absensi.laporan') ? 'active' : '' }}"
                       @click="moreOpen = false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sb-link-label">Lap. Absensi</span>
                    </a>

                    <a href="{{ route('laporan.spp') }}"
                       class="sb-link {{ request()->routeIs('laporan.spp') ? 'active' : '' }}"
                       @click="moreOpen = false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sb-link-label">Lap. SPP</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'bendahara')
                    <a href="{{ route('spp.tunggakan') }}"
                       class="sb-link {{ request()->routeIs('spp.tunggakan') ? 'active' : '' }}"
                       @click="moreOpen = false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <span class="sb-link-label">Tunggakan SPP</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'guru')
                    <a href="{{ route('absensi.index') }}"
                       class="sb-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}"
                       @click="moreOpen = false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="sb-link-label">Absensi Saya</span>
                    </a>
                @endif

                <div class="sb-divider col-span-full"></div>

                {{-- Profil Saya — tersedia untuk semua role --}}
                <a href="{{ route('profile.edit') }}"
                   class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                   @click="moreOpen = false">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sb-link-label">Profil Saya</span>
                </a>

                <button
                    @click="$dispatch('toggle-dark'); moreOpen = false"
                    class="sb-link w-full"
                    :aria-label="darkMode ? 'Mode terang' : 'Mode gelap'"
                >
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400"
                         fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-4 h-4"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <span class="sb-link-label">Ganti Tema</span>
                </button>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="sb-link danger w-full"
                            @click="moreOpen = false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="sb-link-label">Keluar</span>
                    </button>
                </form>

            </div>
        </div>

        {{-- Bottom Nav --}}
        <nav class="bottom-nav" aria-label="Navigasi utama mobile">

            <a href="{{ route('dashboard') }}"
               class="bn-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               aria-label="Dashboard"
               aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                </svg>
                <span>Beranda</span>
            </a>

            @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
            <a href="{{ route('siswa.index') }}"
               class="bn-item {{ request()->routeIs('siswa.*') ? 'active' : '' }}"
               aria-label="Data Siswa"
               aria-current="{{ request()->routeIs('siswa.*') ? 'page' : 'false' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
                <span>Siswa</span>
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
            <a href="{{ route('spp.index') }}"
               class="bn-item {{ request()->routeIs('spp.index','spp.create','spp.store') ? 'active' : '' }}"
               aria-label="Pembayaran SPP"
               aria-current="{{ request()->routeIs('spp.index') ? 'page' : 'false' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span>SPP</span>
            </a>
            @elseif(Auth::user()->role === 'guru')
            <a href="{{ route('absensi.index') }}"
               class="bn-item {{ request()->routeIs('absensi.index') ? 'active' : '' }}"
               aria-label="Absensi Saya"
               aria-current="{{ request()->routeIs('absensi.index') ? 'page' : 'false' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>Absensi</span>
            </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
            <a href="{{ route('absensi.laporan') }}"
               class="bn-item {{ request()->routeIs('absensi.laporan') ? 'active' : '' }}"
               aria-label="Laporan"
               aria-current="{{ request()->routeIs('absensi.laporan') ? 'page' : 'false' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Laporan</span>
            </a>
            @endif

            <button
                class="bn-item"
                @click="moreOpen = !moreOpen"
                :class="{ 'active': moreOpen }"
                aria-label="Menu lainnya"
                :aria-expanded="moreOpen"
                aria-controls="drawer-more"
            >
                @php
                    $drawerCount = 0;
                    if(in_array(Auth::user()->role, ['admin','kepala_sekolah'])) $drawerCount += 4;
                    if(Auth::user()->role === 'bendahara') $drawerCount += 1;
                    if(Auth::user()->role === 'guru') $drawerCount += 1;
                    $drawerCount += 3; /* profil + tema + logout */
                @endphp
                @if($drawerCount > 0)
                    <span class="bn-more-badge tabular" aria-label="{{ $drawerCount }} item">{{ $drawerCount }}</span>
                @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                </svg>
                <span>Lainnya</span>
            </button>

        </nav>

    </div>{{-- /mobile wrapper --}}

</div>{{-- /app-shell --}}

<script>
function appShell() {
    return {
        darkMode: false,
        sidebarCollapsed: false,

        init() {
            /* Dark mode: preferensi sistem sebagai default, disimpan di localStorage */
            const stored = localStorage.getItem('sippelangi_dark');
            if (stored !== null) {
                this.darkMode = stored === 'true';
            } else {
                this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            /* Sidebar: tersimpan per sesi */
            this.sidebarCollapsed = localStorage.getItem('sippelangi_sidebar_collapsed') === 'true';

            /* Listen event toggle */
            window.addEventListener('toggle-dark', () => this.toggleDark());

            /* NProgress */
            if (typeof NProgress !== 'undefined') {
                NProgress.configure({
                    showSpinner: false,
                    speed: 220,
                    minimum: 0.08,
                    trickleSpeed: 180,
                });
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a[href]');
                    if (link && !link.target && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                        const href = link.getAttribute('href');
                        if (href && !href.startsWith('#') && !href.startsWith('javascript')
                            && !href.startsWith('mailto') && !href.startsWith('tel')) {
                            NProgress.start();
                        }
                    }
                });
                window.addEventListener('pageshow', (e) => {
                    NProgress.done();
                    /* reset stagger counter pada navigasi baru */
                    document.querySelectorAll('.content-inner > *').forEach((el, i) => {
                        el.style.setProperty('--i', i);
                    });
                });
            }

            /* Set stagger index pada load pertama */
            document.querySelectorAll('.content-inner > *').forEach((el, i) => {
                el.style.setProperty('--i', i);
            });

            /* Keyboard: tutup drawer dengan Escape */
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.$dispatch && this.$dispatch('close-drawer');
                }
            });
        },

        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('sippelangi_dark', this.darkMode);
        },

        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sippelangi_sidebar_collapsed', this.sidebarCollapsed);
        },
    };
}
</script>

@stack('scripts')
</body>
</html>