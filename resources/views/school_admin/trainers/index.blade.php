@extends('layouts.school')
@section('title','إدارة المدربين')
@section('page_section','الإدارة')
@section('page_title','إدارة المدربين')

@section('styles')
<style>
:root{--accent:#3b82f6;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;--border:rgba(0,0,0,.06);}
.pg-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;}
.pg-title{font-size:1.2rem;font-weight:800;color:#0f172a;}
.pg-sub{font-size:.78rem;color:#94a3b8;margin-top:2px;}
/* Stats */
.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:22px;}
@media(max-width:640px){.stats-row{grid-template-columns:1fr;}}
.sc{background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 22px;display:flex;align-items:center;gap:16px;}
.sc-icon{width:44px;height:44px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.sc-val{font-size:1.6rem;font-weight:800;color:#0f172a;line-height:1;}
.sc-lbl{font-size:.75rem;color:#94a3b8;font-weight:600;margin-top:3px;}
/* Filter */
.filter-bar{background:#fff;border:1px solid var(--border);border-radius:14px;padding:14px 18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:18px;}
.filter-bar input,.filter-bar select{padding:8px 12px;border:1px solid #e2e8f0;border-radius:9px;font-family:inherit;font-size:.82rem;color:#334155;background:#f8fafc;outline:none;transition:.2s;}
.filter-bar input:focus,.filter-bar select:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,.1);background:#fff;}
/* Cards grid */
.trainers-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px;}
.trainer-card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;transition:transform .25s,box-shadow .25s;}
.trainer-card:hover{transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.07);}
.tc-head{background:linear-gradient(135deg,#0d1b2a,#1e3a5f);padding:22px;text-align:center;position:relative;}
.tc-avatar{width:64px;height:64px;border-radius:16px;object-fit:cover;border:3px solid rgba(255,255,255,.2);}
.tc-avatar-ph{width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--accent),#2563eb);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;margin:0 auto;border:3px solid rgba(255,255,255,.2);}
.tc-name{font-size:.95rem;font-weight:700;color:#fff;margin-top:10px;}
.tc-spec{font-size:.72rem;color:rgba(255,255,255,.6);margin-top:3px;}
.tc-status{position:absolute;top:14px;left:14px;padding:3px 10px;border-radius:20px;font-size:.68rem;font-weight:700;}
.status-active{background:rgba(16,185,129,.2);color:#6ee7b7;}
.status-off{background:rgba(239,68,68,.2);color:#fca5a5;}
.status-leave{background:rgba(245,158,11,.2);color:#fcd34d;}
.tc-body{padding:16px 18px;}
.tc-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f8fafc;font-size:.8rem;}
.tc-row:last-child{border-bottom:none;}
.tc-row .lbl{color:#94a3b8;font-weight:600;}
.tc-row .val{color:#1e293b;font-weight:700;}
.tc-foot{padding:12px 18px;background:#f8fafc;border-top:1px solid var(--border);display:flex;gap:8px;}
.btn-sm-pro{flex:1;padding:8px;border-radius:9px;border:none;font-family:inherit;font-size:.78rem;font-weight:700;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:6px;}
.btn-edit{background:#fef9c3;color:#a16207;}
.btn-edit:hover{background:#fef08a;}
.btn-del{background:#fee2e2;color:#991b1b;}
.btn-del:hover{background:#fecaca;}
/* Alert */
.alert-warn{background:#fffbeb;border:1px solid #fbbf24;border-radius:12px;padding:12px 16px;font-size:.82rem;font-weight:600;color:#92400e;display:flex;align-items:center;gap:10px;margin-bottom:18px;}
/* Modal */
.modal-pro .modal-content{border:none;border-radius:20px;overflow:hidden;}
.modal-pro .modal-header{background:linear-gradient(135deg,#0d1b2a,#1e3a5f);color:#fff;border:none;padding:20px 24px;}
.modal-pro .modal-title{font-weight:700;}
.modal-pro .modal-body{padding:24px;}
.modal-pro .modal-footer{border:none;background:#f8fafc;padding:16px 24px;}
.fg{margin-bottom:14px;}
.fg label{display:block;font-size:.75rem;font-weight:700;color:#475569;margin-bottom:5px;}
.fg .req{color:#ef4444;}
.fp{width:100%;padding:9px 12px;border:1px solid #e2e8f0;border-radius:9px;font-family:inherit;font-size:.84rem;color:#1e293b;background:#fff;outline:none;transition:.2s;direction:rtl;}
.fp:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,.1);}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
@media(max-width:576px){.form-grid-2,.form-grid-3{grid-template-columns:1fr;}}
.btn-pro{padding:10px 22px;border-radius:10px;border:none;font-family:inherit;font-size:.85rem;font-weight:700;cursor:pointer;transition:.2s;display:inline-flex;align-items:center;gap:7px;}
.btn-primary{background:var(--accent);color:#fff;}
.btn-primary:hover{background:#2563eb;transform:translateY(-1px);}
.btn-secondary{background:#f1f5f9;color:#64748b;}
.btn-secondary:hover{background:#e2e8f0;}
.empty-state{text-align:center;padding:60px 20px;color:#94a3b8;}
.empty-state i{font-size:3rem;opacity:.25;display:block;margin-bottom:12px;}
</style>
@endsection

@section('content')
@php $sid = Auth::user()->school_id; @endphp

{{-- Expiry Alert --}}
@if($stats['expiring'] > 0)
<div class="alert-warn">
    <i class="fas fa-exclamation-triangle" style="color:#f59e0b;font-size:1.1rem;"></i>
    <span>تنبيه: <strong>{{ $stats['expiring'] }}</strong> مدرب لديه رخصة تنتهي خلال 30 يوماً. يرجى المراجعة.</span>
</div>
@endif

<div class="pg-head">
    <div>
        <div class="pg-title"><i class="fas fa-chalkboard-teacher" style="color:var(--accent);margin-left:8px;"></i>إدارة المدربين</div>
        <div class="pg-sub">متابعة بيانات المدربين ورخصهم وحالتهم</div>
    </div>
    <button class="btn-pro btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainerModal">
        <i class="fas fa-plus"></i> إضافة مدرب
    </button>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="sc">
        <div class="sc-icon" style="background:#eff6ff;color:var(--accent);"><i class="fas fa-users"></i></div>
        <div><div class="sc-val">{{ $stats['total'] }}</div><div class="sc-lbl">إجمالي المدربين</div></div>
    </div>
    <div class="sc">
        <div class="sc-icon" style="background:#ecfdf5;color:var(--success);"><i class="fas fa-check-circle"></i></div>
        <div><div class="sc-val">{{ $stats['active'] }}</div><div class="sc-lbl">مدربون نشطون</div></div>
    </div>
    <div class="sc">
        <div class="sc-icon" style="background:#fffbeb;color:var(--warning);"><i class="fas fa-id-card"></i></div>
        <div><div class="sc-val">{{ $stats['expiring'] }}</div><div class="sc-lbl">رخص تنتهي قريباً</div></div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('school.trainers.index') }}">
<div class="filter-bar">
    <input type="text" name="search" placeholder="ابحث باسم المدرب..." value="{{ request('search') }}" style="min-width:220px;">
    <select name="status">
        <option value="">كل الحالات</option>
        <option value="نشط"   {{ request('status')=='نشط'   ?'selected':'' }}>نشط</option>
        <option value="موقوف" {{ request('status')=='موقوف' ?'selected':'' }}>موقوف</option>
        <option value="إجازة" {{ request('status')=='إجازة' ?'selected':'' }}>إجازة</option>
    </select>
    <button type="submit" class="btn-pro btn-primary" style="padding:8px 18px;font-size:.82rem;">
        <i class="fas fa-search"></i> بحث
    </button>
    <a href="{{ route('school.trainers.index') }}" class="btn-pro btn-secondary" style="padding:8px 18px;font-size:.82rem;text-decoration:none;">
        <i class="fas fa-undo"></i> إعادة
    </a>
</div>
</form>

{{-- Trainers Grid --}}
@if($trainers->isEmpty())
    <div class="empty-state">
        <i class="fas fa-chalkboard-teacher"></i>
        <div style="font-weight:700;font-size:.95rem;margin-bottom:6px;">لا يوجد مدربون مسجلون</div>
        <div style="font-size:.82rem;">ابدأ بإضافة أول مدرب للمدرسة</div>
    </div>
@else
<div class="trainers-grid">
    @foreach($trainers as $trainer)
    <div class="trainer-card">
        <div class="tc-head">
            @php
                $sc = ['نشط'=>'active','موقوف'=>'off','إجازة'=>'leave'][$trainer->status] ?? 'off';
            @endphp
            <span class="tc-status status-{{ $sc }}">{{ $trainer->status }}</span>
            @if($trainer->image)
                <img src="{{ asset('storage/'.$trainer->image) }}" class="tc-avatar">
            @else
                <div class="tc-avatar-ph">{{ mb_substr($trainer->name,0,1) }}</div>
            @endif
            <div class="tc-name">{{ $trainer->name }}</div>
            <div class="tc-spec">{{ $trainer->specialization }}</div>
        </div>
        <div class="tc-body">
            <div class="tc-row">
                <span class="lbl"><i class="fas fa-phone" style="margin-left:4px;"></i>الجوال</span>
                <span class="val">{{ $trainer->phone ?? '—' }}</span>
            </div>
            <div class="tc-row">
                <span class="lbl"><i class="fas fa-id-card" style="margin-left:4px;"></i>رقم رخصة التدريب</span>
                <span class="val" style="font-family:monospace;">{{ $trainer->license_number ?? '—' }}</span>
            </div>
            <div class="tc-row">
                <span class="lbl"><i class="fas fa-calendar" style="margin-left:4px;"></i>انتهاء الرخصة</span>
                @php $expiry = $trainer->license_expiry ? \Carbon\Carbon::parse($trainer->license_expiry) : null; @endphp
                <span class="val" style="{{ $expiry && $expiry->isPast() ? 'color:#ef4444' : ($expiry && $expiry->diffInDays() < 30 ? 'color:#f59e0b' : '') }}">
                    {{ $expiry ? $expiry->format('Y-m-d') : '—' }}
                    @if($expiry && $expiry->isPast()) <i class="fas fa-exclamation-circle"></i> @endif
                </span>
            </div>
        </div>
        <div class="tc-foot">
            <button class="btn-sm-pro btn-edit" onclick='editTrainer(@json($trainer))'>
                <i class="fas fa-pen"></i> تعديل
            </button>
            <form action="{{ route('school.trainers.destroy', $trainer->id) }}" method="POST"
                  onsubmit="return confirm('حذف المدرب {{ $trainer->name }}؟')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-sm-pro btn-del">
                    <i class="fas fa-trash"></i> حذف
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Modal: Add Trainer --}}
<div class="modal fade modal-pro" id="addTrainerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" dir="rtl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trainerModalTitle">إضافة مدرب جديد</h5>
                <button type="button" class="btn-close btn-close-white ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="trainerForm" action="{{ route('school.trainers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="trainerMethod"></div>
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="fg" style="grid-column:span 2;">
                            <label>الاسم الكامل <span class="req">*</span></label>
                            <input type="text" name="name" id="t_name" class="fp" required>
                        </div>
                        <div class="fg">
                            <label>رقم الجوال</label>
                            <input type="text" name="phone" id="t_phone" class="fp">
                        </div>
                        <div class="fg">
                            <label>رقم الهوية</label>
                            <input type="text" name="identity_number" id="t_identity" class="fp">
                        </div>
                        <div class="fg">
                            <label>رقم رخصة التدريب</label>
                            <input type="text" name="license_number" id="t_license" class="fp">
                        </div>
                        <div class="fg">
                            <label>انتهاء رخصة التدريب</label>
                            <input type="date" name="license_expiry" id="t_expiry" class="fp">
                        </div>
                        <div class="fg">
                            <label>التخصص</label>
                            <select name="specialization" id="t_spec" class="fp">
                                <option value="ملاكي">ملاكي</option>
                                <option value="تجاري">تجاري</option>
                                <option value="حمولة">حمولة</option>
                                <option value="عمومي">عمومي</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label>الحالة</label>
                            <select name="status" id="t_status" class="fp">
                                <option value="نشط">نشط</option>
                                <option value="موقوف">موقوف</option>
                                <option value="إجازة">إجازة</option>
                            </select>
                        </div>
                        <div class="fg" style="grid-column:span 2;">
                            <label>صورة المدرب</label>
                            <input type="file" name="image" class="fp" accept="image/*" style="padding:7px;">
                        </div>
                        <div class="fg" style="grid-column:span 2;">
                            <label>ملاحظات</label>
                            <textarea name="notes" id="t_notes" class="fp" rows="2" style="resize:none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-pro btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-pro btn-primary"><i class="fas fa-save"></i> حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editTrainer(t) {
    document.getElementById('trainerModalTitle').textContent = 'تعديل: ' + t.name;
    document.getElementById('trainerForm').action = '/school/trainers/' + t.id;
    document.getElementById('trainerMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    const f = (id, val) => { const el = document.getElementById(id); if(el) el.value = val ?? ''; };
    f('t_name', t.name); f('t_phone', t.phone); f('t_identity', t.identity_number);
    f('t_license', t.license_number); f('t_expiry', t.license_expiry);
    f('t_spec', t.specialization); f('t_status', t.status); f('t_notes', t.notes);
    new bootstrap.Modal(document.getElementById('addTrainerModal')).show();
}
document.getElementById('addTrainerModal').addEventListener('hidden.bs.modal', () => {
    document.getElementById('trainerModalTitle').textContent = 'إضافة مدرب جديد';
    document.getElementById('trainerForm').action = '{{ route("school.trainers.store") }}';
    document.getElementById('trainerMethod').innerHTML = '';
    document.getElementById('trainerForm').reset();
});
</script>
@endsection
