<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $fillable = ['school_id', 'amount', 'paid_at', 'note', 'recorded_by'];

    protected $casts = ['paid_at' => 'date'];

    public function school()    { return $this->belongsTo(School::class); }
    public function recorder()  { return $this->belongsTo(User::class, 'recorded_by'); }
}
