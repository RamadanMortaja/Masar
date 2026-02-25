<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | نظام مسار</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ══════════════════════════════
           متغيرات — وضع داكن (افتراضي)
        ══════════════════════════════ */
        :root {
            --primary:        #4f6ef7;
            --primary-light:  #818cf8;
            --success:        #10b981;
            --success-light:  #34d399;
            --body-bg:        #07101f;
            --card-bg:        rgba(255,255,255,.97);
            --input-bg:       #f8fafc;
            --border:         #e2e8f0;
            --text-dark:      #0f172a;
            --text-muted:     #64748b;
            --label:          #374151;
            --footer-color:   rgba(41, 38, 38, 0.49);
            --grid-line:      rgba(255,255,255,.018);
            --theme-btn-bg:   rgba(255,255,255,.08);
            --theme-btn-bdr:  rgba(255,255,255,.12);
            --theme-btn-clr:  rgba(255,255,255,.7);
        }

        /* ══════════════════════════════
           وضع نهاري
        ══════════════════════════════ */
        body.light {
            --body-bg:       #dde6f5;
            --footer-color:  rgba(15,23,42,.35);
            --grid-line:     rgba(0,0,0,.03);
            --theme-btn-bg:  rgba(15,23,42,.07);
            --theme-btn-bdr: rgba(15,23,42,.12);
            --theme-btn-clr: rgba(15,23,42,.6);
        }
        body.light .bg-layer {
            background:
                radial-gradient(ellipse 60% 50% at 8% 20%,  rgba(79,110,247,.07) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 92% 80%, rgba(249,115,22,.05) 0%, transparent 70%);
        }
        body.light .tl svg { opacity: .35; }
        body.light .login-footer { color: var(--footer-color); }

        /* ══════════════════════════════
           أساسيات
        ══════════════════════════════ */
        html, body { height: 100%; }

body {
    font-family: 'Cairo', sans-serif;
    background: var(--body-bg);
    /* الحل: تقليل الارتفاع الأدنى والسماح بالتمرير الطبيعي */
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    /* justify-content: flex-start بدلاً من center لضمان عدم قص المحتوى من الأعلى */
    justify-content: flex-start; 
    overflow-x: hidden;
    overflow-y: auto;
    padding: 20px 0; /* تقليل الحشو */
    position: relative;
    transition: background .4s;
}

        /* ══════════════════════════════
           خلفية + شبكة
        ══════════════════════════════ */
        .bg-layer {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 60% 50% at 8%  20%, rgba(79,110,247,.13) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 92% 80%, rgba(249,115,22,.08) 0%, transparent 70%);
            transition: background .4s;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(var(--grid-line) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
            background-size: 52px 52px;
            transition: background .4s;
        }

        /* ══════════════════════════════
           إشارات مرور SVG عائمة
        ══════════════════════════════ */
        .traffic-lane {
            position: fixed; inset: 0; z-index: 1;
            overflow: hidden; pointer-events: none;
        }
        .tl {
            position: absolute; bottom: -160px;
            animation: riseTL linear infinite; opacity: 0;
        }
        .tl-1 { left:4%;  animation-duration:21s; }
        .tl-2 { left:15%; animation-duration:27s; animation-delay:4s; }
        .tl-3 { left:28%; animation-duration:18s; animation-delay:2s; transform-origin:bottom center; transform:scale(1.3); }
        .tl-4 { left:46%; animation-duration:33s; animation-delay:9s; }
        .tl-5 { left:62%; animation-duration:24s; animation-delay:1s; transform-origin:bottom center; transform:scale(.75); }
        .tl-6 { left:76%; animation-duration:19s; animation-delay:6s; }
        .tl-7 { left:90%; animation-duration:29s; animation-delay:3s; transform-origin:bottom center; transform:scale(1.2); }

        @keyframes riseTL {
            0%   { transform:translateY(0);      opacity:0; }
            8%   { opacity:.55; }
            88%  { opacity:.55; }
            100% { transform:translateY(-115vh); opacity:0; }
        }
        .tl svg { width:30px; height:auto; }

        /* ══════════════════════════════
           SPLASH SCREEN
        ══════════════════════════════ */
        #splash {
            position: fixed; inset: 0; z-index: 9999;
            background: #05091a;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 18px;
        }
        #splash.out {
            animation: splashFade .55s ease forwards;
        }
        @keyframes splashFade {
            to { opacity:0; visibility:hidden; pointer-events:none; }
        }
        .splash-icon {
            width: 86px; height: 86px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 2.3rem;
            animation: splashPop .65s cubic-bezier(.34,1.56,.64,1) both,
                       splashGlow 1.1s ease .65s 2;
            box-shadow: 0 0 0 0 rgba(79,110,247,.5);
        }
        @keyframes splashPop {
            from { transform:scale(.4) rotate(-18deg); opacity:0; }
            to   { transform:none; opacity:1; }
        }
        @keyframes splashGlow {
            0%,100% { box-shadow: 0 0 0 0   rgba(79,110,247,.5); }
            50%     { box-shadow: 0 0 0 20px rgba(79,110,247,0);  }
        }
        .splash-title {
            font-size: 25px; font-weight: 900; color: #fff;
            letter-spacing: -1px; animation: splashUp .5s .15s both;
        }
        .splash-sub {
            font-size: 11px; color: rgba(255,255,255,.3);
            letter-spacing: 4px; text-transform: uppercase;
            animation: splashUp .5s .3s both;
        }
        .splash-bar-wrap {
            width: 150px; height: 3px; background: #1e293b;
            border-radius: 10px; overflow: hidden;
            animation: splashUp .5s .4s both;
        }
        .splash-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            border-radius: 10px;
            animation: barGrow 1.9s ease forwards;
        }
        @keyframes barGrow  { from{width:0} to{width:100%} }
        @keyframes splashUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }

        /* ══════════════════════════════
           زر الثيم
        ══════════════════════════════ */
        .theme-btn {
            position: fixed; top: 16px; left: 16px; z-index: 200;
            width: 40px; height: 40px;
            background: var(--theme-btn-bg);
            border: 1px solid var(--theme-btn-bdr);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--theme-btn-clr);
            font-size: 15px; transition: all .25s;
            backdrop-filter: blur(8px);
        }
        .theme-btn:hover { transform: scale(1.1); color: #fff; background: rgba(79,110,247,.25); }
        body.light .theme-btn:hover { color: var(--primary); background: rgba(79,110,247,.1); }

        /* ══════════════════════════════
           حاوية الصفحة
        ══════════════════════════════ */
     .page-wrap {
     position: relative;
     z-index: 10;
     width: 100%;
     max-width: 420px;
     padding: 16px;
     /* إضافة مارجن تلقائي ليتوسط المحتوى في الشاشات الكبيرة */
     margin-top: auto;
     margin-bottom: auto;
      }

        /* ══════════════════════════════
           البطاقة
        ══════════════════════════════ */
.login-card {
    width: 100%;
    background: var(--card-bg);
    border-radius: 26px;
    /* تقليل الـ padding الداخلي لضغط الحجم عمودياً */
    padding: 22px 24px; 
    box-shadow: 0 28px 70px rgba(0,0,0,.6),
                0 0 0 1px rgba(255,255,255,.07),
                inset 0 1px 0 rgba(255,255,255,.9);
    animation: cardIn .6s cubic-bezier(.4,0,.2,1);
}
        @keyframes cardIn {
            from { opacity:0; transform:translateY(22px) scale(.97); }
            to   { opacity:1; transform:none; }
        }

        /* ══════════════════════════════
           الشعار
        ══════════════════════════════ */
        .brand-wrap { text-align:center; margin-bottom:18px; }
        .brand-icon-outer {
            position:relative; width:68px; height:68px; margin:0 auto 11px;
        }
        .brand-ring {
            position:absolute; inset:-7px; border-radius:50%;
            border:2px dashed rgba(79,110,247,.22);
            animation:spinRing 14s linear infinite;
        }
        @keyframes spinRing { to{ transform:rotate(360deg); } }
        .brand-ring::before,.brand-ring::after {
            content:''; position:absolute;
            width:7px; height:7px; border-radius:50%;
            background:var(--primary); box-shadow:0 0 6px var(--primary);
        }
        .brand-ring::before { top:-3.5px; left:50%; transform:translateX(-50%); }
        .brand-ring::after  { bottom:-3.5px; left:50%; transform:translateX(-50%); }
        .brand-icon {
            width:68px; height:68px;
            background:linear-gradient(140deg,var(--primary),var(--primary-light));
            border-radius:20px;
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:1.8rem;
            box-shadow:0 8px 24px rgba(79,110,247,.4);
            animation:iconPop .75s .15s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes iconPop {
            from{ transform:scale(.6) rotate(-20deg); opacity:0; }
            to  { transform:none; opacity:1; }
        }
        .brand-title { font-size:21px; font-weight:900; color:var(--text-dark); letter-spacing:-.3px; }
        .brand-sub   { font-size:11.5px; color:var(--text-muted); margin-top:3px; }

        /* ══════════════════════════════
           فاصل القسم
        ══════════════════════════════ */
        .section-divider { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
        .section-divider-line  { flex:1; height:1px; background:var(--border); }
        .section-divider-label {
            font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.8px;
            white-space:nowrap; background:#f1f5f9; padding:3px 10px;
            border-radius:20px; border:1px solid var(--border);
        }

        /* ══════════════════════════════
           رسالة الخروج (خضراء)
        ══════════════════════════════ */
        .success-box {
            background:#f0fdf4; border:1px solid #bbf7d0;
            border-radius:12px; padding:11px 14px; margin-bottom:14px;
            color:#166534; font-size:12.5px; font-weight:700;
            display:flex; align-items:center; gap:9px;
            animation:slideDown .4s ease;
        }
        .success-box i { color:var(--success); font-size:15px; flex-shrink:0; }
        @keyframes slideDown {
            from{ opacity:0; transform:translateY(-8px); }
            to  { opacity:1; transform:none; }
        }

        /* ══════════════════════════════
           رسالة الخطأ
        ══════════════════════════════ */
        .error-box {
            background:#fff1f2; border:1px solid #fecdd3;
            border-radius:12px; padding:10px 14px; margin-bottom:14px;
            color:#be123c; font-size:12px; font-weight:700;
            display:flex; align-items:center; gap:8px;
            animation:shakeX .45s;
        }
        @keyframes shakeX {
            0%,100%{ transform:translateX(0)  }
            20%    { transform:translateX(-5px) }
            40%    { transform:translateX(5px)  }
            60%    { transform:translateX(-3px) }
            80%    { transform:translateX(3px)  }
        }

        /* ══════════════════════════════
           تحذير المحاولات
        ══════════════════════════════ */
        .attempts-warn {
            background:#fffbeb; border:1px solid #fde68a;
            border-radius:11px; padding:9px 13px; margin-bottom:12px;
            color:#92400e; font-size:12px; font-weight:700;
            display:flex; align-items:center; gap:8px;
        }
        .attempts-warn strong { color:#b45309; font-size:14px; }

        /* ══════════════════════════════
           حقول الإدخال
        ══════════════════════════════ */
        .field-group  { margin-bottom:13px; }
        .field-label  { display:block; font-size:11px; font-weight:700; color:var(--label); margin-bottom:5px; }
        .field-wrap   { position:relative; }

        /* أيقونة اليمين */
        .field-icon {
            position:absolute; right:13px; top:50%; transform:translateY(-50%);
            color:#94a3b8; font-size:14px; transition:color .2s; pointer-events:none;
        }

        /* الحقل — padding يسار إضافي لزر العين */
        .field-input {
            width:100%; padding:11px 40px 11px 40px;
            background:var(--input-bg); border:2px solid var(--border);
            border-radius:13px; font-family:'Cairo',sans-serif;
            font-size:13px; color:var(--text-dark);
            transition:all .2s; outline:none;
        }
        .field-input:focus {
            border-color:var(--primary); background:#fff;
            box-shadow:0 0 0 4px rgba(79,110,247,.09);
        }
        .field-input:focus ~ .field-icon { color:var(--primary); }
        .field-input:disabled { opacity:.5; cursor:not-allowed; }

        /* زر العين */
        .eye-btn {
            position:absolute; left:11px; top:50%; transform:translateY(-50%);
            background:none; border:none; cursor:pointer;
            color:#94a3b8; font-size:14px; padding:4px;
            transition:color .2s; display:flex; align-items:center;
        }
        .eye-btn:hover { color:var(--primary); }

        /* ══════════════════════════════
           تذكرني + نسيت
        ══════════════════════════════ */
        .remember-row {
            display:flex; justify-content:space-between;
            align-items:center; margin-bottom:16px; font-size:12px;
        }
        .remember-label {
            display:flex; align-items:center; gap:6px;
            color:var(--text-muted); cursor:pointer; font-weight:600;
        }
        .remember-label input[type=checkbox] {
            width:14px; height:14px; accent-color:var(--primary); cursor:pointer;
        }
        .forgot-link {
            color:var(--primary); text-decoration:none;
            font-weight:700; font-size:11.5px; transition:color .2s;
        }
        .forgot-link:hover { color:var(--primary-light); }

        /* ══════════════════════════════
           زر الدخول
        ══════════════════════════════ */
        .login-btn {
            width:100%; padding:12px;
            background:linear-gradient(135deg,var(--primary),var(--primary-light));
            border:none; border-radius:13px; color:#fff;
            font-family:'Cairo',sans-serif; font-size:14.5px; font-weight:800;
            cursor:pointer;
            box-shadow:0 5px 18px rgba(79,110,247,.35);
            transition:all .22s;
            display:flex; align-items:center; justify-content:center; gap:8px;
            position:relative; overflow:hidden;
        }
        .login-btn::after {
            content:''; position:absolute; inset:0;
            background:linear-gradient(135deg,rgba(255,255,255,.18),transparent);
            opacity:0; transition:opacity .22s;
        }
        .login-btn:hover:not(:disabled) {
            transform:translateY(-2px);
            box-shadow:0 10px 26px rgba(79,110,247,.44);
        }
        .login-btn:hover:not(:disabled)::after { opacity:1; }
        .login-btn:active:not(:disabled) { transform:scale(.98); }
        .login-btn:disabled {
            background:linear-gradient(135deg,#94a3b8,#cbd5e1);
            cursor:not-allowed; box-shadow:none; transform:none;
        }

        /* ══════════════════════════════
           صندوق Rate-Limit
        ══════════════════════════════ */
        .rate-box {
            display:none;
            background:#fef3c7; border:1px solid #fde68a;
            border-radius:12px; padding:12px 14px; margin-top:10px;
            align-items:center; gap:11px;
        }
        .rate-box.show { display:flex; animation:fadeIn .3s ease; }
        .rate-timer { font-size:22px; font-weight:900; color:#b45309; min-width:34px; }
        .rate-label { font-size:12px; font-weight:700; color:#92400e; }
        .rate-sub   { font-size:10.5px; color:#b45309; margin-top:2px; }

        /* ══════════════════════════════
           فاصل + زر الطالب + واتساب
        ══════════════════════════════ */
        .or-divider { display:flex; align-items:center; gap:10px; margin:12px 0; }
        .or-line    { flex:1; height:1px; background:var(--border); }
        .or-text    { font-size:10.5px; font-weight:700; color:#94a3b8; }

        /* حاوية الأزرار الجانبية */
        .actions-container {
            display: flex;
            gap: 10px; /* المسافة بين الزرين */
            margin-top: 20px;
            width: 100%;
        }
        
        /* التنسيق المشترك للأزرار المصغرة */
        .action-btn {
            flex: 1; /* لجعل الزرين متساويين في العرض */
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        /* زر الطالب - تصميم أنيق */
        .student-alt {
            background: rgba(79, 110, 247, 0.1);
            color: var(--primary);
            border: 1px solid rgba(79, 110, 247, 0.2);
        }
        
        .student-alt:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
        }
        
        /* زر واتساب - تصميم أنيق */
        .wa-alt {
            background: rgba(37, 211, 102, 0.1);
            color: #25d366;
            border: 1px solid rgba(37, 211, 102, 0.2);
        }
        
        .wa-alt:hover {
            background: #25d366;
            color: #fff;
            transform: translateY(-2px);
        }
        
        /* تحسين للجوال: جعل الأزرار تحت بعضها فقط في الشاشات الصغيرة جداً إذا لزم الأمر */
        @media (max-width: 350px) {
            .actions-container {
                flex-direction: column;
            }
        }
        @keyframes waPing {
            0%  { transform:scale(1); opacity:.6; }
            70% { transform:scale(2); opacity:0; }
            100%{ opacity:0; }
        }

        /* ══════════════════════════════
           شريط الطريق + فوتر
        ══════════════════════════════ */
        .road-bar  { width:100%; max-width:420px; margin-top:12px; position:relative; z-index:10; }
        .road-svg  { width:100%; display:block; }

        .login-footer {
            text-align:center; margin-top:9px;
            font-size:11px; color:var(--footer-color);
            position:relative; z-index:10; transition:color .4s;
        }

        @keyframes fadeIn { from{opacity:0} to{opacity:1} }

           /* تحسينات الشاشات الصغيرة (الجوال) */
    @media (max-width: 400px) {
        body { padding: 20px 12px; }
        .login-card { padding: 20px 18px; border-radius: 20px; }
        .brand-icon { width: 60px; height: 60px; font-size: 1.5rem; }
        .brand-icon-outer { width: 60px; height: 60px; margin-bottom: 8px; }
        .brand-title { font-size: 18px; }
        .login-btn { padding: 10px; font-size: 13.5px; }
    }

    /* إخفاء شريط الطريق في حال كانت الشاشة قصيرة جداً لتوفير مساحة */
@media (max-height: 750px) {
    .road-bar {
        display: none;
    }}
    </style>
</head>
<body>

{{-- ══════════════════════════════════════
     SPLASH SCREEN
     — يظهر مرة واحدة فقط لكل جلسة
══════════════════════════════════════ --}}
<div id="splash">
    <div class="splash-icon"><i class="fas fa-route"></i></div>
    <div class="splash-title">نظام مـسار</div>
    <div class="splash-sub">MASSAR · Admin Portal</div>
    <div class="splash-bar-wrap"><div class="splash-bar-fill"></div></div>
</div>

{{-- ══════════════════════════════════════
     زر الثيم (ليلي / نهاري)
══════════════════════════════════════ --}}
<button class="theme-btn" id="themeBtn" title="تبديل الوضع">
    <i class="fas fa-moon" id="themeIco"></i>
</button>

{{-- خلفية --}}
<div class="bg-layer"></div>
<div class="bg-grid"></div>

{{-- ══════════════════════════════════════
     إشارات مرور SVG عائمة
══════════════════════════════════════ --}}
<div class="traffic-lane">
    {{-- أحمر --}}
    <div class="tl tl-1">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#ef4444"><animate attributeName="opacity" values="1;0.15;1" dur="1.6s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    {{-- أصفر --}}
    <div class="tl tl-2">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#f59e0b"><animate attributeName="opacity" values="1;0.15;1" dur="2.1s" begin="0.5s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    {{-- أخضر كبير --}}
    <div class="tl tl-3">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#22c55e"><animate attributeName="opacity" values="1;0.15;1" dur="1.9s" begin="0.9s" repeatCount="indefinite"/></circle>
        </svg>
    </div>
    {{-- أحمر --}}
    <div class="tl tl-4">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#ef4444"><animate attributeName="opacity" values="1;0.15;1" dur="1.4s" begin="0.3s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    {{-- أصفر صغير --}}
    <div class="tl tl-5">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#f59e0b"><animate attributeName="opacity" values="1;0.15;1" dur="2.4s" begin="1.2s" repeatCount="indefinite"/></circle>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
    {{-- أخضر --}}
    <div class="tl tl-6">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="35" r="7.5" fill="#2d2d2d"/>
            <circle cx="19" cy="52" r="7.5" fill="#22c55e"><animate attributeName="opacity" values="1;0.15;1" dur="2s" begin="0.7s" repeatCount="indefinite"/></circle>
        </svg>
    </div>
    {{-- أحمر + أصفر --}}
    <div class="tl tl-7">
        <svg viewBox="0 0 38 95" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="16" y="68" width="6" height="27" rx="2" fill="#1e293b"/>
            <rect x="6" y="2" width="26" height="66" rx="8" fill="#0f172a" stroke="#1e3a5f" stroke-width="1.5"/>
            <circle cx="19" cy="18" r="7.5" fill="#ef4444"><animate attributeName="opacity" values="1;0.2;1"   dur="1.5s"             repeatCount="indefinite"/></circle>
            <circle cx="19" cy="35" r="7.5" fill="#f59e0b"><animate attributeName="opacity" values="0.2;1;0.2" dur="1.5s"             repeatCount="indefinite"/></circle>
            <circle cx="19" cy="52" r="7.5" fill="#2d2d2d"/>
        </svg>
    </div>
</div>

{{-- ══════════════════════════════════════
     المحتوى الرئيسي
══════════════════════════════════════ --}}
<div class="page-wrap">
    <div class="login-card">

        {{-- الشعار --}}
        <div class="brand-wrap">
            <div class="brand-icon-outer">
                <div class="brand-ring"></div>
                <div class="brand-icon"><i class="fas fa-route"></i></div>
            </div>
            <div class="brand-title">نظام مـسار</div>
            <div class="brand-sub">إدارة مدارس السياقة والتدريب</div>
        </div>

        {{-- فاصل --}}
        <div class="section-divider">
            <div class="section-divider-line"></div>
            <div class="section-divider-label">دخول المشرف</div>
            <div class="section-divider-line"></div>
        </div>

        {{-- ✅ رسالة نجاح تسجيل الخروج --}}
        @if(session('status') === 'logged-out')
            <div class="success-box">
                <i class="fas fa-check-circle"></i>
                <span>تم تسجيل خروجك بنجاح، إلى اللقاء!</span>
            </div>
        @endif

        {{-- ❌ رسالة خطأ من السيرفر --}}
        @if ($errors->any())
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i>
                بيانات الدخول غير صحيحة، يرجى المحاولة مجدداً
            </div>
        @endif

        {{-- ✅ حساب Rate Limit من session السيرفر --}}
        @php
            $lockSecs      = session('lockout_seconds', 0);
            $attempts      = session('login_attempts', 0);
            $maxAttempts   = 5;
            $attemptsLeft  = max(0, $maxAttempts - $attempts);
            $isLocked      = $lockSecs > 0;
        @endphp

        {{-- تحذير المحاولات --}}
        @if($attempts > 0 && !$isLocked)
            <div class="attempts-warn">
                <i class="fas fa-exclamation-triangle"></i>
                تبقّى <strong>{{ $attemptsLeft }}</strong>
                {{ $attemptsLeft == 1 ? 'محاولة' : 'محاولات' }} قبل التعطيل المؤقت
            </div>
        @endif

        {{-- النموذج --}}
        <form method="POST" action="{{ route('login') }}"
              id="adminForm"
              data-lockout="{{ $lockSecs }}">
            @csrf

            {{-- البريد الإلكتروني --}}
            <div class="field-group">
                <label class="field-label">البريد الإلكتروني</label>
                <div class="field-wrap">
                    <input
                        type="email"
                        name="email"
                        class="field-input"
                        placeholder="name@example.com"
                        value="{{ old('email') }}"
                        enterkeyhint="next"
                        autocomplete="email"
                        @if($isLocked) disabled @endif
                        required autofocus>
                    <i class="fas fa-envelope field-icon"></i>
                </div>
            </div>

            {{-- كلمة المرور --}}
            <div class="field-group">
                <label class="field-label">كلمة المرور</label>
                <div class="field-wrap">
                    <input
                        type="password"
                        name="password"
                        id="pwField"
                        class="field-input"
                        placeholder="••••••••"
                        enterkeyhint="go"
                        autocomplete="current-password"
                        @if($isLocked) disabled @endif
                        required>
                    <i class="fas fa-lock field-icon"></i>
                    {{-- زر العين --}}
                    <button type="button" class="eye-btn" id="eyeBtn" tabindex="-1" title="إظهار/إخفاء كلمة المرور">
                        <i class="fas fa-eye" id="eyeIco"></i>
                    </button>
                </div>
            </div>

            {{-- تذكرني --}}
            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    تذكرني
                </label>
                <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
            </div>

            {{-- زر الدخول --}}
            <button type="submit" class="login-btn" id="loginBtn"
                    @if($isLocked) disabled @endif>
                <i class="fas fa-sign-in-alt"></i>
                دخول النظام
            </button>

            {{-- ✅ صندوق Rate Limit --}}
            <div class="rate-box @if($isLocked) show @endif" id="rateBox">
                <i class="fas fa-shield-alt" style="color:#b45309;font-size:22px;flex-shrink:0"></i>
                <div>
                    <div class="rate-label">محاولات كثيرة — يرجى الانتظار</div>
                    <div style="display:flex;align-items:baseline;gap:5px;margin-top:3px;">
                        <span class="rate-timer" id="countdown">{{ $lockSecs ?: 60 }}</span>
                        <span class="rate-sub">ثانية قبل إعادة المحاولة</span>
                    </div>
                </div>
            </div>

        </form>

    {{-- حاوية الأزرار الجانبية --}}
    <div class="actions-container">
        {{-- زر بوابة الطالب --}}
        <a href="{{ route('student.login') }}" class="action-btn student-alt">
            <i class="fas fa-user-graduate"></i>
            <span>بوابة الطالب</span>
        </a>
    
        {{-- زر واتساب --}}
        
         <a href="https://wa.me/970597219128?text=مرحباً،%20أحتاج%20مساعدة%20في%20تسجيل%20دخول%20المشرف"
        target="_blank" class="action-btn wa-alt">
            <i class="fab fa-whatsapp"></i>
            <span>الدعم الفني</span>
        </a>
    
    </div>

    {{-- شريط الطريق المتحرك --}}
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
        نظام مسار  &copy; {{ date('Y') }} — جميع الحقوق محفوظة
    </div>
</div>

<script>
/* ══════════════════════════════════════════════════
   1) SPLASH SCREEN — مرة واحدة لكل جلسة
══════════════════════════════════════════════════ */
(function () {
    const splash = document.getElementById('splash');
    if (sessionStorage.getItem('adminSplashDone')) {
        splash.remove();                         // سبق وأُظهر → أزله فوراً
        return;
    }
    sessionStorage.setItem('adminSplashDone', '1');
    setTimeout(() => {
        splash.classList.add('out');
        splash.addEventListener('animationend', () => splash.remove(), { once: true });
    }, 2200);
})();

/* ══════════════════════════════════════════════════
   2) إظهار / إخفاء كلمة المرور
══════════════════════════════════════════════════ */
(function () {
    const btn   = document.getElementById('eyeBtn');
    const field = document.getElementById('pwField');
    const ico   = document.getElementById('eyeIco');
    if (!btn) return;
    btn.addEventListener('click', () => {
        const show  = field.type === 'password';
        field.type  = show ? 'text' : 'password';
        ico.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
    });
})();

/* ══════════════════════════════════════════════════
   3) RATE LIMITING — يعتمد 100% على السيرفر
   ──────────────────────────────────────────────────
   • السيرفر يُمرّر lockout_seconds عبر session
   • Blade يضعها في data-lockout على الـ form
   • JS يقرأها ويشغّل العداد التنازلي
   • عند الوصول لـ 0 → reload لمسح session السيرفر
══════════════════════════════════════════════════ */
(function () {
    const form      = document.getElementById('adminForm');
    const loginBtn  = document.getElementById('loginBtn');
    const rateBox   = document.getElementById('rateBox');
    const countdown = document.getElementById('countdown');
    if (!form) return;

    const serverLock = parseInt(form.dataset.lockout || '0');
    if (serverLock > 0) runCountdown(serverLock);

    function runCountdown(secs) {
        loginBtn.disabled = true;
        rateBox.classList.add('show');
        let s = secs;
        countdown.textContent = s;
        const t = setInterval(() => {
            s--;
            countdown.textContent = s;
            if (s <= 0) {
                clearInterval(t);
                window.location.reload();    // يمسح session ويُعيد الصفحة نظيفة
            }
        }, 1000);
    }
})();

/* ══════════════════════════════════════════════════
   4) وضع ليلي / نهاري — يُحفظ في localStorage
══════════════════════════════════════════════════ */
(function () {
    const btn  = document.getElementById('themeBtn');
    const ico  = document.getElementById('themeIco');
    const body = document.body;

    // طبّق الثيم المحفوظ قبل الرسم لتجنب الوميض
    if (localStorage.getItem('msrAdminTheme') === 'light') {
        body.classList.add('light');
        ico.className = 'fas fa-sun';
    }

    btn.addEventListener('click', () => {
        const isLight = body.classList.toggle('light');
        ico.className = isLight ? 'fas fa-sun' : 'fas fa-moon';
        localStorage.setItem('msrAdminTheme', isLight ? 'light' : 'dark');
    });
})();
</script>