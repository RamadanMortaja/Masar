<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // تصدير البيانات المهمة فقط
        return Question::select('id', 'question_text', 'answer_1', 'answer_2', 'answer_3', 'answer_4', 'correct_answer', 'license_type', 'theory_type', 'exam_type')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'نص السؤال',
            'إجابة 1',
            'إجابة 2',
            'إجابة 3',
            'إجابة 4',
            'رقم الإجابة الصحيحة',
            'نوع الرخصة',
            'التصنيف',
            'نوع الامتحان',
        ];
    }
}