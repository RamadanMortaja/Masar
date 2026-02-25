{{--
    =====================================================
    بوابة الطالب - لوحة التحكم الرئيسية
    نفس اللوجيك البرمجي الأصلي (Dexie + Pulse + Sync)
    بتصميم احترافي محسّن
    =====================================================
--}}
@extends('layouts.student')

@section('content')

@php
    // ====================================================
    // نفس اللوجيك الأصلي تماماً - جلب بيانات الطالب
    // ====================================================
    $studentId = session('student_id');
    $student = \App\Models\Student::with('school')->find($studentId);
    $firstName = explode(' ', $student->name ?? session('student_name', 'الطالب'))[0];
@endphp

{{-- ============================================================
     الـ CSS الكامل للداشبورد
     ============================================================ --}}
<style>
    /* ---- المتغيرات الأساسية ---- */
    :root {
        --bg: #f0f4ff;
        --surface: #ffffff;
        --surface2: #f8faff;
        --border: rgba(99, 120, 255, 0.1);
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --accent: #4f6ef7;
        --accent-light: rgba(79, 110, 247, 0.1);
        --accent-glow: rgba(79, 110, 247, 0.25);
        --success: #10b981;
        --success-light: rgba(16, 185, 129, 0.1);
        --danger: #ef4444;
        --danger-light: rgba(239, 68, 68, 0.1);
        --warning: #f59e0b;
        --warning-light: rgba(245, 158, 11, 0.1);
        --radius: 20px;
        --radius-sm: 12px;
        --shadow: 0 4px 24px rgba(79, 110, 247, 0.08);
        --shadow-md: 0 8px 32px rgba(79, 110, 247, 0.12);
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ---- الوضع الليلي ---- */
    body.dark-mode {
        --bg: #080e1a;
        --surface: #111827;
        --surface2: #1a2234;
        --border: rgba(79, 110, 247, 0.15);
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --text-muted: #64748b;
        --accent-light: rgba(79, 110, 247, 0.15);
        --shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
        --shadow-md: 0 8px 32px rgba(0, 0, 0, 0.5);
    }

    /* ---- الجسم والأساس ---- */
    body {
        background: var(--bg) !important;
        font-family: 'Cairo', 'Segoe UI', sans-serif;
        transition: background 0.4s ease, color 0.4s ease;
    }

    .dash-page {
        padding: 0 0 100px;
        max-width: 500px;
        margin: 0 auto;
    }

    /* ---- هيدر الترحيب ---- */
    .hero-header {
        background: linear-gradient(145deg, #1e3a8a 0%, #4f6ef7 60%, #818cf8 100%);
        padding: 28px 20px 24px;
        position: relative;
        overflow: hidden;
    }

    .hero-header::before {
        content: '';
        position: absolute;
        top: -60px; left: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .hero-header::after {
        content: '';
        position: absolute;
        bottom: -80px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }

    .hero-greeting small {
        display: block;
        font-size: 12px;
        color: rgba(255,255,255,0.65);
        margin-bottom: 4px;
    }

    .hero-greeting h1 {
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        margin: 0;
        line-height: 1.2;
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sync-badge {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: var(--transition);
    }

    .sync-badge.synced {
        background: rgba(16, 185, 129, 0.25);
        border-color: rgba(16, 185, 129, 0.4);
    }

    .theme-btn {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: #fff;
        font-size: 15px;
        transition: var(--transition);
    }

    .theme-btn:hover { background: rgba(255,255,255,0.25); transform: rotate(20deg); }

    /* شريط التقدم في الهيدر */
    .hero-progress-section {
        position: relative;
        z-index: 2;
    }

    .hero-progress-label {
        display: flex;
        justify-content: space-between;
        color: rgba(255,255,255,0.8);
        font-size: 12px;
        margin-bottom: 8px;
    }

    .hero-progress-bar-wrap {
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
        margin-bottom: 18px;
    }

    .hero-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #a5f3fc, #6ee7b7);
        border-radius: 10px;
        width: 0%;
        transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .hero-progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* إحصائيات الهيدر الثلاثة */
    .hero-stats {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1px;
        background: rgba(255,255,255,0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .hero-stat {
        background: rgba(255,255,255,0.07);
        padding: 12px 8px;
        text-align: center;
        backdrop-filter: blur(4px);
        transition: var(--transition);
    }

    .hero-stat:hover { background: rgba(255,255,255,0.12); }

    .hero-stat .val {
        display: block;
        font-size: 20px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 4px;
    }

    .hero-stat .lbl {
        font-size: 11px;
        color: rgba(255,255,255,0.6);
    }

    /* ---- المحتوى الرئيسي ---- */
    .dash-body {
        padding: 20px 16px 0;
    }

    /* ---- بطاقات عامة ---- */
    .d-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: var(--transition);
        margin-bottom: 16px;
    }

    .d-card:hover { box-shadow: var(--shadow-md); }

    .d-card-header {
        padding: 16px 18px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border);
    }

    .d-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .d-card-title .dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .d-card-body { padding: 16px 18px; }

    /* ---- بطاقة بيانات الطالب ---- */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-icon {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .info-icon.blue { background: rgba(79, 110, 247, 0.1); color: var(--accent); }
    .info-icon.green { background: var(--success-light); color: var(--success); }
    .info-icon.amber { background: var(--warning-light); color: var(--warning); }
    .info-icon.teal { background: rgba(20, 184, 166, 0.1); color: #14b8a6; }
    .info-icon.red { background: var(--danger-light); color: var(--danger); }

    .info-label {
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 2px;
    }

    .info-val {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    /* بيانات الفحص الطبي تمتد على كامل العرض */
    .info-full {
        grid-column: 1 / -1;
        padding-top: 14px;
        border-top: 1px solid var(--border);
    }

    .medical-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 6px;
    }

    .med-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .med-badge.neutral { background: var(--surface2); color: var(--text-secondary); border: 1px solid var(--border); }
    .med-badge.danger { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(239,68,68,0.15); }

    /* ---- نصيحة ذكية ---- */
    .smart-hint {
        display: none;
        background: linear-gradient(135deg, var(--accent-light), rgba(129, 140, 248, 0.08));
        border: 1px solid rgba(79, 110, 247, 0.2);
        border-radius: var(--radius);
        padding: 16px 18px;
        margin-bottom: 16px;
        flex-direction: row-reverse;
        align-items: flex-start;
        gap: 12px;
        animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
        from { opacity:0; transform: translateY(-8px); }
        to { opacity:1; transform: translateY(0); }
    }

    .hint-icon {
        width: 44px; height: 44px;
        background: var(--accent);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px var(--accent-glow);
    }

    .hint-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 3px;
    }

    .hint-body {
        font-size: 12px;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* ---- أزرار التدريب والامتحان ---- */
    .action-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .action-btn {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 14px;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: var(--transition);
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.25s;
    }

    .action-btn:hover::before { opacity: 1; }
    .action-btn:active { transform: scale(0.97); }

    .action-btn.primary { border-color: rgba(79, 110, 247, 0.3); }
    .action-btn.primary::before { background: linear-gradient(135deg, rgba(79,110,247,0.04), rgba(79,110,247,0.08)); }

    .action-btn.exam-btn {
        border: 2px solid var(--accent);
        background: linear-gradient(135deg, var(--accent-light), rgba(129,140,248,0.06));
    }

    .action-btn.exam-btn::before {
        background: linear-gradient(135deg, rgba(79,110,247,0.08), rgba(129,140,248,0.1));
    }

    .action-icon {
        width: 54px; height: 54px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        margin: 0 auto 10px;
    }

    .action-icon.blue { background: var(--accent-light); color: var(--accent); }
    .action-icon.blue-solid { background: var(--accent); color: #fff; box-shadow: 0 4px 16px var(--accent-glow); }

    .action-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 3px;
    }

    .action-sub {
        font-size: 10px;
        color: var(--text-muted);
    }

    /* ---- بنك الأخطاء ---- */
    .errors-card {
        display: none;
        background: linear-gradient(135deg, rgba(239,68,68,0.07), rgba(239,68,68,0.03));
        border: 1px solid rgba(239,68,68,0.2);
        border-radius: var(--radius);
        padding: 16px 18px;
        margin-bottom: 16px;
        flex-direction: row-reverse;
        align-items: center;
        gap: 14px;
        text-decoration: none;
        transition: var(--transition);
    }

    .errors-card:hover { background: rgba(239,68,68,0.1); transform: translateY(-1px); }
    .errors-card:active { transform: scale(0.98); }

    .errors-icon {
        width: 50px; height: 50px;
        background: var(--danger);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(239,68,68,0.3);
    }

    .errors-info { flex: 1; }
    .errors-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 2px;
    }

    .errors-sub {
        font-size: 11px;
        color: var(--text-secondary);
    }

    .errors-arrow {
        color: var(--danger);
        font-size: 16px;
        opacity: 0.6;
    }

    /* ---- روابط القائمة ---- */
    .nav-list {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 16px;
    }

    .nav-link-item {
        display: flex;
        align-items: center;
        flex-direction: row-reverse;
        gap: 14px;
        padding: 15px 18px;
        text-decoration: none;
        transition: var(--transition);
        border-bottom: 1px solid var(--border);
    }

    .nav-link-item:last-child { border-bottom: none; }

    .nav-link-item:hover {
        background: var(--surface2);
    }

    .nav-link-item:active { transform: scale(0.99); }

    .nav-link-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .nav-link-text { flex: 1; }
    .nav-link-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        display: block;
    }
    .nav-link-sub {
        font-size: 11px;
        color: var(--text-muted);
    }

    .nav-link-arrow {
        color: var(--text-muted);
        font-size: 13px;
    }

    /* ---- آخر الامتحانات ---- */
    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding: 0 2px;
    }

    .section-title h6 {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .section-title a {
        font-size: 12px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
    }

    /* بطاقة نتيجة الامتحان */
    .result-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        transition: var(--transition);
        box-shadow: var(--shadow);
    }

    .result-card:hover { box-shadow: var(--shadow-md); }

    .result-icon {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .result-icon.success { background: var(--success-light); color: var(--success); }
    .result-icon.fail { background: var(--danger-light); color: var(--danger); }

    .result-info { flex: 1; }
    .result-status {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .result-date {
        font-size: 11px;
        color: var(--text-muted);
    }

    .result-score {
        font-size: 20px;
        font-weight: 800;
    }

    .result-score.success { color: var(--success); }
    .result-score.fail { color: var(--danger); }

    /* رسالة فارغة */
    .empty-msg {
        background: var(--surface);
        border: 1px dashed var(--border);
        border-radius: var(--radius-sm);
        padding: 24px;
        text-align: center;
        color: var(--text-muted);
        font-size: 12px;
    }

    .empty-msg .empty-icon { font-size: 32px; margin-bottom: 8px; opacity: 0.5; }

    /* ---- تنبيهات (Alerts) ---- */
    .alert-banner {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.25);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-bottom: 12px;
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: #92400e;
        font-weight: 600;
        animation: pulse-border 2s infinite;
    }

    body.dark-mode .alert-banner { color: #fbbf24; }

    @keyframes pulse-border {
        0%, 100% { border-color: rgba(245, 158, 11, 0.25); }
        50% { border-color: rgba(245, 158, 11, 0.5); }
    }

    /* ---- loading skeleton ---- */
    .skeleton {
        background: linear-gradient(90deg, var(--surface2) 25%, var(--border) 50%, var(--surface2) 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* ---- Animations عند التحميل ---- */
    .fade-up {
        opacity: 0;
        transform: translateY(14px);
        animation: fadeUp 0.5s ease forwards;
    }

    .fade-up:nth-child(1) { animation-delay: 0.05s; }
    .fade-up:nth-child(2) { animation-delay: 0.12s; }
    .fade-up:nth-child(3) { animation-delay: 0.19s; }
    .fade-up:nth-child(4) { animation-delay: 0.26s; }
    .fade-up:nth-child(5) { animation-delay: 0.33s; }
    .fade-up:nth-child(6) { animation-delay: 0.40s; }

    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>

{{-- ============================================================
     الهيدر - منطقة الترحيب والإحصائيات
     ============================================================ --}}
<div class="hero-header">

    {{-- الصف الأول: الاسم + الأزرار --}}
    <div class="hero-top">
        <div class="hero-greeting">
            <small>مرحباً بك في مسارك 👋</small>
            <h1>{{ $firstName }}</h1>
        </div>
        <div class="hero-actions">
            {{-- شارة المزامنة - نفس id الأصلي --}}
            <div id="db-status-badge" class="sync-badge">
                <span id="overall-percent">0%</span>
                <i class="fas fa-sync-alt fa-sm"></i>
            </div>
            {{-- زر الوضع الليلي - نفس id الأصلي --}}
            <button class="theme-btn" onclick="toggleDarkMode()">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>
        </div>
    </div>

    {{-- شريط التقدم --}}
    <div class="hero-progress-section">
        <div class="hero-progress-label">
            <span>إجمالي الإنجاز</span>
            <span id="sync-text">جاري الفحص...</span>
        </div>
        <div class="hero-progress-bar-wrap">
            <div class="hero-progress-bar-fill" id="overall-progress-bar"></div>
        </div>

        {{-- الإحصائيات الثلاث - نفس ids الأصلية --}}
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="val" id="stat-viewed">0</span>
                <span class="lbl">تم حله</span>
            </div>
            <div class="hero-stat">
                <span class="val" style="color: #fbbf24;" id="stat-wrong">0</span>
                <span class="lbl">أخطاء</span>
            </div>
            <div class="hero-stat">
                <span class="val" style="color: #6ee7b7;" id="stat-ratio">0%</span>
                <span class="lbl">نجاح الامتحانات</span>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     المحتوى الرئيسي
     ============================================================ --}}
<div class="dash-body">

    {{-- ---- 1. تنبيهات الفحص الطبي (نفس اللوجيك الأصلي) ---- --}}
    @if(isset($alerts) && count($alerts) > 0)
        @foreach($alerts as $alert)
        <div class="alert-banner fade-up">
            <i class="fas fa-exclamation-triangle" style="color: var(--warning); font-size: 16px;"></i>
            <span>{{ $alert }}</span>
        </div>
        @endforeach
    @endif

    {{-- ---- 2. بيانات الملف التدريبي ---- --}}
    @if($student)
    <div class="d-card fade-up">
        <div class="d-card-header">
            <div class="d-card-title">
                <div class="dot"></div>
                الملف التدريبي
            </div>
            <span style="font-size: 11px; color: var(--text-muted); background: var(--surface2); padding: 4px 10px; border-radius: 20px; border: 1px solid var(--border);">
                {{ $student->status ?? 'يدرس' }}
            </span>
        </div>
        <div class="d-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon blue"><i class="fas fa-school"></i></div>
                    <div>
                        <div class="info-label">المدرسة</div>
                        <div class="info-val">{{ $student->school->name ?? 'غير محدد' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon green"><i class="fas fa-id-badge"></i></div>
                    <div>
                        <div class="info-label">نوع الرخصة</div>
                        <div class="info-val">{{ $student->license_type ?? 'غير محدد' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon amber"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <div class="info-label">نوع الإشارات</div>
                        <div class="info-val">{{ $student->theory_type ?? 'غير محدد' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon teal"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="info-label">تاريخ الامتحان</div>
                        <div class="info-val" dir="ltr" style="text-align: right;">
                            {{ $student->theory_exam_date ?? 'غير محدد' }}
                        </div>
                    </div>
                </div>

                {{-- الفحص الطبي - سطر كامل --}}
                <div class="info-item info-full">
                    <div class="info-icon red"><i class="fas fa-notes-medical"></i></div>
                    <div style="flex:1">
                        <div class="info-label">صلاحية الفحص الطبي</div>
                        <div class="medical-badges">
                            <span class="med-badge neutral">
                                <i class="fas fa-calendar-plus fa-xs"></i>
                                إصدار: {{ $student->medical_test_date ?? '---' }}
                            </span>
                            <span class="med-badge danger">
                                <i class="fas fa-calendar-times fa-xs"></i>
                                انتهاء: {{ $student->medical_test_expiry ?? '---' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ---- 3. النصيحة الذكية (نفس id الأصلي) ---- --}}
    <div id="smart-hint" class="smart-hint fade-up">
        <div class="hint-icon"><i class="fas fa-lightbulb"></i></div>
        <div>
            <div class="hint-title">نصيحة مسارك</div>
            <p class="hint-body mb-0" id="hint-text"></p>
        </div>
    </div>

    {{-- ---- 4. أزرار التدريب والامتحان ---- --}}
    <div class="action-grid fade-up">
        <a href="{{ route('student.practice.modes') }}" class="action-btn primary">
            <div class="action-icon blue">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="action-label">التدريب الذكي</div>
            <div class="action-sub">تعلم خطوة بخطوة</div>
        </a>
        <a href="{{ route('student.exam') }}" class="action-btn exam-btn">
            <div class="action-icon blue-solid">
                <i class="fas fa-bolt"></i>
            </div>
            <div class="action-label">امتحان تجريبي</div>
            <div class="action-sub">30 سؤال • محاكاة حقيقية</div>
        </a>
    </div>

    {{-- ---- 5. بنك الأخطاء (نفس id الأصلي، يظهر فقط إذا كان هناك أخطاء) ---- --}}
    <a href="{{ route('student.practice.view', ['mode' => 'errors']) }}"
       id="wrong-questions-box"
       class="errors-card fade-up">
        <div class="errors-icon"><i class="fas fa-times-circle"></i></div>
        <div class="errors-info">
            <div class="errors-title">بنك الأخطاء</div>
            <div class="errors-sub">راجع الأسئلة التي أخطأت بها لضمان النجاح</div>
        </div>
        <i class="fas fa-chevron-left errors-arrow"></i>
    </a>

    {{-- ---- 6. روابط سريعة ---- --}}
    <div class="nav-list fade-up">
        <a href="{{ route('student.traffic.signs') }}" class="nav-link-item">
            <div class="nav-link-icon" style="background: rgba(20,184,166,0.1); color: #14b8a6;">
                <i class="fas fa-traffic-light"></i>
            </div>
            <div class="nav-link-text">
                <span class="nav-link-title">موسوعة الإشارات</span>
                <span class="nav-link-sub">تعرف على جميع إشارات المرور</span>
            </div>
            <i class="fas fa-chevron-left nav-link-arrow"></i>
        </a>

        <a href="{{ route('student.exam.history') }}" class="nav-link-item">
            <div class="nav-link-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                <i class="fas fa-history"></i>
            </div>
            <div class="nav-link-text">
                <span class="nav-link-title">سجل النتائج</span>
                <span class="nav-link-sub">راجع امتحاناتك السابقة</span>
            </div>
            <i class="fas fa-chevron-left nav-link-arrow"></i>
        </a>

        <a href="{{ route('student.financial') }}" class="nav-link-item">
            <div class="nav-link-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="nav-link-text">
                <span class="nav-link-title">الحساب المالي</span>
                <span class="nav-link-sub">تفاصيل الرسوم والمدفوعات</span>
            </div>
            <i class="fas fa-chevron-left nav-link-arrow"></i>
        </a>

        <a href="{{ route('student.profile') }}" class="nav-link-item">
            <div class="nav-link-icon" style="background: rgba(79,110,247,0.1); color: var(--accent);">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="nav-link-text">
                <span class="nav-link-title">ملفي الشخصي</span>
                <span class="nav-link-sub">بياناتي وإنجازاتي</span>
            </div>
            <i class="fas fa-chevron-left nav-link-arrow"></i>
        </a>
    </div>

    {{-- ---- 7. آخر الامتحانات (نفس id الأصلي) ---- --}}
    <div id="recent-results-area" class="fade-up">
        <div class="section-title">
            <h6>آخر الامتحانات</h6>
            <a href="{{ route('student.exam.history') }}">
                عرض الكل <i class="fas fa-arrow-left fa-xs"></i>
            </a>
        </div>
        <div id="recent-results-list">
            <div class="empty-msg">
                <div class="empty-icon">📋</div>
                لا توجد امتحانات مسجلة بعد
            </div>
        </div>
    </div>

</div>
{{-- /dash-body --}}

@endsection

@section('scripts')
{{-- Dexie.js - نفس المكتبة الأصلية --}}
<script src="https://unpkg.com/dexie/dist/dexie.js"></script>
<script src="{{ asset('js/student/offline-engine.js') }}?v={{ time() }}"></script>
<script>
// ============================================================
// اللوجيك البرمجي - نفس الكود الأصلي بالكامل (بدون تعديل)
// فقط تحسين طريقة عرض النتائج في الـ HTML
// ============================================================

// --- 1. الوضع الليلي (Dark Mode) --- نفس الأصل
function toggleDarkMode() {
    const body = document.body;
    const icon = document.getElementById('theme-icon');
    body.classList.toggle('dark-mode');

    if (body.classList.contains('dark-mode')) {
        icon.classList.replace('fa-moon', 'fa-sun');
        localStorage.setItem('theme', 'dark');
    } else {
        icon.classList.replace('fa-sun', 'fa-moon');
        localStorage.setItem('theme', 'light');
    }
}

// التحقق من الوضع المحفوظ مسبقاً
if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
    document.getElementById('theme-icon').classList.replace('fa-moon', 'fa-sun');
}

// --- 2. تهيئة قاعدة Dexie --- نفس الأصل
const db = new Dexie("MasarOfflineDB");
db.version(11).stores({
    questions: "id, category, is_viewed",
    traffic_signs: "id, category, code",
    exam_results: "++id, student_id, score, status, date, synced",
    wrong_questions: "q_id, count"
});

// --- 3. تحديث واجهة الداشبورد --- نفس اللوجيك الأصلي
async function updateDashboardUI() {
    try {
        if (!db.isOpen()) await db.open();

        // حساب الإنجاز
        const allQ = await db.questions.toArray();
        const viewedQ = allQ.filter(q => q.is_viewed === 1);
        const totalQ = allQ.length;
        const viewedCount = viewedQ.length;

        const percent = totalQ > 0 ? Math.round((viewedCount / totalQ) * 100) : 0;

        document.getElementById('overall-percent').innerText = percent + '%';
        document.getElementById('overall-progress-bar').style.width = percent + '%';
        document.getElementById('stat-viewed').innerText = viewedCount;

        if (totalQ > 0) {
            document.getElementById('sync-text').innerText = "✓ " + totalQ + " سؤال متزامن";
            const badge = document.getElementById('db-status-badge');
            badge.classList.add('synced');
        }

        // الأخطاء
        const wrongData = await db.wrong_questions.toArray();
        const wrongCount = wrongData.length;
        document.getElementById('stat-wrong').innerText = wrongCount;

        // إظهار/إخفاء بنك الأخطاء
        const wrongBox = document.getElementById('wrong-questions-box');
        if (wrongCount > 0) {
            wrongBox.style.display = 'flex';
        } else {
            wrongBox.style.display = 'none';
        }

        // النصيحة الذكية - نفس المنطق الأصلي
        const hintBox = document.getElementById('smart-hint');
        const hintText = document.getElementById('hint-text');

        if (wrongCount >= 5) {
            hintBox.style.display = 'flex';
            hintBox.querySelector('.hint-icon').style.background = '#ef4444';
            hintText.innerText = `احترس! تراكمت لديك ${wrongCount} أخطاء، قم بمراجعتها قبل الامتحان.`;
        } else if (percent > 0 && percent < 100) {
            hintBox.style.display = 'flex';
            hintText.innerText = `لقد أنجزت ${percent}% من البنك، استمر في التدريب للوصول للعلامة الكاملة!`;
        } else if (percent === 100) {
            hintBox.style.display = 'flex';
            hintBox.querySelector('.hint-icon').style.background = '#10b981';
            hintText.innerText = `عمل رائع! لقد ختمت كل الأسئلة، أنت جاهز للامتحان التجريبي بقوة.`;
        } else {
            hintBox.style.display = 'flex';
            hintText.innerText = `ابدأ بـ "التدريب الذكي" لتتعلم القوانين والإشارات خطوة بخطوة.`;
        }

        // إحصائيات الامتحانات - نفس الأصل
        const results = await db.exam_results.toArray();
        const totalExams = results.length;
        const successExams = results.filter(r => r.status === 'ناجح').length;
        const ratio = totalExams > 0 ? Math.round((successExams / totalExams) * 100) : 0;
        document.getElementById('stat-ratio').innerText = ratio + "%";

        // عرض آخر 3 نتائج - بتصميم محسّن
        const recentResultsList = document.getElementById('recent-results-list');
        if (totalExams > 0) {
            const recent = results.slice(-3).reverse();
            let html = '';
            recent.forEach(res => {
                const isSuccess = res.status === 'ناجح';
                const dateStr = new Date(res.date).toLocaleDateString('ar-EG', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });
                html += `
                    <div class="result-card">
                        <div class="result-icon ${isSuccess ? 'success' : 'fail'}">
                            <i class="fas ${isSuccess ? 'fa-check' : 'fa-times'}"></i>
                        </div>
                        <div class="result-info">
                            <div class="result-status">${res.status}</div>
                            <div class="result-date">${dateStr}</div>
                        </div>
                        <div class="result-score ${isSuccess ? 'success' : 'fail'}">${res.score}<span style="font-size:12px;opacity:.5">/30</span></div>
                    </div>`;
            });
            recentResultsList.innerHTML = html;
        }

    } catch (e) {
        console.error("Dashboard Sync Error:", e);
    }
}

// --- 4. النبض (Pulse) --- نفس الأصل
async function sendPulse() {
    try {
        const viewedCount = await db.questions.where('is_viewed').equals(1).count();
        const eCount = await db.wrong_questions.count();

        await fetch("{{ route('student.pulse') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                viewed_questions_count: viewedCount,
                errors_count: eCount
            })
        });
    } catch (err) {
        console.warn("Pulse failed (offline mode)");
    }
}

// --- التنفيذ عند التحميل --- نفس الأصل
document.addEventListener('DOMContentLoaded', () => {
    updateDashboardUI();
    setInterval(updateDashboardUI, 5000);
    setTimeout(sendPulse, 2000);
    setInterval(sendPulse, 60000);
});

window.addEventListener('load', () => {
    setTimeout(syncAllFromCloud, 1000);
});
</script>
@endsection
