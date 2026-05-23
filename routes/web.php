<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\InviteCodeController;

/*
|--------------------------------------------------------------------------
| Code Generate Pages
|--------------------------------------------------------------------------
*/

Route::get('/invite/generate', [InviteCodeController::class, 'generate'])->name('invite.generate');

Route::post('/invite/verify', [InviteCodeController::class, 'verify']);

Route::post('/invite/use', [InviteCodeController::class, 'useCode']);

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::view('/privacy', 'static.privacy')->name('privacy');
Route::view('/about', 'static.about')->name('about');
Route::view('/contact', 'static.contact')->name('contact');

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (
        auth()->check() &&
        in_array(auth()->user()->role, ['admin', 'coordinator', 'staff'])
    ) {
        return redirect()->route('home');
    }

    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Users
        Route::get('users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');

        Route::put('users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');

        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        Route::get('users/trashed', [AdminUserController::class, 'trashed'])
            ->name('users.trashed');

        Route::post('users/{id}/restore', [AdminUserController::class, 'restore'])
            ->name('users.restore');

        Route::delete('users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])
            ->name('users.forceDelete');

        // Reports
        Route::get('reports/daily', [ReportController::class, 'daily'])
            ->name('reports.daily');

        Route::get('reports/monthly', [ReportController::class, 'monthly'])
            ->name('reports.monthly');

        Route::get('reports/yearly', [ReportController::class, 'yearly'])
            ->name('reports.yearly');
    });

/*
|--------------------------------------------------------------------------
| Authenticated Employee Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,coordinator,staff'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Home
        |--------------------------------------------------------------------------
        */

        // Route::get('/home', function () {
        //     return view('home');
        // })->name('home');
        Route::get('/home', [DashboardController::class, 'index'])->name('home');
        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        /*
        |--------------------------------------------------------------------------
        | Sales
        |--------------------------------------------------------------------------
        */

        Route::resource('sales', SaleController::class);
        
        Route::get('/online-orders', [SaleController::class, 'onlineOrders'])
            ->name('online-orders.index');

        Route::get('/online-orders/{sale}', [SaleController::class, 'onlineShow'])
            ->name('online-orders.show');

        Route::put('/online-orders/{sale}', [SaleController::class, 'updateOnlineOrder'])
            ->name('online-orders.update');
            
        Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])
            ->name('sales.invoice');

        /*
        |--------------------------------------------------------------------------
        | Parts - View Access
        |--------------------------------------------------------------------------
        */

        // Index
        Route::get('/parts', [PartController::class, 'index'])
            ->name('parts.index');

        /*
        |--------------------------------------------------------------------------
        | Admin + Coordinator Routes
        |--------------------------------------------------------------------------
        */

        Route::middleware(['role:admin,coordinator'])
            ->group(function () {

                // Create
                Route::get('/parts/create', [PartController::class, 'create'])
                    ->name('parts.create');

                Route::post('/parts', [PartController::class, 'store'])
                    ->name('parts.store');

                // Trashed
                Route::get('/parts/trashed', [PartController::class, 'trashed'])
                    ->name('parts.trashed');

                // Restore
                Route::post('/parts/{id}/restore', [PartController::class, 'restore'])
                    ->name('parts.restore');

                // Force Delete
                Route::delete('/parts/{id}/force-delete', [PartController::class, 'forceDelete'])
                    ->name('parts.forceDelete');

                // Edit
                Route::get('/parts/{part}/edit', [PartController::class, 'edit'])
                    ->name('parts.edit');

                Route::put('/parts/{part}', [PartController::class, 'update'])
                    ->name('parts.update');

                // Delete
                Route::delete('/parts/{part}', [PartController::class, 'destroy'])
                    ->name('parts.destroy');

                // Image Delete
                Route::delete('/parts/images/{image}', [PartController::class, 'destroyImage'])
                    ->name('parts.images.destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Part Details
        |--------------------------------------------------------------------------
        */

        Route::get('/parts/{part}', [PartController::class, 'show'])
            ->name('parts.show');
            });

require __DIR__ . '/auth.php';