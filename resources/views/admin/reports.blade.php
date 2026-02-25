@extends('layouts.admin')
@section('title', 'تقارير النظام | نظام مسار')
@section('page_section', 'التقارير')
@section('page_title', 'تقارير النظام')

@section('styles')
<style>
.metrics-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px; }
@media(max-width:1000px){.metrics-4{grid-template-columns:repeat(2,1fr);}}
@media(max-width:540px) {.metrics-4{grid-template-columns:1fr;}}
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
@media(max-width:900px){.two-col{grid-template-columns:1fr;}}
.chart-wrap { padding:16px; height:220px; position:relative; }
.top-school-row {
    display:flex; align-items:center; gap:12px;
    padding:10px 16px; border-bottom:1px solid var(--border);
}
.top-school-row:last-child { border-bottom:none; }
.rank-num {
    width:24px; height:24px; border-radius:7px;
    background:rgba(255,255,255,.05); color:var(--text-3);
    display:flex; align-items:center; justify-content:center;
    font-size:.72rem; font-weight:900; flex-shrink:0;
}
.fin-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:11px 18px; border-bottom:1px solid var(--border);
}
.fin-row:last-child { border-bottom:none; }
</style>
@endsection

@section('content')

<div class="page-hd">
    <div>
        <div class="page-hd-title"><i class="fas fa-chart-bar" style="color:var(--blue-light);margin-left:8px;"></i>تقارير النظام</div>
        <div class="page-hd-sub">اشتراكات المدارس وإحصاءات المنظومة</div>
    </div>
    <a href="{{ route('admin.subscriptions') }}" class="btn-pro btn-ghost" style="text-decoration:none;">
        <i class="fas fa-file-invoice-dollar"></i> إدارة الاشتراكات
    </a>
</div>

{{-- ─── Key Metrics ─── --}}
<div class="metrics-4">
    <div class="stat-card">
        <div class="stat-icon ic-blue"><i class="fas fa-school"></i></div>
        <div>
            <div class="stat-label">مدارس مشتركة</div>
            <div class="stat-val">{{ $stats['schools_total'] }}</div>
            <div class="stat-trend t-blue">{{ $stats['schools_active'] }} نشطة</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon ic-green"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-label">إيرادات الاشتراكات</div>
            <div class="stat-val" style="font-size:1.3rem;">{{ number_format($stats['total_revenue']) }} ₪</div>
            <div class="stat-trend t-green">محصّل من المدارس</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon ic-amber"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-label">إجمالي الطلاب</div>
            <div class="stat-val">{{ number_format($stats['students_total']) }}</div>
            <div class="stat-trend t-amber">في كافة المدارس</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon ic-purple"><i class="fas fa-database"></i></div>
        <div>
            <div class="stat-label">بنك الأسئلة</div>
            <div class="stat-val">{{ number_format($stats['questions_total']) }}</div>
            <div class="stat-trend t-muted">سؤال مسجل</div>
        </div>
    </div>
</div>

{{-- ─── Charts ─── --}}
<div class="two-col">
    {{-- Monthly revenue chart --}}
    <div class="c-card">
        <div class="c-card-head">
            <div><div class="c-card-title">إيرادات الاشتراكات الشهرية</div><div class="c-card-sub">آخر 6 أشهر</div></div>
        </div>
        <div class="chart-wrap"><canvas id="revenueChart"></canvas></div>
    </div>

    {{-- Collection summary --}}
    <div class="c-card">
        <div class="c-card-head">
            <div><div class="c-card-title">ملخص الاشتراكات</div><div class="c-card-sub">توزيع حالات المدارس</div></div>
        </div>
        <div style="padding:4px 0;">
            <div class="fin-row">
                <span style="font-size:.82rem;font-weight:600;color:var(--text-2);">
                    <i class="fas fa-circle" style="color:var(--green);font-size:.5rem;margin-left:6px;"></i>
                    مدارس نشطة
                </span>
                <span style="font-weight:800;color:#6ee7b7;">{{ $stats['schools_active'] }}</span>
            </div>
            <div class="fin-row">
                <span style="font-size:.82rem;font-weight:600;color:var(--text-2);">
                    <i class="fas fa-circle" style="color:var(--red);font-size:.5rem;margin-left:6px;"></i>
                    اشتراكات منتهية
                </span>
                <span style="font-weight:800;color:#fca5a5;">{{ $stats['expired_count'] }}</span>
            </div>
            <div class="fin-row">
                <span style="font-size:.82rem;font-weight:600;color:var(--text-2);">
                    <i class="fas fa-circle" style="color:var(--amber);font-size:.5rem;margin-left:6px;"></i>
                    تنتهي خلال 30 يوم
                </span>
                <span style="font-weight:800;color:#fcd34d;">{{ $stats['expiring_count'] }}</span>
            </div>
            <div class="fin-row">
                <span style="font-size:.82rem;font-weight:600;color:var(--text-2);">
                    <i class="fas fa-circle" style="color:var(--blue-light);font-size:.5rem;margin-left:6px;"></i>
                    إيرادات هذا الشهر
                </span>
                <span style="font-weight:800;color:var(--blue-light);">{{ number_format($stats['this_month_revenue']) }} ₪</span>
            </div>
            <div class="fin-row" style="border-top:1px solid var(--border-md);margin-top:4px;">
                <span style="font-size:.84rem;font-weight:800;color:var(--text-1);">الإجمالي المحصّل</span>
                <span style="font-weight:900;color:#6ee7b7;font-size:.92rem;">{{ number_format($stats['total_revenue']) }} ₪</span>
            </div>
        </div>
    </div>
</div>

{{-- ─── Monthly Students + Top Schools ─── --}}
<div class="two-col">
    {{-- Monthly student registrations --}}
    <div class="c-card">
        <div class="c-card-head">
            <div><div class="c-card-title">تسجيلات الطلاب الشهرية</div><div class="c-card-sub">آخر 6 أشهر</div></div>
        </div>
        <div class="chart-wrap"><canvas id="studentsChart"></canvas></div>
    </div>

    {{-- Top schools by students --}}
    <div class="c-card">
        <div class="c-card-head">
            <div><div class="c-card-title">أكثر المدارس طلاباً</div><div class="c-card-sub">ترتيب حسب عدد المسجلين</div></div>
        </div>
        @php $maxSt = $topSchools->max(fn($s)=>$s->students_count??0) ?: 1; @endphp
        @foreach($topSchools as $idx => $s)
        @php
            $cnt = $s->students_count ?? 0;
            $pct = round($cnt/$maxSt*100);
            $colors = ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4'];
            $c = $colors[$idx % 5];
        @endphp
        <div class="top-school-row">
            <div class="rank-num">{{ $idx+1 }}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:.82rem;font-weight:700;color:var(--text-1);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $s->name }}</div>
                <div style="display:flex;align-items:center;gap:7px;margin-top:4px;">
                    <div style="background:rgba(255,255,255,.06);border-radius:3px;height:4px;width:80px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $c }};border-radius:3px;"></div>
                    </div>
                    <span style="font-size:.68rem;color:var(--text-3);">{{ $cnt }} طالب</span>
                </div>
            </div>
            <span style="font-weight:800;color:{{ $c }};font-size:.82rem;">{{ number_format($cnt) }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- ─── Recent Subscription Payments ─── --}}
<div class="c-card">
    <div class="c-card-head">
        <div><div class="c-card-title"><i class="fas fa-coins" style="color:var(--amber);margin-left:6px;"></i>آخر دفعات الاشتراكات</div><div class="c-card-sub">سجل المدفوعات المحصّلة من المدارس</div></div>
        <a href="{{ route('admin.subscriptions') }}" class="c-card-link">عرض الكل ›</a>
    </div>
    <div style="overflow-x:auto;">
    <table class="c-table">
        <thead>
            <tr><th>المدرسة</th><th>المبلغ</th><th>الملاحظة</th><th>بواسطة</th><th>التاريخ</th></tr>
        </thead>
        <tbody>
        @forelse($recentPayments as $pay)
        <tr>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:28px;height:28px;border-radius:7px;background:rgba(16,185,129,.1);color:#6ee7b7;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:900;flex-shrink:0;">
                        {{ mb_substr($pay->school->name??'?',0,1) }}
                    </div>
                    <span style="font-weight:700;color:var(--text-1);font-size:.82rem;">{{ $pay->school->name ?? '—' }}</span>
                </div>
            </td>
            <td style="font-weight:900;color:#6ee7b7;font-size:.87rem;">{{ number_format($pay->amount) }} ₪</td>
            <td style="font-size:.78rem;color:var(--text-3);">{{ $pay->note ?? '—' }}</td>
            <td style="font-size:.78rem;color:var(--text-3);">{{ $pay->recorder->name ?? 'النظام' }}</td>
            <td style="font-size:.74rem;color:var(--text-3);">
                <div>{{ \Carbon\Carbon::parse($pay->paid_at)->format('Y-m-d') }}</div>
            </td>
        </tr>
        @empty
        <tr><td colspan="5"><div class="empty-state"><i class="fas fa-coins"></i><strong>لا توجد دفعات</strong><span>لم يتم تسجيل أي دفعات اشتراك بعد</span></div></td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
Chart.defaults.color = '#64748b';
Chart.defaults.font.family = 'Tajawal';

// Revenue chart
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json($monthlyRevenue->pluck('month')),
        datasets: [{
            label: 'الإيرادات',
            data: @json($monthlyRevenue->pluck('total')),
            backgroundColor: 'rgba(59,130,246,.6)',
            borderColor: '#3b82f6',
            borderWidth: 1.5,
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: 'rgba(255,255,255,.04)' }, beginAtZero: true, ticks: { font: { size: 11 } } }
        }
    }
});

// Students chart
new Chart(document.getElementById('studentsChart'), {
    type: 'line',
    data: {
        labels: @json($monthlyStudents->pluck('month')),
        datasets: [{
            label: 'طلاب جدد',
            data: @json($monthlyStudents->pluck('count')),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16,185,129,.07)',
            borderWidth: 2,
            pointBackgroundColor: '#10b981',
            pointRadius: 4,
            fill: true,
            tension: .35,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: 'rgba(255,255,255,.04)' }, beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } } }
        }
    }
});
</script>
@endsection
