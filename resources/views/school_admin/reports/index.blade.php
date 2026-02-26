@extends('layouts.school')
@section('title','التقارير والإحصاءات')
@section('page_section','التقارير')
@section('page_title','ملخص التقارير والإحصاءات')

@section('styles')
<style>
:root{--accent:#3b82f6;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;}
.pg-title{font-size:1.2rem;font-weight:800;color:var(--text-1);margin-bottom:22px;}
.metrics-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
@media(max-width:1000px){.metrics-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px) {.metrics-grid{grid-template-columns:1fr;}}
.metric{background:var(--card-bg);border:1px solid var(--border);border-radius:16px;padding:22px;position:relative;overflow:hidden;transition:.25s;}
.metric:hover{transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,.07);}
.metric::before{content:'';position:absolute;top:0;right:0;width:4px;height:100%;border-radius:0 16px 16px 0;}
.metric.blue::before{background:var(--accent);}
.metric.green::before{background:var(--success);}
.metric.amber::before{background:var(--warning);}
.metric.red::before{background:var(--danger);}
.metric-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1rem;margin-bottom:14px;}
.metric.blue  .metric-icon{background:rgba(59,130,246,.12);color:var(--accent);}
.metric.green .metric-icon{background:rgba(16,185,129,.12);color:var(--success);}
.metric.amber .metric-icon{background:rgba(245,158,11,.12);color:var(--warning);}
.metric.red   .metric-icon{background:rgba(239,68,68,.12);color:var(--danger);}
.metric-val{font-size:1.8rem;font-weight:900;color:var(--text-1);line-height:1;margin-bottom:4px;}
.metric-label{font-size:.75rem;font-weight:700;color:var(--text-muted);}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:22px;}
@media(max-width:900px){.two-col{grid-template-columns:1fr;}}
.card{background:var(--card-bg);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.card-head{padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.card-head .title{font-size:.92rem;font-weight:700;color:var(--text-1);}
.card-head .sub{font-size:.72rem;color:var(--text-muted);margin-top:2px;}
.list-item{display:flex;align-items:center;justify-content:space-between;padding:13px 20px;border-bottom:1px solid var(--border);}
.list-item:last-child{border-bottom:none;}
.li-name{font-weight:700;font-size:.85rem;color:var(--text-1);}
.li-sub{font-size:.72rem;color:var(--text-muted);margin-top:2px;}
.li-amount{font-size:.92rem;font-weight:800;color:var(--success);}
.li-receipt{font-family:monospace;font-size:.75rem;color:var(--text-muted);margin-top:2px;}
/* Financial Bar */
.fin-section{padding:20px 22px;}
.fin-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;}
.fin-row .label{font-size:.82rem;font-weight:600;color:var(--text-2);}
.fin-row .amount{font-size:.9rem;font-weight:800;}
.progress-bar-wrap{background:var(--bg);border-radius:10px;height:10px;overflow:hidden;margin-bottom:8px;}
.progress-bar-fill{height:100%;border-radius:10px;transition:width 1s ease;}
/* Status donut placeholder */
.donut-wrap{display:flex;gap:20px;align-items:center;padding:20px 22px;}
.donut-legend{flex:1;}
.legend-item{display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:.82rem;font-weight:600;color:#334155;}
.legend-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.print-btn{padding:8px 16px;background:#f1f5f9;border:1px solid var(--border);border-radius:9px;font-family:inherit;font-size:.78rem;font-weight:700;cursor:pointer;color:#64748b;transition:.2s;display:inline-flex;align-items:center;gap:6px;}

body.dark-mode .progress-bar-fill[style*="#e2e8f0"] { background: var(--border) !important; }
.print-btn:hover{background:#e2e8f0;}
@media print{
    #sidebar,.topbar,.page-breadcrumb,.print-btn{display:none!important;}
    #main{margin:0!important;}
    .two-col{grid-template-columns:1fr;}
}
</style>
@endsection

@section('content')
@php
    $collectRate = $summary['total_revenue'] > 0
        ? round($summary['collected'] / $summary['total_revenue'] * 100)
        : 0;
@endphp

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div class="pg-title" style="margin-bottom:0;"><i class="fas fa-chart-bar" style="color:var(--accent);margin-left:8px;"></i>التقارير والإحصاءات</div>
    <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> طباعة التقرير</button>
</div>

{{-- Key Metrics --}}
<div class="metrics-grid">
    <div class="metric blue">
        <div class="metric-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="metric-val">{{ $summary['students_count'] }}</div>
        <div class="metric-label">إجمالي الطلاب</div>
    </div>
    <div class="metric green">
        <div class="metric-icon"><i class="fas fa-book-open"></i></div>
        <div class="metric-val">{{ $summary['studying'] }}</div>
        <div class="metric-label">طلاب في مرحلة الدراسة</div>
    </div>
    <div class="metric amber">
        <div class="metric-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="metric-val">{{ $summary['passed'] }}</div>
        <div class="metric-label">طلاب ناجحون</div>
    </div>
    <div class="metric red">
        <div class="metric-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="metric-val">{{ $summary['sessions_month'] }}</div>
        <div class="metric-label">حصص هذا الشهر</div>
    </div>
</div>

<div class="two-col">
    {{-- Financial Summary --}}
    <div class="card">
        <div class="card-head">
            <div>
                <div class="title">الملخص المالي</div>
                <div class="sub">نسبة التحصيل: {{ $collectRate }}%</div>
            </div>
        </div>
        <div class="fin-section">
            <div class="fin-row">
                <span class="label">إجمالي الرسوم المقررة</span>
                <span class="amount" style="color:#0f172a;">{{ number_format($summary['total_revenue']) }} ₪</span>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width:100%;background:#e2e8f0;"></div>
            </div>

            <div class="fin-row">
                <span class="label">المبلغ المحصّل</span>
                <span class="amount" style="color:var(--success);">{{ number_format($summary['collected']) }} ₪</span>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width:{{ $collectRate }}%;background:var(--success);"></div>
            </div>

            <div class="fin-row" style="border-top:1px dashed var(--border);padding-top:14px;margin-top:6px;">
                <span class="label" style="font-size:.88rem;font-weight:700;color:var(--text-1);">المبالغ المستحقة</span>
                <span class="amount" style="color:var(--danger);font-size:1rem;">{{ number_format($summary['outstanding']) }} ₪</span>
            </div>

            <div style="margin-top:20px;background:var(--bg);border-radius:12px;padding:14px 16px;display:flex;gap:20px;">
                <div style="text-align:center;flex:1;">
                    <div style="font-size:.7rem;color:var(--text-muted);font-weight:600;">المدربون</div>
                    <div style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin-top:4px;">{{ $summary['trainers_count'] }}</div>
                </div>
                <div style="width:1px;background:var(--border);"></div>
                <div style="text-align:center;flex:1;">
                    <div style="font-size:.7rem;color:var(--text-muted);font-weight:600;">المركبات</div>
                    <div style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin-top:4px;">{{ $summary['vehicles_count'] }}</div>
                </div>
                <div style="width:1px;background:var(--border);"></div>
                <div style="text-align:center;flex:1;">
                    <div style="font-size:.7rem;color:#94a3b8;font-weight:600;">نجاح الطلاب</div>
                    @php
                        $passRate = $summary['students_count'] > 0
                            ? round($summary['passed'] / $summary['students_count'] * 100) : 0;
                    @endphp
                    <div style="font-size:1.3rem;font-weight:800;color:var(--success);margin-top:4px;">{{ $passRate }}%</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Status Breakdown --}}
    <div class="card">
        <div class="card-head">
            <div>
                <div class="title">توزيع حالات الطلاب</div>
                <div class="sub">{{ $summary['students_count'] }} طالب إجمالي</div>
            </div>
        </div>
        <div class="donut-wrap">
            {{-- Visual bar chart replacement --}}
            <div class="donut-legend" style="width:100%;">
                @php
                    $statuses = [
                        ['يدرس',   $summary['studying'],  '#3b82f6'],
                        ['ناجح',   $summary['passed'],    '#10b981'],
                        ['راسب',   \App\Models\Student::where('school_id',Auth::user()->school_id)->where('status','راسب')->count(), '#ef4444'],
                        ['موقوف',  \App\Models\Student::where('school_id',Auth::user()->school_id)->where('status','موقوف')->count(), '#f59e0b'],
                    ];
                @endphp
                @foreach($statuses as [$label, $count, $color])
                @php $pct = $summary['students_count'] > 0 ? round($count / $summary['students_count'] * 100) : 0; @endphp
                <div style="margin-bottom:14px;">
                    <div style="display:flex;justify-content:space-between;font-size:.78rem;font-weight:700;color:#334155;margin-bottom:5px;">
                        <span style="display:flex;align-items:center;gap:6px;">
                            <span style="width:9px;height:9px;border-radius:50%;background:{{ $color }};display:inline-block;"></span>
                            {{ $label }}
                        </span>
                        <span>{{ $count }} طالب ({{ $pct }}%)</span>
                    </div>
                    <div style="background:#f1f5f9;border-radius:6px;height:8px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:6px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Bottom Row --}}
<div class="two-col" style="margin-top:20px;">
    {{-- Recent Payments --}}
    <div class="card">
        <div class="card-head">
            <div>
                <div class="title">آخر المدفوعات</div>
                <div class="sub">أحدث 10 سندات قبض</div>
            </div>
            <a href="{{ route('school.payments.index') }}" style="font-size:.78rem;font-weight:700;color:var(--accent);text-decoration:none;">
                عرض الكل <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        @forelse($recentPayments as $pay)
        <div class="list-item">
            <div>
                <div class="li-name">{{ $pay->student->name ?? '—' }}</div>
                <div class="li-receipt">{{ $pay->receipt_number }} · {{ \Carbon\Carbon::parse($pay->payment_date)->format('Y-m-d') }}</div>
            </div>
            <div class="li-amount">{{ number_format($pay->amount) }} ₪</div>
        </div>
        @empty
        <div style="text-align:center;padding:30px;color:#94a3b8;font-size:.85rem;">لا توجد مدفوعات</div>
        @endforelse
    </div>

    {{-- Top Students --}}
    <div class="card">
        <div class="card-head">
            <div>
                <div class="title">أعلى الطلاب دفعاً</div>
                <div class="sub">الطلاب الذين سددوا أكثر</div>
            </div>
        </div>
        @forelse($topStudents as $idx => $st)
        <div class="list-item">
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="width:28px;height:28px;border-radius:8px;background:{{ ['#eff6ff','#ecfdf5','#fffbeb','#fef2f2','#fdf4ff'][$idx] ?? '#f1f5f9' }};
                    color:{{ ['#1d4ed8','#065f46','#713f12','#991b1b','#7e22ce'][$idx] ?? '#334155' }};
                    display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;">
                    {{ $idx+1 }}
                </span>
                <div>
                    <div class="li-name">{{ $st->name }}</div>
                    <div class="li-sub">{{ $st->license_type }} · {{ $st->phone ?? '' }}</div>
                </div>
            </div>
            <div>
                <div class="li-amount">{{ number_format($st->paid_amount) }} ₪</div>
                <div style="font-size:.7rem;color:{{ ($st->total_amount - $st->paid_amount) > 0 ? 'var(--danger)' : 'var(--success)' }};font-weight:600;text-align:left;">
                    {{ ($st->total_amount - $st->paid_amount) > 0 ? 'متبقي '.number_format($st->total_amount - $st->paid_amount).' ₪' : 'مسدد بالكامل ✓' }}
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:30px;color:#94a3b8;font-size:.85rem;">لا توجد بيانات</div>
        @endforelse
    </div>
</div>
@endsection
