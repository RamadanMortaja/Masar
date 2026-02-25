@extends('layouts.admin')
@section('title','إدارة المدارس | نظام مسار')
@section('page_section','إدارة النظام')
@section('page_title','المدارس')

@section('styles')
<style>
.schools-layout { display:grid; grid-template-columns:1fr 360px; gap:18px; align-items:start; }
@media(max-width:1100px) { .schools-layout{grid-template-columns:1fr;} }

.school-card {
  background:var(--bg-card);
  border:1px solid var(--border);
  border-radius:var(--radius-lg);
  overflow:hidden;
  transition:.22s;
  position:relative;
}
.school-card:hover { border-color:var(--border-md); transform:translateY(-2px); }
.school-card.inactive { opacity:.6; }
.school-card-top {
  padding:14px 16px;
  display:flex; align-items:flex-start;
  justify-content:space-between; gap:10px;
}
.school-avatar-lg {
  width:40px; height:40px;
  border-radius:11px;
  background:rgba(59,130,246,.15);
  color:var(--blue-light);
  display:flex; align-items:center; justify-content:center;
  font-size:.92rem; font-weight:900;
  flex-shrink:0;
}
.school-card-name { font-size:.87rem; font-weight:800; color:var(--text-1); line-height:1.3; }
.school-card-code { font-size:.65rem; color:var(--text-3); font-family:monospace; margin-top:2px; }
.school-card-body { padding:0 16px 14px; }
.school-card-footer {
  padding:11px 16px;
  border-top:1px solid var(--border);
  display:flex; gap:7px;
  background:rgba(255,255,255,.02);
}

/* dropdown menu */
.dd-trigger {
  width:28px;height:28px;
  border-radius:7px;
  background:rgba(255,255,255,.05);
  border:1px solid var(--border-md);
  color:var(--text-3);
  display:flex;align-items:center;justify-content:center;
  font-size:.7rem;
  cursor:pointer;
  transition:.15s;
  flex-shrink:0;
}
.dd-trigger:hover{background:var(--bg-hover);color:var(--text-1);}
.dd-menu {
  position:absolute; top:46px; left:16px;
  background:var(--bg-card);
  border:1px solid var(--border-md);
  border-radius:var(--radius);
  box-shadow:0 16px 40px rgba(0,0,0,.5);
  z-index:100; min-width:170px;
  display:none;
  overflow:hidden;
}
.dd-menu.open{display:block;animation:fadeInDown .15s ease;}
.dd-item {
  display:flex;align-items:center;gap:9px;
  padding:9px 14px;
  font-size:.79rem;font-weight:600;color:var(--text-2);
  cursor:pointer;transition:.15s;
  border:none;width:100%;background:transparent;
  text-decoration:none;
  direction:rtl;
}
.dd-item:hover{background:var(--bg-hover);color:var(--text-1);}
.dd-item.red:hover{color:#fca5a5;}
.dd-sep{height:1px;background:var(--border);margin:4px 0;}

/* Add School form card */
.add-form-card {
  background:var(--bg-card);
  border:1px solid var(--border);
  border-radius:var(--radius-lg);
  overflow:hidden;
  position:sticky; top:20px;
}
.add-form-head {
  padding:14px 18px;
  border-bottom:1px solid var(--border);
  background:rgba(59,130,246,.06);
}
.add-form-title{font-size:.87rem;font-weight:800;color:var(--text-1);}

/* Expiry warning */
.expiry-warn { color:var(--red); animation:blink 2s infinite; }
@keyframes blink{0%,100%{opacity:1;}50%{opacity:.5;}}
</style>
@endsection

@section('content')

{{-- Stats Row --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;">
  @php
    $totalS  = $schools->count();
    $activeS = $schools->where('is_active',true)->count();
    $expiredS = $schools->filter(fn($s)=>\Carbon\Carbon::parse($s->subscription_end)->isPast())->count();
    $critQ   = $schools->filter(fn($s)=>$s->student_limit>0 && ($s->students_count/$s->student_limit) > .9)->count();
  @endphp
  <div class="stat-card"><div class="stat-icon ic-blue"><i class="fas fa-school"></i></div><div><div class="stat-label">الإجمالي</div><div class="stat-val">{{ $totalS }}</div></div></div>
  <div class="stat-card"><div class="stat-icon ic-green"><i class="fas fa-check-circle"></i></div><div><div class="stat-label">نشطة</div><div class="stat-val">{{ $activeS }}</div></div></div>
  <div class="stat-card"><div class="stat-icon ic-amber"><i class="fas fa-exclamation-triangle"></i></div><div><div class="stat-label">كوتة ممتلئة</div><div class="stat-val">{{ $critQ }}</div></div></div>
  <div class="stat-card"><div class="stat-icon ic-red"><i class="fas fa-times-circle"></i></div><div><div class="stat-label">اشتراك منتهٍ</div><div class="stat-val">{{ $expiredS }}</div></div></div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('schools.index') }}">
<div class="filter-bar">
  <input type="text" name="search" placeholder="ابحث باسم أو كود..." value="{{ request('search') }}" class="f-input" style="min-width:200px;">
  <select name="status" class="f-select">
    <option value="">كل الحالات</option>
    <option value="active"   {{ request('status')=='active'   ?'selected':'' }}>نشطة</option>
    <option value="inactive" {{ request('status')=='inactive' ?'selected':'' }}>معطلة</option>
  </select>
  <select name="archived" class="f-select">
    <option value="0" {{ request('archived','0')=='0'?'selected':'' }}>غير مؤرشفة</option>
    <option value="1" {{ request('archived')=='1'?'selected':'' }}>المؤرشفة</option>
  </select>
  <button type="submit" class="btn-pro btn-blue btn-sm"><i class="fas fa-filter"></i> فلترة</button>
  <a href="{{ route('schools.index') }}" class="btn-pro btn-ghost btn-sm" style="text-decoration:none;"><i class="fas fa-undo"></i></a>
  <a href="{{ route('schools.trashed') }}" class="btn-pro btn-red btn-sm" style="text-decoration:none;margin-right:auto;">
    <i class="fas fa-trash-alt"></i> سلة المحذوفات
  </a>
</div>
</form>

{{-- Layout: Schools Grid + Add Form --}}
<div class="schools-layout">

  {{-- Schools Grid --}}
  <div>
    @if($errors->any())
    <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:var(--radius);padding:12px 16px;margin-bottom:14px;font-size:.79rem;color:#fca5a5;">
      <i class="fas fa-exclamation-circle" style="margin-left:6px;"></i>
      {{ $errors->first() }}
    </div>
    @endif

    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
    @forelse($schools as $school)
    @php
      $cnt  = $school->students_count ?? 0;
      $pct  = $school->student_limit > 0 ? round($cnt / $school->student_limit * 100) : 0;
      $qc   = $pct >= 90 ? 'var(--red)' : ($pct >= 70 ? 'var(--amber)' : 'var(--green)');
      $exp  = \Carbon\Carbon::parse($school->subscription_end);
      $days = now()->diffInDays($exp, false);
    @endphp
    <div class="school-card {{ !$school->is_active ? 'inactive' : '' }}" id="sc-{{ $school->id }}">
      <div class="school-card-top">
        <div style="display:flex;gap:10px;align-items:flex-start;flex:1;min-width:0;">
          <div class="school-avatar-lg">{{ mb_substr($school->name,0,1) }}</div>
          <div style="flex:1;min-width:0;">
            <div class="school-card-name">{{ $school->name }}</div>
            <div class="school-card-code">{{ $school->school_code }}</div>
            <div style="margin-top:6px;display:flex;gap:5px;flex-wrap:wrap;">
              @if(!$school->is_active)
                <span class="badge-pro badge-muted"><i class="fas fa-pause-circle"></i> موقوف</span>
              @elseif($days <= 0)
                <span class="badge-pro badge-red"><i class="fas fa-times-circle"></i> منتهٍ</span>
              @elseif($days <= 30)
                <span class="badge-pro badge-amber"><i class="fas fa-clock"></i> {{ $days }} يوم</span>
              @else
                <span class="badge-pro badge-green"><i class="fas fa-circle" style="font-size:.4rem;"></i> نشط</span>
              @endif
              <span class="badge-pro badge-muted">{{ ['monthly'=>'شهري','yearly'=>'سنوي'][$school->plan] ?? $school->plan }}</span>
            </div>
          </div>
        </div>

        {{-- Dropdown --}}
        <div style="position:relative;">
          <div class="dd-trigger" onclick="toggleDD('dd-{{ $school->id }}')">
            <i class="fas fa-ellipsis-v"></i>
          </div>
          <div class="dd-menu" id="dd-{{ $school->id }}">
            <button class="dd-item" onclick="openEditById({{ $school->id }})">
              <i class="fas fa-pen" style="width:14px;color:var(--blue-light);"></i> تعديل البيانات
            </button>
            <div class="dd-sep"></div>
            <form action="{{ route('schools.archive', $school->id) }}" method="POST">
              @csrf
              <button type="submit" class="dd-item">
                <i class="fas fa-archive" style="width:14px;color:var(--amber);"></i>
                {{ $school->is_archived ? 'فك الأرشفة' : 'أرشفة' }}
              </button>
            </form>
            <form action="{{ route('schools.toggle', $school->id) }}" method="POST">
              @csrf @method('PATCH')
              <button type="submit" class="dd-item">
                <i class="fas {{ $school->is_active ? 'fa-pause' : 'fa-play' }}" style="width:14px;color:var(--amber);"></i>
                {{ $school->is_active ? 'إيقاف' : 'تفعيل' }}
              </button>
            </form>
            <div class="dd-sep"></div>
            <button class="dd-item" onclick="openLogs({{ $school->id }}, '{{ addslashes($school->name) }}')">
              <i class="fas fa-history" style="width:14px;color:var(--blue-light);"></i> سجل العمليات
            </button>
            <div class="dd-sep"></div>
            <form action="{{ route('schools.destroy', $school->id) }}" method="POST"
                  onsubmit="return confirm('نقل المدرسة لسلة المحذوفات؟')">
              @csrf @method('DELETE')
              <button type="submit" class="dd-item red">
                <i class="fas fa-trash" style="width:14px;"></i> حذف مؤقت
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="school-card-body">
        {{-- Quota bar --}}
        <div style="margin-bottom:10px;">
          <div style="display:flex;justify-content:space-between;font-size:.7rem;font-weight:700;margin-bottom:5px;">
            <span style="color:var(--text-3);">الكوتة</span>
            <span style="color:{{ $qc }};">{{ $cnt }} / {{ $school->student_limit }}</span>
          </div>
          <div class="qbar-wrap" style="height:7px;">
            <div class="qbar-fill" style="width:{{ min($pct,100) }}%;background:{{ $qc }};"></div>
          </div>
        </div>

        {{-- Subscription end --}}
        <div style="display:flex;justify-content:space-between;font-size:.76rem;">
          <span style="color:var(--text-3);">انتهاء الاشتراك</span>
          <span class="{{ $days <= 0 ? 'expiry-warn' : ($days <= 7 ? 'expiry-warn' : '') }}"
                style="font-weight:700;color:{{ $days <= 7 ? 'var(--red)' : ($days <= 30 ? 'var(--amber)' : 'var(--text-2)') }};">
            {{ $exp->format('Y-m-d') }}
          </span>
        </div>
      </div>

      <div class="school-card-footer">
        <a href="{{ route('students.index', ['school_id' => $school->id]) }}"
           class="btn-pro btn-ghost btn-sm" style="text-decoration:none;flex:1;justify-content:center;">
          <i class="fas fa-user-graduate"></i> الطلاب
        </a>
        <form action="{{ route('schools.toggle', $school->id) }}" method="POST" style="flex:1;">
          @csrf @method('PATCH')
          <button type="submit" class="btn-pro btn-sm w-100 justify-content-center
            {{ $school->is_active ? 'btn-red' : 'btn-green' }}" style="width:100%;">
            <i class="fas {{ $school->is_active ? 'fa-pause' : 'fa-play' }}"></i>
            {{ $school->is_active ? 'إيقاف' : 'تفعيل' }}
          </button>
        </form>
      </div>
    </div>
    @empty
    <div style="grid-column:span 2;">
      <div class="empty-state" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);">
        <i class="fas fa-school"></i>
        <strong>لا توجد مدارس</strong>
        <span>أضف مدرسة جديدة من النموذج الجانبي</span>
      </div>
    </div>
    @endforelse
    </div>

    {{-- Hidden schools data for JS --}}
    <script id="schools-data-json" type="application/json">
    {
    @foreach($schools as $school)
    "{{ $school->id }}": {
        "id": {{ $school->id }},
        "name": @json($school->name),
        "student_limit": {{ $school->student_limit ?? 0 }},
        "plan": @json($school->plan),
        "subscription_end": @json($school->subscription_end),
        "manager": @json($school->manager ? ['name' => $school->manager->name, 'email' => $school->manager->email] : null)
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
    }
    </script>
  </div>

  {{-- Add School Form --}}
  <div class="add-form-card">
    <div class="add-form-head">
      <div class="add-form-title"><i class="fas fa-plus-circle" style="color:var(--blue-light);margin-left:7px;"></i>إضافة مدرسة جديدة</div>
    </div>
    <div style="padding:16px;">
      <form action="{{ route('schools.store') }}" method="POST">
        @csrf
        <div class="form-section">
          <div class="form-section-title"><i class="fas fa-school"></i> بيانات المدرسة</div>
          <div class="form-group">
            <label class="form-label-pro">اسم المدرسة *</label>
            <input type="text" name="name" class="form-input-pro" required placeholder="مدرسة الإتحاد">
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label-pro">كود المدرسة *</label>
              <input type="text" name="school_code" class="form-input-pro" required placeholder="ATH-001">
            </div>
            <div class="form-group">
              <label class="form-label-pro">حد الطلاب</label>
              <input type="number" name="student_limit" class="form-input-pro" value="150" min="1">
            </div>
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label-pro">الخطة</label>
              <select name="plan" class="form-input-pro">
                <option value="monthly">شهري</option>
                <option value="yearly">سنوي</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label-pro">انتهاء الاشتراك *</label>
              <input type="date" name="subscription_end" class="form-input-pro" required>
            </div>
          </div>
        </div>

        <div class="form-section" style="border-bottom:none;margin-bottom:0;padding-bottom:0;">
          <div class="form-section-title"><i class="fas fa-user-shield"></i> بيانات المدير</div>
          <div class="form-group">
            <label class="form-label-pro">اسم المدير *</label>
            <input type="text" name="manager_name" class="form-input-pro" required placeholder="محمد أبوشعبان">
          </div>
          <div class="form-group">
            <label class="form-label-pro">البريد الإلكتروني *</label>
            <input type="email" name="manager_email" class="form-input-pro" required placeholder="admin@school.com">
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label-pro">كلمة المرور *</label>
            <input type="password" name="manager_password" class="form-input-pro" required placeholder="••••••••">
          </div>
        </div>

        <button type="submit" class="btn-pro btn-blue w-100" style="margin-top:18px;justify-content:center;">
          <i class="fas fa-plus-circle"></i> إنشاء المدرسة والمدير
        </button>
      </form>
    </div>
  </div>
</div>

{{-- ─── Modal: Edit School ─── --}}
<div class="modal fade modal-pro" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" dir="rtl">
    <div class="modal-content">
      <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5 class="modal-title"><i class="fas fa-pen" style="color:var(--blue-light);margin-left:7px;"></i>تعديل بيانات المدرسة</h5>
        <div class="btn-close-dark" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
      </div>
      <form id="editForm" action="" method="POST">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="form-section">
            <div class="form-section-title"><i class="fas fa-school"></i> بيانات المدرسة</div>
            <div class="form-grid-2">
              <div class="form-group">
                <label class="form-label-pro">الاسم</label>
                <input type="text" name="name" id="e_name" class="form-input-pro">
              </div>
              <div class="form-group">
                <label class="form-label-pro">حد الطلاب</label>
                <input type="number" name="student_limit" id="e_limit" class="form-input-pro">
              </div>
              <div class="form-group">
                <label class="form-label-pro">الخطة</label>
                <select name="plan" id="e_plan" class="form-input-pro">
                  <option value="monthly">شهري</option>
                  <option value="yearly">سنوي</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label-pro">انتهاء الاشتراك</label>
                <input type="date" name="subscription_end" id="e_sub" class="form-input-pro">
              </div>
            </div>
          </div>
          <div class="form-section" style="border-bottom:none;margin-bottom:0;">
            <div class="form-section-title"><i class="fas fa-user-shield"></i> بيانات المدير (اختياري)</div>
            <div class="form-grid-3">
              <div class="form-group">
                <label class="form-label-pro">الاسم</label>
                <input type="text" name="manager_name" id="e_mname" class="form-input-pro">
              </div>
              <div class="form-group">
                <label class="form-label-pro">الإيميل</label>
                <input type="email" name="manager_email" id="e_memail" class="form-input-pro">
              </div>
              <div class="form-group">
                <label class="form-label-pro">كلمة مرور جديدة</label>
                <input type="password" name="manager_password" class="form-input-pro" placeholder="اتركه فارغاً للإبقاء">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="display:flex;gap:9px;">
          <button type="button" class="btn-pro btn-ghost" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn-pro btn-blue"><i class="fas fa-save"></i> حفظ التعديلات</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ─── Modal: Logs ─── --}}
<div class="modal fade modal-pro" id="logsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" dir="rtl">
    <div class="modal-content">
      <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5 class="modal-title"><i class="fas fa-scroll" style="color:var(--blue-light);margin-left:7px;"></i>سجل عمليات: <span id="logsSchoolName"></span></h5>
        <div class="btn-close-dark" data-bs-dismiss="modal"><i class="fas fa-times"></i></div>
      </div>
      <div class="modal-body" style="padding:0;">
        <div id="logsBody" style="max-height:380px;overflow-y:auto;">
          <div class="empty-state"><i class="fas fa-spinner fa-spin"></i><strong>جارٍ التحميل...</strong></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Dropdown toggle
function toggleDD(id) {
  document.querySelectorAll('.dd-menu').forEach(m => { if(m.id !== id) m.classList.remove('open'); });
  document.getElementById(id)?.classList.toggle('open');
}
document.addEventListener('click', function(e) {
  if (!e.target.closest('.dd-trigger') && !e.target.closest('.dd-menu')) {
    document.querySelectorAll('.dd-menu').forEach(m => m.classList.remove('open'));
  }
});

// Edit modal - load from JSON data store
let schoolsData = {};
try {
  const jsonEl = document.getElementById('schools-data-json');
  if (jsonEl) schoolsData = JSON.parse(jsonEl.textContent);
} catch(e) { console.warn('Could not parse schools data', e); }

function openEditById(id) {
  const s = schoolsData[String(id)];
  if (!s) { console.warn('School not found:', id); return; }
  openEdit(s);
}

function openEdit(s) {
  document.getElementById('editForm').action = '/admin/schools/' + s.id;
  document.getElementById('e_name').value  = s.name  || '';
  document.getElementById('e_limit').value = s.student_limit || '';
  document.getElementById('e_plan').value  = s.plan  || 'monthly';
  document.getElementById('e_sub').value   = s.subscription_end || '';
  // manager fields
  document.getElementById('e_mname').value  = (s.manager && s.manager.name)  ? s.manager.name  : '';
  document.getElementById('e_memail').value = (s.manager && s.manager.email) ? s.manager.email : '';
  new bootstrap.Modal(document.getElementById('editModal')).show();
}

// Logs modal (inline from PHP for simplicity)
const schoolLogs = <?php echo json_encode(
    \App\Models\School::all()->mapWithKeys(function($s) {
        $logs = \App\Models\ActivityLog::where('model_id', $s->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get()
            ->map(function($l) {
                return [
                    'action' => $l->action,
                    'user'   => $l->user->name ?? 'النظام',
                    'desc'   => $l->description,
                    'time'   => $l->created_at->format('Y-m-d H:i'),
                ];
            });
        return [$s->id => $logs];
    })
); ?>;

function openLogs(id, name) {
  document.getElementById('logsSchoolName').textContent = name;
  const logs = schoolLogs[id] || [];
  if (!logs.length) {
    document.getElementById('logsBody').innerHTML = '<div class="empty-state" style="padding:40px;"><i class="fas fa-scroll"></i><strong>لا توجد عمليات مسجلة</strong></div>';
  } else {
    document.getElementById('logsBody').innerHTML = logs.map(l => `
      <div style="display:flex;align-items:center;gap:10px;padding:11px 16px;border-bottom:1px solid var(--border);">
        <div style="width:28px;height:28px;border-radius:7px;background:rgba(59,130,246,.12);color:var(--blue-light);display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0;">
          <i class="fas fa-circle-dot"></i>
        </div>
        <div style="flex:1;">
          <div style="font-size:.8rem;font-weight:700;color:var(--text-1);">${l.action}</div>
          <div style="font-size:.72rem;color:var(--text-3);">${l.desc}</div>
        </div>
        <div style="text-align:left;">
          <div style="font-size:.72rem;color:var(--text-3);">${l.user}</div>
          <div style="font-size:.66rem;color:var(--text-3);font-family:monospace;">${l.time}</div>
        </div>
      </div>
    `).join('');
  }
  new bootstrap.Modal(document.getElementById('logsModal')).show();
}
</script>
@endsection
