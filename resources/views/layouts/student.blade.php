<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f6ef7">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <meta name="mobile-web-app-capable" content="yes">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}")
                    .then(r => console.log('SW:', r.scope))
                    .catch(e => console.log('SW Error:', e));
            });
        }
    </script>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>مسار - بوابة الطالب</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* =====================================================
           نظام التصميم المشترك - مسار للطلاب
           ===================================================== */
        :root {
            --bg:          #f0f4ff;
            --surface:     #ffffff;
            --surface2:    #f7f9ff;
            --border:      rgba(79, 110, 247, 0.1);
            --text-1:      #0f172a;
            --text-2:      #64748b;
            --text-3:      #94a3b8;
            --accent:      #4f6ef7;
            --accent-lt:   rgba(79, 110, 247, 0.1);
            --accent-glow: rgba(79, 110, 247, 0.25);
            --success:     #10b981;
            --success-lt:  rgba(16, 185, 129, 0.1);
            --danger:      #ef4444;
            --danger-lt:   rgba(239, 68, 68, 0.1);
            --warn:        #f59e0b;
            --warn-lt:     rgba(245, 158, 11, 0.1);
            --r:           20px;
            --r-sm:        12px;
            --sh:          0 2px 16px rgba(79, 110, 247, 0.07);
            --sh-md:       0 6px 28px rgba(79, 110, 247, 0.12);
            --tr:          all 0.22s cubic-bezier(.4,0,.2,1);
            --input-bg:    #ffffff;
        }

        body.dark-mode {
            --bg:       #080e1a;
            --surface:  #111827;
            --surface2: #1a2234;
            --border:   rgba(79,110,247,.15);
            --text-1:   #f1f5f9;
            --text-2:   #94a3b8;
            --text-3:   #64748b;
            --sh:        0 2px 16px rgba(0,0,0,.4);
            --sh-md:     0 6px 28px rgba(0,0,0,.5);
            --input-bg:  rgba(255,255,255,.05);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg) !important;
            color: var(--text-1);
            transition: background .4s, color .4s;
            -webkit-font-smoothing: antialiased;
            margin-bottom: 80px;
        }

        /* ---- شريط التنقل السفلي ---- */
        .bottom-nav {
            background: var(--surface) !important;
            border-top: 1px solid var(--border) !important;
            box-shadow: 0 -4px 20px rgba(79,110,247,.08) !important;
            transition: background .4s;
        }

        .nav-link-custom {
            color: var(--text-3) !important;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            transition: var(--tr);
        }

        .nav-link-custom i {
            font-size: 20px;
            display: block;
            margin-bottom: 2px;
        }

        .nav-link-custom.active { color: var(--accent) !important; }

        .btn-exam-center {
            width: 55px; height: 55px;
            background: linear-gradient(135deg, var(--accent), #818cf8);
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-top: -28px;
            border: 4px solid var(--bg);
            box-shadow: 0 4px 14px var(--accent-glow);
            transition: var(--tr);
            text-decoration: none;
        }

        .btn-exam-center:hover { transform: scale(1.08); color: #fff; }

        /* ---- بطاقة عامة ---- */
        .s-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            box-shadow: var(--sh);
            transition: var(--tr);
            overflow: hidden;
        }

        /* ---- هيدر الصفحات ---- */
        .page-header {
            background: linear-gradient(145deg,#1e3a8a 0%,#4f6ef7 60%,#818cf8 100%);
            padding: 20px 18px 18px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute; top: -50px; left: -50px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
        }

        /* ---- أيقونة دائرة ---- */
        .s-icon {
            width: 42px; height: 42px;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;
        }
        .s-icon-blue   { background: var(--accent-lt);           color: var(--accent); }
        .s-icon-green  { background: var(--success-lt);          color: var(--success); }
        .s-icon-amber  { background: var(--warn-lt);             color: var(--warn); }
        .s-icon-red    { background: var(--danger-lt);           color: var(--danger); }
        .s-icon-teal   { background: rgba(20,184,166,.1);        color: #14b8a6; }
        .s-icon-purple { background: rgba(139,92,246,.1);        color: #8b5cf6; }

        /* ---- Badge ---- */
        .s-badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
        .s-badge-success { background:var(--success-lt); color:var(--success); }
        .s-badge-danger  { background:var(--danger-lt);  color:var(--danger); }
        .s-badge-warn    { background:var(--warn-lt);    color:var(--warn); }
        .s-badge-accent  { background:var(--accent-lt);  color:var(--accent); }
        .s-badge-muted   { background:var(--surface2); color:var(--text-2); border:1px solid var(--border); }

        /* ---- أزرار ---- */
        .s-btn {
            display:inline-flex; align-items:center; justify-content:center; gap:6px;
            padding:11px 20px; border-radius:var(--r-sm); border:none;
            font-family:'Cairo',sans-serif; font-size:13px; font-weight:700;
            cursor:pointer; transition: var(--tr); text-decoration:none;
        }
        .s-btn-primary { background:linear-gradient(135deg,var(--accent),#818cf8); color:#fff; box-shadow:0 4px 14px var(--accent-glow); }
        .s-btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 20px var(--accent-glow); color:#fff; }
        .s-btn-ghost { background:var(--surface2); color:var(--text-2); border:1px solid var(--border); }
        .s-btn-danger { background:var(--danger-lt); color:var(--danger); border:1px solid rgba(239,68,68,.15); }
        .s-btn-full { width:100%; }

        /* ---- انيميشن ---- */
        .fade-up { opacity:0; transform:translateY(12px); animation:fu .45s ease forwards; }
        .fade-up:nth-child(1){animation-delay:.04s}
        .fade-up:nth-child(2){animation-delay:.10s}
        .fade-up:nth-child(3){animation-delay:.16s}
        .fade-up:nth-child(4){animation-delay:.22s}
        .fade-up:nth-child(5){animation-delay:.28s}
        .fade-up:nth-child(6){animation-delay:.34s}
        .fade-up:nth-child(7){animation-delay:.40s}
        @keyframes fu { to{ opacity:1; transform:none; } }

        .xs { font-size: 11px; }
        .tap:active { transform: scale(.97); }

        /* Ensure ALL modals are scrollable */
        .modal-dialog .modal-content { max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; }
        .modal-dialog .modal-body { overflow-y: auto; flex: 1; }
    </style>
</head>
<body>

    @yield('content')

    <!-- شريط التنقل السفلي -->
    @php $hideNav = request()->routeIs('student.exam') || request()->routeIs('student.practice.view'); @endphp
    <div class="bottom-nav fixed-bottom d-flex justify-content-around align-items-center py-2 px-3" id="studentBottomNav" @if($hideNav) style="display:none!important;" @endif>
        <a href="{{ route('student.dashboard') }}"
           class="nav-link-custom text-center {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> الرئيسية
        </a>
        <a href="{{ route('student.practice.modes') }}"
           class="nav-link-custom text-center {{ request()->routeIs('student.practice.*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i> تدريب
        </a>
        <a href="javascript:void(0)" onclick="confirmStartExam()" class="btn-exam-center">
            <i class="fas fa-play fa-sm"></i>
        </a>
        <a href="{{ route('student.financial') }}"
           class="nav-link-custom text-center {{ request()->routeIs('student.financial') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> مالية
        </a>
        <a href="{{ route('student.profile') }}"
           class="nav-link-custom text-center {{ request()->routeIs('student.profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i> حسابي
        </a>
    </div>

    <!-- Exam Confirm Modal -->
    <div id="examConfirmModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
        <div style="background:var(--surface);border-radius:20px;padding:28px 24px;max-width:340px;width:90%;margin:auto;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.4);">
            <div style="width:56px;height:56px;border-radius:16px;background:var(--accent-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:22px;color:var(--accent);">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div style="font-size:17px;font-weight:800;color:var(--text-1);margin-bottom:8px;">بدء الامتحان</div>
            <div style="font-size:13px;color:var(--text-2);margin-bottom:22px;line-height:1.6;">هل أنت متأكد من دخول الامتحان؟<br>لن تتمكن من الخروج بعد البدء.</div>
            <div style="display:flex;gap:10px;">
                <button onclick="document.getElementById('examConfirmModal').style.display='none'" class="s-btn s-btn-ghost" style="flex:1;">إلغاء</button>
                <a href="{{ route('student.exam') }}" class="s-btn s-btn-primary" style="flex:1;justify-content:center;">ابدأ الآن</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/dexie/dist/dexie.js"></script>
    <script src="{{ asset('js/student/offline-engine.js') }}"></script>
    <script>
    function confirmStartExam() {
        const modal = document.getElementById('examConfirmModal');
        modal.style.display = 'flex';
    }
    // Init dark mode
    (function() {
        const t = localStorage.getItem('studentTheme');
        if (t === 'dark') document.body.classList.add('dark-mode');
    })();
    </script>
    @yield('scripts')
</body>
</html>
