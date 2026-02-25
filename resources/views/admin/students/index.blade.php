@extends($layout)

@section('title', 'إدارة الطلاب')
@section('page_section', 'إدارة النظام')
@section('page_title', 'إدارة شؤون الطلاب')

@section('styles')
<style>
/* ── CSS vars bridge: works under BOTH admin layout (dark) and school layout (light) ── */
:root {
    /* Fallback values if not defined by parent layout */
    --st-card-bg:     var(--bg-card, var(--card-bg, #ffffff));
    --st-surface:     var(--bg-surface, var(--card-bg, #f8fafc));
    --st-hover:       var(--bg-hover, #f1f5f9);
    --st-border:      var(--border, rgba(0,0,0,.07));
    --st-border-md:   var(--border-md, var(--border, rgba(0,0,0,.12)));
    --st-text-1:      var(--text-1, #1e293b);
    --st-text-2:      var(--text-2, #475569);
    --st-text-3:      var(--text-3, var(--text-muted, #94a3b8));
    --st-blue:        var(--blue, var(--accent, #3b82f6));
    --st-blue-light:  var(--blue-light, var(--accent, #60a5fa));
    --st-blue-glow:   var(--blue-glow, rgba(59,130,246,.2));
    --st-radius:      var(--radius, 12px);
    --st-radius-lg:   var(--radius-lg, 18px);
}

.t-card {
    background: var(--st-card-bg);
    border: 1px solid var(--st-border);
    border-radius: var(--st-radius-lg);
    overflow: hidden;
}
.t-card-head {
    padding: 14px 20px;
    border-bottom: 1px solid var(--st-border);
    display: flex; align-items: center;
    justify-content: space-between; gap: 12px; flex-wrap: wrap;
}
.t-count {
    background: rgba(59,130,246,.12); color: var(--st-blue-light);
    padding: 3px 10px; border-radius: 20px;
    font-size: .72rem; font-weight: 800;
}
.students-table { width: 100%; border-collapse: collapse; }
.students-table thead th {
    background: rgba(0,0,0,.02);
    padding: 10px 15px;
    font-size: .65rem; font-weight: 800;
    color: var(--st-text-3);
    text-transform: uppercase; letter-spacing: .07em;
    border-bottom: 1px solid var(--st-border);
    white-space: nowrap;
}
.students-table tbody td {
    padding: 12px 15px;
    font-size: .83rem; color: var(--st-text-2);
    border-bottom: 1px solid var(--st-border);
    vertical-align: middle;
}
.students-table tbody tr:last-child td { border-bottom: none; }
.students-table tbody tr:hover td { background: var(--st-hover); }

.st-avatar-placeholder {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
    color: #fff; display: inline-flex;
    align-items: center; justify-content: center;
    font-weight: 900; font-size: .88rem; flex-shrink: 0;
}
.st-avatar { width: 36px; height: 36px; border-radius: 9px; object-fit: cover; flex-shrink: 0; }

/* Status badges */
.s-badge {
    padding: 3px 9px; border-radius: 20px;
    font-size: .68rem; font-weight: 800;
    display: inline-flex; align-items: center; gap: 4px;
}
.s-badge.studying { background: rgba(59,130,246,.12); color: #3b82f6; }
.s-badge.pass     { background: rgba(16,185,129,.12); color: #10b981; }
.s-badge.fail     { background: rgba(239,68,68,.12);  color: #ef4444; }
.s-badge.stopped  { background: rgba(245,158,11,.12); color: #f59e0b; }

/* Action buttons */
.act-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: none; display: inline-flex;
    align-items: center; justify-content: center;
    font-size: .78rem; cursor: pointer; transition: all .18s;
    text-decoration: none;
}
.act-btn.view   { background: rgba(6,182,212,.1);  color: #0891b2; }
.act-btn.edit   { background: rgba(245,158,11,.1); color: #d97706; }
.act-btn.delete { background: rgba(239,68,68,.1);  color: #dc2626; }
.act-btn:hover  { transform: translateY(-2px); }

/* Balance */
.balance-cell .amount { font-weight: 800; font-size: .83rem; }
.balance-cell .amount.owed  { color: #ef4444; }
.balance-cell .amount.clear { color: #10b981; }
.balance-cell .sub { font-size: .68rem; color: var(--st-text-3); }

/* Online pulse */
.online-dot {
    width: 7px; height: 7px; border-radius: 50%;
    display: inline-block; margin-left: 4px; background: #22c55e;
    animation: pulse-online 2s infinite;
}
@keyframes pulse-online {
    0%  { box-shadow: 0 0 0 0 rgba(34,197,94,.7); }
    70% { box-shadow: 0 0 0 7px rgba(34,197,94,0); }
    100%{ box-shadow: 0 0 0 0 rgba(34,197,94,0); }
}

/* Modal overrides — work for both themes */
.modal-pro .modal-content {
    background: var(--st-card-bg);
    border: 1px solid var(--st-border-md);
    border-radius: var(--st-radius-lg);
}
.modal-pro .modal-header {
    background: var(--st-surface);
    border-bottom: 1px solid var(--st-border);
    padding: 16px 20px;
}
.modal-pro .modal-title { color: var(--st-text-1); font-weight: 800; font-size: .9rem; }
.modal-pro .modal-body  { padding: 22px; overflow-y: auto; }
.modal-pro .modal-footer {
    background: var(--st-surface);
    border-top: 1px solid var(--st-border);
    padding: 13px 20px;
}

/* Scrollable modal body */
.modal-dialog-scrollable .modal-body { overflow-y: auto; }

.form-pro {
    width: 100%; padding: 8px 12px;
    border: 1px solid var(--st-border-md);
    border-radius: var(--st-radius, 12px);
    font-family: 'Tajawal', 'IBM Plex Sans Arabic', sans-serif;
    font-size: .83rem;
    color: var(--st-text-1); background: rgba(0,0,0,.02);
    outline: none; transition: .18s; direction: rtl;
}
.form-pro:focus {
    border-color: var(--st-blue);
    box-shadow: 0 0 0 3px var(--st-blue-glow);
}
.form-pro::placeholder { color: var(--st-text-3); }
.form-pro option { background: var(--st-card-bg); color: var(--st-text-1); }

/* Dark mode adaption for school layout */
body.dark-mode .form-pro { background: rgba(255,255,255,.04); color: #f1f5f9; border-color: rgba(255,255,255,.12); }
body.dark-mode .form-pro:focus { background: #1f2d40; }
body.dark-mode .students-table thead th { background: rgba(255,255,255,.03); }
body.dark-mode .students-table tbody tr:hover td { background: #1f2d40; }
body.dark-mode .t-card { background: #1a2332; border-color: rgba(255,255,255,.07); }
body.dark-mode .modal-pro .modal-content { background: #1a2332; border-color: rgba(255,255,255,.1); }
body.dark-mode .modal-pro .modal-header, body.dark-mode .modal-pro .modal-footer { background: #111827; }

/* Admin layout dark vars already defined so above vars resolve correctly */
/* school layout light: --card-bg=#fff so st-card-bg=#fff ✓ */

.form-label-d {
    display: block; font-size: .7rem; font-weight: 800;
    color: var(--st-text-3); margin-bottom: 5px;
    text-transform: uppercase; letter-spacing: .05em;
}
.form-section-d {
    margin-bottom: 18px; padding-bottom: 16px;
    border-bottom: 1px solid var(--st-border);
}
.section-label-d {
    font-size: .7rem; font-weight: 800;
    color: var(--st-blue); text-transform: uppercase;
    letter-spacing: .08em; margin-bottom: 12px;
    display: flex; align-items: center; gap: 7px;
}
.profile-header-d {
    background: linear-gradient(135deg, #0d1b2a, #1d4ed8);
    padding: 24px; color: #fff; text-align: center;
}
.profile-avatar-big-d {
    width: 64px; height: 64px; border-radius: 16px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.7rem; font-weight: 900; color: #fff;
    margin: 0 auto 10px;
    border: 2px solid rgba(255,255,255,.2);
}
.profile-grid-d {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 14px; padding: 18px;
}
.p-field label {
    font-size: .66rem; font-weight: 800;
    color: var(--st-text-3); text-transform: uppercase;
    letter-spacing: .05em; display: block; margin-bottom: 2px;
}
.p-field span { font-size: .84rem; font-weight: 600; color: var(--st-text-1); }

/* Locked field styling */
.form-pro:disabled, .form-pro[readonly] {
    opacity: .55; cursor: not-allowed; background: rgba(0,0,0,.04) !important;
}
.locked-hint {
    font-size: .65rem; color: #f59e0b; margin-top: 3px;
    display: flex; align-items: center; gap: 4px;
}

.t-pagination { padding: 13px 18px; border-top: 1px solid var(--st-border); }

/* Filter bar compat */
.filter-bar {
    background: var(--st-card-bg) !important;
    border: 1px solid var(--st-border) !important;
}
.f-input, .f-select {
    background: rgba(0,0,0,.02) !important;
    color: var(--st-text-1) !important;
    border-color: var(--st-border-md) !important;
}
body.dark-mode .f-input, body.dark-mode .f-select {
    background: rgba(255,255,255,.04) !important;
    color: #f1f5f9 !important;
}

/* Page header compat */
.page-hd-title { font-size: 1.1rem; font-weight: 900; color: var(--st-text-1); }
.page-hd-sub   { font-size: .72rem; color: var(--st-text-3); margin-top: 2px; }

@media print {
    #sidebar, #topbar, .filter-bar, .page-hd,
    .act-btn, form[method="POST"] { display: none !important; }
    #main, .content-area { margin: 0 !important; background: #fff !important; color: #000 !important; }
}
</style>
@endsection

@section('content')
@php
    $isAdmin  = Auth::user()->role === 'admin';
@endphp

<div class="page-hd">
    <div>
        <div class="page-hd-title">
            <i class="fas fa-users" style="color:var(--blue-light);margin-left:8px;"></i>
            إدارة شؤون الطلاب
        </div>
        <div class="page-hd-sub">
            سجل بيانات الطلاب الأكاديمية والمالية
            @if(request('school_id')) — مدرسة محددة @endif
        </div>
    </div>
    <button class="btn-pro btn-blue" onclick="openAddModal()">
        <i class="fas fa-user-plus"></i> تسجيل طالب جديد
    </button>
</div>

{{-- Filter Bar --}}
<form action="{{ $isAdmin ? route('students.index') : route('school.students.index') }}" method="GET">
    @if(request('school_id'))<input type="hidden" name="school_id" value="{{ request('school_id') }}">@endif
    <div class="filter-bar">
        <input type="text" name="search" placeholder="ابحث بالاسم أو رقم الهوية..."
               value="{{ request('search') }}" class="f-input" style="min-width:240px;">
        @if($isAdmin)
        <select name="school_id" class="f-select">
            <option value="">كل المدارس</option>
            @foreach(\App\Models\School::orderBy('name')->get() as $s)
                <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select>
        @endif
        <button type="submit" class="btn-pro btn-blue btn-sm"><i class="fas fa-filter"></i> بحث</button>
        <a href="{{ $isAdmin ? route('students.index') : route('school.students.index') }}"
           class="btn-pro btn-ghost btn-sm" style="text-decoration:none;"><i class="fas fa-undo"></i></a>
        <button type="button" onclick="window.print()" class="btn-pro btn-ghost btn-sm" style="margin-right:auto;">
            <i class="fas fa-print"></i>
        </button>
    </div>
</form>

{{-- Table --}}
<div class="t-card">
    <div class="t-card-head">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:.9rem;font-weight:800;color:var(--text-1);">الطلاب المسجلون</span>
            <span class="t-count">{{ $students->total() }} طالب</span>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="students-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الطالب</th>
                    <th>الهوية</th>
                    <th>الرخصة</th>
                    <th>الحالة</th>
                    <th>المالية</th>
                    <th>النشاط</th>
                    <th>الأخطاء</th>
                    <th>نسبة النجاح</th>
                    @if($isAdmin)<th>المدرسة</th>@endif
                    <th style="text-align:center;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $student)
                <tr>
                    <td style="color:var(--text-3);font-size:.72rem;">{{ $students->firstItem() + $idx }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($student->image)
                                <img src="{{ asset('storage/'.$student->image) }}" class="st-avatar">
                            @else
                                <span class="st-avatar-placeholder">{{ mb_substr($student->name, 0, 1) }}</span>
                            @endif
                            <div>
                                <div style="font-weight:700;color:var(--text-1);font-size:.85rem;">{{ $student->name }}</div>
                                <div style="font-size:.68rem;color:var(--text-3);">{{ $student->phone ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:.78rem;color:var(--text-3);">{{ $student->identity_number }}</td>
                    <td>
                        <span class="badge-pro badge-blue">{{ $student->license_type ?: 'غير محدد' }}</span>
                    </td>
                    <td>
                        @php
                            $maps = ['يدرس'=>'studying','ناجح'=>'pass','راسب'=>'fail','موقوف'=>'stopped'];
                            $c = $maps[$student->status] ?? 'stopped';
                            $ic = ['يدرس'=>'fa-book-open','ناجح'=>'fa-check-circle','راسب'=>'fa-times-circle','موقوف'=>'fa-pause-circle'];
                        @endphp
                        <span class="s-badge {{ $c }}">
                            <i class="fas {{ $ic[$student->status] ?? 'fa-circle' }}"></i>
                            {{ $student->status }}
                        </span>
                    </td>
                    <td class="balance-cell">
                        @php $bal = ($student->total_amount ?? 0) - ($student->paid_amount ?? 0); @endphp
                        <div class="amount {{ $bal > 0 ? 'owed' : 'clear' }}">{{ number_format($bal) }} ₪</div>
                        <div class="sub">من {{ number_format($student->total_amount ?? 0) }} ₪</div>
                    </td>
                    <td>
                        @php
                            $isOnline = $student->last_activity_at
                                && \Carbon\Carbon::parse($student->last_activity_at)->diffInMinutes() < 10;
                        @endphp
                        @if($isOnline)
                            <span class="online-dot"></span>
                            <span style="font-size:.76rem;font-weight:700;color:#6ee7b7;">نشط</span>
                        @elseif($student->last_activity_at)
                            <span style="font-size:.73rem;color:var(--text-3);">
                                {{ \Carbon\Carbon::parse($student->last_activity_at)->diffForHumans() }}
                            </span>
                        @else
                            <span style="font-size:.73rem;color:var(--text-3);">لم يسجل دخول</span>
                        @endif
                    </td>
                    <td>
                        @if(($student->unresolved_errors_count ?? 0) > 0)
                            <span class="badge-pro badge-red">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $student->unresolved_errors_count }}
                            </span>
                        @else
                            <span style="font-size:.76rem;color:#6ee7b7;font-weight:700;">
                                <i class="fas fa-check-circle"></i> نظيف
                            </span>
                        @endif
                    </td>
                    @php
                        $totalExams = \App\Models\ExamResult::where('student_id', $student->id)->count() ?? 0;
                        $passedExams = \App\Models\ExamResult::where('student_id', $student->id)->where('status', 'ناجح')->count() ?? 0;
                        $successRate = $totalExams > 0 ? round(($passedExams / $totalExams) * 100) : null;
                    @endphp
                    <td>
                        @if($successRate !== null)
                            @php $rColor = $successRate >= 70 ? '#6ee7b7' : ($successRate >= 50 ? '#fcd34d' : '#fca5a5'); @endphp
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div style="flex:1;height:5px;background:rgba(255,255,255,.06);border-radius:4px;overflow:hidden;min-width:40px;">
                                    <div style="width:{{ $successRate }}%;height:100%;background:{{ $rColor }};border-radius:4px;"></div>
                                </div>
                                <span style="font-size:.72rem;font-weight:800;color:{{ $rColor }};">{{ $successRate }}%</span>
                            </div>
                        @else
                            <span style="font-size:.72rem;color:var(--text-3);">—</span>
                        @endif
                    </td>
                    @if($isAdmin)
                    <td style="font-size:.77rem;font-weight:600;color:var(--text-3);">
                        {{ $student->school->name ?? '—' }}
                    </td>
                    @endif
                    <td>
                        <div style="display:flex;gap:4px;justify-content:center;">
                            <button class="act-btn view" onclick='showProfile(@json($student))' title="عرض الملف">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="act-btn edit" onclick='editStudent(@json($student))' title="تعديل">
                                <i class="fas fa-pen"></i>
                            </button>
                            @if($isAdmin)
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11">
                        <div class="empty-state">
                            <i class="fas fa-user-slash"></i>
                            <strong>لا يوجد طلاب</strong>
                            <span>{{ request('search') ? 'لا توجد نتائج لبحثك' : 'لم يُسجَّل أي طالب بعد' }}</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
    <div class="t-pagination">{{ $students->withQueryString()->links() }}</div>
    @endif
</div>


{{-- ══ MODAL: Add / Edit Student ══ --}}
<div class="modal fade modal-pro" id="studentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" dir="rtl">
        <div class="modal-content">
            <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h5 class="modal-title" id="modalTitle">تسجيل طالب جديد</h5>
                <div style="width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,.06);border:1px solid var(--border-md);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--text-2);cursor:pointer;" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <form id="studentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                <div class="modal-body">

                    {{-- بيانات شخصية --}}
                    <div class="form-section-d">
                        <div class="section-label-d"><i class="fas fa-user"></i> البيانات الشخصية</div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                            <div style="grid-column:span 2;">
                                <label class="form-label-d">الاسم الكامل *</label>
                                <input type="text" id="f_name" name="name" class="form-pro" required>
                            </div>
                            <div>
                                <label class="form-label-d">رقم الهوية *</label>
                                <input type="text" id="f_id" name="identity_number" class="form-pro" required>
                            </div>
                            <div>
                                <label class="form-label-d">تاريخ الميلاد</label>
                                <input type="date" id="f_birth" name="birth_date" class="form-pro">
                            </div>
                            <div>
                                <label class="form-label-d">رقم الجوال</label>
                                <input type="text" id="f_phone" name="phone" class="form-pro">
                            </div>
                            <div>
                                <label class="form-label-d">الجنس</label>
                                <select id="f_gender" name="gender" class="form-pro">
                                    <option value="ذكر">ذكر</option>
                                    <option value="أنثى">أنثى</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label-d">صورة الطالب</label>
                                <input type="file" name="image" class="form-pro" accept="image/*" style="padding:6px;">
                            </div>
                            @if($isAdmin)
                            <div>
                                <label class="form-label-d">المدرسة *</label>
                                <select id="f_school" name="school_id" class="form-pro" onchange="filterTrainersVehicles(this.value)">
                                    <option value="">— اختر مدرسة —</option>
                                    @foreach($schools as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <input type="hidden" name="school_id" value="{{ Auth::user()->school_id }}">
                            @endif
                        </div>
                    </div>

                    {{-- بيانات الرخصة --}}
                    <div class="form-section-d">
                        <div class="section-label-d"><i class="fas fa-id-card"></i> الرخصة والتدريب</div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                            <div>
                                <label class="form-label-d">
                                    الحالة
                                    @if(!$isAdmin)<span id="status-lock-hint" class="locked-hint" style="display:none;"><i class="fas fa-lock"></i> مقفل — ناجح</span>@endif
                                </label>
                                <select id="f_status" name="status" class="form-pro">
                                    <option value="يدرس">يدرس</option>
                                    <option value="ناجح">ناجح</option>
                                    <option value="راسب">راسب</option>
                                    <option value="موقوف">موقوف</option>
                                    <option value="منسحب">منسحب</option>
                                </select>
                            </div>
                            <div><label class="form-label-d">نوع الرخصة</label>
                                <select id="f_license" name="license_type" class="form-pro">
                                    <option value="ملاكي">ملاكي</option>
                                    <option value="تجاري">تجاري</option>
                                    <option value="عمومي">عمومي</option>
                                    <option value="حمولة">حمولة</option>
                                </select>
                            </div>
                            <div><label class="form-label-d">ناقل الحركة</label>
                                <select id="f_gear" name="gear_type" class="form-pro">
                                    <option value="manual">عادي (يدوي)</option>
                                    <option value="auto">أتوماتيك</option>
                                </select>
                            </div>
                            <div><label class="form-label-d">المدرب المسؤول</label>
                                <select id="f_trainer_select" name="trainer_name" class="form-pro">
                                    <option value="">— اختر مدرب —</option>
                                    @foreach($trainers as $tr)
                                        <option value="{{ $tr->name }}" data-school="{{ $tr->school_id }}">{{ $tr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="form-label-d">معلومات السيارة</label>
                                <select id="f_car_select" name="car_info" class="form-pro">
                                    <option value="">— اختر سيارة —</option>
                                    @foreach($vehicles as $v)
                                        <option value="{{ $v->plate_number }} - {{ $v->brand }} {{ $v->model }}" data-school="{{ $v->school_id }}">{{ $v->plate_number }} - {{ $v->brand }} {{ $v->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="form-label-d">نوع الامتحان النظري</label>
                                <select id="f_theory" name="theory_type" class="form-pro">
                                    <option value="تحريري">تحريري</option>
                                    <option value="شفوي">شفوي</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- بيان قفل الحقول لمدير المدرسة عند التعديل --}}
                    @if(!$isAdmin)
                    <div id="school-admin-lock-notice" style="display:none;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:10px;padding:10px 14px;margin-bottom:14px;font-size:.78rem;color:#b45309;display:none;align-items:center;gap:8px;">
                        <i class="fas fa-lock" style="color:#f59e0b;"></i>
                        <span>بعض الحقول (الاسم، الهوية، تاريخ الميلاد، الجنس) مقفلة عند التعديل — يمكن تعديلها فقط من قِبل المدير العام</span>
                    </div>
                    @endif

                    {{-- الفحوصات --}}
                    <div class="form-section-d">
                        <div class="section-label-d"><i class="fas fa-heartbeat"></i> الفحص الطبي والامتحانات</div>
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
                            <div><label class="form-label-d">تاريخ الفحص الطبي</label><input type="date" id="f_medical" name="medical_test_date" class="form-pro"></div>
                            <div><label class="form-label-d">انتهاء الفحص</label><input type="date" id="f_medexp" name="medical_test_expiry" class="form-pro"></div>
                            <div><label class="form-label-d">نتيجة الفحص</label>
                                <select id="f_medres" name="medical_test_result" class="form-pro">
                                    <option value="">—</option>
                                    <option value="لائق">لائق</option>
                                    <option value="غير لائق">غير لائق</option>
                                </select>
                            </div>
                            <div><label class="form-label-d">تاريخ امتحان النظري</label><input type="date" id="f_thdate" name="theory_exam_date" class="form-pro"></div>
                            <div><label class="form-label-d">انتهاء صلاحية النظري</label><input type="date" id="f_thexp" name="theory_exam_expiry" class="form-pro"></div>
                            <div><label class="form-label-d">نتيجة النظري</label>
                                <select id="f_thres" name="theory_exam_result" class="form-pro">
                                    <option value="">—</option>
                                    <option value="ناجح">ناجح</option>
                                    <option value="راسب">راسب</option>
                                </select>
                            </div>
                            <div><label class="form-label-d">تاريخ الامتحان العملي</label><input type="date" id="f_prdate" name="practical_test_date" class="form-pro"></div>
                            <div><label class="form-label-d">نتيجة العملي</label>
                                <select id="f_prres" name="practical_test_result" class="form-pro">
                                    <option value="">—</option>
                                    <option value="ناجح">ناجح</option>
                                    <option value="راسب">راسب</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- المالية --}}
                    <div class="form-section-d">
                        <div class="section-label-d"><i class="fas fa-wallet"></i> البيانات المالية</div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                            <div><label class="form-label-d">نوع الدفع</label>
                                <select id="f_paytype" name="payment_type" class="form-pro">
                                    <option value="بالدرس">بالدرس</option>
                                    <option value="شامل">شامل</option>
                                    <option value="أقساط">أقساط</option>
                                </select>
                            </div>
                            <div><label class="form-label-d">المبلغ الإجمالي (₪)</label>
                                <input type="number" id="f_total" name="total_amount" class="form-pro" value="0" min="0">
                            </div>
                            <div><label class="form-label-d">المبلغ المدفوع (₪)</label>
                                <input type="number" id="f_paid" name="paid_amount" class="form-pro" value="0" min="0">
                            </div>
                        </div>
                    </div>

                    {{-- كلمة المرور --}}
                    <div>
                        <div class="section-label-d"><i class="fas fa-lock"></i> بيانات الدخول</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div>
                                <label class="form-label-d">كلمة المرور <span id="passRequired" style="color:var(--red);">*</span></label>
                                <input type="password" id="f_pass" name="password" class="form-pro" placeholder="6 أحرف على الأقل">
                                <small style="color:var(--text-3);font-size:.7rem;" id="passHint"></small>
                            </div>
                            <div>
                                <label class="form-label-d">ملاحظات</label>
                                <textarea id="f_notes" name="notes" class="form-pro" rows="2" style="resize:none;"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="display:flex;gap:9px;">
                    <button type="button" class="btn-pro btn-ghost" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-pro btn-blue">
                        <i class="fas fa-save"></i> <span id="submitLabel">حفظ الطالب</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ MODAL: Profile View ══ --}}
<div class="modal fade modal-pro" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" dir="rtl">
        <div class="modal-content">
            <div id="profileHeader" class="profile-header-d">
                <div id="profileAvatar" class="profile-avatar-big-d">?</div>
                <div id="profileName" style="font-size:1.1rem;font-weight:800;margin-bottom:3px;">—</div>
                <div id="profileSchool" style="font-size:.78rem;color:rgba(255,255,255,.6);">—</div>
            </div>
            <div id="profileGrid" class="profile-grid-d"></div>
            <div class="modal-footer" style="display:flex;gap:9px;">
                <button class="btn-pro btn-ghost" data-bs-dismiss="modal">إغلاق</button>
                <button class="btn-pro btn-blue" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Filter trainers and vehicles by selected school (admin only)
function filterTrainersVehicles(schoolId) {
    schoolId = String(schoolId);
    document.querySelectorAll('#f_trainer_select option[data-school]').forEach(opt => {
        opt.style.display = (!schoolId || opt.dataset.school === schoolId) ? '' : 'none';
    });
    document.querySelectorAll('#f_car_select option[data-school]').forEach(opt => {
        opt.style.display = (!schoolId || opt.dataset.school === schoolId) ? '' : 'none';
    });
    // Reset to first visible
    const trSel = document.getElementById('f_trainer_select');
    const carSel = document.getElementById('f_car_select');
    if (trSel) trSel.value = '';
    if (carSel) carSel.value = '';
}

const IS_ADMIN  = @json($isAdmin);
const STORE_URL = IS_ADMIN
    ? "{{ route('students.store') }}"
    : "{{ route('school.students.store') }}";

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'تسجيل طالب جديد';
    document.getElementById('studentForm').action = STORE_URL;
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('studentForm').reset();
    document.getElementById('passRequired').style.display = 'inline';
    document.getElementById('passHint').textContent = '';
    document.getElementById('submitLabel').textContent = 'حفظ الطالب';
    // Unlock all fields for new student
    ['f_name','f_id','f_birth','f_phone','f_gender'].forEach(id => {
        const el = document.getElementById(id); if(el){ el.readOnly=false; el.disabled=false; }
    });
    const statusEl = document.getElementById('f_status'); if(statusEl) statusEl.disabled = false;
    const lockNotice = document.getElementById('school-admin-lock-notice');
    if (lockNotice) lockNotice.style.display = 'none';
    const statusHint = document.getElementById('status-lock-hint');
    if (statusHint) statusHint.style.display = 'none';
    // For admin: show all trainers/vehicles
    filterTrainersVehicles('');
    @if(!$isAdmin)
    filterTrainersVehicles('{{ Auth::user()->school_id }}');
    @endif
    new bootstrap.Modal(document.getElementById('studentModal')).show();
}

function editStudent(s) {
    document.getElementById('modalTitle').textContent = 'تعديل: ' + s.name;
    const url = IS_ADMIN ? `/admin/students/${s.id}` : `/school/students/${s.id}`;
    document.getElementById('studentForm').action = url;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

    const fill = (id, val) => { const el = document.getElementById(id); if(el) el.value = val ?? ''; };
    fill('f_name', s.name); fill('f_id', s.identity_number); fill('f_birth', s.birth_date);
    fill('f_phone', s.phone); fill('f_gender', s.gender || 'ذكر'); fill('f_status', s.status || 'يدرس');
    fill('f_license', s.license_type || 'ملاكي'); fill('f_gear', s.gear_type || 'manual');
    fill('f_theory', s.theory_type || 'تحريري');
    fill('f_medical', s.medical_test_date); fill('f_medexp', s.medical_test_expiry);
    fill('f_medres', s.medical_test_result); fill('f_thdate', s.theory_exam_date);
    fill('f_thexp', s.theory_exam_expiry); fill('f_thres', s.theory_exam_result);
    fill('f_prdate', s.practical_test_date); fill('f_prres', s.practical_test_result);
    fill('f_paytype', s.payment_type || 'بالدرس'); fill('f_total', s.total_amount || 0);
    fill('f_paid', s.paid_amount || 0); fill('f_notes', s.notes);

    // Filter trainers/vehicles by school, then set values
    const schoolId = IS_ADMIN ? String(s.school_id || '') : '{{ Auth::user()->school_id ?? '' }}';
    if(document.getElementById('f_school')) {
        document.getElementById('f_school').value = s.school_id || '';
    }
    filterTrainersVehicles(schoolId);
    // Small delay to allow filtering before setting value
    setTimeout(() => {
        fill('f_trainer_select', s.trainer_name);
        fill('f_car_select', s.car_info);
    }, 50);

    // School admin: lock personal fields and status if ناجح
    const isSchoolAdmin = !IS_ADMIN;
    const lockFields = ['f_name','f_id','f_birth','f_phone','f_gender'];
    lockFields.forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.readOnly = isSchoolAdmin; el.disabled = isSchoolAdmin; }
    });
    const statusEl = document.getElementById('f_status');
    const statusHint = document.getElementById('status-lock-hint');
    if (statusEl && isSchoolAdmin && s.status === 'ناجح') {
        statusEl.disabled = true;
        if (statusHint) statusHint.style.display = 'flex';
    } else {
        if (statusEl) statusEl.disabled = false;
        if (statusHint) statusHint.style.display = 'none';
    }
    // Show lock notice for school admin
    const lockNotice = document.getElementById('school-admin-lock-notice');
    if (lockNotice) lockNotice.style.display = isSchoolAdmin ? 'flex' : 'none';

    document.getElementById('passRequired').style.display = 'none';
    document.getElementById('passHint').textContent = 'اتركها فارغة إذا لم تريد تغييرها';
    document.getElementById('f_pass').value = '';
    document.getElementById('submitLabel').textContent = 'حفظ التعديلات';
    new bootstrap.Modal(document.getElementById('studentModal')).show();
}

function showProfile(s) {
    document.getElementById('profileAvatar').textContent = s.name ? s.name.charAt(0) : '?';
    document.getElementById('profileName').textContent   = s.name || '—';
    document.getElementById('profileSchool').textContent = 'رقم الهوية: ' + (s.identity_number || '—');

    const fields = [
        ['رقم الهوية', s.identity_number], ['الجوال', s.phone],
        ['تاريخ الميلاد', s.birth_date], ['الجنس', s.gender],
        ['الحالة', s.status], ['نوع الرخصة', s.license_type],
        ['ناقل الحركة', s.gear_type === 'manual' ? 'يدوي' : 'أتوماتيك'],
        ['المدرب', s.trainer_name], ['معلومات السيارة', s.car_info],
        ['نوع امتحان النظري', s.theory_type], ['تاريخ الفحص الطبي', s.medical_test_date],
        ['نتيجة الفحص الطبي', s.medical_test_result], ['تاريخ نظري', s.theory_exam_date],
        ['نتيجة النظري', s.theory_exam_result], ['تاريخ العملي', s.practical_test_date],
        ['نتيجة العملي', s.practical_test_result],
        ['إجمالي الرسوم', (s.total_amount || 0) + ' ₪'],
        ['المدفوع', (s.paid_amount || 0) + ' ₪'],
        ['المتبقي', ((s.total_amount || 0) - (s.paid_amount || 0)) + ' ₪'],
        ['الملاحظات', s.notes || '—'],
    ];
    document.getElementById('profileGrid').innerHTML = fields.map(([lbl, val]) =>
        `<div class="p-field"><label>${lbl}</label><span>${val || '—'}</span></div>`
    ).join('');
    new bootstrap.Modal(document.getElementById('profileModal')).show();
}
</script>
@endsection
