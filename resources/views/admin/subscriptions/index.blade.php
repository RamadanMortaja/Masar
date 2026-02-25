@extends('layouts.admin')
@section('title','الاشتراكات المالية | نظام مسار')
@section('page_section','إدارة النظام')
@section('page_title','الاشتراكات المالية')

@section('styles')
<style>
.metrics-4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
@media(max-width:1000px){.metrics-4{grid-template-columns:repeat(2,1fr);}}
@media(max-width:540px){.metrics-4{grid-template-columns:1fr;}}

.plan-chip{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.66rem;font-weight:800;}
.chip-monthly{background:rgba(59,130,246,.12);color:var(--blue-light);}
.chip-yearly {background:rgba(139,92,246,.12);color:#c4b5fd;}

.sub-row-school{display:flex;align-items:center;gap:9px;}
.sub-avatar{width:32px;height:32px;border-radius:9px;background:rgba(59,130,246,.1);color:var(--blue-light);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:.78rem;flex-shrink:0;}

/* Payment history per school */
.pay-hist-item{padding:10px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
.pay-hist-item:last-child{border-bottom:none;}
.pay-receipt{font-family:monospace;font-size:.72rem;background:rgba(255,255,255,.06);padding:2px 8px;border-radius:5px;color:var(--text-3);}

/* Chart wrap */
.chart-wrap-h{padding:16px;height:200px;position:relative;}

/* Plan cards */
.plan-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:8px;}
.plan-opt{border:1.5px solid var(--border-md);border-radius:10px;padding:12px;cursor:pointer;transition:.18s;text-align:center;}
.plan-opt:hover{border-color:var(--blue);}
.plan-opt.selected{border-color:var(--blue);background:rgba(59,130,246,.08);}
.plan-opt input{display:none;}
.plan-opt .pname{font-size:.78rem;font-weight:800;color:var(--text-1);margin-top:6px;}
.plan-opt .pprice{font-size:.66rem;color:var(--text-3);margin-top:2px;}

.two-col-rev{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}
@media(max-width:900px){.two-col-rev{grid-template-columns:1fr;}}
</style>
@endsection

@section('content')

{{-- ─── Summary Metrics ─── --}}
<div class="metrics-4">
  <div class="stat-card">
    <div class="stat-icon ic-green"><i class="fas fa-check-circle"></i></div>
    <div>
      <div class="stat-label">مدارس نشطة</div>
      <div class="stat-val">{{ $stats['active'] }}</div>
      <div class="stat-trend t-green">اشتراك ساري</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon ic-red"><i class="fas fa-times-circle"></i></div>
    <div>
      <div class="stat-label">اشتراكات منتهية</div>
      <div class="stat-val">{{ $stats['expired'] }}</div>
      <div class="stat-trend t-red">تحتاج تجديد</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon ic-amber"><i class="fas fa-clock"></i></div>
    <div>
      <div class="stat-label">تنتهي خلال 30 يوم</div>
      <div class="stat-val">{{ $stats['expiring'] }}</div>
      <div class="stat-trend t-amber">تحذير مبكر</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon ic-blue"><i class="fas fa-coins"></i></div>
    <div>
      <div class="stat-label">إجمالي الإيرادات</div>
      <div class="stat-val" style="font-size:1.3rem;">{{ number_format($stats['total_revenue']) }} ₪</div>
      <div class="stat-trend t-blue">من اشتراكات المدارس</div>
    </div>
  </div>
</div>

{{-- ─── Revenue + Chart ─── --}}
<div class="two-col-rev">
  {{-- Monthly chart --}}
  <div class="c-card">
    <div class="c-card-head">
      <div><div class="c-card-title">إيرادات الاشتراكات</div><div class="c-card-sub">آخر 6 أشهر</div></div>
    </div>
    <div class="chart-wrap-h"><canvas id="revenueChart"></canvas></div>
  </div>

  {{-- Stats breakdown --}}
  <div class="c-card">
    <div class="c-card-head"><div><div class="c-card-title">توزيع الخطط</div><div class="c-card-sub">حسب نوع الاشتراك</div></div></div>
    <div style="padding:14px 16px;">
      @php
        $monthlyCount = $allSchools->where('plan','monthly')->count();
        $yearlyCount  = $allSchools->where('plan','yearly')->count();
        $total = $allSchools->count() ?: 1;
      @endphp
      <div style="margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;font-size:.77rem;font-weight:700;margin-bottom:6px;">
          <span style="color:var(--text-2);">شهري</span>
          <span style="color:var(--blue-light);">{{ $monthlyCount }} مدرسة</span>
        </div>
        <div style="background:rgba(255,255,255,.06);border-radius:4px;height:8px;overflow:hidden;">
          <div style="height:100%;width:{{ round($monthlyCount/$total*100) }}%;background:var(--blue);border-radius:4px;"></div>
        </div>
      </div>
      <div>
        <div style="display:flex;justify-content:space-between;font-size:.77rem;font-weight:700;margin-bottom:6px;">
          <span style="color:var(--text-2);">سنوي</span>
          <span style="color:#c4b5fd;">{{ $yearlyCount }} مدرسة</span>
        </div>
        <div style="background:rgba(255,255,255,.06);border-radius:4px;height:8px;overflow:hidden;">
          <div style="height:100%;width:{{ round($yearlyCount/$total*100) }}%;background:#8b5cf6;border-radius:4px;"></div>
        </div>
      </div>
      <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--border);">
        <div style="font-size:.7rem;font-weight:800;color:var(--text-3);text-transform:uppercase;letter-spacing:.07em;margin-bottom:10px;">ملخص سريع</div>
        @php
          $thisMonth = $subscriptionPayments->where('paid_at','>=',now()->startOfMonth())->sum('amount');
          $lastMonth = $subscriptionPayments->where('paid_at','>=',now()->subMonth()->startOfMonth())
                         ->where('paid_at','<',now()->startOfMonth())->sum('amount');
        @endphp
        <div style="display:flex;justify-content:space-between;font-size:.79rem;padding:6px 0;border-bottom:1px solid var(--border);">
          <span style="color:var(--text-3);">هذا الشهر</span>
          <span style="font-weight:800;color:var(--green);">{{ number_format($thisMonth) }} ₪</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.79rem;padding:6px 0;border-bottom:1px solid var(--border);">
          <span style="color:var(--text-3);">الشهر الماضي</span>
          <span style="font-weight:700;color:var(--text-2);">{{ number_format($lastMonth) }} ₪</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.79rem;padding:6px 0;">
          <span style="color:var(--text-3);">إجمالي المحصّل</span>
          <span style="font-weight:900;color:var(--blue-light);font-size:.88rem;">{{ number_format($stats['total_revenue']) }} ₪</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ─── Filter + Table ─── --}}
<form method="GET" action="{{ route('admin.subscriptions') }}">
<div class="filter-bar">
  <input type="text" name="search" placeholder="اسم المدرسة أو الكود..." value="{{ request('search') }}" class="f-input" style="min-width:200px;">
  <select name="status" class="f-select">
    <option value="">كل الحالات</option>
    <option value="active"   {{ request('status')=='active'  ?'selected':'' }}>نشطة</option>
    <option value="expiring" {{ request('status')=='expiring'?'selected':'' }}>تنتهي قريباً</option>
    <option value="expired"  {{ request('status')=='expired' ?'selected':'' }}>منتهية</option>
  </select>
  <select name="plan" class="f-select">
    <option value="">كل الخطط</option>
    <option value="monthly" {{ request('plan')=='monthly'?'selected':'' }}>شهري</option>
    <option value="yearly"  {{ request('plan')=='yearly' ?'selected':'' }}>سنوي</option>
  </select>
  <button type="submit" class="btn-pro btn-blue btn-sm"><i class="fas fa-filter"></i> فلترة</button>
  <a href="{{ route('admin.subscriptions') }}" class="btn-pro btn-ghost btn-sm" style="text-decoration:none;"><i class="fas fa-undo"></i></a>
  <button type="button" class="btn-pro btn-green btn-sm" onclick="openAddPayment()"
          style="margin-right:auto;">
    <i class="fas fa-plus"></i> تسجيل دفعة
  </button>
</div>
</form>

<div class="c-card">
  <div class="c-card-head">
    <div><div class="c-card-title">سجل الاشتراكات والدفعات</div><div class="c-card-sub">{{ $schools->total() }} مدرسة</div></div>
  </div>
  <div style="overflow-x:auto;">
  <table class="c-table">
    <thead>
      <tr>
        <th>المدرسة</th>
        <th>الخطة</th>
        <th>آخر دفعة</th>
        <th>المبلغ المدفوع</th>
        <th>انتهاء الاشتراك</th>
        <th>الحالة</th>
        <th style="text-align:right;">إجراءات</th>
      </tr>
    </thead>
    <tbody>
    @forelse($schools as $s)
      @php
        $exp  = \Carbon\Carbon::parse($s->subscription_end);
        $days = now()->diffInDays($exp, false);
        $bClass = !$s->is_active ? 'badge-muted'
          : ($days <= 0 ? 'badge-red' : ($days <= 30 ? 'badge-amber' : 'badge-green'));
        $bText  = !$s->is_active ? 'موقوف'
          : ($days <= 0 ? 'منتهٍ' : ($days <= 30 ? $days.' يوم' : 'نشط'));
        $latestPay = $s->subscriptionPayments->last();
      @endphp
      <tr>
        <td>
          <div class="sub-row-school">
            <div class="sub-avatar">{{ mb_substr($s->name,0,1) }}</div>
            <div>
              <div style="font-weight:700;color:var(--text-1);font-size:.83rem;">{{ $s->name }}</div>
              <div style="font-size:.66rem;color:var(--text-3);font-family:monospace;">{{ $s->school_code }}</div>
            </div>
          </div>
        </td>
        <td>
          <span class="plan-chip {{ $s->plan == 'yearly' ? 'chip-yearly' : 'chip-monthly' }}">
            {{ $s->plan == 'yearly' ? '🏆 سنوي' : '📅 شهري' }}
          </span>
        </td>
        <td style="font-size:.77rem;color:var(--text-3);">
          {{ $latestPay ? \Carbon\Carbon::parse($latestPay->paid_at)->format('Y-m-d') : '—' }}
        </td>
        <td style="font-weight:800;color:var(--green);">
          {{ number_format($s->subscriptionPayments->sum('amount')) }} ₪
        </td>
        <td>
          <span style="font-size:.8rem;font-weight:700;color:{{ $days<=0?'var(--red)':($days<=30?'var(--amber)':'var(--text-2)') }};">
            {{ $exp->format('Y-m-d') }}
          </span>
        </td>
        <td><span class="badge-pro {{ $bClass }}">{{ $bText }}</span></td>
        <td>
          <div style="display:flex;gap:5px;justify-content:flex-end;padding-left:8px;">
            <button class="btn-pro btn-green btn-icon" onclick='openRenew(@json($s))' title="تجديد">
              <i class="fas fa-sync-alt" style="font-size:.72rem;"></i>
            </button>
            <button class="btn-pro btn-amber btn-icon" onclick='openPayHistory(@json(["id"=>$s->id,"name"=>$s->name,"pays"=>$s->subscriptionPayments]))' title="سجل الدفعات">
              <i class="fas fa-list" style="font-size:.72rem;"></i>
            </button>
            <form action="{{ route('schools.toggle', $s->id) }}" method="POST">
              @csrf @method('PATCH')
              <button type="submit" class="{{ $s->is_active ? 'btn-pro btn-red btn-icon' : 'btn-pro btn-green btn-icon' }}"
                      title="{{ $s->is_active ? 'إيقاف' : 'تفعيل' }}"
                      onclick="return confirm('{{ $s->is_active ? 'إيقاف هذه المدرسة؟' : 'تفعيل هذه المدرسة؟' }}')">
                <i class="fas {{ $s->is_active ? 'fa-pause' : 'fa-play' }}" style="font-size:.68rem;"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="7"><div class="empty-state"><i class="fas fa-file-invoice-dollar"></i><strong>لا توجد نتائج</strong></div></td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
  @if($schools->hasPages())
  <div style="padding:13px 18px;border-top:1px solid var(--border);">
    {{ $schools->withQueryString()->links() }}
  </div>
  @endif
</div>

{{-- ─── Modals ─── --}}

{{-- Renew Modal --}}
<div class="modal fade modal-pro" id="renewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" dir="rtl">
    <div class="modal-content">
      <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5 class="modal-title"><i class="fas fa-sync-alt" style="color:var(--green);margin-left:7px;"></i>تجديد اشتراك: <span id="renewName"></span></h5>
        <div class="btn-close-dark" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
      </div>
      <form id="renewForm" method="POST" action="">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label-pro">تاريخ الانتهاء الجديد *</label>
              <input type="date" name="subscription_end" id="r_end" class="form-input-pro" required>
            </div>
            <div class="form-group">
              <label class="form-label-pro">حد الطلاب</label>
              <input type="number" name="student_limit" id="r_limit" class="form-input-pro" min="1">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label-pro">مبلغ الاشتراك المدفوع (₪)</label>
            <input type="number" name="subscription_amount" id="r_amount" class="form-input-pro" min="0" step="0.01" placeholder="0">
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label-pro">ملاحظة</label>
            <input type="text" name="subscription_note" class="form-input-pro" placeholder="تجديد سنوي، دفعة جزئية...">
          </div>
        </div>
        <div class="modal-footer" style="display:flex;gap:9px;">
          <button type="button" class="btn-pro btn-ghost" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn-pro btn-blue"><i class="fas fa-check"></i> تأكيد التجديد</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Payment History Modal --}}
<div class="modal fade modal-pro" id="payHistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" dir="rtl">
    <div class="modal-content">
      <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5 class="modal-title"><i class="fas fa-list" style="color:var(--amber);margin-left:7px;"></i>سجل دفعات: <span id="payHistName"></span></h5>
        <div class="btn-close-dark" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
      </div>
      <div class="modal-body" style="padding:0;">
        <div id="payHistBody"></div>
      </div>
    </div>
  </div>
</div>

{{-- Add Payment Modal --}}
<div class="modal fade modal-pro" id="addPayModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" dir="rtl">
    <div class="modal-content">
      <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5 class="modal-title"><i class="fas fa-plus" style="color:var(--green);margin-left:7px;"></i>تسجيل دفعة اشتراك</h5>
        <div class="btn-close-dark" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
      </div>
      <form action="{{ route('admin.subscriptions.payment') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label-pro">المدرسة *</label>
            <select name="school_id" class="form-input-pro" required>
              <option value="">— اختر المدرسة —</option>
              @foreach($allSchools as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label-pro">المبلغ (₪) *</label>
              <input type="number" name="amount" class="form-input-pro" required min="1" step="0.01">
            </div>
            <div class="form-group">
              <label class="form-label-pro">تاريخ الدفع *</label>
              <input type="date" name="paid_at" class="form-input-pro" required value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label-pro">ملاحظة</label>
            <input type="text" name="note" class="form-input-pro" placeholder="وصف الدفعة...">
          </div>
        </div>
        <div class="modal-footer" style="display:flex;gap:9px;">
          <button type="button" class="btn-pro btn-ghost" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn-pro btn-blue"><i class="fas fa-save"></i> حفظ الدفعة</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Revenue chart
const months = @json($monthlyRevenue->pluck('month'));
const revs   = @json($monthlyRevenue->pluck('total'));
new Chart(document.getElementById('revenueChart'), {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      label: 'إيرادات',
      data: revs,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,.07)',
      borderWidth: 2,
      pointBackgroundColor: '#3b82f6',
      pointRadius: 4,
      fill: true,
      tension: .35,
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{display:false} },
    scales:{
      x:{ grid:{display:false}, ticks:{font:{size:10},color:'#475569'} },
      y:{ grid:{color:'rgba(255,255,255,.04)'}, ticks:{font:{size:10},color:'#475569'}, beginAtZero:true }
    }
  }
});

function openRenew(s) {
  document.getElementById('renewName').textContent = s.name;
  document.getElementById('renewForm').action = '/admin/subscriptions/' + s.id + '/renew';
  document.getElementById('r_end').value   = s.subscription_end || '';
  document.getElementById('r_limit').value = s.student_limit    || '';
  document.getElementById('r_amount').value = '';
  new bootstrap.Modal(document.getElementById('renewModal')).show();
}

function openPayHistory(d) {
  document.getElementById('payHistName').textContent = d.name;
  const pays = d.pays || [];
  document.getElementById('payHistBody').innerHTML = pays.length
    ? pays.map(p => `
      <div class="pay-hist-item">
        <div style="width:30px;height:30px;border-radius:8px;background:rgba(16,185,129,.1);color:#6ee7b7;display:flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0;">
          <i class="fas fa-coins"></i>
        </div>
        <div style="flex:1;">
          <div style="font-size:.8rem;font-weight:700;color:var(--text-1);">${(parseFloat(p.amount||0)).toLocaleString()} ₪</div>
          <div style="font-size:.68rem;color:var(--text-3);">${p.note || '—'}</div>
        </div>
        <div style="font-size:.68rem;color:var(--text-3);font-family:monospace;">${p.paid_at || ''}</div>
      </div>`).join('')
    : '<div class="empty-state" style="padding:36px;"><i class="fas fa-coins"></i><strong>لا توجد دفعات مسجلة</strong></div>';
  new bootstrap.Modal(document.getElementById('payHistModal')).show();
}

function openAddPayment() {
  new bootstrap.Modal(document.getElementById('addPayModal')).show();
}
</script>
@endsection
