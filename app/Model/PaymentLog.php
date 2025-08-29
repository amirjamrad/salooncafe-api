<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $guarded = ['id'];
    public function payment() {
        return $this->belongsTo(Payments::class);
    }
}
