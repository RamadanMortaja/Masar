@extends('layouts.admin')
@section('title','لوحة التحكم | نظام مسار')
@section('page_title','لوحة التحكم')

@section('styles')
<style>
.metrics-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px; }
@media(max-width:1100px){.metrics-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:580px) {.metrics-grid{grid-template-columns:1fr;}}
.two-col   { display:grid; grid-template-columns:1.5fr 1fr; gap:16px; margin-bottom:16px; }
.three-col { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px; }
@media(max-width:1000px){.two-col,.three-col{grid-template-columns:1fr;}}

/* Alert banner */
.alert-strip {
  border-radius: var(--radius-lg);
  padding: 12px 16px;
  display: flex; align-items: center; gap: 12px;
  margin-bottom: 14px;
  font-size: .8rem; font-weight: 700;
}
.alert-strip.fire  { background:rgba(239,68,68,.1);  border:1px solid rgba(239,68,68,.25);  color:#fca5a5; }
.alert-strip.warn  { background:rgba(245,158,11,.1); border:1px solid rgba(245,158,11,.25); color:#fcd34d; }
.strip-icon { width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0; }
.strip-link { margin-right:auto; font-size:.72rem; font-weight:700; color:inherit; text-decoration:none; opacity:.8; }
.strip-link:hover { opacity:1; }

/* Quota bar */
.qbar-wrap  { background:rgba(255,255,255,.06); border-radius:4px; height:6px; overflow:hidden; }
.qbar-fill  { height:100%; border-radius:4px; }

/* School mini-card */
.school-row { display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border);transition:.15s; }
.school-row:hover{background:var(--bg-hover);}
.school-row:last-child{border-bottom:none;}
.school-avatar{width:32px;height:32px;border-radius:8px;background:rgba(59,130,246,.15);color:var(--blue-light);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;flex-shrink:0;}
.school-name{font-size:.82rem;font-weight:700;color:var(--text-1);}
.school-code{font-size:.66rem;color:var(--text-3);font-family:monospace;}

/* Log row */
.log-row{display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border);}
.log-row:last-child{border-bottom:none;}
.log-icon{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0;}

/* Chart canvas */
.chart-canvas-wrap { padding:16px; height:200px; position:relative; }
</style>
@endsection

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; background: var(--card-bg); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--border);">
    <div>
        <h4 style="font-weight: 800; color: var(--text-1); margin-bottom: 4px;">أهلاً بك، {{ Auth::user()->name }} 👋</h4>
        <p style="color: var(--text-3); font-size: 0.85rem; margin: 0;">نظام مسار: إليك تحديثات المدارس والاشتراكات لليوم.</p>
    </div>
    <div class="d-none d-md-block">
        <a href="#" class="btn-pro btn-blue">
            <i class="fas fa-plus"></i> مدرسة جديدة
        </a>
    </div>
</div>

{{-- ─── Alert Banners ─── --}}
@if($criticalCount > 0)
<div class="alert-strip fire">
  <div class="strip-icon" style="background:rgba(239,68,68,.15);color:#fca5a5;"><i class="fas fa-fire-alt"></i></div>
  <div>
    <strong>{{ $criticalCount }} مدرسة</strong> — اشتراكها منتهٍ أو ينتهي خلال أسبوع!
  </div>
  <a href="{{ route('admin.subscriptions') }}" class="strip-link">تجديد فوري <i class="fas fa-arrow-left" style="font-size:.6rem;"></i></a>
</div>
@endif
@if($warningCount > 0)
<div class="alert-strip warn">
  <div class="strip-icon" style="background:rgba(245,158,11,.15);color:#fcd34d;"><i class="fas fa-clock"></i></div>
  <div>
    <strong>{{ $warningCount }} مدرسة</strong> — اشتراكها ينتهي خلال 30 يوماً
  </div>
  <a href="{{ route('admin.subscriptions') }}" class="strip-link">عرض التفاصيل <i class="fas fa-arrow-left" style="font-size:.6rem;"></i></a>
</div>
@endif

{{-- ─── Key Metrics ─── --}}
<div class="metrics-grid">
  <div class="stat-card">
    <div class="stat-icon ic-blue"><i class="fas fa-school"></i></div>
    <div>
      <div class="stat-label">المدارس النشطة</div>
      <div class="stat-val">{{ $stats['schools_active'] }}</div>
      <div class="stat-trend t-muted"><i class="fas fa-building" style="font-size:.6rem;"></i> من {{ $stats['schools_total'] }} مسجلة</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon ic-green"><i class="fas fa-users"></i></div>
    <div>
      <div class="stat-label">إجمالي الطلاب</div>
      <div class="stat-val">{{ number_format($stats['students_total']) }}</div>
      <div class="stat-trend t-green"><i class="fas fa-arrow-up" style="font-size:.6rem;"></i> {{ $stats['students_month'] }} هذا الشهر</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon ic-amber"><i class="fas fa-file-invoice-dollar"></i></div>
    <div>
      <div class="stat-label">إيرادات الاشتراكات</div>
      <div class="stat-val" style="font-size:1.35rem;">{{ number_format($stats['subscription_revenue']) }} ₪</div>
      <div class="stat-trend t-amber"><i class="fas fa-coins" style="font-size:.6rem;"></i> محصّل من المدارس</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon ic-purple"><i class="fas fa-database"></i></div>
    <div>
      <div class="stat-label">بنك الأسئلة</div>
      <div class="stat-val">{{ number_format($stats['questions_total']) }}</div>
      <div class="stat-trend t-muted"><i class="fas fa-traffic-light" style="font-size:.6rem;"></i> سؤال في النظام</div>
    </div>
  </div>
</div>

{{-- ─── Row 2: Charts ─── --}}
<div class="two-col">
  {{-- Bar Chart: Schools Quota --}}
  <div class="c-card">
    <div class="c-card-head">
      <div>
        <div class="c-card-title">استهلاك كوتة المدارس</div>
        <div class="c-card-sub">نسبة الطلاب الحاليين من الحد المسموح</div>
      </div>
    </div>
    <div class="chart-canvas-wrap"><canvas id="quotaChart"></canvas></div>
  </div>

  {{-- Student Status Donut --}}
  <div class="c-card">
    <div class="c-card-head">
      <div>
        <div class="c-card-title">حالات الطلاب</div>
        <div class="c-card-sub">توزيع في كافة المدارس</div>
      </div>
    </div>
    <div class="chart-canvas-wrap" style="height:230px;">
      <canvas id="statusChart"></canvas>
    </div>
  </div>
</div>

{{-- ─── Row 3: Schools Table + Top Schools ─── --}}
<div class="two-col">
  {{-- Schools overview --}}
  <div class="c-card">
    <div class="c-card-head">
      <div>
        <div class="c-card-title">المدارس — نظرة سريعة</div>
        <div class="c-card-sub">آخر 8 مدارس مع حالة الاشتراك والكوتة</div>
      </div>
      <a href="{{ route('schools.index') }}" class="c-card-link">عرض الكل ›</a>
    </div>
    <div style="overflow-x:auto;">
      <table class="c-table">
        <thead>
          <tr>
            <th>المدرسة</th>
            <th>الكوتة</th>
            <th>انتهاء الاشتراك</th>
            <th>الحالة</th>
          </tr>
        </thead>
        <tbody>
        @foreach($latestSchools as $s)
          @php
            $cnt  = $s->students_count ?? 0;
            $pct  = $s->student_limit > 0 ? round($cnt / $s->student_limit * 100) : 0;
            $qc   = $pct >= 90 ? 'var(--red)' : ($pct >= 70 ? 'var(--amber)' : 'var(--green)');
            $exp  = \Carbon\Carbon::parse($s->subscription_end);
            $days = now()->diffInDays($exp, false);
            $badgeClass = !$s->is_active ? 'badge-muted' : ($days <= 0 ? 'badge-red' : ($days <= 30 ? 'badge-amber' : 'badge-green'));
            $badgeText  = !$s->is_active ? 'موقوف' : ($days <= 0 ? 'منتهٍ' : ($days <= 30 ? $days.' يوم' : 'نشط'));
          @endphp
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:9px;">
                <div class="school-avatar">{{ mb_substr($s->name,0,1) }}</div>
                <div>
                  <div class="school-name">{{ Str::limit($s->name,22) }}</div>
                  <div class="school-code">{{ $s->school_code }}</div>
                </div>
              </div>
            </td>
            <td>
              <div style="display:flex;align-items:center;gap:8px;min-width:100px;">
                <div class="qbar-wrap" style="width:60px;height:6px;">
                  <div class="qbar-fill" style="width:{{ min($pct,100) }}%;background:{{ $qc }};"></div>
                </div>
                <span style="font-size:.7rem;font-weight:700;color:{{ $qc }};">{{ $cnt }}/{{ $s->student_limit }}</span>
              </div>
            </td>
            <td>
              <span style="font-size:.78rem;font-weight:600;color:{{ $days<=0?'var(--red)':($days<=30?'var(--amber)':'var(--text-2)') }};">
                {{ $exp->format('Y-m-d') }}
              </span>
            </td>
            <td><span class="badge-pro {{ $badgeClass }}">{{ $badgeText }}</span></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Top schools ranking --}}
  <div class="c-card">
    <div class="c-card-head">
      <div>
        <div class="c-card-title">أكثر المدارس طلاباً</div>
        <div class="c-card-sub">ترتيب حسب عدد المسجلين</div>
      </div>
    </div>
    @foreach($topSchools as $idx => $s)
      @php
        $cnt = $s->students_count ?? 0;
        $max = $topSchools->max(fn($x)=>$x->students_count ?? 0) ?: 1;
        $pct = round($cnt / $max * 100);
        $colors = ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4'];
        $c = $colors[$idx % 5];
      @endphp
      <div style="padding:11px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:11px;">
        <div style="width:24px;height:24px;border-radius:7px;background:rgba(255,255,255,.05);color:var(--text-3);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:900;flex-shrink:0;">{{ $idx+1 }}</div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:.82rem;font-weight:700;color:var(--text-1);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $s->name }}</div>
          <div style="display:flex;align-items:center;gap:6px;margin-top:4px;">
            <div style="background:rgba(255,255,255,.06);border-radius:3px;height:4px;width:70px;overflow:hidden;">
              <div style="height:100%;width:{{ $pct }}%;background:{{ $c }};border-radius:3px;"></div>
            </div>
            <span style="font-size:.68rem;color:var(--text-3);">{{ $cnt }} طالب</span>
          </div>
        </div>
        <div style="font-size:.78rem;font-weight:800;color:{{ $c }};">{{ number_format($cnt) }}</div>
      </div>
    @endforeach
  </div>
</div>

{{-- ─── Row 4: Activity Log ─── --}}
<div class="c-card">
  <div class="c-card-head">
    <div>
      <div class="c-card-title"><i class="fas fa-scroll" style="color:var(--blue-light);margin-left:7px;"></i>آخر العمليات</div>
      <div class="c-card-sub">{{ $recentLogs->count() }} عملية موثّقة مؤخراً</div>
    </div>
    <a href="{{ route('admin.logs') }}" class="c-card-link">السجل الكامل ›</a>
  </div>
  <div style="overflow-x:auto;">
    <table class="c-table">
      <thead>
        <tr><th>العملية</th><th>المستخدم</th><th>التفاصيل</th><th>الوقت</th></tr>
      </thead>
      <tbody>
      @forelse($recentLogs as $log)
        @php
          $a = strtolower($log->action ?? '');
          [$lc,$li] = str_contains($a,'creat') ? ['ic-green','fas fa-plus']
            : (str_contains($a,'updat')||str_contains($a,'تعديل') ? ['ic-blue','fas fa-pen']
            : (str_contains($a,'delet')||str_contains($a,'حذف')  ? ['ic-red','fas fa-trash']
            : ['ic-purple','fas fa-circle']));
        @endphp
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="log-icon {{ $lc }}"><i class="{{ $li }}"></i></div>
              <span style="font-weight:700;color:var(--text-1);">{{ $log->action }}</span>
            </div>
          </td>
          <td style="color:var(--text-3);font-size:.78rem;">{{ $log->user->name ?? 'النظام' }}</td>
          <td style="color:var(--text-3);font-size:.78rem;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $log->description }}</td>
          <td style="font-size:.7rem;color:var(--text-3);white-space:nowrap;">{{ $log->created_at->diffForHumans() }}</td>
        </tr>
      @empty
        <tr><td colspan="4"><div class="empty-state"><i class="fas fa-scroll"></i><strong>لا توجد عمليات</strong></div></td></tr>
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

// ── Quota Bar Chart ──
const schools = @json($chartData['names']);
const counts  = @json($chartData['counts']);
const limits  = @json($chartData['limits']);
new Chart(document.getElementById('quotaChart'), {
  type: 'bar',
  data: {
    labels: schools,
    datasets: [
      { label: 'الطلاب الحاليين', data: counts,
        backgroundColor: counts.map((c,i) => {
          const p = limits[i] > 0 ? c/limits[i] : 0;
          return p >= .9 ? 'rgba(239,68,68,.7)' : p >= .7 ? 'rgba(245,158,11,.7)' : 'rgba(59,130,246,.7)';
        }),
        borderRadius: 5, borderSkipped: false },
      { label: 'الحد الأقصى', data: limits,
        backgroundColor: 'rgba(255,255,255,.05)',
        borderRadius: 5, borderSkipped: false }
    ]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{display:false} },
    scales:{
      x:{ grid:{display:false}, ticks:{font:{size:10}} },
      y:{ grid:{color:'rgba(255,255,255,.04)'}, ticks:{font:{size:10}}, beginAtZero:true }
    }
  }
});

// ── Status Donut ──
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: ['يدرس','ناجح','راسب','موقوف'],
    datasets: [{
      data: [{{ $stats['studying'] }}, {{ $stats['passed'] }}, {{ $stats['failed'] }}, {{ $stats['stopped'] }}],
      backgroundColor: ['rgba(59,130,246,.8)','rgba(16,185,129,.8)','rgba(239,68,68,.8)','rgba(245,158,11,.8)'],
      borderWidth: 0, hoverOffset: 5,
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    cutout: '68%',
    plugins:{
      legend:{ position:'bottom', labels:{ padding:12, font:{size:11}, color:'#94a3b8' } }
    }
  }
});
</script>
@endsection
