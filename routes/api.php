<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentCallbackController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleUserController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum')->name('logout');


Route::prefix('admin')->middleware('auth:sanctum','permission:access-dashboard')->group(function () {


    //user route admin
    Route::prefix('user')->group(function () {
        Route::get('/',[UserController::class,'index'])->middleware('permission:read-users');
        Route::post('/update/{user}',[UserController::class,'update'])->middleware('permission:update-users');
        Route::group(['middleware' => ['permission:delete-users']], function () {
            Route::delete('delete/{user}',[UserController::class,'delete'])->middleware('permission:delete-users');
            Route::delete('force-delete/{user}',[UserController::class,'forceDelete'])->middleware('permission:delete-users');
        });
        Route::get('restore/{id}',[UserController::class,'restore'])->middleware('permission:restore-users');
        Route::post('/{user}/assign-role', [RoleUserController::class, 'assignRole'])->middleware('auth:sanctum','permission:manage-roles')->name('admin.user.assign-role');

    });

    //category route admin
    Route::prefix('category')->group(function () {
        Route::get('/',[CategoryController::class,'index'])->middleware('permission:read-categories');
        Route::get('/show/{category}',[CategoryController::class,'show'])->middleware('permission:read-categories');
        Route::post('/store',[CategoryController::class,'store'])->middleware('permission:store-categories');
        Route::patch('/update/{category}',[CategoryController::class,'update'])->middleware('permission:update-categories');
        Route::group(['middleware' => 'permission:delete-categories'], function () {
            Route::delete('delete/{category}',[CategoryController::class,'delete'])->middleware('permission:delete-categories');
            Route::delete('force-delete/{category}',[CategoryController::class,'forceDelete'])->middleware('permission:delete-categories');
        });
        Route::get('restore/{id}',[CategoryController::class,'restore'])->middleware('permission:restore-categories');
        Route::get('/{category}/items', [CategoryController::class,'getItems'])->middleware('permission:read-items');
    });

    //item route admin
    Route::prefix('item')->group(function () {
        Route::get('/',[ItemController::class,'index'])->middleware('permission:read-items');
        Route::get('/show/{item}',[ItemController::class,'show'])->middleware('permission:read-items');
        Route::post('/store',[ItemController::class,'store'])->middleware('permission:store-items');
        Route::post('/update/{item}',[ItemController::class,'update'])->middleware('permission:update-items');
        Route::group(['middleware' => ['permission:delete-items']], function () {
            Route::delete('/delete/{item}', [ItemController::class,'delete']);
            Route::delete('/force-delete/{item}', [ItemController::class,'forceDelete']);
        });
        Route::get('restore/{id}', [ItemController::class, 'restore'])->middleware('permission:restore-items');
    });

        Route::prefix('permission')->middleware('auth:sanctum','permission:manage-permissions')->group(function () {
            Route::get('/',[PermissionController::class,'index']);
            Route::post('/store',[PermissionController::class,'store']);
            Route::post('/update/{permission}',[PermissionController::class,'update']);
            Route::delete('/delete/{permission}',[PermissionController::class,'delete']);
    }   );
    Route::prefix('role')->middleware('auth:sanctum','permission:manage-roles')->group(function () {
        Route::get('/',[RoleUserController::class,'index']);
        Route::post('/store',[RoleUserController::class,'store']);
        Route::post('/update/{role}',[RoleUserController::class,'update']);
        Route::delete('/delete/{role}',[RoleUserController::class,'delete']);
    }   );
});


Route::prefix('/')->middleware('auth:sanctum')->group(function () {
    Route::get('categories',[CategoryController::class,'indexUserCategory'])->middleware('permission:read-categories');
    Route::get('items',[ItemController::class,'indexUserItem'])->middleware('permission:read-items');
    Route::post('order/item',[OrderController::class,'store'])->middleware('auth:sanctum');
});

Route::post('/payment/pay/{order}', [PaymentController::class, 'pay'])->name('api.payment.pay')->middleware('auth:sanctum');
Route::get('/payment/callback/{payment}', [PaymentCallbackController::class, 'handle'])->name('api.payment.callback');
