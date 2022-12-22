<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [BlogsController::class, 'index'])->name('index');

Route::prefix('admin')->group(function () {
    Route::post('/update-credentials', [AuthController::class, 'update_credentials']);
    Route::get('/', [AuthController::class, 'show_login']);
    Route::post('/', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogsController::class, 'admin_index'])->name('admin.index');
        Route::get('/create', [BlogsController::class, 'show_create_blog'])->name('blogs.show-create');
        Route::post('/', [BlogsController::class, 'create_blog'])->name('blogs.create');
    });
});
