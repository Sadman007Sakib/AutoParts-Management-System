<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartController;
use App\Http\Controllers\SaleController;

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController;


Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // optional restore route if you have SoftDeletes
    Route::post('users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');

    //used for daily sales report
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/yearly', [ReportController::class, 'yearly'])->name('reports.yearly');
});

Route::view('/privacy', 'static.privacy')->name('privacy');
Route::view('/about', 'static.about')->name('about');
Route::view('/contact', 'static.contact')->name('contact');


// landing redirect (optional)
Route::get('/', function () {
    if (auth()->check() && in_array(auth()->user()->role, ['admin','coordinator'])) {
        return redirect()->route('parts.index');
    }
    return view('welcome');
    });

// Route::get('/dashboard', function () {
//     return view('dashboard');
//     })->middleware(['auth', 'verified'])->name('dashboard');

// authenticated group
Route::middleware(['auth'])->group(function () {

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); });

    // Full CRUD for sales (index, create, store, show, edit, update, destroy)
    Route::resource('sales', SaleController::class);

    // listing (all authenticated users)
    Route::get('/parts', [PartController::class, 'index'])->name('parts.index');

        // staff landing page
    Route::get('/home', function () {
        return view('home');
    })->name('home')->middleware('auth');




// admin/coordinator can create, edit, update, delete
Route::middleware(['role:admin,coordinator'])->group(function () {
    Route::get('/parts/create', [PartController::class, 'create'])->name('parts.create');
    Route::post('/parts', [PartController::class, 'store'])->name('parts.store');
    Route::get('/parts/{part}/edit', [PartController::class, 'edit'])->name('parts.edit');
    Route::put('/parts/{part}', [PartController::class, 'update'])->name('parts.update');
    Route::delete('/parts/{part}', [PartController::class, 'destroy'])->name('parts.destroy');
    // show trashed items
    Route::get('/parts/trashed', [\App\Http\Controllers\PartController::class, 'trashed'])
        ->name('parts.trashed')
        ->middleware(['auth','role:admin,coordinator']);
    // restore and force-delete
    Route::post('/parts/{id}/restore', [\App\Http\Controllers\PartController::class, 'restore'])
        ->name('parts.restore')
        ->middleware(['auth','role:admin,coordinator']);
    Route::delete('/parts/{id}/force-delete', [\App\Http\Controllers\PartController::class, 'forceDelete'])
        ->name('parts.forceDelete')
        ->middleware(['auth','role:admin,coordinator']);
    // image force delete    
    Route::delete('/parts/images/{image}', [PartController::class, 'destroyImage'])
    ->name('parts.images.destroy')
    ->middleware(['auth','role:admin,coordinator']);

    // show profile edit (uses resources/views/profile/edit.blade.php)
    Route::get('/profile', function () {
    return view('profile.edit');
    })->name('profile.edit')->middleware('auth');

});

    // optional show (you can restrict or leave open)
    Route::get('/parts/{part}', [PartController::class, 'show'])->name('parts.show');
});

require __DIR__.'/auth.php';
