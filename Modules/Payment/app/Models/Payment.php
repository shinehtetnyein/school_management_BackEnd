<?php

namespace Modules\Payment\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\Models\User;

class Payment extends Model
{
    protected $fillable = ['amount', 'payment_date', 'status', 'method', 'account_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
