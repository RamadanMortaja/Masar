<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    private $examType;

    // استقبال نوع الامتحان (تحريري أو شفوي) من الكنترولر
    public function __construct($examType)
    {
        $this->examType = $examType;
    }

    public function model(array $row)
    {
        // 1. فك شيفرة نوع الرخصة (QuestionLicenseType)
        // 1=ملاكي، 2=تجاري، 3=عمومي، 4=حمولة
        $licenseMap = [
            1 => 'ملاكي',
            2 => 'تجاري',
            4 => 'عمومي',
            3 => 'حمولة',
        ];

        // 2. فك شيفرة نوع السؤال (QuestionQuestionType)
        // 0=إشارات، 1=قوانين سير، 2=عام، 3=ميكانيكا
        $typeMap = [
            1 => 'إشارات',
            2 => 'قوانين سير',
          //  2 => 'عام',
            3 => 'ميكانيكا',
        ];

        return new Question([
          'question_text'    => $row['questiontext'], // من ملف الإكسل إلى قاعدة البيانات
          'answer_1'         => $row['questionanswer1'],
          'answer_2'         => $row['questionanswer2'],
          'answer_3'         => $row['questionanswer3'] ?? null,
          'answer_4'         => $row['questionanswer4'] ?? null,
          'correct_answer'   => $row['questioncorrectanswer'],
          'license_type'     => $licenseMap[$row['questionlicensetype']] ?? 'ملاكي',
          'theory_type'      => $typeMap[$row['questionquestiontype']] ?? 'عام',
          'exam_type'        => $this->examType,
          'sign_id'          => $row['questionquestionsigns'] ?? null,  
            // في حال وجود شرح في الملف (اختياري)
            'question_explanation'  => $row['question_explanation'] ?? null,
        ]);
    }
}