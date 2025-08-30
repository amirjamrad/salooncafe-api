<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ApiMessages;
use App\Helpers\ActivityLogger;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginUserRequest;
use App\Http\Requests\Admin\RegisterUserRequest;
use App\Http\Requests\Admin\RoleUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Model\ActivityLog;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request){
        $users = User::paginate(10);
        activity()
            ->By(auth()->user())
            ->withProperties([
                'ip' => $request->ip(),
                'agent' => $request->header('User-Agent'),
            ])
            ->event('Read')

            ->log('Listing all users');

        return Response::successResponse(200,"",UserResource::collection($users));
    }
    public function register(RegisterUserRequest $request,User $user){
        $validatedUser = $request->validated();
        $validatedUser['password'] = Hash::make($validatedUser['password']);
        $user = User::select('phone')->where('phone',$validatedUser['phone'])->first();
        if($user)
        {
            return Response::errorResponse(422,'این شماره همراه قبلا ثبت نام شده است');
        }
        $registerUserData = User::create($validatedUser);
        $registerUserData->syncRoles(['customer']);
        $token = $registerUserData->createToken('register-token')->plainTextToken;
        $registerUserData->makeHidden(['password']);
        return Response::successResponse(
            200,
            __('api.user_register_success'),
            [
                'user' => new UserResource($registerUserData),
                'token' => $token,
            ]
        );
    }

    public function login(LoginUserRequest $request){
        $validatedUser = $request->validated();
        $loginUserData = User::where('phone',$validatedUser['phone'])->first();
        if(!$loginUserData || !Hash::check($validatedUser['password'],$loginUserData->password))
        {
            return Response::errorResponse(401,ApiMessages::LOGIN_INVALID_USER_ERROR);
        }
        $token = $loginUserData->createToken('login-token')->plainTextToken;

        if($request->boolean('remember'))
        {
            $rememberToken = Str::random(60);
            $expiresAt = Carbon::now()->addDay();
            $loginUserData->forceFill([
                'remember_token' => \hash('sha256',$rememberToken ),
                'remember_token_expires_at' => $expiresAt
            ])->save();
        }
        $loginUserData->makeHidden(['password']);

        return Response::successResponse(
          200,
          __('api.user_login_success'),
          [
              'user' => new UserResource($loginUserData),
              'token' => $token,
          ]
        );

    }


    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return Response::successResponse(
          200,
          __('api.user_logout_success'),
            []
        );
    }

    public function update(UpdateUserRequest $request, User $user){
        $validatedUser = $request->validated();

        if (isset($validatedUser['password'])) {
            $validatedUser['password'] = Hash::make($validatedUser['password']);
        }
        $user->update($validatedUser);
        $admin = $request->user();
        ActivityLog::create([
            'user_id' => $admin->id,
            'user_fullname' => $admin->full_name,
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->full_name} - Update user info",
            'changes' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => \Carbon\Carbon::now(),
        ]);
        return Response::successResponse(200,__('api.user_update_success'),new UserResource($user));
    }
    public function delete(User $user){
        $user->delete();
        return Response::successResponse(200,__('api.user_delete_success'),new UserResource($user));
    }
    public function forceDelete(User $user){
        $user->forceDelete();
        return Response::successResponse(200,__('api.user_delete_success'),new UserResource($user));
    }
    public function restore($id){

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return Response::successResponse(200,__('api.user_restore_success'),new UserResource($user));
    }

    public function assignRole(RoleUserRequest $request,User $user, Role $role){
        $validatedRole = $request->validated();
        if(! auth()->user()->hasRole('Owner'))
        {
            return Response::errorResponse(422,__('api.add_role_error'));
        }
        $user->syncRoles([$request->role]);

        return Response::successResponse(
            200,
            __('api.user_assignRole_success'),
            ['user' => new UserResource($user)]
        );
    }
}
