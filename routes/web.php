<?php

use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // account 
    Route::get('/account', [AccountController::class, 'account'])->name('account');
    Route::get('/account-list', [AccountController::class, 'accountList'])->name('account.list');
    Route::post('/account/store', [AccountController::class, 'store'])->name('account.store');
    Route::post('/account-by-id', [AccountController::class, 'accountById'])->name('account.by.id');
    Route::delete('/account-delete/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::put('/account-update/{id}', [AccountController::class, 'update'])->name('account.update');

    // category 
    Route::get('/category', [CategoryController::class, 'category'])->name('category');
    Route::post('/category-store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category-list', [CategoryController::class, 'categoryList'])->name('category.list');
    Route::post('/category-by-id', [CategoryController::class, 'categoryById'])->name('category.by.id');
    Route::delete('/category-delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::post('/category-update', [CategoryController::class, 'update'])->name('category.update');
});





Route::get('/admin/login', [AuthController::class, 'admin_login'])->name('admin.login');
Route::get('/admin/register', [AuthController::class, 'admin_register'])->name('admin.register');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
