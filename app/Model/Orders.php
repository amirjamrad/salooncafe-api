<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function payments()
    {
        return $this->hasMany(Payments::class);
    }
}
