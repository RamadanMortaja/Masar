<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use App\Models\Question; 
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // تم إضافتها لتعامل مع الجداول مباشرة
use Illuminate\Support\Facades\Schema; // تم إضافتها لفحص الجداول

class StudentController extends Controller
{
    /**
     * لوحة تحكم الطالب (Mobile Dashboard)
     */
    public function dashboard()
    {
        $studentId = session('student_id');
        $student = Student::with(['school'])->find($studentId);

        if (!$student) {
            return redirect()->route('student.login')->with('error', 'يرجى تسجيل الدخول مجدداً');
        }

        $alerts = [];
    if ($student->medical_test_expiry) {
        $days = \Carbon\Carbon::now()->diffInDays($student->medical_test_expiry, false);
        if ($days <= 7 && $days > 0) $alerts[] = "تنبيه: فحصك الطبي ينتهي خلال $days أيام.";
        if ($days <= 0) $alerts[] = "تنبيه: فحصك الطبي منتهي حالياً.";
    }

    return view('student.dashboard', compact('student', 'alerts'));
    }

    /**
     * عرض قائمة الطلاب (للأدمن ومدير المدرسة)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $layout = ($user->role === 'school_admin') ? 'layouts.school' : 'layouts.admin';

        $query = Student::with('school');

        if ($user->role === 'school_admin') {
            $query->where('school_id', $user->school_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('identity_number', 'like', "%$search%");
            });
        }

        if ($request->filled('school_id') && $user->role === 'admin') {
            $query->where('school_id', $request->school_id);
        }

        $students = $query->latest()->paginate(10);
        $schools = School::all();

        // For trainer/vehicle dropdowns per school
        $schoolId = $user->role === 'school_admin' ? $user->school_id : null;
        $trainers = \App\Models\Trainer::when($schoolId, fn($q) => $q->where('school_id', $schoolId))->where('status', 'نشط')->get(['id', 'name', 'school_id']);
        $vehicles = \App\Models\Vehicle::when($schoolId, fn($q) => $q->where('school_id', $schoolId))->where('status', 'متاحة')->get(['id', 'plate_number', 'brand', 'model', 'school_id']);

        return view('admin.students.index', compact('students', 'schools', 'layout', 'trainers', 'vehicles'));
    }

    /**
     * إضافة طالب جديد
     */
    public function store(Request $request)
{
    $user = Auth::user();
    $data = $request->all();

    // 1. تحديد مدرسة الطالب
    if ($user->role === 'school_admin') {
        $data['school_id'] = $user->school_id;
    }

    // 2. التحقق من "الكوتة" (فقط لمدراء المدارس)
    // الأدمن العام يمكنه إضافة طلاب دون قيود
    if ($user->role === 'school_admin') {
        $school = \App\Models\School::find($user->school_id);
        
        if ($school) {
            $currentStudentsCount = \App\Models\Student::where('school_id', $school->id)->count();
            
            if ($currentStudentsCount >= $school->student_limit) {
                return redirect()->back()->with('error', 'عذراً، لقد وصلت المدرسة للحد الأقصى المسموح به من الطلاب (الكوتة: ' . $school->student_limit . '). يرجى التواصل مع الإدارة لزيادة الكوتة.');
            }
        }
    }

    // 3. التحقق من البيانات (Validation)
    $request->validate([
        'name' => 'required',
        'identity_number' => 'required|unique:students',
        'password' => 'required|min:6',
        'school_id' => 'required',
    ]);

    // 4. معالجة البيانات والحفظ
    $data['password'] = Hash::make($request->password);
    $data['total_amount'] = $request->total_amount ?? 0;
    $data['paid_amount'] = $request->paid_amount ?? 0;

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('students', 'public');
    }

    $student = Student::create($data);
    ActivityLog::create(['user_id' => Auth::id(), 'action' => 'إضافة طالب', 'model_type' => 'Student', 'model_id' => $student->id, 'description' => 'تم إضافة طالب جديد: ' . $student->name]);

    return redirect()->back()->with('success', 'تم إضافة ملف الطالب بنجاح.');
}

    /**
     * تحديث بيانات الطالب
     */
    public function update(Request $request, $id) 
    {
        $student = Student::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'school_admin' && $student->school_id !== $user->school_id) {
            abort(403);
        }

        $data = $request->all();

        if ($user->role === 'school_admin') {
            unset($data['name'], $data['identity_number'], $data['birth_date'], $data['phone'], $data['gender'], $data['school_id']);
            if ($student->status === 'ناجح') { $data['status'] = 'ناجح'; }
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('image')) {
            if ($student->image) { Storage::disk('public')->delete($student->image); }
            $data['image'] = $request->file('image')->store('students', 'public');
        }

        $student->update($data);
        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'تعديل طالب', 'model_type' => 'Student', 'model_id' => $student->id, 'description' => 'تم تعديل بيانات الطالب: ' . $student->name]);
        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح.');
    }

    /**
     * حذف طالب
     */
    public function destroy($id)
    {
        if (Auth::user()->role === 'school_admin') {
            return redirect()->back()->with('error', 'عذراً، لا تمتلك صلاحية حذف ملفات الطلاب.');
        }

        $student = Student::findOrFail($id);
        if ($student->image) { Storage::disk('public')->delete($student->image); }
        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'حذف طالب', 'model_type' => 'Student', 'model_id' => $id, 'description' => 'تم حذف الطالب: ' . $student->name]);
        $student->delete();
        return redirect()->back()->with('success', 'تم حذف ملف الطالب بنجاح.');
    }



    public function getSyncQuestions()
    {
        $studentId = session('student_id');
        $student = Student::find($studentId);
    
        if (!$student) return response()->json(['error' => 'Unauthorized'], 401);
    
        // منطق التراكمية (نظيف ومختصر)
        $allowedLicenses = ['ملاكي'];
        if ($student->license_type == 'تجاري') $allowedLicenses = ['ملاكي', 'تجاري'];
        elseif ($student->license_type == 'حمولة') $allowedLicenses = ['ملاكي', 'تجاري', 'حمولة'];
        elseif ($student->license_type == 'عمومي') $allowedLicenses = ['ملاكي', 'عمومي'];
    
        return Question::whereIn('license_type', $allowedLicenses)
            ->where(function($q) use ($student) {
                $q->where('exam_type', $student->theory_type)->orWhere('exam_type', 'كلاهما');
            })
            ->get()
            ->map(function ($q) {
                $questionImages = [];
                if (!empty($q->sign_id)) {
                    $ids = explode(';', $q->sign_id);
                    foreach ($ids as $id) {
                        $fileName = trim(basename($id));
                        foreach (['svg', 'png', 'jpg'] as $ext) {
                            if (file_exists(public_path("images/signs/all/{$fileName}.{$ext}"))) {
                                $questionImages[] = asset("images/signs/all/{$fileName}.{$ext}");
                                break; 
                            }
                        }
                    }
                }
    
                return [
                    'id'             => $q->id,
                    'question_text'  => $q->question_text,
                    'category'       => $q->theory_type, 
                    'images'         => $questionImages,
                    'explanation'    => $q->question_explanation,
                    'options'        => [$q->answer_1, $q->answer_2, $q->answer_3, $q->answer_4],
                    'correct_option' => (int)$q->correct_answer,
                ];
            });
    }



    /**
     * عرض صفحة التدريب للطالب
     */
    public function startPractice()
    {
        $studentId = session('student_id');
        $student = Student::find($studentId);

        if (!$student) {
            return redirect()->route('student.login')->with('error', 'يرجى تسجيل الدخول مجدداً');
        }

        $questions = Question::where(function($query) use ($student) {
            $query->where('QuestionLicenseType', $student->license_type)
                  ->orWhere('QuestionLicenseType', 'كلاهما');
        })
        ->inRandomOrder()
        ->get();

        if ($questions->isEmpty()) {
            $questions = Question::latest()->take(30)->get();
        }

        return view('student.practice', compact('questions', 'student'));
    }

    /**
     * عرض صفحة إشارات المرور
     */
    public function trafficSigns()
    {
        return view('student.traffic_signs'); 
    }

    /**
     * API لجلب الإشارات للمزامنة (Offline Sync)
     */
    public function getSignsForSync()
{
    try {
        if (!Schema::hasTable('traffic_signs')) {
            return response()->json(['error' => 'Table traffic_signs does not exist.'], 404);
        }

        $signs = DB::table('traffic_signs')->get()->map(function($sign) {
            // تحويل المسار المخزن إلى رابط كامل يمكن للمتصفح قراءته
            // إذا كانت الصور في المجلد public/images/signs
            $sign->image = asset($sign->image); 
            return $sign;
        });

        return response()->json($signs);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
    }
}

public function saveExamResult(Request $request)
{
    try {
        $studentId = session('student_id');
        
        // حفظ النتيجة في جدول (يجب أن يكون لديك جدول بهذا الاسم أو عدله لاسم جدولك)
        DB::table('exam_results')->insert([
            'student_id' => $studentId,
            'score'      => $request->score,
            'status'     => $request->status, // ناجح أو راسب
            'exam_date'  => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'تم حفظ النتيجة سحابياً']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function updateActivityPulse(Request $request)
{
    // الحصول على id الطالب من الجلسة (تأكد أن الطالب مسجل دخول)
    $studentId = session('student_id');
    
    if (!$studentId) {
        return response()->json(['error' => 'No session found'], 401);
    }

    $student = \App\Models\Student::find($studentId);
    
    if ($student) {
        // تحديث الحقول مباشرة
        $student->last_activity_at = now();
        $student->unresolved_errors_count = $request->errors_count ?? 0;
        $student->save();

        return response()->json(['status' => 'success', 'updated_at' => $student->last_activity_at]);
    }

    return response()->json(['error' => 'Student not found'], 404);
}

// تسجيل مشاهدة السؤال (التقدم)
public function markQuestionAsViewed(Request $request)
{
    $studentId = session('student_id');
    $qId = $request->question_id;

    if (!$studentId) return response()->json(['error' => 'Unauthorized'], 401);

    // استخدام updateOrInsert لمنع التكرار والحفاظ على أداء SQLite
    DB::table('student_question_progress')->updateOrInsert(
        ['student_id' => $studentId, 'question_id' => $qId],
        ['is_viewed' => 1, 'updated_at' => now()]
    );

    return response()->json(['success' => true]);
}

// تسجيل أو تحديث الخطأ في سؤال معين
public function syncError(Request $request)
{
    $studentId = session('student_id');
    $qId = $request->question_id;
    $count = $request->count;

    if (!$studentId) return response()->json(['error' => 'Unauthorized'], 401);

    if ($count == 0) {
        // إذا كانت القيمة 0، نحذف الخطأ تماماً من قاعدة بيانات النظام
        DB::table('student_errors')
            ->where('student_id', $studentId)
            ->where('question_id', $qId)
            ->delete();
        return response()->json(['status' => 'deleted']);
    }

    // المنطق العادي للتحديث أو الإضافة
    DB::table('student_errors')->updateOrInsert(
        ['student_id' => $studentId, 'question_id' => $qId],
        ['error_count' => $count, 'updated_at' => now()]
    );

    return response()->json(['status' => 'synced']);
}

public function syncExamDataBatch(Request $request)
{
    $studentId = session('student_id');
    if (!$studentId) return response()->json(['error' => 'Unauthenticated'], 401);

    try {
        DB::beginTransaction();

        // 1. تسجيل التقدم (student_question_progress)
        if ($request->has('viewed_ids')) {
            foreach ($request->viewed_ids as $qId) {
                DB::table('student_question_progress')->updateOrInsert(
                    ['student_id' => $studentId, 'question_id' => $qId],
                    ['is_viewed' => 1, 'updated_at' => now()]
                );
            }
        }

        // 2. تسجيل الأخطاء (متوافق مع SQLite)
        if ($request->has('wrong_ids')) {
            foreach ($request->wrong_ids as $qId) {
                // نبحث أولاً إذا كان السجل موجوداً
                $errorRecord = DB::table('student_errors')
                    ->where('student_id', $studentId)
                    ->where('question_id', $qId)
                    ->first();

                if ($errorRecord) {
                    // إذا وجدناه، نزيد العداد يدوياً (لتجنب مشاكل SQLSTATE في SQLite)
                    DB::table('student_errors')
                        ->where('id', $errorRecord->id)
                        ->update([
                            'error_count' => $errorRecord->error_count + 1,
                            'updated_at' => now()
                        ]);
                } else {
                    // إذا لم نجده، ننشئ سجلاً جديداً
                    DB::table('student_errors')->insert([
                        'student_id' => $studentId,
                        'question_id' => $qId,
                        'error_count' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        // 3. حفظ النتيجة (exam_results)
        if ($request->has('result')) {
            $res = $request->result;
            
            // منع التكرار في آخر 30 ثانية
            $exists = DB::table('exam_results')
                ->where('student_id', $studentId)
                ->where('score', $res['score'])
                ->where('created_at', '>', now()->subSeconds(30))
                ->exists();

            if (!$exists) {
                      DB::table('exam_results')->insert([
                'student_id' => $studentId,
                'score'      => $res['score'],
                'status'     => $res['status'],
                'exam_date'  => $res['date'] ?? now(), // استخدم التاريخ القادم من المتصفح
                'created_at' => $res['date'] ?? now(), 
                'updated_at' => now()
                ]);
            }
        }

        DB::commit();
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("SQLite Sync Error: " . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getStudentSyncData()
{
    $studentId = session('student_id');

    // جلب الأخطاء: مصفوفة تحتوي على (معرف السؤال والعدد)
    $errors = DB::table('student_errors')
                ->where('student_id', $studentId)
                ->select('question_id as q_id', 'error_count as count')
                ->get();

    // جلب التقدم: قائمة بجميع الـ IDs للأسئلة التي شاهدها
    $viewedIds = DB::table('student_question_progress')
                   ->where('student_id', $studentId)
                   ->pluck('question_id');


    $examResults = DB::table('exam_results')
                    ->where('student_id', $studentId)
                    ->select('score', 'status', 'exam_date as date') 
                    ->get();

    return response()->json([
        'errors' => $errors,
        'viewed_ids' => $viewedIds,
        'exam_results' => $examResults 
    ]);
}

public function profile()
{
    $studentId = session('student_id');

    // 1. جلب بيانات الطالب مع اسم المدرسة
    $student = DB::table('students')
        ->leftJoin('schools', 'students.school_id', '=', 'schools.id')
        ->select('students.*', 'schools.name as school_name')
        ->where('students.id', $studentId)
        ->first();

    // 2. إحصائيات الامتحانات العامة
    $examStats = DB::table('exam_results')
        ->where('student_id', $studentId)
        ->select(
            DB::raw('count(*) as total_exams'),
            DB::raw('max(score) as best_score'),
            DB::raw('sum(case when status = "ناجح" then 1 else 0 end) as success_count')
        )->first();

    // 3. نسبة الإنجاز الكلية (بدون تقسيم فئات حالياً لتجنب الخطأ)
    $totalQuestions = DB::table('questions')->count();
    $viewedCount = DB::table('student_question_progress')
        ->where('student_id', $studentId)
        ->where('is_viewed', 1)
        ->count();
    
    $progressPercent = $totalQuestions > 0 ? round(($viewedCount / $totalQuestions) * 100) : 0;

    // مصفوفة فارغة لتجنب خطأ Undefined variable في Blade
    $categories = []; 
    // جلب الحصص
    $drivingSessions = \App\Models\DrivingSession::with('trainer')
        ->where('student_id', $studentId)
        ->orderBy('session_date', 'desc')
        ->take(10)
        ->get();

    return view('student.profile', compact('student', 'examStats', 'progressPercent', 'categories', 'drivingSessions'));
}

    public function resetStudy()
    {
        $studentId = session('student_id');
        
        // حذف التقدم والأخطاء سحابياً
        DB::table('student_question_progress')->where('student_id', $studentId)->delete();
        DB::table('student_errors')->where('student_id', $studentId)->delete();
        
        return back()->with('success_reset', 'تم تصفير التقدم بنجاح');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('student.login');
    }


public function financial()
{
    $studentId = session('student_id');

    // جلب البيانات المالية مباشرة من جدول الطلاب بناءً على الكود الذي أرسلته
    $student = DB::table('students')
        ->where('id', $studentId)
        ->select('total_amount', 'paid_amount', 'name')
        ->first();

    if (!$student) {
        return redirect()->route('student.login');
    }

    $totalFees = $student->total_amount ?? 0;
    $paidAmount = $student->paid_amount ?? 0;
    $remainingAmount = $totalFees - $paidAmount;

    // بما أن الكود الحالي لا يحتوي على جدول دفعات منفصل، سنرسل مصفوفة فارغة 
    // أو يمكننا لاحقاً جلبها إذا أضفت جدولاً للسندات.
    $payments = []; 

    return view('student.financial', compact('totalFees', 'paidAmount', 'remainingAmount', 'payments'));
}
    }