<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BoqController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DsrController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PvController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
// Root redirect — works for both guests and authenticated users
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('/forgot-password',       [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password',      [ForgotPasswordController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}',[ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password',       [ResetPasswordController::class, 'reset'])->name('password.store');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', ProjectController::class);

    Route::prefix('projects/{project}')->name('projects.')->group(function () {

        Route::get('hub', [ProjectController::class, 'hub'])->name('hub');

        /* BOQ */
        Route::prefix('boq')->name('boq.')->group(function () {
            Route::get('/',           [BoqController::class, 'index'])->name('index');
            Route::get('/create',     [BoqController::class, 'create'])->name('create');
            Route::post('/',          [BoqController::class, 'store'])->name('store');
            Route::get('/{boq}/edit', [BoqController::class, 'edit'])->name('edit');
            Route::put('/{boq}',      [BoqController::class, 'update'])->name('update');
            Route::delete('/{boq}',   [BoqController::class, 'destroy'])->name('destroy');
            Route::post('/import',    [BoqController::class, 'import'])->name('import');
            Route::get('/export',     [BoqController::class, 'export'])->name('export');
        });

        /* Progress */
        Route::prefix('progress')->name('progress.')->group(function () {
            Route::get('/',             [ProgressController::class, 'index'])->name('index');
            Route::get('/create',       [ProgressController::class, 'create'])->name('create');
            Route::post('/',            [ProgressController::class, 'store'])->name('store');
            Route::get('/{entry}/edit', [ProgressController::class, 'edit'])->name('edit');
            Route::put('/{entry}',      [ProgressController::class, 'update'])->name('update');
            Route::delete('/{entry}',   [ProgressController::class, 'destroy'])->name('destroy');
            Route::get('/report',       [ProgressController::class, 'report'])->name('report');
            Route::get('/export',       [ProgressController::class, 'export'])->name('export');
        });

        /* Billing */
        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/',                [BillingController::class, 'index'])->name('index');
            Route::get('/config',          [BillingController::class, 'config'])->name('config');
            Route::post('/config',         [BillingController::class, 'saveConfig'])->name('config.save');
            Route::get('/create',          [BillingController::class, 'create'])->name('create');
            Route::post('/',               [BillingController::class, 'store'])->name('store');
            Route::get('/{bill}',          [BillingController::class, 'show'])->name('show');
            Route::get('/{bill}/edit',     [BillingController::class, 'edit'])->name('edit');
            Route::put('/{bill}',          [BillingController::class, 'update'])->name('update');
            Route::delete('/{bill}',       [BillingController::class, 'destroy'])->name('destroy');
            Route::post('/{bill}/advance', [BillingController::class, 'advance'])->name('advance');
            Route::get('/{bill}/pdf',      [BillingController::class, 'pdf'])->name('pdf');
        });

        /* DSR */
        Route::prefix('dsr')->name('dsr.')->group(function () {
            Route::get('/',        [DsrController::class, 'index'])->name('index');
            Route::get('/report',  [DsrController::class, 'report'])->name('report');
            Route::get('/{boq}',   [DsrController::class, 'edit'])->name('edit');
            Route::put('/{boq}',   [DsrController::class, 'update'])->name('update');
        });

        /* Inventory */
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/',                     [InventoryController::class, 'index'])->name('index');
            Route::get('/materials/create',     [InventoryController::class, 'createMaterial'])->name('materials.create');
            Route::post('/materials',           [InventoryController::class, 'storeMaterial'])->name('materials.store');
            Route::get('/materials/{mat}/edit', [InventoryController::class, 'editMaterial'])->name('materials.edit');
            Route::put('/materials/{mat}',      [InventoryController::class, 'updateMaterial'])->name('materials.update');
            Route::delete('/materials/{mat}',   [InventoryController::class, 'destroyMaterial'])->name('materials.destroy');
            Route::get('/transactions/create',  [InventoryController::class, 'createTxn'])->name('transactions.create');
            Route::post('/transactions',        [InventoryController::class, 'storeTxn'])->name('transactions.store');
            Route::get('/report',               [InventoryController::class, 'report'])->name('report');
            Route::get('/export',               [InventoryController::class, 'export'])->name('export');
        });

        /* Price Variation */
        Route::prefix('pv')->name('pv.')->group(function () {
            Route::get('/',          [PvController::class, 'index'])->name('index');
            Route::get('/create',    [PvController::class, 'create'])->name('create');
            Route::post('/',         [PvController::class, 'store'])->name('store');
            Route::get('/{pv}/edit', [PvController::class, 'edit'])->name('edit');
            Route::put('/{pv}',      [PvController::class, 'update'])->name('update');
            Route::delete('/{pv}',   [PvController::class, 'destroy'])->name('destroy');
            Route::get('/calculate', [PvController::class, 'calculate'])->name('calculate');
            Route::get('/pdf',       [PvController::class, 'pdf'])->name('pdf');
        });

        /* Expenses */
        Route::prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/',            [ExpenseController::class, 'index'])->name('index');
            Route::get('/create',      [ExpenseController::class, 'create'])->name('create');
            Route::post('/',           [ExpenseController::class, 'store'])->name('store');
            Route::get('/{exp}/edit',  [ExpenseController::class, 'edit'])->name('edit');
            Route::put('/{exp}',       [ExpenseController::class, 'update'])->name('update');
            Route::delete('/{exp}',    [ExpenseController::class, 'destroy'])->name('destroy');
            Route::get('/report',      [ExpenseController::class, 'report'])->name('report');
            Route::get('/export',      [ExpenseController::class, 'export'])->name('export');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password',  [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status',   [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users/{user}/assign-projects',  [UserController::class, 'assignProjectsForm'])->name('users.assign-projects');
        Route::post('users/{user}/assign-projects', [UserController::class, 'assignProjects'])->name('users.assign-projects.save');
    });
});
