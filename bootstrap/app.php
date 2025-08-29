<?php

use App\Helpers\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //404
        $exceptions->renderable(function (NotFoundHttpException $e,$request){
            $provider = $e->getPrevious();
            if($provider instanceof ModelNotFoundException){
                return Response::errorResponse(404,'Requested resource could not be found.');
            }
            return Response::errorResponse(404,'The requested URL does not exist.');
        });

        //403
        $exceptions->renderable(function (AuthorizationException $e, $request){
            return Response::errorResponse(403,'You are not authorized to perform this action.');
        });

        //401
        $exceptions->renderable(function (AuthenticationException $e, $request){
            return Response::errorResponse(401,'Authentication required. Please login.');
        });

        //422
        $exceptions->renderable(function (ValidationException $e, $request){
            return Response::errorResponse(422,$e->getMessage());
        });

        //500
        $exceptions->renderable(function (\Exception $e, $request) {
            $message = app()->environment('production')
                ? 'An unexpected error occurred. Please try again later.'
                : $e->getMessage();

            return Response::errorResponse(500, $message);
        });

    })->create();
