<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $guarded = ['id'];
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
    public function paymentsLog() {
        return $this->hasMany(PaymentLog::class);
    }
}
