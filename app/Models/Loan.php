<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes, HasFactory;

    const PAID = ['not_yet' => 0, 'yes' => 1];
    const FREQUENCY = ['weekly' => 1, 'monthly' => 2, 'quarterly' => 3, 'yearly' => 4];
    const PROCESS_STATUS = ['pending' => 1, 'approved' => 2, 'rejected' => 3];
    const REPAYMENT_COMPLETED = ['not_yet' => 0, 'yes' => 1];

    protected $fillable = [
        'user_id',
        'amount',
        'term',
        'frequency',
        'process_status',
        'repayment_completed',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}
