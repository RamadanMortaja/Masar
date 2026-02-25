@extends('layouts.admin')
@section('title','سلة المحذوفات | نظام مسار')
@section('page_section','المدارس')
@section('page_title','سلة المحذوفات')

@section('content')
<div class="page-hd">
  <div>
    <div class="page-hd-title"><i class="fas fa-trash-alt" style="color:var(--red);margin-left:8px;"></i>سلة المحذوفات</div>
    <div class="page-hd-sub">المدارس المحذوفة مؤقتاً — يمكن استعادتها أو حذفها نهائياً</div>
  </div>
  <a href="{{ route('schools.index') }}" class="btn-pro btn-ghost" style="text-decoration:none;">
    <i class="fas fa-arrow-right"></i> العودة للمدارس
  </a>
</div>

<div class="c-card">
  <div style="overflow-x:auto;">
  <table class="c-table">
    <thead>
      <tr><th>المدرسة</th><th>الكود</th><th>تاريخ الحذف</th><th style="text-align:center;">إجراءات</th></tr>
    </thead>
    <tbody>
    @forelse($schools as $school)
    <tr>
      <td>
        <div style="display:flex;align-items:center;gap:9px;">
          <div style="width:32px;height:32px;border-radius:9px;background:rgba(239,68,68,.1);color:#fca5a5;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:900;flex-shrink:0;">
            {{ mb_substr($school->name,0,1) }}
          </div>
          <span style="font-weight:700;color:var(--text-1);">{{ $school->name }}</span>
        </div>
      </td>
      <td style="font-family:monospace;font-size:.75rem;color:var(--text-3);">{{ $school->school_code }}</td>
      <td>
        <div style="font-size:.79rem;color:var(--text-2);">{{ $school->deleted_at->format('Y-m-d') }}</div>
        <div style="font-size:.68rem;color:var(--text-3);">{{ $school->deleted_at->diffForHumans() }}</div>
      </td>
      <td>
        <div style="display:flex;gap:7px;justify-content:center;">
          <form action="{{ route('schools.restore', $school->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-pro btn-green btn-sm"><i class="fas fa-undo"></i> استعادة</button>
          </form>
          <form action="{{ route('schools.forceDelete', $school->id) }}" method="POST"
                onsubmit="return confirm('حذف نهائي لـ {{ addslashes($school->name) }} وكل بياناتها؟ هذا الإجراء لا يمكن التراجع عنه!')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-pro btn-red btn-sm"><i class="fas fa-eraser"></i> حذف نهائي</button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="4"><div class="empty-state"><i class="fas fa-recycle"></i><strong>سلة المحذوفات فارغة</strong><span>لا توجد مدارس محذوفة حالياً</span></div></td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
</div>
@endsection
