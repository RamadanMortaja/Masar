<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'school_id','student_id','receipt_number',
        'amount','payment_method','payment_date',
        'description','received_by',
    ];

    public function school()  { return $this->belongsTo(School::class); }
    public function student() { return $this->belongsTo(Student::class); }

    // توليد رقم سند تلقائي
    public static function generateReceiptNumber(int $schoolId): string
    {
        $last = self::where('school_id', $schoolId)->latest()->first();
        $num  = $last ? (int) substr($last->receipt_number, -4) + 1 : 1;
        return 'RCP-' . $schoolId . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
