@extends('layouts.school')
@section('title','إدارة المركبات')
@section('page_section','الإدارة')
@section('page_title','إدارة المركبات')

@section('styles')
<style>
:root{--accent:#3b82f6;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;}
.pg-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;}
.pg-title{font-size:1.2rem;font-weight:800;color:var(--text-1);}
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;}
@media(max-width:900px){.stats-row{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px){.stats-row{grid-template-columns:1fr;}}
.sc{background:var(--card-bg);border:1px solid var(--border);border-radius:14px;padding:16px 20px;display:flex;align-items:center;gap:14px;}
.sc-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
.sc-val{font-size:1.5rem;font-weight:800;color:var(--text-1);line-height:1;}
.sc-lbl{font-size:.72rem;color:var(--text-muted);font-weight:600;margin-top:3px;}
.filter-bar{background:var(--card-bg);border:1px solid var(--border);border-radius:14px;padding:14px 18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:18px;}
.filter-bar select,.filter-bar input{padding:8px 12px;border:1px solid var(--border);border-radius:9px;font-family:inherit;font-size:.82rem;color:var(--text-2);background:var(--input-bg);outline:none;transition:.2s;}
.filter-bar select:focus,.filter-bar input:focus{border-color:var(--accent);background:var(--card-bg);}
/* Table */
.t-card{background:var(--card-bg);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.t-card-head{padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.vt{width:100%;border-collapse:collapse;}
.vt thead th{background:rgba(255,255,255,.03);padding:11px 16px;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid var(--border);white-space:nowrap;}
.vt tbody td{padding:13px 16px;font-size:.83rem;color:var(--text-2);border-bottom:1px solid var(--border);vertical-align:middle;}
.vt tbody tr:hover td{background:rgba(255,255,255,.05);}
.vt tbody tr:last-child td{border-bottom:none;}
.plate-badge{background:#0f172a;color:#fff;padding:4px 12px;border-radius:8px;font-family:monospace;font-size:.82rem;letter-spacing:1px;}
.s-badge{padding:4px 10px;border-radius:20px;font-size:.7rem;font-weight:700;display:inline-flex;align-items:center;gap:4px;}
.s-active{background:#ecfdf5;color:#065f46;}
.s-maintenance{background:#fffbeb;color:#713f12;}
.s-off{background:#fef2f2;color:#991b1b;}
.alert-chip{display:inline-flex;align-items:center;gap:4px;background:#fef2f2;color:#ef4444;padding:3px 8px;border-radius:6px;font-size:.68rem;font-weight:700;}
.act-btn{width:32px;height:32px;border-radius:8px;border:none;display:inline-flex;align-items:center;justify-content:center;font-size:.82rem;cursor:pointer;transition:.2s;}
.act-btn.edit{background:#fef9c3;color:#a16207;}
.act-btn.del{background:#fee2e2;color:#991b1b;}
.act-btn:hover{transform:translateY(-2px);box-shadow:0 4px 8px rgba(0,0,0,.1);}
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
.alert-warn{background:#fffbeb;border:1px solid #fbbf24;border-radius:12px;padding:12px 16px;font-size:.82rem;font-weight:600;color:#92400e;display:flex;align-items:center;gap:10px;margin-bottom:18px;}
.empty-state{text-align:center;padding:60px 20px;color:#94a3b8;}
.empty-state i{font-size:3rem;opacity:.25;display:block;margin-bottom:12px;}
</style>
@endsection

@section('content')

@if($stats['alerts'] > 0)
<div class="alert-warn">
    <i class="fas fa-exclamation-triangle" style="color:#f59e0b;font-size:1.1rem;"></i>
    <span>تنبيه: <strong>{{ $stats['alerts'] }}</strong> مركبة لديها تأمين أو ترخيص أو صيانة تستحق قريباً.</span>
</div>
@endif

<div class="pg-head">
    <div>
        <div class="pg-title"><i class="fas fa-car-side" style="color:var(--accent);margin-left:8px;"></i>إدارة المركبات</div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:2px;">متابعة المركبات والتأمين والصيانة</div>
    </div>
    <button class="btn-pro btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
        <i class="fas fa-plus"></i> إضافة مركبة
    </button>
</div>

<div class="stats-row">
    <div class="sc"><div class="sc-icon" style="background:#eff6ff;color:var(--accent);"><i class="fas fa-car"></i></div><div><div class="sc-val">{{ $stats['total'] }}</div><div class="sc-lbl">إجمالي المركبات</div></div></div>
    <div class="sc"><div class="sc-icon" style="background:#ecfdf5;color:var(--success);"><i class="fas fa-check-circle"></i></div><div><div class="sc-val">{{ $stats['active'] }}</div><div class="sc-lbl">نشطة</div></div></div>
    <div class="sc"><div class="sc-icon" style="background:#fffbeb;color:var(--warning);"><i class="fas fa-tools"></i></div><div><div class="sc-val">{{ $stats['maintenance'] }}</div><div class="sc-lbl">في الصيانة</div></div></div>
    <div class="sc"><div class="sc-icon" style="background:#fef2f2;color:var(--danger);"><i class="fas fa-bell"></i></div><div><div class="sc-val">{{ $stats['alerts'] }}</div><div class="sc-lbl">تنبيهات عاجلة</div></div></div>
</div>

<form method="GET" action="{{ route('school.vehicles.index') }}">
<div class="filter-bar">
    <select name="status">
        <option value="">كل الحالات</option>
        <option value="نشط"          {{ request('status')=='نشط'          ?'selected':'' }}>نشط</option>
        <option value="صيانة"        {{ request('status')=='صيانة'        ?'selected':'' }}>صيانة</option>
        <option value="خارج الخدمة" {{ request('status')=='خارج الخدمة' ?'selected':'' }}>خارج الخدمة</option>
    </select>
    <select name="type">
        <option value="">كل الأنواع</option>
        <option value="ملاكي"  {{ request('type')=='ملاكي'  ?'selected':'' }}>ملاكي</option>
        <option value="تجاري"  {{ request('type')=='تجاري'  ?'selected':'' }}>تجاري</option>
        <option value="حمولة"  {{ request('type')=='حمولة'  ?'selected':'' }}>حمولة</option>
    </select>
    <button type="submit" class="btn-pro btn-primary" style="padding:8px 18px;font-size:.82rem;"><i class="fas fa-filter"></i> فلترة</button>
    <a href="{{ route('school.vehicles.index') }}" class="btn-pro btn-secondary" style="padding:8px 18px;font-size:.82rem;text-decoration:none;"><i class="fas fa-undo"></i></a>
</div>
</form>

<div class="t-card">
    <div class="t-card-head">
        <span style="font-size:.9rem;font-weight:700;color:#1e293b;">المركبات المسجلة <span style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;margin-right:6px;">{{ $vehicles->count() }}</span></span>
    </div>
    <div style="overflow-x:auto;">
    @if($vehicles->isEmpty())
        <div class="empty-state"><i class="fas fa-car-side"></i><div style="font-weight:700;">لا توجد مركبات مسجلة</div></div>
    @else
        <table class="vt">
            <thead>
                <tr>
                    <th>اللوحة</th><th>المركبة</th><th>النوع</th><th>ناقل الحركة</th>
                    <th>الحالة</th><th>التأمين</th><th>الترخيص</th><th>الصيانة القادمة</th>
                    <th>العداد</th><th style="text-align:center;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
            @foreach($vehicles as $v)
            @php
                $insExpiry = $v->insurance_expiry ? \Carbon\Carbon::parse($v->insurance_expiry) : null;
                $licExpiry = $v->license_expiry   ? \Carbon\Carbon::parse($v->license_expiry)   : null;
                $nextMaint = $v->next_maintenance ? \Carbon\Carbon::parse($v->next_maintenance) : null;
                $insAlert  = $insExpiry && $insExpiry->isPast();
                $licAlert  = $licExpiry && $licExpiry->isPast();
                $maintAlert= $nextMaint && $nextMaint->isPast();
                $sMap = ['نشط'=>'s-active','صيانة'=>'s-maintenance','خارج الخدمة'=>'s-off'];
            @endphp
            <tr>
                <td><span class="plate-badge">{{ $v->plate_number }}</span></td>
                <td>
                    <div style="font-weight:700;color:#0f172a;">{{ $v->brand }} {{ $v->model }}</div>
                    <div style="font-size:.7rem;color:#94a3b8;">{{ $v->year }} — {{ $v->color }}</div>
                </td>
                <td><span style="background:#eff6ff;color:#1d4ed8;padding:3px 9px;border-radius:20px;font-size:.7rem;font-weight:700;">{{ $v->vehicle_type }}</span></td>
                <td style="font-size:.8rem;">{{ $v->gear_type === 'manual' ? 'يدوي' : 'أتوماتيك' }}</td>
                <td><span class="s-badge {{ $sMap[$v->status] ?? 's-off' }}">{{ $v->status }}</span></td>
                <td>
                    <span style="{{ $insAlert ? 'color:#ef4444;font-weight:700;' : 'color:#334155;' }}">
                        {{ $insExpiry ? $insExpiry->format('Y-m-d') : '—' }}
                        @if($insAlert) <span class="alert-chip"><i class="fas fa-exclamation"></i>منتهٍ</span> @endif
                    </span>
                </td>
                <td>
                    <span style="{{ $licAlert ? 'color:#ef4444;font-weight:700;' : 'color:#334155;' }}">
                        {{ $licExpiry ? $licExpiry->format('Y-m-d') : '—' }}
                        @if($licAlert) <span class="alert-chip"><i class="fas fa-exclamation"></i>منتهٍ</span> @endif
                    </span>
                </td>
                <td>
                    <span style="{{ $maintAlert ? 'color:#ef4444;font-weight:700;' : 'color:#334155;' }}">
                        {{ $nextMaint ? $nextMaint->format('Y-m-d') : '—' }}
                        @if($maintAlert) <span class="alert-chip"><i class="fas fa-wrench"></i>مستحقة</span> @endif
                    </span>
                </td>
                <td style="font-family:monospace;font-size:.8rem;">{{ number_format($v->km_reading) }} كم</td>
                <td>
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <button class="act-btn edit" onclick='editVehicle(@json($v))'><i class="fas fa-pen"></i></button>
                        <form action="{{ route('school.vehicles.destroy', $v->id) }}" method="POST"
                              onsubmit="return confirm('حذف هذه المركبة؟')">
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
</div>

{{-- Modal --}}
<div class="modal fade modal-pro" id="addVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" dir="rtl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehicleModalTitle">إضافة مركبة جديدة</h5>
                <button type="button" class="btn-close btn-close-white ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="vehicleForm" action="{{ route('school.vehicles.store') }}" method="POST">
                @csrf
                <div id="vehicleMethod"></div>
                <div class="modal-body">
                    <div style="font-size:.72rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;">بيانات المركبة</div>
                    <div class="form-grid-3">
                        <div class="fg"><label>رقم اللوحة <span style="color:#ef4444;">*</span></label><input type="text" name="plate_number" id="v_plate" class="fp" required placeholder="مثال: 12-345-78"></div>
                        <div class="fg"><label>الماركة</label><input type="text" name="brand" id="v_brand" class="fp" placeholder="تويوتا، كيا..."></div>
                        <div class="fg"><label>الموديل</label><input type="text" name="model" id="v_model" class="fp" placeholder="كورولا، سيراتو..."></div>
                        <div class="fg"><label>سنة الصنع</label><input type="number" name="year" id="v_year" class="fp" min="1990" max="2030"></div>
                        <div class="fg"><label>اللون</label><input type="text" name="color" id="v_color" class="fp"></div>
                        <div class="fg"><label>نوع المركبة</label>
                            <select name="vehicle_type" id="v_type" class="fp">
                                <option value="ملاكي">ملاكي</option><option value="تجاري">تجاري</option>
                                <option value="حمولة">حمولة</option><option value="عمومي">عمومي</option>
                            </select>
                        </div>
                        <div class="fg"><label>ناقل الحركة</label>
                            <select name="gear_type" id="v_gear" class="fp">
                                <option value="manual">يدوي</option><option value="auto">أتوماتيك</option>
                            </select>
                        </div>
                        <div class="fg"><label>الحالة</label>
                            <select name="status" id="v_status" class="fp">
                                <option value="نشط">نشط</option><option value="صيانة">صيانة</option>
                                <option value="خارج الخدمة">خارج الخدمة</option>
                            </select>
                        </div>
                        <div class="fg"><label>قراءة العداد (كم)</label><input type="number" name="km_reading" id="v_km" class="fp" min="0" value="0"></div>
                    </div>
                    <div style="font-size:.72rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin:16px 0 12px;">التأمين والترخيص والصيانة</div>
                    <div class="form-grid-2">
                        <div class="fg"><label>انتهاء التأمين</label><input type="date" name="insurance_expiry" id="v_ins" class="fp"></div>
                        <div class="fg"><label>انتهاء الترخيص</label><input type="date" name="license_expiry" id="v_lic" class="fp"></div>
                        <div class="fg"><label>آخر صيانة</label><input type="date" name="last_maintenance" id="v_lastm" class="fp"></div>
                        <div class="fg"><label>موعد الصيانة القادمة</label><input type="date" name="next_maintenance" id="v_nextm" class="fp"></div>
                    </div>
                    <div class="fg"><label>ملاحظات</label><textarea name="notes" id="v_notes" class="fp" rows="2" style="resize:none;"></textarea></div>
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
function editVehicle(v) {
    document.getElementById('vehicleModalTitle').textContent = 'تعديل: ' + v.plate_number;
    document.getElementById('vehicleForm').action = '/school/vehicles/' + v.id;
    document.getElementById('vehicleMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    const f = (id, val) => { const el = document.getElementById(id); if(el) el.value = val ?? ''; };
    f('v_plate',v.plate_number);f('v_brand',v.brand);f('v_model',v.model);
    f('v_year',v.year);f('v_color',v.color);f('v_type',v.vehicle_type);
    f('v_gear',v.gear_type);f('v_status',v.status);f('v_km',v.km_reading);
    f('v_ins',v.insurance_expiry);f('v_lic',v.license_expiry);
    f('v_lastm',v.last_maintenance);f('v_nextm',v.next_maintenance);f('v_notes',v.notes);
    new bootstrap.Modal(document.getElementById('addVehicleModal')).show();
}
</script>
@endsection
