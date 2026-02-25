<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuestionsExport;


class QuestionController extends Controller
{
    /**
     * عرض قائمة الأسئلة مع البحث والتصفية المتقدمة
     */
    public function index(Request $request) 
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = Question::query();

        // 1. البحث النصي
        if ($request->filled('search')) {
           $query->where('question_text', 'like', '%' . $request->search . '%'); // بدلاً من questiontext
        }

        // 2. تصفية حسب نوع الرخصة (ملاكي، تجاري، عمومي، حمولة)
        if ($request->filled('license_type')) {
            $query->where('license_type', $request->license_type);
        }

        // 3. تصفية حسب نوع السؤال (إشارات، قوانين، ميكانيكا، عام)
        if ($request->filled('theory_type')) {
            $query->where('theory_type', $request->theory_type);
        }

        // 4. تصفية حسب نوع الامتحان (تحريري، شفوي)
        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        $questions = $query->latest()->paginate(20);

        // إحصائية لأصعب سؤال (افتراضية - يمكن ربطها لاحقاً بسجل إجابات الطلاب)
        $hardestQuestion = Question::whereNotNull('question_explanation')->first()->questiontext ?? 'لا يوجد بيانات'; 

        return view('admin.questions.index', compact('questions', 'hardestQuestion'));
    }

    /**
     * حفظ سؤال جديد يدوياً من الواجهة
     */
    public function store(Request $request) 
{
    // 1. التحقق من البيانات (تأكد أن الأسماء تطابق الـ name في المودال)
    $request->validate([
        'question_text' => 'required',
        'answer_1' => 'required',
        'answer_2' => 'required',
        'correct_answer' => 'required|integer|between:1,4',
    ]);

    $imagePath = null;
    if ($request->hasFile('question_picture')) {
        $imagePath = $request->file('question_picture')->store('questions', 'public');
    }

    // 2. الحفظ في القاعدة (استخدام الأسماء الصحيحة من الـ Request)
    Question::create([
        'question_text'        => $request->question_text,
        'answer_1'             => $request->answer_1,
        'answer_2'             => $request->answer_2,
        'answer_3'             => $request->answer_3,
        'answer_4'             => $request->answer_4,
        'correct_answer'       => $request->correct_answer,
        'license_type'         => $request->license_type,
        'theory_type'          => $request->theory_type,
        'exam_type'            => $request->exam_type,
        'sign_id'              => $request->sign_id,
        'question_explanation' => $request->question_explanation,
        'question_picture'     => $imagePath,
    ]);

    return redirect()->back()->with('success', 'تم إضافة السؤال بنجاح.');
}

    /**
     * تحديث سؤال موجود
     */
    public function update(Request $request, $id)
{
    $question = Question::findOrFail($id);
    
    // تأكد أن الأسماء هنا تطابق الـ ID والـ Name في فورم التعديل (Edit Modal)
    $data = [
        'question_text'        => $request->question_text,
        'answer_1'             => $request->answer_1,
        'answer_2'             => $request->answer_2,
        'answer_3'             => $request->answer_3,
        'answer_4'             => $request->answer_4,
        'correct_answer'       => $request->correct_answer,
        'license_type'         => $request->license_type,
        'theory_type'          => $request->theory_type,
        'exam_type'            => $request->exam_type,
        'sign_id'              => $request->sign_id,
        'question_explanation' => $request->question_explanation,
    ];

    if ($request->hasFile('question_picture')) {
        // حذف القديمة
        if ($question->question_picture) {
            Storage::disk('public')->delete($question->question_picture);
        }
        $data['question_picture'] = $request->file('question_picture')->store('questions', 'public');
    }

    $question->update($data);

    return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح.');
}

    /**
     * حذف سؤال وصورته
     */
    public function destroy($id) 
    {
        $question = Question::findOrFail($id);
        if ($question->questionquestionpicture) {
            Storage::disk('public')->delete($question->questionquestionpicture);
        }
        $question->delete();
        return redirect()->back()->with('success', 'تم حذف السؤال.');
    }

    /**
     * استيراد ملفات CSV/Excel مع فك الشيفرة
     */
    public function import(Request $request) 
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
            'exam_type'  => 'required' // 'تحريري' أو 'شفوي' من المودال
        ]);

        // نمرر نوع الامتحان (تحريري/شفوي) لملف الـ Import ليتم وسم جميع الأسئلة المرفوعة به
        Excel::import(new QuestionsImport($request->exam_type), $request->file('excel_file'));
        
        return redirect()->back()->with('success', 'تم استيراد كافة الأسئلة بنجاح!');
    }

    public function export() 
     {
    return Excel::download(new QuestionsExport, 'bank-questions-' . date('Y-m-d') . '.xlsx');
    }
}
