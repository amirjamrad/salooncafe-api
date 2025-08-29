<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function entity()
    {
        return $this->morphTo(null, 'entity_type', 'entity_id');
    }


}
