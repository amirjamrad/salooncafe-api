<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Requests\Admin\RoleUserRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Http\Resources\Admin\RoleResource;
use App\Model\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleUserController extends Controller
{
    public function assignRole(RoleUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $authUser = $request->user();

        if(!$authUser->hasRole('Owner')){
            return Response::errorResponse(403,__('api.add_role_error'));
        }

        $role = Role::findOrFail($validated['role_id']);

        $user->syncRoles($role->name);

        return Response::successResponse(200,__('api.sync_role_success'));
    }

    public function index()
    {
        $roles = Role::paginate(10);
        return Response::successResponse(200,'roles',[
            'roles' => RoleResource::collection($roles),
            'links' => RoleResource::collection($roles)->response()->getData()->links,
        ]);
    }

    public function store(RoleRequest $request){
        $validatedRole = $request->validated();
        $validatedRole['guard_name'] = 'api';
        Role::create($validatedRole);
        return Response::successResponse(201,__('api.role_create_success'));
    }
    public function update(RoleRequest $request, Role $role){

        $validatedRole = $request->validated();
        $role->update($validatedRole);
        return Response::successResponse(200,__('api.role_update_success'));
    }
    public function delete(Role $role){
        $role->delete();
        return Response::successResponse(200,__('api.role_delete_success'));
    }
}
