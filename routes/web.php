<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminReportFlagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest')->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('user.role:graduate')->name('dashboard');
    Route::get('/dashboard/majors', [DashboardController::class, 'majors'])->middleware('user.role:graduate')->name('dashboard.majors');
    Route::get('/dashboard/majors/{majorId}', [DashboardController::class, 'majorSubMajors'])->middleware('user.role:graduate')->name('dashboard.majors.show');
    Route::get('/dashboard/submajors/{subMajorId}/roles', [DashboardController::class, 'subMajorRoles'])->middleware('user.role:graduate')->name('dashboard.submajors.roles');
    Route::get('/dashboard/roles/{roleId}', [DashboardController::class, 'roleDetails'])->middleware('user.role:graduate')->name('dashboard.roles.show');
    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->middleware('user.role:professional')->name('employee.dashboard');

    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{id}/buy', [SubscriptionController::class, 'buySingleJob'])->name('jobs.buy');

    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/create/{subMajorId?}', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/evaluations/store', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/evaluations/{id}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::post('/evaluations/{id}/update', [EvaluationController::class, 'update'])->name('evaluations.update');
    Route::post('/evaluations/{id}/delete', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{threadId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessageController::class, 'store'])->name('messages.send');
    Route::post('/messages/{threadId}/reply', [MessageController::class, 'reply'])->name('messages.reply');

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/monthly', [SubscriptionController::class, 'subscribeMonthly'])->name('subscriptions.monthly');
    Route::get('/subscriptions/purchases', [SubscriptionController::class, 'myPurchases'])->name('subscriptions.purchases');
    Route::post('/checkout/start', [CheckoutController::class, 'start'])->name('checkout.start');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/fail', [CheckoutController::class, 'fail'])->name('checkout.fail');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::get('/my-complaints', [ComplaintController::class, 'myIndex'])->name('complaints.index');
    Route::post('/complaints/store', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::post('/complaints/{id}/update', [ComplaintController::class, 'update'])->name('complaints.update');
});

Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::post('/payments/{id}/verify', [AdminController::class, 'verifyPayment'])->name('admin.payments.verify');
    Route::post('/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('admin.payments.reject');
    Route::post('/evaluations/{id}/delete', [AdminController::class, 'deleteEvaluation'])->name('admin.evaluations.delete');

    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{id}/update', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{id}/suspend', [UserManagementController::class, 'suspend'])->name('admin.users.suspend');
    Route::post('/users/{id}/activate', [UserManagementController::class, 'activate'])->name('admin.users.activate');
    Route::post('/users/{id}/delete', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/complaints', [ComplaintController::class, 'index'])->name('admin.complaints');
    Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
    Route::post('/complaints/{id}/resolve', [ComplaintController::class, 'resolve'])->name('admin.complaints.resolve');

    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');
    Route::get('/reports/{id}/print', [ReportController::class, 'print'])->name('admin.reports.print');
    Route::get('/reports/payments/{id}/print', [ReportController::class, 'printPayment'])->name('admin.reports.payments.print');
    Route::get('/report-flags', [AdminReportFlagController::class, 'index'])->name('admin.report-flags.index');
    Route::post('/report-flags/{id}/review', [AdminReportFlagController::class, 'review'])->name('admin.report-flags.review');
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('/catalog', [AdminCatalogController::class, 'index'])->name('admin.catalog.index');
    Route::get('/catalog/majors', [AdminCatalogController::class, 'majors'])->name('admin.catalog.majors');
    Route::get('/catalog/submajors', [AdminCatalogController::class, 'subMajors'])->name('admin.catalog.submajors');
    Route::get('/catalog/roles', [AdminCatalogController::class, 'roles'])->name('admin.catalog.roles');
    Route::post('/majors', [AdminCatalogController::class, 'storeMajor'])->name('admin.majors.store');
    Route::post('/majors/{id}/update', [AdminCatalogController::class, 'updateMajor'])->name('admin.majors.update');
    Route::post('/majors/{id}/delete', [AdminCatalogController::class, 'destroyMajor'])->name('admin.majors.destroy');
    Route::post('/submajors', [AdminCatalogController::class, 'storeSubMajor'])->name('admin.submajors.store');
    Route::post('/submajors/{id}/update', [AdminCatalogController::class, 'updateSubMajor'])->name('admin.submajors.update');
    Route::post('/submajors/{id}/delete', [AdminCatalogController::class, 'destroySubMajor'])->name('admin.submajors.destroy');
    Route::post('/roles', [AdminCatalogController::class, 'storeRole'])->name('admin.roles.store');
    Route::post('/roles/{id}/update', [AdminCatalogController::class, 'updateRole'])->name('admin.roles.update');
    Route::post('/roles/{id}/delete', [AdminCatalogController::class, 'destroyRole'])->name('admin.roles.destroy');
});
