@extends('layouts.admin')
@section('title','سجل العمليات | نظام مسار')
@section('page_section','التقارير')
@section('page_title','سجل العمليات')

@section('content')
<div class="page-hd">
  <div>
    <div class="page-hd-title"><i class="fas fa-scroll" style="color:var(--blue-light);margin-left:8px;"></i>سجل العمليات الكامل</div>
    <div class="page-hd-sub">{{ number_format($logs->total()) }} عملية مسجلة</div>
  </div>
  <form action="{{ route('admin.logs.clear') }}" method="POST"
        onsubmit="return confirm('حذف جميع السجلات الأقدم من 90 يوماً؟')">
    @csrf @method('DELETE')
    <button type="submit" class="btn-pro btn-red btn-sm"><i class="fas fa-broom"></i> تنظيف القديمة</button>
  </form>
</div>

<form method="GET" action="{{ route('admin.logs') }}">
<div class="filter-bar">
  <input type="text" name="search" placeholder="ابحث في الوصف..." value="{{ request('search') }}" class="f-input" style="min-width:200px;">
  <select name="action" class="f-select">
    <option value="">كل العمليات</option>
    @foreach(['إضافة','تعديل','حذف','تفعيل','تعطيل','إضافة طالب','تعديل طالب','حذف طالب','تسجيل دخول','تسجيل خروج'] as $a)
      <option value="{{ $a }}" {{ request('action')==$a?'selected':'' }}>{{ $a }}</option>
    @endforeach
  </select>
  <select name="model" class="f-select">
    <option value="">كل الأنواع</option>
    @foreach(['School','Student','Question','User','Trainer','Vehicle'] as $m)
      <option value="{{ $m }}" {{ request('model')==$m?'selected':'' }}>{{ ['School'=>'مدرسة','Student'=>'طالب','Question'=>'سؤال','User'=>'مستخدم','Trainer'=>'مدرب','Vehicle'=>'سيارة'][$m] }}</option>
    @endforeach
  </select>
  <input type="date" name="from" value="{{ request('from') }}" class="f-input">
  <input type="date" name="to"   value="{{ request('to')   }}" class="f-input">
  <button type="submit" class="btn-pro btn-blue btn-sm"><i class="fas fa-filter"></i> فلترة</button>
  <a href="{{ route('admin.logs') }}" class="btn-pro btn-ghost btn-sm" style="text-decoration:none;"><i class="fas fa-undo"></i></a>
</div>
</form>

<div class="c-card">
  <div style="overflow-x:auto;">
  <table class="c-table">
    <thead>
      <tr><th>#</th><th>العملية</th><th>النوع</th><th>المستخدم</th><th>التفاصيل</th><th>الوقت</th></tr>
    </thead>
    <tbody>
    @forelse($logs as $log)
      @php
        $a = $log->action ?? '';
        [$lc,$li] = str_contains($a,'إضافة')||str_contains($a,'creat') ? ['ic-green','fas fa-plus']
          : (str_contains($a,'تعديل')||str_contains($a,'updat') ? ['ic-blue','fas fa-pen']
          : (str_contains($a,'حذف')||str_contains($a,'delet') ? ['ic-red','fas fa-trash']
          : (str_contains($a,'تفعيل') ? ['ic-green','fas fa-play']
          : (str_contains($a,'تعطيل')||str_contains($a,'إيقاف') ? ['ic-amber','fas fa-pause']
          : ['ic-purple','fas fa-circle']))));
        $m = class_basename($log->model_type ?? '');
        $ml = ['School'=>'مدرسة','Student'=>'طالب','Question'=>'سؤال','User'=>'مستخدم'][$m] ?? ($m ?: '—');
        $mc = ['School'=>'badge-blue','Student'=>'badge-green','Question'=>'badge-amber','User'=>'badge-purple'][$m] ?? 'badge-muted';
      @endphp
      <tr>
        <td style="font-size:.66rem;color:var(--text-3);font-family:monospace;">{{ $log->id }}</td>
        <td>
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.7rem;" class="{{ $lc }}">
              <i class="{{ $li }}"></i>
            </div>
            <span style="font-weight:700;color:var(--text-1);">{{ $a }}</span>
          </div>
        </td>
        <td>
          <span class="badge-pro {{ $mc }}">{{ $ml }}</span>
          @if($log->model_id)<span style="font-size:.62rem;color:var(--text-3);margin-right:4px;">#{{ $log->model_id }}</span>@endif
        </td>
        <td style="font-size:.78rem;">{{ $log->user->name ?? 'النظام' }}</td>
        <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.77rem;color:var(--text-3);">{{ $log->description ?? '—' }}</td>
        <td style="white-space:nowrap;">
          <div style="font-size:.75rem;color:var(--text-2);">{{ $log->created_at->format('Y-m-d') }}</div>
          <div style="font-size:.66rem;color:var(--text-3);font-family:monospace;">{{ $log->created_at->format('H:i:s') }}</div>
        </td>
      </tr>
    @empty
      <tr><td colspan="6"><div class="empty-state"><i class="fas fa-scroll"></i><strong>لا توجد عمليات</strong><span>لا توجد نتائج للفلتر الحالي</span></div></td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
  @if($logs->hasPages())
  <div style="padding:13px 18px;border-top:1px solid var(--border);">{{ $logs->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
