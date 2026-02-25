<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolModuleController;
use App\Http\Controllers\AdminController;


Route::get('/', fn() => redirect()->route('login'));

// مصادقة عامة
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// مصادقة الطالب
Route::get('/student/login',  [StudentAuthController::class, 'showLogin'])->name('student.login');
Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login.post');

/*
|-----------------------------------------------------------------------
| لوحات التحكم - تتطلب مصادقة Laravel Auth
|-----------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // لوحة الأدمن العام
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // إدارة المدارس (أدمن فقط)
    Route::prefix('admin/schools')->name('schools.')->group(function () {
        Route::get('/',                     [SchoolController::class, 'index'])->name('index');
        Route::post('/store',               [SchoolController::class, 'store'])->name('store');
        Route::put('/{school}',             [SchoolController::class, 'update'])->name('update');
        Route::delete('/{school}',          [SchoolController::class, 'destroy'])->name('destroy');
        Route::patch('/{school}/toggle',    [SchoolController::class, 'toggleStatus'])->name('toggle');
        Route::post('/{school}/archive',    [SchoolController::class, 'toggleArchive'])->name('archive');
        Route::get('/trashed',              [SchoolController::class, 'trashed'])->name('trashed');
        Route::post('/{id}/restore',        [SchoolController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [SchoolController::class, 'forceDelete'])->name('forceDelete');
    });
            // --- الاشتراكات المالية ---
        Route::get('/admin/subscriptions',                    [AdminController::class, 'subscriptions'])->name('admin.subscriptions');
        Route::put('/admin/subscriptions/{school}/renew',     [AdminController::class, 'renewSubscription'])->name('admin.subscriptions.renew');
        Route::post('/admin/subscriptions/payment',           [AdminController::class, 'storeSubscriptionPayment'])->name('admin.subscriptions.payment');
        
        // --- سجل العمليات ---
        Route::get('/admin/logs',                             [AdminController::class, 'logs'])->name('admin.logs');
        Route::delete('/admin/logs/clear',                    [AdminController::class, 'clearOldLogs'])->name('admin.logs.clear');
        Route::get('/admin/reports',      [AdminController::class, 'reports'])->name('admin.reports');

    // إدارة الطلاب (أدمن)
    Route::prefix('admin')->group(function () {
        Route::get('students',          [StudentController::class, 'index'])->name('students.index');
        Route::post('students',         [StudentController::class, 'store'])->name('students.store');
        Route::put('students/{id}',     [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{id}',  [StudentController::class, 'destroy'])->name('students.destroy');
    });

    // إدارة الأسئلة (أدمن)
    Route::prefix('admin')->name('questions.')->group(function () {
        Route::get('/questions',          [QuestionController::class, 'index'])->name('index');
        Route::post('/questions/store',   [QuestionController::class, 'store'])->name('store');
        Route::put('/questions/{id}',     [QuestionController::class, 'update'])->name('update');
        Route::delete('/questions/{id}',  [QuestionController::class, 'destroy'])->name('destroy');
        Route::get('/questions/export',   [QuestionController::class, 'export'])->name('export');
        Route::post('/questions/import',  [QuestionController::class, 'import'])->name('import');
    });

    // لوحة مدير المدرسة
    Route::prefix('school')->name('school.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'schoolDashboard'])->name('dashboard');
        Route::get('signals-exam',   fn() => view('school_admin.maintenance', ['feature' => 'امتحان الإشارات']))->name('signals-exam.index');
        Route::get('practical-exam', fn() => view('school_admin.maintenance', ['feature' => 'امتحان العملي']))->name('practical-exam.index');
        // ---- المدربون ----
        Route::get('trainers',           [\App\Http\Controllers\SchoolModuleController::class, 'trainersIndex'])->name('trainers.index');
        Route::post('trainers',          [\App\Http\Controllers\SchoolModuleController::class, 'trainersStore'])->name('trainers.store');
        Route::put('trainers/{trainer}', [\App\Http\Controllers\SchoolModuleController::class, 'trainersUpdate'])->name('trainers.update');
        Route::delete('trainers/{trainer}', [\App\Http\Controllers\SchoolModuleController::class, 'trainersDestroy'])->name('trainers.destroy');
        
        // ---- المركبات ----
        Route::get('vehicles',           [\App\Http\Controllers\SchoolModuleController::class, 'vehiclesIndex'])->name('vehicles.index');
        Route::post('vehicles',          [\App\Http\Controllers\SchoolModuleController::class, 'vehiclesStore'])->name('vehicles.store');
        Route::put('vehicles/{vehicle}', [\App\Http\Controllers\SchoolModuleController::class, 'vehiclesUpdate'])->name('vehicles.update');
        Route::delete('vehicles/{vehicle}', [\App\Http\Controllers\SchoolModuleController::class, 'vehiclesDestroy'])->name('vehicles.destroy');
        
        // ---- جدول الحصص ----
        Route::get('sessions',           [\App\Http\Controllers\SchoolModuleController::class, 'sessionsIndex'])->name('schedule.index');
        Route::post('sessions',          [\App\Http\Controllers\SchoolModuleController::class, 'sessionsStore'])->name('sessions.store');
        Route::put('sessions/{session}', [\App\Http\Controllers\SchoolModuleController::class, 'sessionsUpdate'])->name('sessions.update');
        Route::delete('sessions/{session}', [\App\Http\Controllers\SchoolModuleController::class, 'sessionsDestroy'])->name('sessions.destroy');
        
        // ---- المدفوعات ----
        Route::get('payments',           [\App\Http\Controllers\SchoolModuleController::class, 'paymentsIndex'])->name('payments.index');
        Route::post('payments',          [\App\Http\Controllers\SchoolModuleController::class, 'paymentsStore'])->name('payments.store');
        Route::delete('payments/{payment}', [\App\Http\Controllers\SchoolModuleController::class, 'paymentsDestroy'])->name('payments.destroy');
        
        // ---- التقارير ----
        Route::get('reports',            [\App\Http\Controllers\SchoolModuleController::class, 'reportsIndex'])->name('reports.index');

        // طلاب المدرسة
        Route::get('students',       [StudentController::class, 'index'])->name('students.index');
        Route::post('students',      [StudentController::class, 'store'])->name('students.store');
        Route::put('students/{id}',  [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    });
});

/*
|-----------------------------------------------------------------------
| بوابة الطالب - تتطلب جلسة الطالب
|-----------------------------------------------------------------------
*/
Route::middleware('checkStudentSession')->group(function () {
    Route::get('/student/dashboard',     [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/profile',       [StudentController::class, 'profile'])->name('student.profile');
    Route::get('/student/financial',     [StudentController::class, 'financial'])->name('student.financial');
    Route::get('/student/traffic-signs', [StudentController::class, 'trafficSigns'])->name('student.traffic.signs');
    Route::get('/student/practice-modes', fn() => view('student.practice_modes'))->name('student.practice.modes');
    Route::get('/student/practice-start', fn() => view('student.practice_view'))->name('student.practice.view');
    Route::get('/student/exam',          fn() => view('student.exam'))->name('student.exam');
    Route::get('/student/exam-result',   fn() => view('student.exam_result'))->name('student.exam_result');
    Route::get('/student/exam-history',  fn() => view('student.history'))->name('student.exam.history');
    Route::get('/api/student/sync-questions',     [StudentController::class, 'getSyncQuestions']);
    Route::get('/api/student/sync-traffic-signs', [StudentController::class, 'getSignsForSync']);
    Route::get('/student/get-sync-data',          [StudentController::class, 'getStudentSyncData']);
    Route::post('/student/sync-batch',   [StudentController::class, 'syncExamDataBatch'])->name('student.sync-batch');
    Route::post('/student/mark-viewed',  [StudentController::class, 'markQuestionAsViewed'])->name('student.mark-viewed');
    Route::post('/student/sync-error',   [StudentController::class, 'syncError'])->name('student.sync-error');
    Route::post('/student/save-exam',    [StudentController::class, 'saveExamResult'])->name('student.save-exam');
    Route::post('/student/update-pulse', [StudentController::class, 'updateActivityPulse'])->name('student.pulse');
    Route::post('/student/reset-study',  [StudentController::class, 'resetStudy'])->name('student.reset_study');
    Route::post('/student/logout',       [StudentAuthController::class, 'logout'])->name('student.logout');
});
