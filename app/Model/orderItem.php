<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class orderItem extends Model
{
    protected $guarded = ['id'];
    public function order() {
        return $this->belongsTo(Orders::class);
    }
    public function item() {
        return $this->belongsTo(Item::class);
    }
}
