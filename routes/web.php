<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpensiveController;
use App\Http\Controllers\DailySalesSummaryController;

// Show login page (GET request)
Route::get('/login', [AdminController::class, 'login'])->name('login'); // Default login route
Route::get('/', [AdminController::class, 'login'])->name('admin.login'); // Admin login page

// Handle login (POST request)
Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.login.post');

// Logout route
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protect all admin-related routes with 'auth:admin' middleware
Route::middleware(['auth:admin'])->group(function () {
    // Admin Dashboard
    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Stock Management Routes
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/search', [StockController::class, 'search'])->name('stock.search');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock/store', [StockController::class, 'store'])->name('stock.store');
    Route::get('/stock/edit/{id}', [StockController::class, 'edit'])->name('stock.edit');
    Route::post('/stock/update/{id}', [StockController::class, 'update'])->name('stock.update');
    Route::delete('/stock/delete/{id}', [StockController::class, 'destroy'])->name('stock.delete');

    //bill managr 
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/searchItem', [BillingController::class, 'searchItem'])->name('billing.searchItem');
    Route::post('/billing/generateInvoice', [BillingController::class, 'generateInvoice'])->name('billing.generateInvoice');

    Route::get('/admin/invoices', [SaleController::class, 'index'])->name('invoices.index');
    Route::get('/admin/invoices/{id}', [SaleController::class, 'showBill'])->name('invoices.bill');
    Route::delete('/admin/invoices/{id}', [SaleController::class, 'destroy'])->name('admin.invoices.destroy');

    Route::get('/admin/report', [ReportController::class, 'index'])->name('report.index');

    Route::get('/expenses', [ExpensiveController::class, 'index']);
    Route::post('/expenses', [ExpensiveController::class, 'store']);
    
    Route::get('/admin/profits', [DailySalesSummaryController::class, 'index'])->name('admin.profits.index');

    Route::get('/daily-sales-summary/export', [DailySalesSummaryController::class, 'export'])->name('daily-sales-summary.export');

    // Route::get('/sales/{id}/download', [SaleController::class, 'downloadInvoice'])->name('sales.downloadInvoice');
    
    // routes/web.php
    Route::get('/download-invoice/{invoiceNumber}', [SaleController::class, 'downloadInvoice'])->name('download.invoice');

    
});






