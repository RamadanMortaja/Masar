@extends('layouts.school')
@section('title','إدارة المدفوعات')
@section('page_section','المالية')
@section('page_title','سندات القبض والمدفوعات')

@section('styles')
<style>
:root{--accent:#3b82f6;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;}
.pg-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;}
.pg-title{font-size:1.2rem;font-weight:800;color:var(--text-1);}
.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px;}
@media(max-width:640px){.stats-row{grid-template-columns:1fr;}}
.sc{background:var(--card-bg);border:1px solid var(--border);border-radius:14px;padding:18px 22px;}
.sc-label{font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;}
.sc-val{font-size:1.7rem;font-weight:800;color:var(--text-1);line-height:1;}
.sc-sub{font-size:.75rem;color:var(--text-muted);margin-top:5px;font-weight:600;}
.sc.green .sc-val{color:var(--success);}
.sc.red   .sc-val{color:var(--danger);}
.filter-bar{background:var(--card-bg);border:1px solid var(--border);border-radius:14px;padding:14px 18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:18px;}
.filter-bar input,.filter-bar select{padding:8px 12px;border:1px solid var(--border);border-radius:9px;font-family:inherit;font-size:.82rem;color:var(--text-2);background:var(--input-bg);outline:none;transition:.2s;}
.filter-bar input:focus,.filter-bar select:focus{border-color:var(--accent);background:var(--card-bg);}
.t-card{background:var(--card-bg);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.t-card-head{padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.pt{width:100%;border-collapse:collapse;}
.pt thead th{background:rgba(255,255,255,.03);padding:11px 16px;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;border-bottom:1px solid var(--border);white-space:nowrap;}
.pt tbody td{padding:13px 16px;font-size:.83rem;color:var(--text-2);border-bottom:1px solid var(--border);vertical-align:middle;}
.pt tbody tr:hover td{background:rgba(255,255,255,.05);}
.pt tbody tr:last-child td{border-bottom:none;}
.receipt-badge{background:#0f172a;color:#fff;padding:4px 12px;border-radius:8px;font-family:monospace;font-size:.75rem;letter-spacing:1px;}
.method-badge{padding:4px 10px;border-radius:20px;font-size:.7rem;font-weight:700;}
.m-cash{background:#ecfdf5;color:#065f46;}
.m-transfer{background:#eff6ff;color:#1d4ed8;}
.m-check{background:#fefce8;color:#713f12;}
.act-btn{width:32px;height:32px;border-radius:8px;border:none;display:inline-flex;align-items:center;justify-content:center;font-size:.82rem;cursor:pointer;transition:.2s;}
.act-btn.print{background:#eff6ff;color:#1d4ed8;}
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
@media(max-width:576px){.form-grid-2{grid-template-columns:1fr;}}
.btn-pro{padding:10px 22px;border-radius:10px;border:none;font-family:inherit;font-size:.85rem;font-weight:700;cursor:pointer;transition:.2s;display:inline-flex;align-items:center;gap:7px;}
.btn-primary{background:var(--accent);color:#fff;}
.btn-primary:hover{background:#2563eb;}
.btn-secondary{background:#f1f5f9;color:#64748b;}
.empty-state{text-align:center;padding:60px 20px;color:#94a3b8;}

/* Receipt Print */
.receipt-print{display:none;}
@media print{
    body > *{display:none!important;}
    body { background: #fff !important; }
    .receipt-print{display:block!important;font-family:'IBM Plex Sans Arabic',sans-serif;direction:rtl;padding:40px;max-width:600px;margin:0 auto;color:#000!important;background:#fff!important;}
    .rp-header{text-align:center;border-bottom:2px solid #000;padding-bottom:20px;margin-bottom:24px;}
    .rp-title{font-size:1.5rem;font-weight:800;}
    .rp-row{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px dashed #000;font-size:1rem;}
    .rp-total{font-size:1.4rem;font-weight:800;border-bottom:3px double #000!important;margin-top:10px;padding:15px 0;}
    .rp-sigs{display:flex;justify-content:space-between;margin-top:60px;}
    .rp-sig{text-align:center;}
    .rp-sig-line{border-bottom:1px solid #000;width:150px;margin:25px auto 8px;}
}
</style>
@endsection

@section('content')
<div class="pg-head">
    <div>
        <div class="pg-title"><i class="fas fa-coins" style="color:var(--accent);margin-left:8px;"></i>سندات القبض والمدفوعات</div>
        <div style="font-size:.78rem;color:#94a3b8;margin-top:2px;">تسجيل الدفعات وإصدار السندات</div>
    </div>
    <button class="btn-pro btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
        <i class="fas fa-plus"></i> تسجيل دفعة جديدة
    </button>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="sc green">
        <div class="sc-label"><i class="fas fa-check-circle" style="margin-left:5px;"></i>إجمالي المحصّل</div>
        <div class="sc-val">{{ number_format($totalCollected) }} ₪</div>
        <div class="sc-sub">منذ بداية النظام</div>
    </div>
    <div class="sc" style="border-top:3px solid var(--accent);">
        <div class="sc-label"><i class="fas fa-calendar-alt" style="margin-left:5px;"></i>محصّل هذا الشهر</div>
        <div class="sc-val">{{ number_format($monthCollected) }} ₪</div>
        <div class="sc-sub">{{ now()->translatedFormat('F Y') }}</div>
    </div>
    <div class="sc red">
        <div class="sc-label"><i class="fas fa-exclamation-circle" style="margin-left:5px;"></i>مبالغ مستحقة</div>
        <div class="sc-val">{{ number_format($totalOwed) }} ₪</div>
        <div class="sc-sub">غير محصلة من الطلاب</div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('school.payments.index') }}">
<div class="filter-bar">
    <select name="student_id">
        <option value="">كل الطلاب</option>
        @foreach($students as $st)
            <option value="{{ $st->id }}" {{ request('student_id')==$st->id?'selected':'' }}>{{ $st->name }}</option>
        @endforeach
    </select>
    <input type="date" name="from" value="{{ request('from') }}" placeholder="من تاريخ">
    <input type="date" name="to"   value="{{ request('to') }}"   placeholder="إلى تاريخ">
    <button type="submit" class="btn-pro btn-primary" style="padding:8px 18px;font-size:.82rem;"><i class="fas fa-filter"></i> فلترة</button>
    <a href="{{ route('school.payments.index') }}" class="btn-pro btn-secondary" style="padding:8px 18px;font-size:.82rem;text-decoration:none;"><i class="fas fa-undo"></i></a>
</div>
</form>

{{-- Table --}}
<div class="t-card">
    <div class="t-card-head">
        <span style="font-size:.9rem;font-weight:700;color:#1e293b;">
            السندات
            <span style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;margin-right:6px;">{{ $payments->total() }}</span>
        </span>
        <span style="font-size:.82rem;color:#94a3b8;">
            مجموع الصفحة:
            <strong style="color:#10b981;">{{ number_format($payments->sum('amount')) }} ₪</strong>
        </span>
    </div>
    <div style="overflow-x:auto;">
    @if($payments->isEmpty())
        <div class="empty-state"><i class="fas fa-coins"></i><div style="font-weight:700;">لا توجد مدفوعات مسجلة</div></div>
    @else
        <table class="pt">
            <thead>
                <tr><th>رقم السند</th><th>الطالب</th><th>المبلغ</th><th>طريقة الدفع</th><th>التاريخ</th><th>الوصف</th><th>المستلم</th><th style="text-align:center;">إجراءات</th></tr>
            </thead>
            <tbody>
            @foreach($payments as $pay)
            @php
                $mClass = ['نقدي'=>'m-cash','تحويل'=>'m-transfer','شيك'=>'m-check'][$pay->payment_method] ?? 'm-cash';
            @endphp
            <tr>
                <td><span class="receipt-badge">{{ $pay->receipt_number }}</span></td>
                <td>
                    <div style="font-weight:700;">{{ $pay->student->name ?? '—' }}</div>
                    <div style="font-size:.7rem;color:#94a3b8;">{{ $pay->student->identity_number ?? '' }}</div>
                </td>
                <td style="font-size:1rem;font-weight:800;color:var(--success);">{{ number_format($pay->amount) }} ₪</td>
                <td><span class="method-badge {{ $mClass }}">{{ $pay->payment_method }}</span></td>
                <td style="font-size:.8rem;color:#64748b;">{{ \Carbon\Carbon::parse($pay->payment_date)->format('Y-m-d') }}</td>
                <td style="font-size:.8rem;color:#64748b;max-width:150px;">{{ $pay->description ?? '—' }}</td>
                <td style="font-size:.8rem;font-weight:600;">{{ $pay->received_by ?? '—' }}</td>
                <td>
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <button class="act-btn print" onclick='printReceipt(@json($pay))' title="طباعة السند"><i class="fas fa-print"></i></button>
                        <form action="{{ route('school.payments.destroy', $pay->id) }}" method="POST"
                              onsubmit="return confirm('حذف هذا السند سيرد المبلغ من رصيد الطالب. متأكد؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="act-btn del" title="حذف"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    </div>
    @if($payments->hasPages())
    <div class="t-pagination">{{ $payments->withQueryString()->links() }}</div>
    @endif
</div>

{{-- Modal: Add Payment --}}
<div class="modal fade modal-pro" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" dir="rtl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تسجيل دفعة جديدة</h5>
                <button type="button" class="btn-close btn-close-white ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('school.payments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="fg">
                        <label>الطالب <span style="color:#ef4444">*</span></label>
                        <select name="student_id" id="p_student" class="fp" required onchange="loadBalance(this)">
                            <option value="">— اختر الطالب —</option>
                            @foreach($students as $st)
                                <option value="{{ $st->id }}"
                                    data-total="{{ $st->total_amount }}"
                                    data-paid="{{ $st->paid_amount }}">
                                    {{ $st->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Balance Info --}}
                    <div id="balanceInfo" style="display:none;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:14px;font-size:.82rem;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:#64748b;font-weight:600;">الرسوم الكاملة</span>
                            <strong id="b_total" style="color:#0f172a;">—</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:6px;">
                            <span style="color:#64748b;font-weight:600;">المدفوع</span>
                            <strong id="b_paid" style="color:var(--success);">—</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:6px;padding-top:6px;border-top:1px dashed #bbf7d0;">
                            <span style="color:#64748b;font-weight:700;">المتبقي</span>
                            <strong id="b_remaining" style="color:var(--danger);font-size:.95rem;">—</strong>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="fg">
                            <label>المبلغ (₪) <span style="color:#ef4444">*</span></label>
                            <input type="number" name="amount" class="fp" min="1" step="0.01" required placeholder="0.00">
                        </div>
                        <div class="fg">
                            <label>طريقة الدفع</label>
                            <select name="payment_method" class="fp">
                                <option value="نقدي">نقدي</option>
                                <option value="تحويل">تحويل بنكي</option>
                                <option value="شيك">شيك</option>
                            </select>
                        </div>
                    </div>
                    <div class="fg">
                        <label>تاريخ الدفع <span style="color:#ef4444">*</span></label>
                        <input type="date" name="payment_date" class="fp" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="fg">
                        <label>الوصف / الملاحظة</label>
                        <input type="text" name="description" class="fp" placeholder="مثال: دفعة أولى، قسط شهر مارس...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-pro btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-pro btn-primary"><i class="fas fa-save"></i> تسجيل وحفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Receipt Print Area (hidden until print) --}}
<div class="receipt-print" id="receiptPrintArea"></div>
@endsection

@section('scripts')
<script>
function loadBalance(sel) {
    const opt = sel.options[sel.selectedIndex];
    const total = parseFloat(opt.dataset.total||0);
    const paid  = parseFloat(opt.dataset.paid||0);
    const rem   = total - paid;
    if (sel.value) {
        document.getElementById('balanceInfo').style.display = 'block';
        document.getElementById('b_total').textContent     = total.toLocaleString() + ' ₪';
        document.getElementById('b_paid').textContent      = paid.toLocaleString()  + ' ₪';
        document.getElementById('b_remaining').textContent = rem.toLocaleString()   + ' ₪';
    } else {
        document.getElementById('balanceInfo').style.display = 'none';
    }
}

function printReceipt(p) {
    const schoolName = '{{ Auth::user()->school->name ?? "مدرسة" }}';
    const html = `
        <div class="rp-header">
            <div style="font-size:.9rem;color:#555;margin-bottom:4px;">سند قبض</div>
            <div class="rp-title">${schoolName}</div>
            <div style="font-size:.8rem;color:#555;margin-top:4px;">نظام مسار لإدارة مدارس السياقة</div>
        </div>
        <div class="rp-row"><span>رقم السند</span><strong style="font-family:monospace;">${p.receipt_number}</strong></div>
        <div class="rp-row"><span>اسم الطالب</span><strong>${p.student ? p.student.name : p.student_id}</strong></div>
        <div class="rp-row"><span>التاريخ</span><strong>${p.payment_date}</strong></div>
        <div class="rp-row"><span>طريقة الدفع</span><strong>${p.payment_method}</strong></div>
        ${p.description ? `<div class="rp-row"><span>الوصف</span><strong>${p.description}</strong></div>` : ''}
        <div class="rp-row rp-total"><span>المبلغ المستلم</span><strong style="color:#059669;">${parseFloat(p.amount).toLocaleString()} ₪</strong></div>
        <div class="rp-sigs">
            <div class="rp-sig"><div class="rp-sig-line"></div><div>المستلم: ${p.received_by||'—'}</div></div>
            <div class="rp-sig"><div class="rp-sig-line"></div><div>توقيع الدافع</div></div>
        </div>
    `;
    document.getElementById('receiptPrintArea').innerHTML = html;
    window.print();
}
</script>
@endsection
