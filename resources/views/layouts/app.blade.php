<!DOCTYPE html>
<html lang="id" x-data="appShell()" :class="{ 'dark': darkMode }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIP-Pelangi')</title>

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
                        sans: ['Geist', 'sans-serif'],
                        mono: ['Geist Mono', 'monospace'],
                    },
                }
            }
        }
    </script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ══════════════════════════════════════════════
           DESIGN TOKENS — Modern Minimalist
        ══════════════════════════════════════════════ */
        :root {
            --sidebar-w:     240px;
            --sidebar-coll:  56px;
            --topbar-h:      54px;
            --nav-h:         60px;

            /* Accent — single warm blue */
            --accent:        #2563eb;
            --accent-2:      #1d4ed8;
            --accent-soft:   rgba(37,99,235,0.08);
            --accent-muted:  rgba(37,99,235,0.14);
            --accent-ring:   rgba(37,99,235,0.22);

            /* Neutrals */
            --bg:            #f5f6f8;
            --bg-2:          #eef0f3;
            --surface:       #ffffff;
            --surface-2:     #f9fafb;
            --border:        #e4e6ea;
            --border-2:      #d1d5db;

            /* Text */
            --text-1:        #0d1117;
            --text-2:        #4b5563;
            --text-3:        #9ca3af;
            --text-inv:      #ffffff;

            /* Semantic */
            --success:       #16a34a;
            --success-bg:    #f0fdf4;
            --success-border:#bbf7d0;
            --danger:        #dc2626;
            --danger-bg:     #fef2f2;
            --danger-border: #fecaca;
            --warning:       #d97706;

            /* Elevation */
            --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 24px rgba(0,0,0,0.07), 0 4px 8px rgba(0,0,0,0.04);

            --radius-sm: 6px;
            --radius:    10px;
            --radius-lg: 14px;
            --radius-xl: 20px;

            --ease-spring: cubic-bezier(.34,1.56,.64,1);
            --ease-out:    cubic-bezier(.22,1,.36,1);
            --dur-fast:    0.14s;
            --dur-mid:     0.22s;
            --dur-page:    0.32s;
        }

        .dark {
            --accent:        #3b82f6;
            --accent-2:      #2563eb;
            --accent-soft:   rgba(59,130,246,0.10);
            --accent-muted:  rgba(59,130,246,0.18);
            --accent-ring:   rgba(59,130,246,0.26);

            --bg:            #0a0c10;
            --bg-2:          #0f1219;
            --surface:       #141820;
            --surface-2:     #1a1f2e;
            --border:        rgba(255,255,255,0.07);
            --border-2:      rgba(255,255,255,0.12);

            --text-1:        #f0f4ff;
            --text-2:        #8b95a8;
            --text-3:        #3d4a5c;

            --success:       #4ade80;
            --success-bg:    rgba(22,163,74,0.10);
            --success-border:rgba(74,222,128,0.20);
            --danger:        #f87171;
            --danger-bg:     rgba(220,38,38,0.10);
            --danger-border: rgba(248,113,113,0.20);

            --shadow-xs: 0 1px 2px rgba(0,0,0,0.20);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.30), 0 1px 2px rgba(0,0,0,0.20);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.28), 0 2px 4px rgba(0,0,0,0.20);
            --shadow-lg: 0 10px 24px rgba(0,0,0,0.36), 0 4px 8px rgba(0,0,0,0.22);
        }

        *, *::before, *::after { box-sizing: border-box; }

        /* ── NProgress ── */
        #nprogress .bar {
            background: var(--accent) !important;
            height: 2px !important;
        }
        #nprogress .peg { box-shadow: 0 0 8px var(--accent) !important; }
        #nprogress .spinner { display: none !important; }

        /* ── Base ── */
        html, body {
            height: 100%;
            height: 100dvh;
            overflow: hidden;
            font-family: 'Geist', sans-serif;
            background: var(--bg);
            color: var(--text-1);
            -webkit-font-smoothing: antialiased;
            transition: background var(--dur-mid) ease, color var(--dur-mid) ease;
        }

        /* ══════════════════════════════════════════════
           APP SHELL
        ══════════════════════════════════════════════ */
        .app-shell {
            display: flex;
            height: 100dvh;
            overflow: hidden;
        }

        /* ══════════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════════ */
        .sidebar { display: none; }

        @media (min-width: 1024px) {
            .sidebar {
                display: flex;
                flex-direction: column;
                width: var(--sidebar-w);
                flex-shrink: 0;
                height: 100dvh;
                background: var(--surface);
                border-right: 1px solid var(--border);
                transition: width var(--dur-mid) var(--ease-out);
                overflow: visible;
                position: relative;
                z-index: 20;
            }
            .sidebar.collapsed { width: var(--sidebar-coll); }
        }

        .sidebar-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        /* ── Brand ── */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 14px;
            height: var(--topbar-h);
            min-height: var(--topbar-h);
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            overflow: hidden;
        }

        .brand-mark {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(37,99,235,0.30);
        }

        .brand-text {
            overflow: hidden;
            transition: opacity var(--dur-fast) ease, width var(--dur-mid) var(--ease-out);
            white-space: nowrap;
        }
        .sidebar.collapsed .brand-text { opacity: 0; width: 0; pointer-events: none; }

        .brand-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-1);
            letter-spacing: -0.02em;
        }

        .brand-sub {
            font-size: 10px;
            color: var(--text-3);
            font-family: 'Geist Mono', monospace;
            font-weight: 400;
            margin-top: 0.5px;
        }

        /* Collapse btn */
        .collapse-btn {
            margin-left: auto;
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-3);
            background: transparent;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all var(--dur-fast) ease;
        }
        .collapse-btn:hover {
            background: var(--accent-soft);
            border-color: var(--accent-ring);
            color: var(--accent);
        }
        .sidebar.collapsed .collapse-btn { transform: rotate(180deg); }

        /* ── User card ── */
        .user-card {
            margin: 10px 10px 0;
            padding: 9px 10px;
            border-radius: var(--radius);
            background: var(--surface-2);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 9px;
            flex-shrink: 0;
            overflow: hidden;
            transition: background var(--dur-fast) ease;
        }
        .user-card:hover { background: var(--bg-2); }

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
            font-size: 10px;
            color: #fff;
            letter-spacing: 0.02em;
        }

        .user-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .role-pill {
            display: inline-block;
            margin-top: 2px;
            padding: 1px 7px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 600;
            font-family: 'Geist Mono', monospace;
            letter-spacing: 0.04em;
        }

        /* ── Online dot ── */
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            flex-shrink: 0;
            box-shadow: 0 0 0 2px rgba(34,197,94,0.18);
            animation: dot-pulse 3s ease-in-out infinite;
        }
        @keyframes dot-pulse {
            0%, 100% { box-shadow: 0 0 0 2px rgba(34,197,94,0.18); }
            50%       { box-shadow: 0 0 0 4px rgba(34,197,94,0.08); }
        }

        /* ── Section label ── */
        .sb-section {
            padding: 16px 12px 4px;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            color: var(--text-3);
            font-family: 'Geist Mono', monospace;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--dur-fast) ease;
        }
        .sidebar.collapsed .sb-section { opacity: 0; }

        /* ── Nav link ── */
        .sb-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px 10px;
            border-radius: var(--radius-sm);
            font-size: 13px;
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
            transition:
                background var(--dur-fast) ease,
                color var(--dur-fast) ease;
            position: relative;
        }
        .sb-link svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            opacity: 0.45;
            transition: opacity var(--dur-fast) ease;
        }
        .sb-link:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }
        .sb-link:hover svg { opacity: 0.80; }

        .sb-link.active {
            background: var(--accent-muted);
            color: var(--accent);
            font-weight: 600;
        }
        .sb-link.active svg { opacity: 1; }

        /* Left indicator */
        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 2.5px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        /* Collapsed */
        .sidebar.collapsed .sb-link {
            justify-content: center;
            padding: 9px;
        }
        .sidebar.collapsed .sb-link-label { display: none; }

        /* Tooltip for collapsed */
        .sidebar.collapsed .sb-link[data-tip]:hover::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(var(--sidebar-coll) + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-1);
            padding: 5px 10px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            pointer-events: none;
            z-index: 200;
            box-shadow: var(--shadow-md);
        }

        /* Danger */
        .sb-link.danger { color: var(--danger); }
        .sb-link.danger svg { opacity: 0.65; }
        .sb-link.danger:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }
        .sb-link.danger:hover svg { opacity: 1; }

        /* ── Sidebar footer ── */
        .sidebar-footer {
            padding: 10px 10px 12px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* ══════════════════════════════════════════════
           MAIN AREA
        ══════════════════════════════════════════════ */
        .main-area {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
            height: 100dvh;
            overflow: hidden;
        }

        @media (max-width: 1023px) {
            .main-content {
                padding-bottom: calc(var(--nav-h) + env(safe-area-inset-bottom, 0px));
            }
        }

        /* ══════════════════════════════════════════════
           TOPBAR
        ══════════════════════════════════════════════ */
        .topbar {
            display: flex;
            align-items: center;
            height: var(--topbar-h);
            padding: 0 18px;
            flex-shrink: 0;
            position: relative;
            z-index: 10;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }

        .topbar-left { flex: 1; min-width: 0; }

        .topbar-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-1);
            letter-spacing: -0.025em;
            line-height: 1.2;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--text-3);
            margin-top: 1px;
            font-family: 'Geist Mono', monospace;
        }
        .topbar-breadcrumb a {
            color: var(--text-3);
            text-decoration: none;
            transition: color var(--dur-fast) ease;
        }
        .topbar-breadcrumb a:hover { color: var(--accent); }
        .topbar-breadcrumb .sep { opacity: 0.4; }
        .topbar-breadcrumb .current { color: var(--accent); font-weight: 500; }

        /* Date chip */
        .date-chip {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-2);
            background: var(--surface-2);
            border: 1px solid var(--border);
            padding: 4px 10px;
            border-radius: var(--radius-sm);
            white-space: nowrap;
            font-family: 'Geist Mono', monospace;
            letter-spacing: 0.02em;
            transition: border-color var(--dur-fast) ease;
        }
        .date-chip:hover { border-color: var(--border-2); }

        /* Icon button */
        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-2);
            background: transparent;
            border: 1px solid var(--border);
            cursor: pointer;
            flex-shrink: 0;
            transition: all var(--dur-fast) ease;
        }
        .icon-btn:hover {
            background: var(--accent-soft);
            border-color: var(--accent-ring);
            color: var(--accent);
        }
        .icon-btn:active { transform: scale(0.94); }

        /* User chip topbar */
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px 4px 4px;
            border-radius: var(--radius);
            background: var(--surface-2);
            border: 1px solid var(--border);
            cursor: default;
            transition: border-color var(--dur-fast) ease;
        }
        .topbar-user:hover { border-color: var(--border-2); }

        .topbar-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .topbar-sep {
            width: 1px;
            height: 20px;
            background: var(--border);
            margin: 0 2px;
        }

        /* ══════════════════════════════════════════════
           FLASH MESSAGES
        ══════════════════════════════════════════════ */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius);
            font-size: 13px;
            font-weight: 500;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .flash.flash-success {
            background: var(--success-bg);
            border-color: var(--success-border);
            color: var(--success);
        }

        .flash.flash-error {
            background: var(--danger-bg);
            border-color: var(--danger-border);
            color: var(--danger);
        }

        /* auto-dismiss progress bar */
        .flash-auto::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
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
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .flash-success .flash-icon { background: rgba(22,163,74,0.15); }
        .flash-error   .flash-icon { background: rgba(220,38,38,0.15); }

        .flash-close {
            margin-left: auto;
            opacity: 0.45;
            cursor: pointer;
            background: none;
            border: none;
            color: inherit;
            padding: 0;
            flex-shrink: 0;
            transition: opacity var(--dur-fast) ease;
        }
        .flash-close:hover { opacity: 1; }

        /* ══════════════════════════════════════════════
           BOTTOM NAV (mobile)
        ══════════════════════════════════════════════ */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 50;
            display: flex;
            align-items: stretch;
            background: var(--surface);
            border-top: 1px solid var(--border);
            height: calc(var(--nav-h) + env(safe-area-inset-bottom, 0px));
            padding-bottom: env(safe-area-inset-bottom, 0px);
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
            font-size: 9px;
            font-weight: 600;
            font-family: 'Geist Mono', monospace;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            min-height: var(--nav-h);
            min-width: 44px;
            -webkit-tap-highlight-color: transparent;
            cursor: pointer;
            border: none;
            background: transparent;
            position: relative;
            transition: color var(--dur-fast) ease;
        }
        .bn-item svg {
            width: 19px;
            height: 19px;
            transition: transform var(--dur-mid) var(--ease-spring);
        }
        .bn-item:hover { color: var(--accent); }
        .bn-item.active { color: var(--accent); }
        .bn-item.active svg { transform: translateY(-1px); }

        /* active dot indicator */
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
            color: #fff;
            font-size: 8px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            border: 1.5px solid var(--surface);
        }

        /* ══════════════════════════════════════════════
           DRAWER (mobile)
        ══════════════════════════════════════════════ */
        .drawer-backdrop {
            position: fixed;
            inset: 0;
            z-index: 55;
            background: rgba(0,0,0,0.38);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .drawer-panel {
            position: fixed;
            left: 0;
            right: 0;
            bottom: calc(var(--nav-h) + env(safe-area-inset-bottom, 0px));
            z-index: 56;
            background: var(--surface);
            border: 1px solid var(--border);
            border-bottom: none;
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            padding: 6px 12px 14px;
            box-shadow: var(--shadow-lg);
        }

        .drawer-handle {
            width: 32px;
            height: 3px;
            background: var(--border-2);
            border-radius: 9999px;
            margin: 6px auto 14px;
        }

        /* ══════════════════════════════════════════════
           SCROLLBAR
        ══════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 3px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: var(--border-2);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        [x-cloak] { display: none !important; }

        /* ── Page entrance ── */
        .main-content {
            animation: page-enter var(--dur-page) var(--ease-out) both;
        }
        @keyframes page-enter {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Main content padding ── */
        .content-inner {
            padding: 20px 22px;
        }
        @media (max-width: 640px) {
            .content-inner { padding: 14px 14px; }
        }

        /* Stagger children on page load */
        .main-content > * {
            animation: page-enter var(--dur-page) var(--ease-out) both;
            animation-delay: calc(var(--i, 0) * 0.04s + 0.05s);
        }

        /* ── Topbar logo (mobile) ── */
        .topbar-logo-text {
            display: inline-flex;
            align-items: baseline;
            gap: 1px;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
        }
        .topbar-logo-sip {
            color: var(--accent);
        }
        .topbar-logo-dash {
            color: var(--text-3);
            font-weight: 400;
            font-size: 13px;
            margin: 0 1px;
        }
        .topbar-logo-pelangi {
            background: linear-gradient(90deg,  #1e62b0 45%, #669df7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Divider ── */
        .sb-divider {
            height: 1px;
            background: var(--border);
            margin: 4px 0;
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- APP SHELL --}}
<div class="app-shell">

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar" :class="{ 'collapsed': sidebarCollapsed }">
        <div class="sidebar-inner">

            {{-- Brand --}}
            <div class="sidebar-brand">
                <div class="brand-mark">
                    <svg width="18" height="18" viewBox="0 0 22 22" fill="none">
                        <path d="M4 15 Q4 8.5 11 8.5 Q18 8.5 18 15" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <path d="M6.5 15 Q6.5 10.5 11 10.5 Q15.5 10.5 15.5 15" stroke="rgba(255,255,255,0.75)" stroke-width="1.6" fill="none" stroke-linecap="round"/>
                        <path d="M9 15 Q9 12.5 11 12.5 Q13 12.5 13 15" stroke="white" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <line x1="2.5" y1="16" x2="19.5" y2="16" stroke="rgba(255,255,255,0.35)" stroke-width="1.2" stroke-linecap="round"/>
                        <circle cx="11" cy="5.5" r="1" fill="white" opacity="0.85"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <p class="brand-name">SIP-Pelangi</p>
                    <p class="brand-sub">Sistem Informasi PAUD</p>
                </div>
                <button @click="toggleSidebar()" class="collapse-btn"
                        :aria-label="sidebarCollapsed ? 'Buka sidebar' : 'Ciutkan sidebar'">
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            {{-- User card --}}
            <div class="px-2 pt-2.5 flex-shrink-0">
                <div class="user-card">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1 user-card-info">
                        <p class="user-name">{{ Auth::user()->nama_lengkap }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <div class="status-dot"></div>
                            <span class="role-pill
                                @if(Auth::user()->role === 'admin')
                                    bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400
                                @elseif(Auth::user()->role === 'bendahara')
                                    bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400
                                @elseif(Auth::user()->role === 'kepala_sekolah')
                                    bg-sky-50 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400
                                @else
                                    bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-2 py-2 space-y-px">

                <a href="{{ route('dashboard') }}"
                   class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   data-tip="Dashboard">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                    </svg>
                    <span class="sb-link-label">Dashboard</span>
                </a>

                @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
                    <p class="sb-section">Data</p>
                    <a href="{{ route('siswa.index') }}"
                       class="sb-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}"
                       data-tip="Data Siswa">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                        <span class="sb-link-label">Data Siswa</span>
                    </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
                    <a href="{{ route('guru.index') }}"
                       class="sb-link {{ request()->routeIs('guru.*') ? 'active' : '' }}"
                       data-tip="Data Guru">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-4-3.5l4 3.5 4-3.5"/>
                        </svg>
                        <span class="sb-link-label">Data Guru</span>
                    </a>
                    <a href="{{ route('absensi.kelola') }}"
                       class="sb-link {{ request()->routeIs('absensi.kelola','absensi.laporan') ? 'active' : '' }}"
                       data-tip="Kelola Absensi">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="sb-link-label">Kelola Absensi</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'guru')
                    <p class="sb-section">Aktivitas</p>
                    <a href="{{ route('absensi.index') }}"
                       class="sb-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}"
                       data-tip="Absensi Saya">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="sb-link-label">Absensi Saya</span>
                    </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
                    <p class="sb-section">Keuangan</p>
                    <a href="{{ route('spp.index') }}"
                       class="sb-link {{ request()->routeIs('spp.index','spp.create','spp.store') ? 'active' : '' }}"
                       data-tip="Pembayaran SPP">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="sb-link-label">Pembayaran SPP</span>
                    </a>
                    <a href="{{ route('spp.tunggakan') }}"
                       class="sb-link {{ request()->routeIs('spp.tunggakan') ? 'active' : '' }}"
                       data-tip="Tunggakan SPP">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <span class="sb-link-label">Tunggakan SPP</span>
                    </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
                    <p class="sb-section">Laporan</p>
                    <a href="{{ route('laporan.absensi') }}"
                       class="sb-link {{ request()->routeIs('laporan.absensi') ? 'active' : '' }}"
                       data-tip="Lap. Absensi">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sb-link-label">Lap. Absensi</span>
                    </a>
                    <a href="{{ route('laporan.spp') }}"
                       class="sb-link {{ request()->routeIs('laporan.spp') ? 'active' : '' }}"
                       data-tip="Lap. SPP">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sb-link-label">Lap. SPP</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'bendahara')
                    <p class="sb-section">Laporan</p>
                    <a href="{{ route('laporan.spp') }}"
                       class="sb-link {{ request()->routeIs('laporan.spp') ? 'active' : '' }}"
                       data-tip="Lap. SPP">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sb-link-label">Lap. SPP</span>
                    </a>
                @endif

            </nav>

            {{-- Footer --}}
            <div class="sidebar-footer">
                <button @click="toggleDark()"
                        class="sb-link w-full"
                        :aria-label="darkMode ? 'Mode terang' : 'Mode gelap'"
                        data-tip="Ganti Tema">
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'" class="sb-link-label"></span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sb-link danger w-full" data-tip="Keluar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="sb-link-label">Keluar</span>
                    </button>
                </form>
            </div>

        </div>{{-- /sidebar-inner --}}
    </aside>

    {{-- ── MAIN AREA ── --}}
    <div class="main-area">

        {{-- Topbar --}}
        <header class="topbar">
            {{-- Sidebar toggle button (desktop only) --}}
            <button @click="toggleSidebar()"
                    class="icon-btn hidden lg:flex flex-shrink-0"
                    :aria-label="sidebarCollapsed ? 'Buka sidebar' : 'Ciutkan sidebar'">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="topbar-left">
                {{-- Mobile: logo SIP-Pelangi --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 lg:hidden" style="text-decoration:none;">
                    <div class="brand-mark" style="width:28px;height:28px;border-radius:6px;">
                        <svg width="15" height="15" viewBox="0 0 22 22" fill="none">
                            <path d="M4 15 Q4 8.5 11 8.5 Q18 8.5 18 15" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                            <path d="M6.5 15 Q6.5 10.5 11 10.5 Q15.5 10.5 15.5 15" stroke="rgba(255,255,255,0.75)" stroke-width="1.6" fill="none" stroke-linecap="round"/>
                            <path d="M9 15 Q9 12.5 11 12.5 Q13 12.5 13 15" stroke="white" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="topbar-logo-text">
                        <span class="topbar-logo-sip">SIP</span><span class="topbar-logo-dash">·</span><span class="topbar-logo-pelangi">Pelangi</span>
                    </span>
                </a>
                {{-- Desktop: nama halaman & breadcrumb --}}
                <div class="hidden lg:block">
                    <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                        <nav class="topbar-breadcrumb" aria-label="Breadcrumb">
                            <a href="{{ route('dashboard') }}">Beranda</a>
                            <span class="sep">/</span>
                            @yield('breadcrumb')
                        </nav>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="hidden sm:block date-chip select-none">
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('D MMM YYYY') }}
                </span>

                <button @click="toggleDark()"
                        class="icon-btn"
                        :aria-label="darkMode ? 'Mode terang' : 'Mode gelap'">
                    <svg x-show="darkMode" class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- User chip desktop --}}
                <div class="hidden lg:flex items-center gap-2">
                    <div class="topbar-sep"></div>
                    <div class="topbar-user">
                        <div class="topbar-avatar">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 2)) }}
                        </div>
                        <div class="flex flex-col leading-none">
                            <span class="text-xs font-semibold max-w-[7rem] truncate" style="color: var(--text-1)">
                                {{ Auth::user()->nama_lengkap }}
                            </span>
                            <span class="text-[9px] mt-0.5 font-mono" style="color: var(--text-3)">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-4 lg:px-5 pt-3 space-y-2 flex-shrink-0">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-cloak
                     x-init="setTimeout(() => show = false, 4500)"
                     x-transition:leave="transition duration-200 ease-in"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flash flash-success flash-auto">
                    <div class="flash-icon">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="flex-1 text-xs">{{ session('success') }}</span>
                    <button @click="show = false" class="flash-close" aria-label="Tutup">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->has('msg'))
                <div x-data="{ show: true }" x-show="show" x-cloak
                     x-init="setTimeout(() => show = false, 6000)"
                     x-transition:leave="transition duration-200 ease-in"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flash flash-error flash-auto">
                    <div class="flash-icon">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <span class="flex-1 text-xs">{{ $errors->first('msg') }}</span>
                    <button @click="show = false" class="flash-close" aria-label="Tutup">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->any() && !$errors->has('msg'))
                <div x-data="{ open: false }"
                     class="flash flash-error" style="flex-direction: column; align-items: stretch; padding: 0; overflow: hidden;">
                    <button class="flex items-center gap-3 w-full px-3 py-2.5 text-left" @click="open = !open">
                        <div class="flash-icon">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>
                        <span class="font-semibold flex-1 text-xs">{{ $errors->count() }} kesalahan validasi</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition duration-150 ease-out"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="px-3 pb-3"
                         style="border-top: 1px solid var(--danger-border)">
                        <ul class="mt-2 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2 text-xs">
                                    <span class="w-1 h-1 rounded-full flex-shrink-0" style="background: currentColor"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto main-content">
            <div class="content-inner">
                @yield('content')
            </div>
        </main>

    </div>{{-- /main-area --}}

</div>{{-- /app-shell --}}


{{-- BOTTOM NAV + DRAWER — Mobile --}}
<div class="lg:hidden" x-data="{ moreOpen: false }">

    {{-- Backdrop --}}
    <div x-show="moreOpen" x-cloak
         @click="moreOpen = false"
         x-transition:enter="transition duration-150 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-120 ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-backdrop">
    </div>

    {{-- Drawer --}}
    <div x-show="moreOpen" x-cloak
         x-transition:enter="transition duration-220 ease-out"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition duration-160 ease-in"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="drawer-panel">

        <div class="drawer-handle"></div>

        @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
            <a href="{{ route('guru.index') }}"
               class="sb-link {{ request()->routeIs('guru.*') ? 'active' : '' }}"
               @click="moreOpen = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-4-3.5l4 3.5 4-3.5"/>
                </svg>
                <span class="sb-link-label">Data Guru</span>
            </a>
            <a href="{{ route('absensi.kelola') }}"
               class="sb-link {{ request()->routeIs('absensi.kelola','absensi.laporan') ? 'active' : '' }}"
               @click="moreOpen = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="sb-link-label">Kelola Absensi</span>
            </a>
            <a href="{{ route('laporan.absensi') }}"
               class="sb-link {{ request()->routeIs('laporan.absensi') ? 'active' : '' }}"
               @click="moreOpen = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="sb-link-label">Lap. Absensi</span>
            </a>
            <a href="{{ route('laporan.spp') }}"
               class="sb-link {{ request()->routeIs('laporan.spp') ? 'active' : '' }}"
               @click="moreOpen = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="sb-link-label">Lap. SPP</span>
            </a>
        @endif

        @if(Auth::user()->role === 'guru')
            <a href="{{ route('laporan.absensi') }}"
               class="sb-link {{ request()->routeIs('laporan.absensi') ? 'active' : '' }}"
               @click="moreOpen = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="sb-link-label">Lap. Absensi</span>
            </a>
            <a href="{{ route('laporan.spp') }}"
               class="sb-link {{ request()->routeIs('laporan.spp') ? 'active' : '' }}"
               @click="moreOpen = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span class="sb-link-label">Tunggakan SPP</span>
            </a>
        @endif

        <div class="sb-divider"></div>

        <button @click="$dispatch('toggle-dark'); moreOpen = false"
                class="sb-link w-full">
            <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <span class="sb-link-label">Ganti Tema</span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-link danger w-full">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="sb-link-label">Keluar</span>
            </button>
        </form>
    </div>

    {{-- Bottom Nav --}}
    <nav class="bottom-nav" aria-label="Navigasi utama">

        <a href="{{ route('dashboard') }}"
           class="bn-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           aria-label="Dashboard">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
            </svg>
            <span>Beranda</span>
        </a>

        @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
        <a href="{{ route('siswa.index') }}"
           class="bn-item {{ request()->routeIs('siswa.*') ? 'active' : '' }}"
           aria-label="Data Siswa">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <span>Siswa</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin','bendahara','kepala_sekolah']))
        <a href="{{ route('spp.index') }}"
           class="bn-item {{ request()->routeIs('spp.index','spp.create','spp.store') ? 'active' : '' }}"
           aria-label="SPP">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <span>SPP</span>
        </a>
        @elseif(Auth::user()->role === 'guru')
        <a href="{{ route('absensi.index') }}"
           class="bn-item {{ request()->routeIs('absensi.index') ? 'active' : '' }}"
           aria-label="Absensi">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span>Absensi</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin','kepala_sekolah']))
        <a href="{{ route('laporan.absensi') }}"
           class="bn-item {{ request()->routeIs('laporan.absensi') ? 'active' : '' }}"
           aria-label="Laporan">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Laporan</span>
        </a>
        @endif

        <button class="bn-item" @click="moreOpen = !moreOpen"
                :class="moreOpen ? 'active' : ''"
                aria-label="Menu lainnya"
                :aria-expanded="moreOpen">
            @php
                $drawerCount = 0;
                if(in_array(Auth::user()->role, ['admin','kepala_sekolah'])) $drawerCount += 4;
                if(in_array(Auth::user()->role, ['bendahara'])) $drawerCount += 1;
                if(Auth::user()->role === 'guru') $drawerCount += 2;
                $drawerCount += 2;
            @endphp
            @if($drawerCount > 0)
                <span class="bn-more-badge">{{ $drawerCount }}</span>
            @endif
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
            </svg>
            <span>Lainnya</span>
        </button>

    </nav>

</div>{{-- /mobile wrapper --}}

<script>
function appShell() {
    return {
        darkMode: localStorage.getItem('darkMode') === 'true',
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',

        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        },

        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        },

        init() {
            if (localStorage.getItem('darkMode') === null) {
                this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            window.addEventListener('toggle-dark', () => this.toggleDark());

            if (typeof NProgress !== 'undefined') {
                NProgress.configure({ showSpinner: false, speed: 220, minimum: 0.08 });
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a[href]');
                    if (link && !link.target && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                        const href = link.getAttribute('href');
                        if (href && !href.startsWith('#') && !href.startsWith('javascript') && !href.startsWith('mailto')) {
                            NProgress.start();
                        }
                    }
                });
                window.addEventListener('pageshow', () => NProgress.done());
            }
        }
    }
}
</script>

@stack('scripts')
</body>
</html>