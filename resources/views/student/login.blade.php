<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول الطالب | مسار</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ===== متغيرات الثيم ===== */
        :root {
            --primary: #4f6ef7;
            --primary-light: #818cf8;
            --dark-bg: #080e1a;
            --card-bg: rgba(255,255,255,.97);
            --input-bg: #f8fafc;
            --input-border: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --success: #10b981;
            --error-bg: #fff1f2;
            --error-border: #fecdd3;
            --error-text: #be123c;
            --label-color: #374151;
            --footer-color: rgba(255,255,255,.4);
        }

        /* ===== الوضع النهاري ===== */
        body.light-mode {
            --dark-bg: #e8eef8;
            --footer-color: rgba(15,23,42,.4);
        }
        body.light-mode .bg-layer {
            background:
                radial-gradient(ellipse 700px 500px at 15% 20%, rgba(79,110,247,.08) 0%, transparent 70%),
                radial-gradient(ellipse 500px 400px at 85% 80%, rgba(129,140,248,.06) 0%, transparent 70%);
        }
        body.light-mode .bg-grid {
            background-image:
                linear-gradient(rgba(0,0,0,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,.04) 1px, transparent 1px);
        }
        body.light-mode .traffic-lane .tl svg { filter: drop-shadow(0 0 4px rgba(0,0,0,.15)); opacity: .5; }
        body.light-mode .road-bar rect[width="420"] { fill: #cbd5e1; }
        body.light-mode .login-footer { color: rgba(15,23,42,.4); }

        html, body { height: 100%; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--dark-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            transition: background .4s ease;
        }

        /* ===== خلفية ===== */
        .bg-layer {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 700px 500px at 15% 20%, rgba(79,110,247,.12) 0%, transparent 70%),
                radial-gradient(ellipse 500px 400px at 85% 80%, rgba(129,140,248,.08) 0%, transparent 70%);
            transition: background .4s;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
            background-size: 48px 48px;
            transition: background .4s;
        }

        /* ===== إشارات مرور SVG ===== */
        .traffic-lane {
            position: fixed; inset: 0; z-index: 1; overflow: hidden; pointer-events: none;
        }
        .tl {
            position: absolute; bottom: -160px;
            animation: riseTL linear infinite; opacity: 0;
        }
        .tl-1  { left:5%;  animation-duration:22s; }
        .tl-2  { left:16%; animation-duration:28s; animation-delay:4s; }
        .tl-3  { left:30%; animation-duration:19s; animation-delay:2s; }
        .tl-4  { left:47%; animation-duration:32s; animation-delay:9s; }
        .tl-5  { left:62%; animation-duration:25s; animation-delay:1s; }
        .tl-6  { left:78%; animation-duration:20s; animation-delay:6s; }
        .tl-7  { left:91%; animation-duration:30s; animation-delay:3s; }

        @keyframes riseTL {
            0%   { transform:translateY(0);      opacity:0; }
            8%   { opacity:.55; }
            88%  { opacity:.55; }
            100% { transform:translateY(-115vh); opacity:0; }
        }
        .tl svg { width:30px; height:auto; }

        /* ===== SPLASH SCREEN ===== */
        #splash {
            position: fixed; inset: 0; z-index: 9999;
            background: #07101f;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 20px;
            animation: splashOut .5s ease .4s forwards; /* سيُفعَّل بـ JS */
        }
        #splash.hide { animation: splashOut .5s ease forwards; }

        @keyframes splashOut {
            to { opacity: 0; visibility: hidden; pointer-events: none; }
        }

        .splash-logo {
            width: 88px; height: 88px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 26px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 2.4rem;
            box-shadow: 0 0 0 0 rgba(79,110,247,.6);
            animation: splashPop .6s cubic-bezier(.34,1.56,.64,1) both,
                       splashPulse 1.2s ease .6s 2;
        }
        @keyframes splashPop {
            from { transform: scale(.4) rotate(-20deg); opacity:0; }
            to   { transform: none; opacity:1; }
        }
        @keyframes splashPulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(79,110,247,.5); }
            50%     { box-shadow: 0 0 0 18px rgba(79,110,247,0); }
        }

        .splash-title {
            font-size: 26px; font-weight: 900; color: #fff; letter-spacing: -1px;
            animation: fadeUp .5s .2s both;
        }
        .splash-sub {
            font-size: 12px; color: rgba(255,255,255,.35); letter-spacing: 3px;
            text-transform: uppercase; animation: fadeUp .5s .35s both;
        }http://127.0.0.1:8000/student/login
        .splash-bar {
            width: 160px; height: 3px; background: #1e293b; border-radius: 10px; overflow: hidden;
            animation: fadeUp .5s .45s both;
        }
        .splash-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            border-radius: 10px;
            animation: barFill 1.8s ease forwards;
        }
        @keyframes barFill {
            from { width: 0; }
            to   { width: 100%; }
        }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(10px); }
            to   { opacity:1; transform:none; }
        }

        /* ===== زر الثيم ===== */
        .theme-toggle {
            position: fixed; top: 18px; left: 18px; z-index: 100;
            width: 42px; height: 42px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: rgba(255,255,255,.7);
            font-size: 16px; transition: all .25s; backdrop-filter: blur(8px);
        }
        .theme-toggle:hover {
            background: rgba(255,255,255,.16); color: #fff;
            transform: scale(1.08);
        }
        body.light-mode .theme-toggle {
            background: rgba(15,23,42,.08); border-color: rgba(15,23,42,.12);
            color: rgba(15,23,42,.6);
        }
        body.light-mode .theme-toggle:hover { background: rgba(15,23,42,.15); }

        /* ===== حاوية ===== */
        .page-wrap {
            position: relative; z-index: 10;
            width: 100%; max-width: 420px;
            padding: 16px;
            display: flex; flex-direction: column; align-items: center;
        }

        /* ===== البطاقة ===== */
        .login-card {
            width: 100%;
            background: var(--card-bg);
            border-radius: 26px;
            padding: 28px 26px 24px;
            box-shadow: 0 28px 70px rgba(0,0,0,.55), 0 0 0 1px rgba(255,255,255,.07);
            animation: cardIn .6s cubic-bezier(.4,0,.2,1);
        }
        @keyframes cardIn {
            from { opacity:0; transform:translateY(22px) scale(.97); }
            to   { opacity:1; transform:none; }
        }

        /* ===== الشعار ===== */
        .brand-wrap { text-align:center; margin-bottom:20px; }
        .brand-icon-outer {
            position:relative; width:72px; height:72px; margin:0 auto 12px;
        }
        .brand-ring {
            position:absolute; inset:-7px; border-radius:50%;
            border:2px dashed rgba(79,110,247,.22);
            animation:spinRing 14s linear infinite;
        }
        @keyframes spinRing { to { transform:rotate(360deg); } }
        .brand-ring::before, .brand-ring::after {
            content:''; position:absolute; width:7px; height:7px;
            border-radius:50%; background:var(--primary); box-shadow:0 0 6px var(--primary);
        }
        .brand-ring::before { top:-3.5px; left:50%; transform:translateX(-50%); }
        .brand-ring::after  { bottom:-3.5px; left:50%; transform:translateX(-50%); }
        .brand-icon {
            width:72px; height:72px;
            background:linear-gradient(135deg,var(--primary),var(--primary-light));
            border-radius:20px;
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:1.9rem;
            box-shadow:0 8px 24px rgba(79,110,247,.4);
            animation:iconPop .7s .2s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes iconPop {
            from { transform:scale(.6) rotate(-20deg); opacity:0; }
            to   { transform:none; opacity:1; }
        }
        .brand-title { font-size:21px; font-weight:900; color:var(--text-dark); }
        .brand-sub   { font-size:12px; color:var(--text-muted); margin-top:3px; }

        /* ===== رسالة النجاح (خروج) ===== */
        .success-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 13px;
            padding: 11px 14px;
            margin-bottom: 16px;
            color: #166534;
            font-size: 12.5px; font-weight: 700;
            display: flex; align-items: center; gap: 9px;
            animation: slideDown .4s ease;
        }
        @keyframes slideDown {
            from { opacity:0; transform:translateY(-8px); }
            to   { opacity:1; transform:none; }
        }
        .success-box i { color: var(--success); font-size: 15px; }

        /* ===== رسالة الخطأ ===== */
        .error-box {
            background: var(--error-bg); border: 1px solid var(--error-border);
            border-radius: 13px; padding: 11px 14px; margin-bottom: 16px;
            color: var(--error-text); font-size: 12.5px; font-weight: 700;
            display: flex; align-items: center; gap: 9px;
            animation: shakeX .45s;
        }
        @keyframes shakeX {
            0%,100%{transform:translateX(0)} 20%{transform:translateX(-5px)}
            40%{transform:translateX(5px)} 60%{transform:translateX(-3px)} 80%{transform:translateX(3px)}
        }

        /* ===== حقول ===== */
        .field-group { margin-bottom: 14px; }
        .field-label {
            display:block; font-size:11.5px; font-weight:700;
            color:var(--label-color); margin-bottom:6px;
        }
        .field-wrap { position:relative; }
        .field-icon {
            position:absolute; right:13px; top:50%;
            transform:translateY(-50%);
            color:#94a3b8; font-size:14px; transition:color .2s; pointer-events:none;
        }
        .field-input {
            width:100%; padding:11px 40px 11px 42px;
            background:var(--input-bg); border:2px solid var(--input-border);
            border-radius:13px; font-family:'Cairo',sans-serif;
            font-size:13px; color:var(--text-dark);
            transition:all .2s; outline:none;
        }
        .field-input:focus {
            border-color:var(--primary); background:#fff;
            box-shadow:0 0 0 4px rgba(79,110,247,.09);
        }
        .field-input:focus ~ .field-icon { color:var(--primary); }

        /* ===== زر إظهار الباسورد ===== */
        .toggle-pw {
            position:absolute; left:12px; top:50%;
            transform:translateY(-50%);
            background:none; border:none; cursor:pointer;
            color:#94a3b8; font-size:14px; padding:4px;
            transition:color .2s; z-index:2;
            display:flex; align-items:center;
        }
        .toggle-pw:hover { color:var(--primary); }

        /* ===== زر الدخول ===== */
        .login-btn {
            width:100%; padding:13px;
            background:linear-gradient(135deg,var(--primary),var(--primary-light));
            border:none; border-radius:13px; color:#fff;
            font-family:'Cairo',sans-serif; font-size:14.5px; font-weight:800;
            cursor:pointer; box-shadow:0 5px 18px rgba(79,110,247,.35);
            transition:all .22s; margin-top:4px;
            display:flex; align-items:center; justify-content:center; gap:8px;
            position:relative; overflow:hidden;
        }
        .login-btn::after {
            content:''; position:absolute; inset:0;
            background:linear-gradient(135deg,rgba(255,255,255,.18),transparent);
            opacity:0; transition:opacity .22s;
        }
        .login-btn:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 10px 26px rgba(79,110,247,.44); }
        .login-btn:hover:not(:disabled)::after { opacity:1; }
        .login-btn:active:not(:disabled) { transform:scale(.98); }
        .login-btn:disabled {
            background: linear-gradient(135deg,#94a3b8,#cbd5e1);
            cursor:not-allowed; box-shadow:none; transform:none;
        }

        /* ===== عداد Rate Limit ===== */
        .rate-limit-box {
            display:none;
            background:#fef3c7; border:1px solid #fde68a;
            border-radius:12px; padding:11px 14px; margin-top:10px;
            font-size:12.5px; font-weight:700; color:#92400e;
            align-items:center; gap:9px;
            animation: fadeIn .3s ease;
        }
        .rate-limit-box.show { display:flex; }
        .rate-limit-timer { font-size:18px; font-weight:900; color:#b45309; min-width:32px; }

        @keyframes fadeIn { from{opacity:0}to{opacity:1} }

        /* ===== فاصل + رابط الدعم ===== */
        .or-divider { display:flex; align-items:center; gap:10px; margin:14px 0 0; }
        .or-line { flex:1; height:1px; background:var(--input-border); }
        .or-text { font-size:10.5px; font-weight:700; color:#94a3b8; }

        .whatsapp-btn {
            width:100%; padding:11px; margin-top:10px;
            background:transparent; border:2px solid #25d366;
            border-radius:13px; color:#25d366;
            font-family:'Cairo',sans-serif; font-size:13px; font-weight:800;
            cursor:pointer; transition:all .22s;
            display:flex; align-items:center; justify-content:center; gap:8px;
            text-decoration:none; position:relative; overflow:hidden;
        }
        .whatsapp-btn::before {
            content:''; position:absolute; inset:0;
            background:linear-gradient(135deg,#25d366,#128c7e);
            opacity:0; transition:opacity .22s; z-index:0;
        }
        .whatsapp-btn span, .whatsapp-btn i {
            position:relative; z-index:1; transition:color .22s;
        }
        .whatsapp-btn:hover::before { opacity:1; }
        .whatsapp-btn:hover span, .whatsapp-btn:hover i { color:#fff; }
        .whatsapp-btn:hover {
            border-color:transparent; transform:translateY(-2px);
            box-shadow:0 8px 20px rgba(37,211,102,.28);
        }

        /* نبضة واتساب */
        .wa-ping {
            position:relative; display:inline-flex;
        }
        .wa-ping::after {
            content:''; position:absolute; inset:-3px; border-radius:50%;
            background:rgba(37,211,102,.4);
            animation:waPing 1.8s ease infinite;
        }
        @keyframes waPing {
            0%    { transform:scale(1); opacity:.6; }
            70%   { transform:scale(2); opacity:0; }
            100%  { opacity:0; }
        }

        /* ===== شريط الطريق ===== */
        .road-bar { width:100%; max-width:420px; margin-top:12px; position:relative; z-index:10; }
        .road-svg { width:100%; display:block; }

        /* ===== فوتر ===== */
        .login-footer {
            text-align:center; margin-top:9px;
            font-size:11px; color:var(--footer-color);
            position:relative; z-index:10;
            transition:color .4s;
        }
    </style>
</head>
<body>

<!-- ===== SPLASH SCREEN ===== -->
<div id="splash">
    <div class="splash-logo"><i class="fas fa-user-graduate"></i></div>
    <div class="splash-title">بوابة الطالب</div>
    <div class="splash-sub">MASSAR · Student Portal</div>
    <div class="splash-bar"><div class="splash-bar-fill"></div></div>
</div>

<!-- ===== زر الثيم ===== -->
<button class="theme-toggle" id="themeToggle" title="تبديل الوضع">
    <i class="fas fa-moon" id="themeIcon"></i>
</button>

<!-- خلفية -->
<div class="bg-layer"></div>
<div class="bg-grid"></div>

<!-- إشارات مرور SVG -->
<div class="traffic-lane">
    <!-- أحمر -->
    <div class="tl tl-1">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#ef4444"><animate attributeName="opacity" values="1;0.15;1" dur="1.6s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    <!-- أصفر -->
    <div class="tl tl-2">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#f59e0b"><animate attributeName="opacity" values="1;0.15;1" dur="2.1s" begin="0.5s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    <!-- أخضر كبير -->
    <div class="tl tl-3" style="transform:scale(1.4);transform-origin:bottom center">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#22c55e"><animate attributeName="opacity" values="1;0.15;1" dur="1.9s" begin="0.9s" repeatCount="indefinite"/></circle>
        </svg>
    </div>
    <!-- أحمر -->
    <div class="tl tl-4">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#ef4444"><animate attributeName="opacity" values="1;0.15;1" dur="1.4s" begin="0.3s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    <!-- أصفر صغير -->
    <div class="tl tl-5" style="transform:scale(0.75);transform-origin:bottom center">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#f59e0b"><animate attributeName="opacity" values="1;0.15;1" dur="2.4s" begin="1.2s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    <!-- أخضر -->
    <div class="tl tl-6">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#22c55e"><animate attributeName="opacity" values="1;0.15;1" dur="2s" begin="0.7s" repeatCount="indefinite"/></circle>
        </svg>
    </div>
    <!-- أحمر+أصفر -->
    <div class="tl tl-7">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#ef4444"><animate attributeName="opacity" values="1;0.2;1" dur="1.5s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="35" r="7.5" fill="#f59e0b"><animate attributeName="opacity" values="0.2;1;0.2" dur="1.5s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
</div>

<!-- ===== المحتوى ===== -->
<div class="page-wrap">
    <div class="login-card">

        <div class="brand-wrap">
            <div class="brand-icon-outer">
                <div class="brand-ring"></div>
                <div class="brand-icon"><i class="fas fa-user-graduate"></i></div>
            </div>
            <div class="brand-title">بوابة الطالب</div>
            <div class="brand-sub">ابدأ رحلتك التعليمية في نظام مسار</div>
        </div>

        {{-- رسالة النجاح عند تسجيل الخروج --}}
        @if(session('logout_success'))
            <div class="success-box">
                <i class="fas fa-check-circle"></i>
                تم تسجيل خروجك بنجاح، إلى اللقاء!
            </div>
        @endif

        {{-- رسالة الخطأ --}}
        @if(session('error'))
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.login.post') }}" method="POST" id="loginForm">
            @csrf

            {{-- رقم الهوية --}}
            <div class="field-group">
                <label class="field-label">رقم الهوية</label>
                <div class="field-wrap">
                    <input
                        type="text"
                        name="identity_number"
                        id="identity_number"
                        class="field-input"
                        placeholder="أدخل رقم هويتك"
                        inputmode="numeric"
                        enterkeyhint="next"
                        autocomplete="username"
                        value="{{ old('identity_number', Cookie::get('remember_identity', '')) }}"
                        required autofocus>
                    <i class="fas fa-id-card field-icon"></i>
                </div>
            </div>

            {{-- كلمة المرور --}}
            <div class="field-group">
                <label class="field-label">كلمة المرور</label>
                <div class="field-wrap">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="field-input"
                        placeholder="••••••••"
                        inputmode="text"
                        enterkeyhint="go"
                        autocomplete="current-password"
                        required>
                    <i class="fas fa-lock field-icon"></i>
                    <button type="button" class="toggle-pw" id="togglePw" tabindex="-1" title="إظهار/إخفاء">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            {{-- زر الدخول --}}
            <button type="submit" class="login-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                دخول المنصة
            </button>

            {{-- Rate Limit Box --}}
            <div class="rate-limit-box" id="rateLimitBox">
                <i class="fas fa-shield-alt" style="color:#b45309;font-size:18px;"></i>
                <div>
                    <div style="font-size:12px;">محاولات كثيرة — يرجى الانتظار</div>
                    <div style="display:flex;align-items:baseline;gap:5px;margin-top:2px;">
                        <span class="rate-limit-timer" id="countdown">60</span>
                        <span style="font-size:11px;color:#92400e;">ثانية</span>
                    </div>
                </div>
            </div>

        </form>

        {{-- دعم فني --}}
        <div class="or-divider">
            <div class="or-line"></div>
            <div class="or-text">تحتاج مساعدة؟</div>
            <div class="or-line"></div>
        </div>

        <a href="https://wa.me/970597219128?text=مرحباً،%20أحتاج%20مساعدة%20في%20تسجيل%20الدخول%20لبوابة%20الطالب"
           target="_blank"
           class="whatsapp-btn">
            <span class="wa-ping">
                <i class="fab fa-whatsapp" style="font-size:18px;"></i>
            </span>
            <span>الدعم الفني</span>
        </a>

    </div>

    <!-- شريط الطريق -->
    <div class="road-bar">
        <svg class="road-svg" viewBox="0 0 420 26" xmlns="http://www.w3.org/2000/svg">
            <rect width="420" height="26" fill="#111827"/>
            <line x1="0" y1="13" x2="420" y2="13" stroke="#374151" stroke-width="1"/>
            <line x1="10"  y1="13" x2="48"  y2="13" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="68"  y1="13" x2="106" y2="13" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="126" y1="13" x2="164" y2="13" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="184" y1="13" x2="222" y2="13" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="242" y1="13" x2="280" y2="13" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="300" y1="13" x2="338" y2="13" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="358" y1="13" x2="410" y2="13" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
            <circle r="3.5" fill="#fde68a" opacity=".9"><animateMotion dur="3.8s" repeatCount="indefinite" path="M-30,7 H450"/></circle>
            <circle r="2.2" fill="#fde68a" opacity=".55"><animateMotion dur="3.8s" begin="1.4s" repeatCount="indefinite" path="M-30,19 H450"/></circle>
            <circle r="3.5" fill="#ef4444" opacity=".8"><animateMotion dur="5s" repeatCount="indefinite" path="M450,7 H-30"/></circle>
            <circle r="2.2" fill="#ef4444" opacity=".5"><animateMotion dur="5s" begin="2.3s" repeatCount="indefinite" path="M450,20 H-30"/></circle>
        </svg>
    </div>

    <div class="login-footer">
        نظام مسار &copy; {{ date('Y') }} — بوابة الطلاب الرسمية
    </div>
</div>

<script>
/* ===================================================
   1) SPLASH SCREEN
   — يظهر مرة واحدة فقط لكل جلسة (sessionStorage)
   =================================================== */
window.addEventListener('DOMContentLoaded', () => {
    const splash = document.getElementById('splash');
    const shown  = sessionStorage.getItem('splashShown');

    if (shown) {
        // سبق وأُظهر — أزله فوراً بدون أنيميشن
        splash.remove();
    } else {
        sessionStorage.setItem('splashShown', '1');
        setTimeout(() => {
            splash.classList.add('hide');
            splash.addEventListener('animationend', () => splash.remove(), { once: true });
        }, 2200);
    }
});

/* ===================================================
   2) إظهار / إخفاء كلمة المرور
   =================================================== */
const togglePw  = document.getElementById('togglePw');
const pwField   = document.getElementById('password');
const eyeIcon   = document.getElementById('eyeIcon');

togglePw.addEventListener('click', () => {
    const isHidden = pwField.type === 'password';
    pwField.type   = isHidden ? 'text' : 'password';
    eyeIcon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
});

/* ===================================================
   3) RATE LIMITING — يعتمد 100% على قيمة السيرفر
   ===================================================
   السيرفر يُمرّر lockout_seconds عبر session.
   الـ View يضعها في data-lockout على الـ form.
   JS يقرأها ويبدأ العداد التنازلي مباشرة.
   لا يوجد localStorage — القرار دائماً من السيرفر.
   =================================================== */
(function initRateLimit() {
    const loginBtn     = document.getElementById('loginBtn');
    const rateLimitBox = document.getElementById('rateLimitBox');
    const countdownEl  = document.getElementById('countdown');

    // ✅ القيمة الأولية من السيرفر (Blade ضخّها في data-attribute)
    const form         = document.getElementById('loginForm');
    const serverLock   = parseInt(form.dataset.lockoutSeconds || '0');

    if (serverLock > 0) {
        startCountdown(serverLock);
    }

    function startCountdown(seconds) {
        loginBtn.disabled = true;
        rateLimitBox.classList.add('show');

        let remaining = seconds;
        countdownEl.textContent = remaining;

        const timer = setInterval(() => {
            remaining--;
            countdownEl.textContent = remaining;

            if (remaining <= 0) {
                clearInterval(timer);
                // ✅ أعد تحميل الصفحة لمسح session السيرفر
                window.location.reload();
            }
        }, 1000);
    }
})();

/* ===================================================
   4) وضع ليلي / نهاري
   =================================================== */
const themeToggle = document.getElementById('themeToggle');
const themeIcon   = document.getElementById('themeIcon');
const body        = document.body;

const savedTheme = localStorage.getItem('msrTheme');
if (savedTheme === 'light') {
    body.classList.add('light-mode');
    themeIcon.className = 'fas fa-sun';
}

themeToggle.addEventListener('click', () => {
    const isLight = body.classList.toggle('light-mode');
    themeIcon.className  = isLight ? 'fas fa-sun' : 'fas fa-moon';
    localStorage.setItem('msrTheme', isLight ? 'light' : 'dark');
});
</script>

</body>
</html>
