<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\AccountTypeController;
use App\Http\Controllers\Admin\AnnouncementController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Semua route yang butuh login DAN akun tidak sedang di-suspend admin
// digabung dalam satu grup, supaya tidak ada yang lupa terproteksi lagi.
Route::middleware(['auth', 'verified', 'not_banned'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])
        ->middleware(['auth', 'verified', 'not_banned'])
        ->name('dashboard.export.pdf');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    Route::resource('accounts', AccountController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('budgets', BudgetController::class)->except(['show']);
    Route::resource('saving-goals', SavingGoalController::class)->except(['show']);
    Route::resource('recurring', RecurringTransactionController::class)->except(['show']);

    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');

    Route::get('/saving-goals/{savingGoal}/allocate', [SavingGoalController::class, 'allocateForm'])->name('saving-goals.allocate.form');
    Route::post('/saving-goals/{savingGoal}/allocate', [SavingGoalController::class, 'allocate'])->name('saving-goals.allocate');

    Route::get('/saving-goals/{savingGoal}/withdraw', [SavingGoalController::class, 'withdrawForm'])->name('saving-goals.withdraw.form');
    Route::post('/saving-goals/{savingGoal}/withdraw', [SavingGoalController::class, 'withdraw'])->name('saving-goals.withdraw');
});

Route::middleware(['auth', 'verified', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-role', [UserManagementController::class, 'toggleRole'])->name('users.toggle-role');
    Route::patch('/users/{user}/toggle-ban', [UserManagementController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::patch('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');

    Route::get('/categories', [CategoryManagementController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryManagementController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryManagementController::class, 'destroy'])->name('categories.destroy');

    Route::get('/account-types', [AccountTypeController::class, 'index'])->name('account-types.index');
    Route::post('/account-types', [AccountTypeController::class, 'store'])->name('account-types.store');
    Route::delete('/account-types/{accountType}', [AccountTypeController::class, 'destroy'])->name('account-types.destroy');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::patch('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleActive'])->name('announcements.toggle');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});

require __DIR__ . '/auth.php';
