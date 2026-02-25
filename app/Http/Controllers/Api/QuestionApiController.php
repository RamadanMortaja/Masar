<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Student;
use Illuminate\Http\Request;

class QuestionApiController extends Controller
{
    public function getAllQuestions(Request $request)
    {
        // جلب الطالب من التوكن (بدل السيشين)
        $student = $request->user();

        if (!$student) {
            return response()->json(['error' => 'غير مصرح لك بالدخول'], 401);
        }

        // منطق التراكمية الخاص بك
        $allowedLicenses = ['ملاكي'];
        if ($student->license_type == 'تجاري') {
            $allowedLicenses = ['ملاكي', 'تجاري'];
        } elseif ($student->license_type == 'حمولة') {
            $allowedLicenses = ['ملاكي', 'تجاري', 'حمولة'];
        } elseif ($student->license_type == 'عمومي') {
            $allowedLicenses = ['ملاكي', 'عمومي'];
        }

        // جلب الأسئلة بناءً على منطق الفلترة الخاص بك
        $questions = Question::whereIn('license_type', $allowedLicenses)
            ->where(function($q) use ($student) {
                $q->where('exam_type', $student->theory_type)
                  ->orWhere('exam_type', 'كلاهما');
            })
            ->get()
            ->map(function ($q) {
                // منطق معالجة صور الإشارات (Sign IDs)
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
                    'images'         => $questionImages, // مصفوفة الصور كاملة الروابط
                    'explanation'    => $q->question_explanation,
                    'options'        => array_filter([$q->answer_1, $q->answer_2, $q->answer_3, $q->answer_4]),
                    'correct_option' => (int)$q->correct_answer,
                    'license_type'   => $q->license_type
                ];
            });

        return response()->json([
            'status' => 'success',
            'count' => $questions->count(),
            'student_license' => $student->license_type,
            'questions' => $questions
        ]);
    }
}