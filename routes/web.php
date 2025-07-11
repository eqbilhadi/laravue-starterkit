<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Navigation\NavManagementController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth'], 'prefix' => 'rbac', 'as' => 'rbac.'], function () {
    Route::resource('navigation-management', NavManagementController::class)
        ->except('show')
        ->names('nav')
        ->parameters([
            'navigation-management' => 'sysMenu'
        ]);
});

Route::get('/stream/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('stream.file');



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
