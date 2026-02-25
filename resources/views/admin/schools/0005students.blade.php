@extends('layouts.admin')

@section('title', 'إدارة الطلاب - ' . $school->name)

@section('content')
<div class="container-fluid">
    
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark">
                <i class="fas fa-users-cog me-2 text-primary"></i> إدارة طلاب: {{ $school->name }}
            </h3>
            <p class="text-muted">يمكنك إضافة وإدارة الطلاب ونوع الرخصة المطلوبة.</p>
        </div>
        <div class="col-md-4 text-md-end">
            @if($students->count() < $school->student_limit)
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="fas fa-plus-circle me-1"></i> إضافة طالب جديد
                </button>
            @else
                <button class="btn btn-secondary shadow-sm" disabled title="تم الوصول للحد الأقصى">
                    <i class="fas fa-exclamation-triangle me-1"></i> السعة ممتلئة
                </button>
            @endif
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-light-primary text-primary p-3 rounded-circle me-3">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">العدد الحالي</h6>
                        <h4 class="fw-bold mb-0">{{ $students->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-light-warning text-warning p-3 rounded-circle me-3">
                        <i class="fas fa-door-open fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">السعة الإجمالية</h6>
                        <h4 class="fw-bold mb-0">{{ $school->student_limit }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-light-success text-success p-3 rounded-circle me-3">
                        <i class="fas fa-check-double fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">المقاعد المتبقية</h6>
                        <h4 class="fw-bold mb-0">{{ $school->student_limit - $students->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th>اسم الطالب</th>
                            <th>رقم الهوية</th>
                            <th>نوع الرخصة</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            <tr>
                                <td class="px-4">{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark text-end">{{ $student->name }}</td>
                                <td><code class="text-primary">{{ $student->identity_number }}</code></td>
                                <td><span class="badge bg-info text-dark">{{ $student->license_type }}</span></td>
                                <td>{{ $student->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm rounded-pill">
                                            <i class="fas fa-trash-alt"></i> حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">لا يوجد طلاب مسجلين.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">إضافة طالب جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    
                    <div class="mb-3 text-end">
                        <label class="form-label fw-bold">اسم الطالب الرباعي</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3 text-end">
                        <label class="form-label fw-bold">رقم الهوية</label>
                        <input type="number" name="identity_number" class="form-control" required>
                    </div>

                    <div class="mb-3 text-end">
                        <label class="form-label fw-bold">نوع الرخصة</label>
                        <select name="license_type" class="form-select" required>
                            <option value="" disabled selected>اختر النوع...</option>
                            <option value="خصوصي">خصوصي</option>
                            <option value="عمومي">عمومي</option>
                            <option value="شحن خفيف">شحن خفيف</option>
                            <option value="شحن ثقيل">شحن ثقيل</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4 text-white">حفظ البيانات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-light-primary { background: #e7f1ff; }
    .bg-light-warning { background: #fff8e1; }
    .bg-light-success { background: #e8f5e9; }
    .icon-box { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
</style>
@endsection