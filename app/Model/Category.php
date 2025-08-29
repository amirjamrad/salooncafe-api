<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    protected $guarded = ['id'];
    use SoftDeletes;
    use LogsActivity;
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        // TODO: Implement getActivitylogOptions() method.
        return LogOptions::defaults()->logOnly(['name','deleted_at','created_at','updated_at'])->useLogName('Category');

    }
}
