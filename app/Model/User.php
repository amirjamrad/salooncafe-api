<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, HasRoles,LogsActivity;
    protected $guarded = ['id'];
    protected string $guard_name = 'api';


    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        // TODO: Implement getActivitylogOptions() method.
        return LogOptions::defaults()->logOnly(['full_name','phone','password','role_id','is_active','remember_token','deleted_at','created_at','updated_at'])->useLogName('User');
    }
}
