@extends('layouts.school')

@section('title', 'لوحة التحكم')
@section('page_section', 'الرئيسية')
@section('page_title', 'نظرة عامة على مدرستك')

@section('styles')
<style>
    /* ====== STATS GRID ====== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }
    @media(max-width:1100px){ .stats-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:576px)  { .stats-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 22px;
        border: 1px solid var(--border);
        transition: transform .25s, box-shadow .25s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.08); }

    .stat-card .card-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 14px;
    }
    .stat-card .card-val {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-1);
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-card .card-label { font-size: .8rem; color: var(--text-muted); font-weight: 500; }
    .stat-card .card-sub   { font-size: .75rem; margin-top: 8px; font-weight: 600; }

    .stat-card.blue  .card-icon { background: rgba(59,130,246,.12); color: #3b82f6; }
    .stat-card.green .card-icon { background: rgba(16,185,129,.12); color: #10b981; }
    .stat-card.amber .card-icon { background: rgba(245,158,11,.12); color: #f59e0b; }
    .stat-card.red   .card-icon { background: rgba(239,68,68,.12); color: #ef4444; }

    /* Quota bar */
    .quota-bar {
        height: 6px;
        border-radius: 6px;
        background: var(--bg);
        overflow: hidden;
        margin-top: 10px;
    }
    .quota-bar-fill { height: 100%; border-radius: 6px; transition: width .8s ease; }

    /* ====== WELCOME BANNER ====== */
    .welcome-card {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-3) 100%);
        border-radius: 20px;
        padding: 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        border: 1px solid rgba(59,130,246,.2);
    }
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -60px; left: -60px;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(59,130,246,.3) 0%, transparent 70%);
        pointer-events: none;
    }
    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -40px; left: 40%;
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(16,185,129,.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .welcome-card .greeting { font-size: .82rem; color: rgba(255,255,255,.6); margin-bottom: 6px; }
    .welcome-card h1 { font-size: 1.6rem; font-weight: 800; margin-bottom: 6px; }
    .welcome-card p  { font-size: .88rem; color: rgba(255,255,255,.65); margin-bottom: 0; max-width: 500px; }

    .welcome-meta {
        display: flex; gap: 12px; flex-wrap: wrap; margin-top: 18px;
    }
    .meta-chip {
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: .75rem;
        color: rgba(255,255,255,.8);
        display: flex; align-items: center; gap: 6px;
    }
    .meta-chip .dot {
        width: 7px; height: 7px; border-radius: 50%;
        animation: blink 1.5s infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

    /* ====== SUBSCRIPTION ALERT ====== */
    .sub-alert {
        background: linear-gradient(90deg, #fef3c7, #fffbeb);
        border: 1px solid #fbbf24;
        border-radius: 14px;
        padding: 14px 18px;
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 20px;
        font-size: .85rem; font-weight: 600; color: #92400e;
    }
    .sub-alert-icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: #fbbf24; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0;
    }

    /* ====== TABLE CARD ====== */
    .table-card {
        background: var(--card-bg);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .table-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-header .title { font-size: .95rem; font-weight: 700; color: var(--text-1); }
    .table-card-header .sub   { font-size: .75rem; color: var(--text-muted); margin-top: 2px; }

    .t-table { width: 100%; border-collapse: collapse; }
    .t-table thead th {
        background: rgba(255,255,255,.03);
        padding: 12px 18px;
        font-size: .75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .t-table tbody td {
        padding: 14px 18px;
        font-size: .85rem;
        color: var(--text-2);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .t-table tbody tr:last-child td { border-bottom: none; }
    .t-table tbody tr:hover td { background: rgba(255,255,255,.05); }

    /* Student avatar */
    .s-avatar {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem;
        margin-left: 10px;
    }
    .s-name { font-weight: 600; color: var(--text-1); }
    .s-id   { font-size: .72rem; color: var(--text-muted); font-family: monospace; }

    /* Badges */
    .badge-status {
        padding: 4px 10px; border-radius: 20px;
        font-size: .72rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-studying { background: #eff6ff; color: #1d4ed8; }
    .badge-pass     { background: #ecfdf5; color: #065f46; }
    .badge-fail     { background: #fef2f2; color: #991b1b; }
    .badge-pending  { background: #fefce8; color: #713f12; }

    /* Quick actions */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    @media(max-width:576px){ .actions-grid { grid-template-columns: repeat(2,1fr); } }

    .action-btn {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        text-decoration: none;
        transition: all .2s;
        display: block;
    }
    .action-btn:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 16px rgba(59,130,246,.1);
        transform: translateY(-2px);
    }
    .action-btn .a-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        margin: 0 auto 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        transition: all .2s;
    }
    .action-btn:hover .a-icon { transform: scale(1.1); }
    .action-btn .a-label { font-size: .78rem; font-weight: 700; color: var(--text-1); }

    /* 2-col layout */
    .two-col { display: grid; grid-template-columns: 1fr 340px; gap: 20px; margin-top: 20px; }
    @media(max-width:1024px){ .two-col { grid-template-columns: 1fr; } }
    .right-col { display: flex; flex-direction: column; gap: 16px; }

    /* Activity list */
    .activity-list { padding: 0 22px 16px; }
    .activity-item {
        display: flex; gap: 12px; align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }
    .activity-item:last-child { border-bottom: none; }
    .a-dot {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; flex-shrink: 0; margin-top: 2px;
    }
    .a-dot.blue  { background: rgba(59,130,246,.12); color: #3b82f6; }
    .a-dot.green { background: rgba(16,185,129,.12); color: #10b981; }
    .a-dot.amber { background: rgba(245,158,11,.12); color: #f59e0b; }
    .a-text   { font-size: .82rem; font-weight: 600; color: var(--text-1); }
    .a-time   { font-size: .72rem; color: var(--text-muted); margin-top: 2px; }

    /* Animate on load */
    .animate-in {
        opacity: 0; transform: translateY(16px);
        animation: fadeUp .45s ease forwards;
    }
    @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }
</style>
@endsection

@section('content')
@php
    $school       = Auth::user()->school;
    $total        = $school->students()->count();
    $limit        = $school->student_limit;
    $quota        = $limit > 0 ? round($total / $limit * 100) : 0;
    $studying     = $school->students()->where('status','يدرس')->count();
    $passed       = $school->students()->where('status','ناجح')->count();
    $subEnd       = \Carbon\Carbon::parse($school->subscription_end);
    $daysLeft     = now()->diffInDays($subEnd, false);
    $recentStudents = $school->students()->latest()->take(8)->get();
    $financials   = $school->students()
                        ->select(\DB::raw('sum(total_amount) as total, sum(paid_amount) as paid'))
                        ->first();
    $remaining    = ($financials->total ?? 0) - ($financials->paid ?? 0);
@endphp

{{-- Subscription Alert --}}
@if($daysLeft > 0 && $daysLeft <= 10)
<div class="sub-alert animate-in" style="--delay:.05s">
    <div class="sub-alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div>
        <strong>تنبيه اشتراك:</strong>
        اشتراك مدرستك ينتهي خلال <strong>{{ floor($daysLeft) }} يوم</strong> ({{ $subEnd->format('Y-m-d') }}). يرجى التواصل مع الإدارة للتجديد.
    </div>
</div>
@elseif($daysLeft <= 0)
<div class="sub-alert animate-in" style="--delay:.05s; border-color:#ef4444; background:linear-gradient(90deg,#fef2f2,#fff5f5); color:#991b1b;">
    <div class="sub-alert-icon" style="background:#ef4444;"><i class="fas fa-ban"></i></div>
    <div><strong>الاشتراك منتهٍ.</strong> تواصل مع الإدارة لتجديد الاشتراك وتفعيل الخدمة.</div>
</div>
@endif

{{-- Welcome Banner --}}
<div class="welcome-card animate-in" style="animation-delay:.05s">
    <div class="greeting">مرحباً بك في نظام مسار ✦</div>
    <h1>{{ $school->name }}</h1>
    <p>لوحة إدارة شاملة لمتابعة طلابك، امتحاناتهم، والحالة المالية — كل ما تحتاجه في مكان واحد.</p>
    <div class="welcome-meta">
        <div class="meta-chip">
            <span class="dot" style="background:#10b981;"></span>
            النظام يعمل بشكل طبيعي
        </div>
        <div class="meta-chip">
            <i class="fas fa-calendar-alt" style="font-size:.7rem;"></i>
            {{ \Carbon\Carbon::now()->translatedFormat('l، j F Y') }}
        </div>
        <div class="meta-chip">
            <i class="fas fa-key" style="font-size:.7rem;"></i>
            كود: {{ $school->school_code }}
        </div>
        <div class="meta-chip">
            <i class="fas fa-layer-group" style="font-size:.7rem;"></i>
            {{ $school->plan == 'yearly' ? 'اشتراك سنوي' : 'اشتراك شهري' }}
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card blue animate-in" style="animation-delay:.1s">
        <div class="card-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="card-val">{{ $total }}</div>
        <div class="card-label">إجمالي الطلاب المسجلين</div>
        <div class="quota-bar">
            <div class="quota-bar-fill" style="width:{{ min($quota,100) }}%; background:{{ $quota>90 ? '#ef4444' : ($quota>70 ? '#f59e0b' : '#3b82f6') }};"></div>
        </div>
        <div class="card-sub" style="color:{{ $quota>90 ? '#ef4444' : 'var(--text-muted)' }};">
            {{ $total }} / {{ $limit }} من الكوتة ({{ $quota }}%)
        </div>
    </div>

    <div class="stat-card green animate-in" style="animation-delay:.15s">
        <div class="card-icon"><i class="fas fa-book-open"></i></div>
        <div class="card-val">{{ $studying }}</div>
        <div class="card-label">طلاب في مرحلة الدراسة</div>
        <div class="card-sub" style="color:#10b981;">
            <i class="fas fa-circle" style="font-size:.5rem;"></i>
            نشطون حالياً في المنصة
        </div>
    </div>

    <div class="stat-card amber animate-in" style="animation-delay:.2s">
        <div class="card-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="card-val {{ $daysLeft < 7 && $daysLeft > 0 ? 'text-danger' : '' }}">
            @if($daysLeft > 0) {{ floor($daysLeft) }}
            @else <span style="font-size:1.2rem;">منتهٍ</span>
            @endif
        </div>
        <div class="card-label">أيام متبقية على الاشتراك</div>
        <div class="card-sub" style="color:#f59e0b;">ينتهي {{ $subEnd->format('Y-m-d') }}</div>
    </div>

    <div class="stat-card red animate-in" style="animation-delay:.25s">
        <div class="card-icon"><i class="fas fa-coins"></i></div>
        <div class="card-val" style="font-size:1.4rem;">{{ number_format($remaining) }}</div>
        <div class="card-label">مبالغ مستحقة غير محصلة (₪)</div>
        <div class="card-sub" style="color:var(--text-muted);">
            إجمالي محصّل: {{ number_format($financials->paid ?? 0) }} ₪
        </div>
    </div>
</div>

{{-- Two Column Section --}}
<div class="two-col">
    {{-- Recent Students Table --}}
    <div class="table-card animate-in" style="animation-delay:.3s">
        <div class="table-card-header">
            <div>
                <div class="title">آخر الطلاب المسجلين</div>
                <div class="sub">أحدث {{ count($recentStudents) }} طالب تم إضافتهم</div>
            </div>
            <a href="{{ route('school.students.index') }}"
               style="font-size:.78rem; font-weight:700; color:var(--accent); text-decoration:none;">
                عرض الكل <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <div style="overflow-x:auto;">
            <table class="t-table">
                <thead>
                    <tr>
                        <th>الطالب</th>
                        <th>الهوية</th>
                        <th>نوع الرخصة</th>
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentStudents as $st)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                @if($st->image)
                                    <img src="{{ asset('storage/'.$st->image) }}"
                                         style="width:36px;height:36px;border-radius:10px;object-fit:cover;">
                                @else
                                    <span class="s-avatar">{{ mb_substr($st->name,0,1) }}</span>
                                @endif
                                <div>
                                    <div class="s-name">{{ $st->name }}</div>
                                    <div class="s-id">{{ $st->phone ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-family:monospace;font-size:.8rem;color:#64748b;">{{ $st->identity_number }}</td>
                        <td>
                            <span style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;">
                                {{ $st->license_type ?: 'غير محدد' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $badges = ['يدرس'=>'studying','ناجح'=>'pass','راسب'=>'fail','موقوف'=>'pending'];
                                $cls = $badges[$st->status] ?? 'pending';
                                $icons = ['يدرس'=>'fa-book-open','ناجح'=>'fa-check-circle','راسب'=>'fa-times-circle','موقوف'=>'fa-pause-circle'];
                                $icon = $icons[$st->status] ?? 'fa-circle';
                            @endphp
                            <span class="badge-status badge-{{ $cls }}">
                                <i class="fas {{ $icon }}"></i> {{ $st->status }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:.78rem;">{{ $st->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">
                            <i class="fas fa-user-slash" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            لا يوجد طلاب مسجلون حتى الآن
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="right-col">
        {{-- Quick Actions --}}
        <div class="table-card animate-in" style="animation-delay:.35s">
            <div class="table-card-header">
                <div>
                    <div class="title">إجراءات سريعة</div>
                </div>
            </div>
            <div style="padding:16px;">
                <div class="actions-grid">
                    <a href="{{ route('school.students.index') }}" class="action-btn">
                        <div class="a-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-user-plus"></i></div>
                        <div class="a-label">إضافة طالب</div>
                    </a>
                    <a href="{{ route('school.students.index') }}" class="action-btn">
                        <div class="a-icon" style="background:#ecfdf5;color:#10b981;"><i class="fas fa-search"></i></div>
                        <div class="a-label">بحث طالب</div>
                    </a>
                    <a href="{{ route('school.signals-exam.index') }}" class="action-btn">
                        <div class="a-icon" style="background:#fffbeb;color:#f59e0b;"><i class="fas fa-traffic-light"></i></div>
                        <div class="a-label">امتحان إشارات</div>
                    </a>
                    <a href="{{ route('school.practical-exam.index') }}" class="action-btn">
                        <div class="a-icon" style="background:#fdf4ff;color:#a855f7;"><i class="fas fa-car"></i></div>
                        <div class="a-label">امتحان عملي</div>
                    </a>
                    <a href="{{ route('school.students.index') }}" class="action-btn">
                        <div class="a-icon" style="background:#fff7ed;color:#ea580c;"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="a-label">الحالة المالية</div>
                    </a>
                    <a href="{{ route('school.students.index') }}" class="action-btn">
                        <div class="a-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-print"></i></div>
                        <div class="a-label">طباعة تقرير</div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Quota Card --}}
        <div class="table-card animate-in" style="animation-delay:.4s">
            <div class="table-card-header">
                <div>
                    <div class="title">استهلاك الكوتة</div>
                    <div class="sub">{{ $total }} من أصل {{ $limit }} مقعد</div>
                </div>
                <span style="font-size:1.4rem;font-weight:800;color:{{ $quota>90?'#ef4444':'#3b82f6' }};">{{ $quota }}%</span>
            </div>
            <div style="padding:16px 22px 20px;">
                <div style="height:10px;background:var(--bg);border-radius:10px;overflow:hidden;">
                    <div style="height:100%;width:{{ min($quota,100) }}%;border-radius:10px;
                        background:{{ $quota>90 ? 'linear-gradient(90deg,#ef4444,#dc2626)' : ($quota>70 ? 'linear-gradient(90deg,#f59e0b,#d97706)' : 'linear-gradient(90deg,#3b82f6,#2563eb)') }};
                        transition:width 1s ease;">
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:10px;font-size:.75rem;color:var(--text-muted);font-weight:600;">
                    <span>{{ $total }} مسجل</span>
                    <span>{{ max(0, $limit - $total) }} مقعد متبقي</span>
                </div>

                @php
                    $byStatus = [
                        ['يدرس', $studying, '#3b82f6'],
                        ['ناجح',  $passed,  '#10b981'],
                        ['موقوف', $school->students()->where('status','موقوف')->count(), '#f59e0b'],
                    ];
                @endphp
                <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px;">
                    @foreach($byStatus as [$label, $count, $color])
                    <div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;">
                        <span style="display:flex;align-items:center;gap:6px;color:var(--text-1);font-weight:600;">
                            <span style="width:8px;height:8px;border-radius:50%;background:{{ $color }};display:inline-block;"></span>
                            {{ $label }}
                        </span>
                        <span style="font-weight:700;color:{{ $color }};">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
