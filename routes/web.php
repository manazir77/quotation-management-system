<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\TermsConditionController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Items
    Route::resource('items', ItemController::class)->except(['show']);
    Route::get('/items/search', [ItemController::class, 'search'])->name('items.search');

    // Terms & Conditions
    Route::resource('terms', TermsConditionController::class)->except(['show']);

    // Quotations
    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::get('/quotations/cancel', [QuotationController::class, 'cancel'])->name('quotations.cancel');
    Route::post('/quotations/step1', [QuotationController::class, 'storeStep1'])->name('quotations.storeStep1');
    Route::get('/quotations/step2', [QuotationController::class, 'step2'])->name('quotations.step2');
    Route::get('/quotations/step3', [QuotationController::class, 'step3'])->name('quotations.step3');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/quotations/{quotation}/print', [QuotationController::class, 'print'])->name('quotations.print');
    Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

    // Quotation AJAX routes
    Route::post('/quotations/add-item', [QuotationController::class, 'addItem'])->name('quotations.addItem');
    Route::post('/quotations/update-item', [QuotationController::class, 'updateItem'])->name('quotations.updateItem');
    Route::post('/quotations/remove-item', [QuotationController::class, 'removeItem'])->name('quotations.removeItem');
});
