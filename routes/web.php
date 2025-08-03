<?php

use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/account', [AccountController::class, 'account'])->name('account');
    Route::get('/account-list', [AccountController::class, 'accountList'])->name('account.list');
    Route::post('/account/store', [AccountController::class, 'store'])->name('account.store');
    Route::delete('/account-delete/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::post('/account-by-id', [AccountController::class, 'accountById'])->name('account.by.id');
    Route::put('/account-update/{id}', [AccountController::class, 'update'])->name('account.update');
});





Route::get('/admin/login', [AuthController::class, 'admin_login'])->name('admin.login');
Route::get('/admin/register', [AuthController::class, 'admin_register'])->name('admin.register');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
