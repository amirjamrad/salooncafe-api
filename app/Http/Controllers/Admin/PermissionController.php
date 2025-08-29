<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(10);
        return Response::successResponse(200,'Permissions',[
            'permissions' => PermissionResource::collection($permissions),
            'links' => PermissionResource::collection($permissions)->response()->getData()->links,
        ]);
    }

    public function store(PermissionRequest $request)
    {
        $validatedPermission = $request->validated();
        $validatedPermission['guard_name'] = 'api';
        Permission::create($validatedPermission);

        return Response::successResponse(200, 'Permission created successfully.',  $validatedPermission);
    }
    public function update(PermissionRequest $request,Permission $permission)
    {
        $validatedPermission = $request->validated();
        $validatedPermission['guard_name'] = 'api';
       $permission->update($validatedPermission);

        return Response::successResponse(200, 'Permission updated successfully.',  $validatedPermission);
    }

    public function delete(Permission $permission)
    {
        $permission->delete();
        return Response::successResponse(200, 'Permission deleted successfully.');
    }
}
