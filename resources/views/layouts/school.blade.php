<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام مسار | لوحة مدير المدرسة')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 270px;
            --navy: #0d1b2a;
            --navy-2: #1b2e45;
            --navy-3: #1e3a5f;
            --accent: #3b82f6;
            --accent-glow: rgba(59,130,246,.35);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-muted: #94a3b8;
            --bg: #f0f4f9;
            --card-bg: #ffffff;
            --border: rgba(0,0,0,.06);
        }

        /* Ensure ALL modals are scrollable */
        .modal-dialog .modal-content { max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; }
        .modal-dialog .modal-body { overflow-y: auto; flex: 1; }
        .modal-dialog-scrollable .modal-body { overflow-y: auto; }
        .page-link svg { width: 12px !important; height: 12px !important; font-size: .75rem !important; }

        /* ── DARK MODE ── */
        body.dark-mode {
            --bg: #0b1220;
            --card-bg: #1a2332;
            --border: rgba(255,255,255,.07);
            --text-muted: #94a3b8;
            color: #f1f5f9;
        }
        body.dark-mode .content-area,
        body.dark-mode #mainLayout { background: #0b1220 !important; }
        body.dark-mode .topbar { background: #111827 !important; border-bottom-color: rgba(255,255,255,.07) !important; }
        body.dark-mode .page-breadcrumb { background: #0f1d2e !important; border-bottom-color: rgba(255,255,255,.06) !important; }
        body.dark-mode .card, body.dark-mode .s-card { background: #1a2332 !important; border-color: rgba(255,255,255,.07) !important; color: #f1f5f9; }
        body.dark-mode table thead th { background: rgba(255,255,255,.03) !important; color: #64748b !important; border-color: rgba(255,255,255,.07) !important; }
        body.dark-mode table tbody td { color: #94a3b8 !important; border-color: rgba(255,255,255,.07) !important; }
        body.dark-mode table tbody tr:hover td { background: #1f2d40 !important; }
        body.dark-mode .alert-pro { background: #1a2332; border-color: rgba(255,255,255,.1); }
        body.dark-mode input, body.dark-mode select, body.dark-mode textarea { background: rgba(255,255,255,.04) !important; border-color: rgba(255,255,255,.12) !important; color: #f1f5f9 !important; }
        body.dark-mode .filter-bar { background: #1a2332 !important; border-color: rgba(255,255,255,.07) !important; }
        body.dark-mode .modal-content { background: #1a2332 !important; border-color: rgba(255,255,255,.1) !important; }
        body.dark-mode .modal-header, body.dark-mode .modal-footer { background: #111827 !important; border-color: rgba(255,255,255,.07) !important; }
        body.dark-mode .breadcrumb-item.active { color: #94a3b8 !important; }
        body.dark-mode .user-name { color: #f1f5f9 !important; }
        body.dark-mode .page-link { background: #1a2332 !important; border-color: rgba(255,255,255,.12) !important; color: #94a3b8 !important; }
        body.dark-mode .page-item.active .page-link { background: var(--accent) !important; color: #fff !important; }
        body.dark-mode .dropdown-menu { background: #1a2332 !important; border-color: rgba(255,255,255,.1) !important; }
        body.dark-mode .dropdown-item { color: #94a3b8 !important; }
        body.dark-mode .dropdown-item:hover { background: #1f2d40 !important; color: #f1f5f9 !important; }
        .page-link svg { width: 12px !important; height: 12px !important; }

        * { box-sizing: border-box; }

        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            background: var(--bg);
            color: #1e293b;
            overflow-x: hidden;
            margin: 0;
        }

        /* ===================== SIDEBAR ===================== */
        #sidebar {
            position: fixed;
            top: 0; right: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }

        /* خلفية ديكورية داخل السايدبار */
        #sidebar::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 260px; height: 260px;
            background: var(--accent-glow);
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }

        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }

        .logo-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--accent), #2563eb);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px var(--accent-glow);
            flex-shrink: 0;
        }

        .logo-text { line-height: 1.2; }
        .logo-text .brand { font-size: 1.1rem; font-weight: 700; color: #fff; }
        .logo-text .sub { font-size: .72rem; color: var(--text-muted); }

        /* School badge */
        .school-badge {
            margin: 16px 16px 8px;
            background: rgba(59,130,246,.12);
            border: 1px solid rgba(59,130,246,.25);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .school-badge .avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), #1d4ed8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: #fff;
            flex-shrink: 0;
        }
        .school-badge .name { font-size: .82rem; color: #cbd5e1; font-weight: 600; }
        .school-badge .role { font-size: .7rem; color: var(--text-muted); }

        /* NAV */
        .nav-section-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .1em;
            color: var(--text-muted);
            text-transform: uppercase;
            padding: 16px 22px 6px;
            opacity: .7;
        }

        .sidebar-nav { padding: 6px 12px; flex: 1; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 2px; }

        .nav-item { margin-bottom: 2px; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: rgba(255,255,255,.6) !important;
            text-decoration: none;
            border-radius: 10px;
            font-size: .88rem;
            font-weight: 500;
            transition: all .2s;
            position: relative;
        }
        .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,.06);
        }
        .nav-link.active {
            color: #fff !important;
            background: linear-gradient(90deg, rgba(59,130,246,.35), rgba(59,130,246,.12));
            box-shadow: inset 3px 0 0 var(--accent);
        }
        .nav-link .nav-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: rgba(255,255,255,.05);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            transition: all .2s;
            flex-shrink: 0;
        }
        .nav-link.active .nav-icon { background: var(--accent); }
        .nav-link:hover .nav-icon { background: rgba(255,255,255,.1); }

        .nav-badge {
            margin-right: auto;
            background: var(--accent);
            color: #fff;
            font-size: .65rem;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
        }
        .nav-badge.warning { background: var(--warning); color: #000; }
        .nav-badge.soon { background: rgba(255,255,255,.1); color: var(--text-muted); }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .btn-logout {
            width: 100%;
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px;
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 10px;
            color: #fca5a5;
            font-size: .85rem; font-weight: 600;
            cursor: pointer; transition: all .2s;
            text-decoration: none;
        }
        .btn-logout:hover { background: rgba(239,68,68,.2); color: #f87171; }

        /* Overlay mobile */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; }

        /* ===================== MAIN CONTENT ===================== */
        #main {
            margin-right: var(--sidebar-w);
            min-height: 100vh;
            transition: margin .3s;
        }

        /* TOPBAR */
        .topbar {
            background: var(--card-bg);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0;
            z-index: 100;
        }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #f1f5f9;
            border: none;
            display: flex; align-items: center; justify-content: center;
            color: #64748b;
            cursor: pointer; transition: all .2s;
            text-decoration: none;
            font-size: .9rem;
        }
        .topbar-btn:hover { background: #e2e8f0; color: var(--accent); }

        .topbar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 12px 6px 6px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid var(--border);
            cursor: pointer;
        }
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), #2563eb);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem;
        }
        .user-name { font-size: .82rem; font-weight: 600; color: #334155; }
        .user-role { font-size: .68rem; color: var(--text-muted); }

        /* Breadcrumb */
        .page-breadcrumb {
            padding: 18px 28px 0;
        }
        .breadcrumb-item { font-size: .78rem; color: var(--text-muted); }
        .breadcrumb-item.active { color: #334155; font-weight: 600; }
        .breadcrumb-item + .breadcrumb-item::before { content: '‹'; color: var(--text-muted); }

        /* Content area */
        .content-area { padding: 20px 28px 40px; }

        /* Alerts */
        .alert-pro {
            padding: 14px 18px;
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 12px;
            font-size: .88rem; font-weight: 500;
        }
        .alert-pro.success { background: #ecfdf5; color: #065f46; }
        .alert-pro.error   { background: #fef2f2; color: #991b1b; }
        .alert-pro .alert-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; flex-shrink: 0;
        }
        .alert-pro.success .alert-icon { background: #d1fae5; color: #059669; }
        .alert-pro.error   .alert-icon { background: #fee2e2; color: #ef4444; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(100%); }
            #sidebar.open { transform: translateX(0); }
            #main { margin-right: 0; }
            .topbar { padding: 12px 16px; }
            .content-area { padding: 16px 16px 40px; }
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<nav id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-route"></i></div>
        <div class="logo-text">
            <div class="brand">نظام مسار</div>
            <div class="sub">إدارة مدارس السياقة</div>
        </div>
    </div>

    <div class="school-badge">
        <div class="avatar"><i class="fas fa-school"></i></div>
        <div>
            <div class="name">{{ Auth::user()->school->name ?? 'مدرسة' }}</div>
            <div class="role">مدير المدرسة</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-label">الرئيسية</div>
        <div class="nav-item">
            <a href="{{ route('school.dashboard') }}" class="nav-link {{ request()->routeIs('school.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                لوحة التحكم
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('school.students.index') }}" class="nav-link {{ request()->routeIs('school.students.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-graduate"></i></span>
                إدارة الطلاب
                @php $sc = Auth::user()->school->students()->count(); @endphp
                @if($sc > 0) <span class="nav-badge">{{ $sc }}</span> @endif
            </a>
        </div>

        <div class="nav-section-label" style="margin-top:8px;">الإدارة</div>
        <div class="nav-item">
            <a href="{{ route('school.trainers.index') }}" class="nav-link {{ request()->routeIs('school.trainers.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                المدربون
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('school.vehicles.index') }}" class="nav-link {{ request()->routeIs('school.vehicles.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-car-side"></i></span>
                المركبات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('school.schedule.index') }}" class="nav-link {{ request()->routeIs('school.schedule.*') || request()->routeIs('school.sessions.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                جدول الحصص
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('school.payments.index') }}" class="nav-link {{ request()->routeIs('school.payments.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-coins"></i></span>
                المدفوعات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('school.reports.index') }}" class="nav-link {{ request()->routeIs('school.reports.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                التقارير
            </a>
        </div>

        <div class="nav-section-label" style="margin-top:8px;">الامتحانات</div>
        <div class="nav-item">
            <a href="{{ route('school.signals-exam.index') }}" class="nav-link">
                <span class="nav-icon"><i class="fas fa-traffic-light"></i></span>
                امتحان الإشارات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('school.practical-exam.index') }}" class="nav-link">
                <span class="nav-icon"><i class="fas fa-car"></i></span>
                الامتحان العملي
            </a>
        </div>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" id="logoutForm">@csrf</form>
        <a href="#" onclick="document.getElementById('logoutForm').submit()" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
        </a>
    </div>
</nav>

<div id="main">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="topbar-btn d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-none d-md-block">
                <div style="font-size:.82rem; color:var(--text-muted);">@yield('page_section', 'لوحة التحكم')</div>
                <div style="font-size:1rem; font-weight:700; color:#1e293b; margin-top:1px;">@yield('page_title', 'نظرة عامة')</div>
            </div>
        </div>

        <div class="topbar-right">
            <div id="liveClock" style="font-size:.82rem; color:var(--text-muted); font-weight:600;"></div>

            <button id="schoolThemeBtn" onclick="toggleSchoolTheme()" title="تبديل الوضع"
                    style="width:34px;height:34px;border-radius:9px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.78rem;transition:.18s;">
                <i class="fas fa-sun" id="schoolThemeIcon"></i>
            </button>

            {{-- Notification Bell --}}
            @php
                $schoolId = Auth::user()->school_id;
                $expiringStudents = \App\Models\Student::where('school_id', $schoolId)
                    ->whereNotNull('medical_test_expiry')
                    ->whereDate('medical_test_expiry', '<=', now()->addDays(30))
                    ->count();
                $schoolObj = \App\Models\School::find($schoolId);
                $subExpiring = $schoolObj && \Carbon\Carbon::parse($schoolObj->subscription_end)->diffInDays(now(), false) >= -30;
                $totalAlerts = $expiringStudents + ($subExpiring ? 1 : 0);
            @endphp
            <div style="position:relative;" id="schoolNotifWrap">
                <button onclick="toggleSchoolNotif()" title="التنبيهات"
                        style="width:34px;height:34px;border-radius:9px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.78rem;transition:.18s;position:relative;">
                    <i class="fas fa-bell"></i>
                    @if($totalAlerts > 0)
                        <span style="position:absolute;top:4px;right:4px;width:8px;height:8px;border-radius:50%;background:#ef4444;border:1.5px solid #fff;"></span>
                    @endif
                </button>
                <div id="schoolNotifPanel" style="display:none;position:absolute;top:calc(100% + 10px);left:0;width:300px;background:var(--card-bg,#fff);border:1px solid rgba(0,0,0,.1);border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.15);z-index:9999;overflow:hidden;">
                    <div style="padding:12px 16px;border-bottom:1px solid rgba(0,0,0,.07);font-size:.82rem;font-weight:800;color:#1e293b;display:flex;align-items:center;gap:7px;">
                        <i class="fas fa-bell" style="color:#f59e0b;"></i> التنبيهات
                        @if($totalAlerts > 0)<span style="background:rgba(239,68,68,.1);color:#ef4444;padding:2px 8px;border-radius:20px;font-size:.66rem;font-weight:800;">{{ $totalAlerts }}</span>@endif
                    </div>
                    @if($subExpiring)
                    <div style="padding:11px 14px;border-bottom:1px solid rgba(0,0,0,.05);display:flex;gap:10px;align-items:flex-start;">
                        <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;flex-shrink:0;margin-top:5px;"></span>
                        <div>
                            <div style="font-size:.76rem;color:#1e293b;font-weight:600;">اشتراك المدرسة ينتهي قريباً</div>
                            <div style="font-size:.65rem;color:#94a3b8;margin-top:2px;">{{ $schoolObj ? \Carbon\Carbon::parse($schoolObj->subscription_end)->format('Y-m-d') : '' }}</div>
                        </div>
                    </div>
                    @endif
                    @if($expiringStudents > 0)
                    <div style="padding:11px 14px;border-bottom:1px solid rgba(0,0,0,.05);display:flex;gap:10px;align-items:flex-start;">
                        <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;flex-shrink:0;margin-top:5px;"></span>
                        <div>
                            <div style="font-size:.76rem;color:#1e293b;font-weight:600;">{{ $expiringStudents }} طالب — فحص طبي منتهي أو ينتهي قريباً</div>
                            <div style="font-size:.65rem;color:#94a3b8;margin-top:2px;">راجع قائمة الطلاب للتفاصيل</div>
                        </div>
                    </div>
                    @endif
                    @if($totalAlerts === 0)
                    <div style="padding:20px;text-align:center;font-size:.78rem;color:#94a3b8;">
                        <i class="fas fa-check-circle" style="color:#10b981;margin-bottom:6px;display:block;font-size:1.2rem;"></i>
                        لا توجد تنبيهات
                    </div>
                    @endif
                </div>
            </div>

            <a href="{{ route('school.students.index') }}" class="topbar-btn" title="إضافة طالب">
                <i class="fas fa-user-plus"></i>
            </a>

            <div class="topbar-user">
                <div class="user-avatar">{{ mb_substr(Auth::user()->name ?? 'م', 0, 1) }}</div>
                <div class="d-none d-sm-block">
                    <div class="user-name">{{ Auth::user()->name ?? 'مدير المدرسة' }}</div>
                    <div class="user-role">مدير المدرسة</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('school.dashboard') }}" style="color:var(--text-muted); text-decoration:none;">مسار</a></li>
                <li class="breadcrumb-item active">@yield('page_title', 'لوحة التحكم')</li>
            </ol>
        </nav>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert-pro success">
                <div class="alert-icon"><i class="fas fa-check"></i></div>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert-pro error">
                <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Theme Toggle
    function toggleSchoolTheme() {
        const body = document.body;
        const isDark = !body.classList.contains('dark-mode');
        body.classList.toggle('dark-mode', isDark);
        localStorage.setItem('schoolTheme', isDark ? 'dark' : 'light');
        document.getElementById('schoolThemeIcon').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
    }
    (function() {
        const saved = localStorage.getItem('schoolTheme');
        if (saved === 'dark') {
            document.body.classList.add('dark-mode');
            const icon = document.getElementById('schoolThemeIcon');
            if (icon) icon.className = 'fas fa-sun';
        }
    })();

    // Notification bell
    function toggleSchoolNotif() {
        const panel = document.getElementById('schoolNotifPanel');
        if (panel) panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('schoolNotifWrap');
        if (wrap && !wrap.contains(e.target)) {
            const panel = document.getElementById('schoolNotifPanel');
            if (panel) panel.style.display = 'none';
        }
    });

    // Sidebar toggle
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const toggle   = document.getElementById('sidebarToggle');

    toggle?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });
    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });

    // Live Clock
    function updateClock() {
        const el = document.getElementById('liveClock');
        if (!el) return;
        el.textContent = new Date().toLocaleTimeString('ar-PS', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
@yield('scripts')
</body>
</html>
