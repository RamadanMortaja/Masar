@extends('layouts.school')
@section('title','جدول الحصص')
@section('page_section','الجدول')
@section('page_title','جدول حصص التدريب')

@section('styles')
<style>
:root{--accent:#3b82f6;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;}
.pg-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;}
.pg-title{font-size:1.2rem;font-weight:800;color:var(--text-1);}
.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px;}
@media(max-width:640px){.stats-row{grid-template-columns:1fr;}}
.sc{background:var(--card-bg);border:1px solid var(--border);border-radius:14px;padding:16px 20px;display:flex;align-items:center;gap:14px;}
.sc-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
.sc-val{font-size:1.5rem;font-weight:800;color:var(--text-1);line-height:1;}
.sc-lbl{font-size:.72rem;color:var(--text-muted);font-weight:600;margin-top:3px;}
.filter-bar{background:var(--card-bg);border:1px solid var(--border);border-radius:14px;padding:14px 18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:18px;}
.filter-bar input,.filter-bar select{padding:8px 12px;border:1px solid var(--border);border-radius:9px;font-family:inherit;font-size:.82rem;color:var(--text-2);background:var(--input-bg);outline:none;transition:.2s;}
.filter-bar input:focus,.filter-bar select:focus{border-color:var(--accent);background:var(--card-bg);}
.t-card{background:var(--card-bg);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.t-card-head{padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.st{width:100%;border-collapse:collapse;}
.st thead th{background:rgba(255,255,255,.03);padding:11px 16px;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;border-bottom:1px solid var(--border);white-space:nowrap;}
.st tbody td{padding:13px 16px;font-size:.83rem;color:var(--text-2);border-bottom:1px solid var(--border);vertical-align:middle;}
.st tbody tr:hover td{background:rgba(255,255,255,.05);}
.st tbody tr:last-child td{border-bottom:none;}
.s-badge{padding:4px 10px;border-radius:20px;font-size:.7rem;font-weight:700;display:inline-flex;align-items:center;gap:4px;}
.s-scheduled{background:#eff6ff;color:#1d4ed8;}
.s-done{background:#ecfdf5;color:#065f46;}
.s-cancelled{background:#fef2f2;color:#991b1b;}
.s-absent{background:#fefce8;color:#713f12;}
.rating-stars{color:#f59e0b;font-size:.8rem;letter-spacing:1px;}
.act-btn{width:32px;height:32px;border-radius:8px;border:none;display:inline-flex;align-items:center;justify-content:center;font-size:.82rem;cursor:pointer;transition:.2s;}
.act-btn.edit{background:#fef9c3;color:#a16207;}
.act-btn.del{background:#fee2e2;color:#991b1b;}
.act-btn:hover{transform:translateY(-2px);box-shadow:0 4px 8px rgba(0,0,0,.1);}
.t-pagination{padding:14px 20px;border-top:1px solid #f1f5f9;}
.modal-pro .modal-content{border:none;border-radius:20px;overflow:hidden;}
.modal-pro .modal-header{background:linear-gradient(135deg,#0d1b2a,#1e3a5f);color:#fff;border:none;padding:20px 24px;}
.modal-pro .modal-title{font-weight:700;}
.modal-pro .modal-body{padding:24px;}
.modal-pro .modal-footer{border:none;background:var(--bg);padding:16px 24px;}
.fg{margin-bottom:14px;}
.fg label{display:block;font-size:.75rem;font-weight:700;color:var(--text-2);margin-bottom:5px;}
.fp{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:9px;font-family:inherit;font-size:.84rem;color:var(--text-1);background:var(--input-bg);outline:none;transition:.2s;direction:rtl;}
.fp:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,.1);}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
@media(max-width:576px){.form-grid-2,.form-grid-3{grid-template-columns:1fr;}}
.btn-pro{padding:10px 22px;border-radius:10px;border:none;font-family:inherit;font-size:.85rem;font-weight:700;cursor:pointer;transition:.2s;display:inline-flex;align-items:center;gap:7px;}
.btn-primary{background:var(--accent);color:#fff;}
.btn-primary:hover{background:#2563eb;}
.btn-secondary{background:#f1f5f9;color:#64748b;}
.empty-state{text-align:center;padding:60px 20px;color:#94a3b8;}
.empty-state i{font-size:3rem;opacity:.25;display:block;margin-bottom:12px;}
</style>
@endsection

@section('content')
<div class="pg-head">
    <div>
        <div class="pg-title"><i class="fas fa-calendar-alt" style="color:var(--accent);margin-left:8px;"></i>جدول حصص التدريب</div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:2px;">جدولة الحصص وربط الطالب بالمدرب والمركبة</div>
    </div>
    <button class="btn-pro btn-primary" data-bs-toggle="modal" data-bs-target="#addSessionModal">
        <i class="fas fa-plus"></i> جدولة حصة جديدة
    </button>
</div>

<div class="stats-row">
    <div class="sc"><div class="sc-icon" style="background:#eff6ff;color:var(--accent);"><i class="fas fa-calendar-day"></i></div><div><div class="sc-val">{{ $todayCount }}</div><div class="sc-lbl">حصص اليوم</div></div></div>
    <div class="sc"><div class="sc-icon" style="background:#ecfdf5;color:var(--success);"><i class="fas fa-calendar-week"></i></div><div><div class="sc-val">{{ $weekCount }}</div><div class="sc-lbl">حصص هذا الأسبوع</div></div></div>
    <div class="sc"><div class="sc-icon" style="background:#fffbeb;color:var(--warning);"><i class="fas fa-users"></i></div><div><div class="sc-val">{{ $trainers->count() }}</div><div class="sc-lbl">مدربون متاحون</div></div></div>
</div>

<form method="GET" action="{{ route('school.schedule.index') }}">
<div class="filter-bar">
    <input type="date" name="date" value="{{ request('date') }}" title="فلترة بتاريخ">
    <select name="trainer_id">
        <option value="">كل المدربين</option>
        @foreach($trainers as $tr)
            <option value="{{ $tr->id }}" {{ request('trainer_id')==$tr->id?'selected':'' }}>{{ $tr->name }}</option>
        @endforeach
    </select>
    <select name="status">
        <option value="">كل الحالات</option>
        @foreach(['مجدولة','مكتملة','ملغاة','غياب'] as $s)
            <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-pro btn-primary" style="padding:8px 18px;font-size:.82rem;"><i class="fas fa-filter"></i> فلترة</button>
    <a href="{{ route('school.schedule.index') }}" class="btn-pro btn-secondary" style="padding:8px 18px;font-size:.82rem;text-decoration:none;"><i class="fas fa-undo"></i></a>
    <a href="{{ route('school.schedule.index') }}?all=1" class="btn-pro btn-secondary" style="padding:8px 18px;font-size:.82rem;text-decoration:none;">كل السجل</a>
</div>
</form>

<div class="t-card">
    <div class="t-card-head">
        <span style="font-size:.9rem;font-weight:700;color:#1e293b;">الحصص <span style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;margin-right:6px;">{{ $sessions->total() }}</span></span>
    </div>
    <div style="overflow-x:auto;">
    @if($sessions->isEmpty())
        <div class="empty-state"><i class="fas fa-calendar-times"></i><div style="font-weight:700;">لا توجد حصص في هذه الفترة</div><div style="font-size:.82rem;margin-top:4px;">ابدأ بجدولة حصة جديدة</div></div>
    @else
        <table class="st">
            <thead>
                <tr><th>التاريخ</th><th>الوقت</th><th>الطالب</th><th>المدرب</th><th>المركبة</th><th>المدة</th><th>الحالة</th><th>التقييم</th><th style="text-align:center;">إجراءات</th></tr>
            </thead>
            <tbody>
            @foreach($sessions as $ses)
            @php
                $badgeMap = ['مجدولة'=>'s-scheduled','مكتملة'=>'s-done','ملغاة'=>'s-cancelled','غياب'=>'s-absent'];
                $bc = $badgeMap[$ses->status] ?? 's-scheduled';
            @endphp
            <tr>
                <td>
                    <div style="font-weight:700;color:var(--text-1);">{{ \Carbon\Carbon::parse($ses->session_date)->format('Y-m-d') }}</div>
                    <div style="font-size:.7rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($ses->session_date)->translatedFormat('l') }}</div>
                </td>
                <td style="font-family:monospace;font-size:.82rem;">
                    {{ substr($ses->start_time,0,5) }}
                    @if($ses->end_time) – {{ substr($ses->end_time,0,5) }} @endif
                </td>
                <td>
                    <div style="font-weight:700;">{{ $ses->student->name ?? '—' }}</div>
                    <div style="font-size:.7rem;color:#94a3b8;">{{ $ses->student->license_type ?? '' }}</div>
                </td>
                <td style="font-weight:600;">{{ $ses->trainer->name ?? '—' }}</td>
                <td style="font-size:.8rem;color:#64748b;">{{ $ses->vehicle ? $ses->vehicle->plate_number : '—' }}</td>
                <td style="font-size:.8rem;">{{ $ses->duration_minutes }} د</td>
                <td><span class="s-badge {{ $bc }}">{{ $ses->status }}</span></td>
                <td>
                    @if($ses->rating)
                        <span class="rating-stars">{{ str_repeat('★',$ses->rating) }}{{ str_repeat('☆',5-$ses->rating) }}</span>
                    @else <span style="color:#cbd5e1;font-size:.75rem;">—</span> @endif
                </td>
                <td>
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <button class="act-btn edit" onclick='editSession(@json($ses))'><i class="fas fa-pen"></i></button>
                        <form action="{{ route('school.sessions.destroy', $ses->id) }}" method="POST" onsubmit="return confirm('حذف هذه الحصة؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="act-btn del"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    </div>
    @if($sessions->hasPages())
    <div class="t-pagination">{{ $sessions->withQueryString()->links() }}</div>
    @endif
</div>

{{-- Modal --}}
<div class="modal fade modal-pro" id="addSessionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" dir="rtl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionModalTitle">جدولة حصة جديدة</h5>
                <button type="button" class="btn-close btn-close-white ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="sessionForm" action="{{ route('school.sessions.store') }}" method="POST">
                @csrf
                <div id="sessionMethod"></div>
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="fg">
                            <label>الطالب <span style="color:#ef4444">*</span></label>
                            <select name="student_id" id="s_student" class="fp" required>
                                <option value="">— اختر الطالب —</option>
                                @foreach($students as $st)
                                    <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->license_type }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fg">
                            <label>المدرب <span style="color:#ef4444">*</span></label>
                            <select name="trainer_id" id="s_trainer" class="fp" required>
                                <option value="">— اختر المدرب —</option>
                                @foreach($trainers as $tr)
                                    <option value="{{ $tr->id }}">{{ $tr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fg">
                            <label>المركبة</label>
                            <select name="vehicle_id" id="s_vehicle" class="fp">
                                <option value="">— بدون تحديد —</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->brand }} {{ $v->model }} ({{ $v->plate_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fg">
                            <label>تاريخ الحصة <span style="color:#ef4444">*</span></label>
                            <input type="date" name="session_date" id="s_date" class="fp" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="fg">
                            <label>وقت البداية <span style="color:#ef4444">*</span></label>
                            <input type="time" name="start_time" id="s_start" class="fp" required>
                        </div>
                        <div class="fg">
                            <label>وقت النهاية</label>
                            <input type="time" name="end_time" id="s_end" class="fp">
                        </div>
                        <div class="fg">
                            <label>المدة (بالدقائق)</label>
                            <input type="number" name="duration_minutes" id="s_dur" class="fp" value="60" min="15" max="480">
                        </div>
                        <div class="fg">
                            <label>الحالة</label>
                            <select name="status" id="s_status" class="fp">
                                @foreach(['مجدولة','مكتملة','ملغاة','غياب'] as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fg">
                            <label>المكان</label>
                            <input type="text" name="location" id="s_loc" class="fp" placeholder="مثال: شارع القدس">
                        </div>
                        <div class="fg">
                            <label>تقييم الطالب (1-5)</label>
                            <select name="rating" id="s_rating" class="fp">
                                <option value="">—</option>
                                @for($i=1;$i<=5;$i++)
                                    <option value="{{ $i }}">{{ str_repeat('★',$i) }} ({{ $i }})</option>
                                @endfor
                            </select>
                        </div>
                        <div class="fg" style="grid-column:span 2;">
                            <label>ملاحظات</label>
                            <textarea name="notes" id="s_notes" class="fp" rows="2" style="resize:none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-pro btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-pro btn-primary"><i class="fas fa-save"></i> حفظ الحصة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editSession(s) {
    document.getElementById('sessionModalTitle').textContent = 'تعديل الحصة';
    document.getElementById('sessionForm').action = '/school/sessions/' + s.id;
    document.getElementById('sessionMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    const f = (id, val) => { const el = document.getElementById(id); if(el) el.value = val ?? ''; };
    f('s_student',s.student_id); f('s_trainer',s.trainer_id); f('s_vehicle',s.vehicle_id);
    f('s_date',s.session_date); f('s_start',s.start_time); f('s_end',s.end_time);
    f('s_dur',s.duration_minutes); f('s_status',s.status); f('s_loc',s.location);
    f('s_rating',s.rating); f('s_notes',s.notes);
    new bootstrap.Modal(document.getElementById('addSessionModal')).show();
}
document.getElementById('addSessionModal').addEventListener('hidden.bs.modal', () => {
    document.getElementById('sessionModalTitle').textContent = 'جدولة حصة جديدة';
    document.getElementById('sessionForm').action = '{{ route("school.sessions.store") }}';
    document.getElementById('sessionMethod').innerHTML = '';
    document.getElementById('sessionForm').reset();
    document.getElementById('s_date').value = '{{ date("Y-m-d") }}';
});
</script>
@endsection
