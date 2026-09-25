<?php

use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\EnsureTenantAcceptedTerms;
use App\Http\Middleware\TenantBookingReminder;
use App\Http\Middleware\PagePinMiddleware;
use App\Http\Middleware\TenantPagePinMiddleware;
use App\Http\Middleware\TenantMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->booted(function () {
        Illuminate\Support\Facades\Route::middleware('web')
            ->group(function () {
                
            });
    })

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'tenant' => TenantMiddleware::class,
            'superadmin' => SuperAdminMiddleware::class,
            'page.pin' => PagePinMiddleware::class,
            'tenant.page.pin' => TenantPagePinMiddleware::class,
            'tenant.terms' => EnsureTenantAcceptedTerms::class,

            // Spatie Permission
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'tenant.booking.reminder' => TenantBookingReminder::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
