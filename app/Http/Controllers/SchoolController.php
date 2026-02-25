<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function index(Request $request) 
{
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    // استخدمنا withCount لإنشاء متغير students_count تلقائياً لكل مدرسة
    $query = School::withCount('students')->with('manager');

    // نظام البحث والفلترة
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('school_code', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('is_active', $request->status == 'active');
    }

    if ($request->filled('archived')) {
        $query->where('is_archived', $request->archived == '1');
    } else {
        // افتراضياً: لا تعرض المدارس المؤرشفة إلا إذا طلب المستخدم ذلك
        $query->where('is_archived', false);
    }

    $schools = $query->latest()->get(); 
    return view('admin.schools.index', compact('schools'));
}

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_code' => 'required|unique:schools,school_code',
            'plan' => 'required|in:monthly,yearly',
            'subscription_end' => 'required|date',
            'manager_name' => 'required|string|max:255',
            'manager_email' => 'required|email|unique:users,email',
            'manager_password' => 'required|min:6',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $school = School::create([
                'name' => $validated['name'],
                'school_code' => $validated['school_code'],
                'plan' => $validated['plan'],
                'subscription_end' => $validated['subscription_end'],
                'student_limit' => $request->student_limit ?? 150,
            ]);

            User::create([
                'name' => $validated['manager_name'],
                'email' => $validated['manager_email'],
                'password' => Hash::make($validated['manager_password']),
                'role' => 'school_admin',
                'school_id' => $school->id,
            ]);

            $this->logActivity('إضافة', $school->id, "قام بإنشاء مدرسة جديدة: {$school->name}");
        });

        return redirect()->back()->with('success', 'تمت إضافة المدرسة والمدير وتسجيل العملية');
    }

    public function update(Request $request, School $school)
    {
        $oldName = $school->name;
        $school->update($request->only(['name', 'plan', 'subscription_end', 'student_limit']));
        
        $this->logActivity('تعديل', $school->id, "عدل بيانات مدرسة: {$oldName}");

        return redirect()->back()->with('success', 'تم التحديث وتسجيل العملية');
    }

    public function toggleStatus(School $school)
    {
        $school->is_active = !$school->is_active;
        $school->save();
        
        $status = $school->is_active ? 'تفعيل' : 'تعطيل';
        $this->logActivity($status, $school->id, "قام بـ {$status} حساب المدرسة");

        return redirect()->back()->with('success', "تم {$status} المدرسة");
    }

    public function toggleArchive($id)
{
    $school = School::findOrFail($id);
    // تبديل حالة الأرشفة (إذا كان مؤرشفاً فكه، والعكس)
    $school->is_archived = !$school->is_archived;
    $school->save();

    $msg = $school->is_archived ? 'تم أرشفة المدرسة' : 'تم إلغاء الأرشفة';
    return redirect()->back()->with('success', $msg);
}


    public function destroy(School $school)
    {
        $schoolName = $school->name;
        $school->delete(); // حذف منطقي Soft Delete

        $this->logActivity('حذف مؤقت', $school->id, "نقل مدرسة {$schoolName} إلى سلة المحذوفات");

        return redirect()->back()->with('success', 'تم نقل المدرسة لسلة المحذوفات');
    }

    // دالة مساعدة لتسجيل النشاطات
    private function logActivity($action, $modelId, $desc) {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => 'School',
            'model_id' => $modelId,
            'description' => $desc
        ]);
    }


public function trashed()
{
    $schools = School::onlyTrashed()->get();
    return view('admin.schools.trashed', compact('schools'));
}

public function restore($id)
{
    $school = School::withTrashed()->findOrFail($id);
    $school->restore();

    return redirect()->route('schools.index')->with('success', 'تم استعادة المدرسة بنجاح');
}

public function forceDelete($id)
{
    $school = School::withTrashed()->findOrFail($id);
    $school->forceDelete();
    return redirect()->back()->with('success', 'تم حذف المدرسة نهائياً');
}

public function show() 
{
    // هذه الدالة موجودة فقط لمنع خطأ 500 في حال نادى النظام مسار show بالخطأ
    return redirect()->route('schools.index');
}
}

