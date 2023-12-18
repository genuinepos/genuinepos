<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HRM\LeaveController;
use App\Http\Controllers\HRM\ShiftController;
use App\Http\Controllers\HRM\HolidayController;
use App\Http\Controllers\HRM\PayrollController;
use App\Http\Controllers\HRM\AllowanceController;
use App\Http\Controllers\HRM\DashboardController;
use App\Http\Controllers\HRM\LeaveTypeController;
use App\Http\Controllers\HRM\AttendanceController;
use App\Http\Controllers\HRM\DepartmentController;
use App\Http\Controllers\HRM\DesignationController;
use App\Http\Controllers\Report\PayrollReportController;
use App\Http\Controllers\Report\AttendanceReportController;
use App\Http\Controllers\HRM\AllowanceAndDeductionController;
use App\Http\Controllers\Report\PayrollPaymentReportController;

Route::group(['prefix' => 'hrm'], function () {

    Route::controller(DesignationController::class)->prefix('designations')->group(function () {

        Route::get('/', 'index')->name('hrm.designations.index');
        Route::get('create', 'create')->name('hrm.designations.create');
        Route::post('store', 'store')->name('hrm.designations.store');
        Route::get('edit/{id}', 'edit')->name('hrm.designations.edit');
        Route::post('update/{id}', 'update')->name('hrm.designations.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.designations.delete');
    });

    Route::controller(DepartmentController::class)->prefix('departments')->group(function () {

        Route::get('/', 'index')->name('hrm.departments.index');
        Route::get('create', 'create')->name('hrm.departments.create');
        Route::post('store', 'store')->name('hrm.departments.store');
        Route::get('edit/{id}', 'edit')->name('hrm.departments.edit');
        Route::post('update/{id}', 'update')->name('hrm.departments.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.departments.delete');
        Route::delete('users/{id}', 'users')->name('hrm.departments.users');
    });

    Route::controller(HolidayController::class)->prefix('holidays')->group(function () {

        Route::get('/', 'index')->name('hrm.holidays.index');
        Route::get('create', 'create')->name('hrm.holidays.create');
        Route::post('store', 'store')->name('hrm.holidays.store');
        Route::get('edit/{id}', 'edit')->name('hrm.holidays.edit');
        Route::post('update/{id}', 'update')->name('hrm.holidays.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.holidays.delete');
    });

    Route::controller(AllowanceAndDeductionController::class)->prefix('allowances-and-deductions')->group(function () {

        Route::get('/', 'index')->name('hrm.allowances.deductions.index');
        Route::post('store', 'store')->name('hrm.allowances.deductions.store');
        Route::get('create', 'create')->name('hrm.allowances.deductions.create');
        Route::get('edit/{id}', 'edit')->name('hrm.allowances.deductions.edit');
        Route::post('update/{id}', 'update')->name('hrm.allowances.deductions.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.allowances.deductions.delete');
    });

    Route::controller(LeaveController::class)->prefix('leaves')->group(function () {
        Route::get('/', 'index')->name('hrm.leaves.index');
        Route::get('create', 'create')->name('hrm.leaves.create');
        Route::post('store', 'store')->name('hrm.leaves.store');
        Route::get('edit/{id}', 'edit')->name('hrm.leaves.edit');
        Route::post('update/{id}', 'update')->name('hrm.leaves.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.leaves.delete');

        Route::controller(LeaveTypeController::class)->prefix('types')->group(function () {

            Route::get('/', 'index')->name('hrm.leave.type.index');
            Route::get('create', 'create')->name('hrm.leave.type.create');
            Route::post('store', 'store')->name('hrm.leave.type.store');
            Route::get('edit/{id}', 'edit')->name('hrm.leave.type.edit');
            Route::post('update/{id}', 'update')->name('hrm.leave.type.update');
            Route::delete('delete/{id}', 'delete')->name('hrm.leave.type.delete');
        });
    });

    Route::controller(ShiftController::class)->prefix('shifts')->group(function () {
        Route::get('/', 'index')->name('hrm.shifts.index');
        Route::get('create', 'create')->name('hrm.shifts.create');
        Route::post('store',  'store')->name('hrm.shifts.store');
        Route::get('edit/{id}', 'edit')->name('hrm.shifts.edit');
        Route::post('update/{id}', 'update')->name('hrm.shifts.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.shifts.delete');
    });

    Route::controller(AttendanceController::class)->prefix('attendances')->group(function () {
        Route::get('/', 'index')->name('hrm.attendances.index');
        Route::get('create', 'create')->name('hrm.attendances.create');
        Route::get('row/{userId}', 'userAttendanceRow')->name('hrm.attendances.row');
        Route::post('store', 'store')->name('hrm.attendances.store');
        Route::get('edit/{id}', 'edit')->name('hrm.attendances.edit');
        Route::post('update/{id}', 'update')->name('hrm.attendances.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.attendances.delete');
    });

    Route::group(['prefix' => 'payrolls'], function () {

        Route::get('/', [PayrollController::class, 'index'])->name('hrm.payroll.index');
        Route::get('get', [PayrollController::class, 'getPayrolls'])->name('hrm.payroll.get.payrolls');
        Route::get('create', [PayrollController::class, 'create'])->name('hrm.payroll.create');
        Route::post('store', [PayrollController::class, 'store'])->name('hrm.payroll.store');
        Route::get('edit/{payrollId}', [PayrollController::class, 'edit'])->name('hrm.payrolls.edit');
        Route::post('update/{payrollId}', [PayrollController::class, 'update'])->name('hrm.payrolls.update');
        Route::get('show/{payrollId}', [PayrollController::class, 'show'])->name('hrm.payrolls.show');
        Route::delete('delete/{payrollId}', [PayrollController::class, 'delete'])->name('hrm.payrolls.delete');
        Route::get('payment/view/{payrollId}', [PayrollController::class, 'paymentView'])->name('hrm.payrolls.payment.view');
        Route::get('payment/{payrollId}', [PayrollController::class, 'payment'])->name('hrm.payrolls.payment');
        Route::post('add/payment/{payrollId}', [PayrollController::class, 'addPayment'])->name('hrm.payrolls.add.payment');
        Route::get('payment/details/{paymentId}', [PayrollController::class, 'paymentDetails'])->name('hrm.payrolls.payment.details');
        Route::delete('payment/delete/{paymentId}', [PayrollController::class, 'paymentDelete'])->name('hrm.payrolls.payment.delete');
        Route::get('payment/edit/{paymentId}', [PayrollController::class, 'paymentEdit'])->name('hrm.payrolls.payment.edit');
        Route::post('payment/update/{paymentId}', [PayrollController::class, 'paymentUpdate'])->name('hrm.payrolls.payment.update');
        Route::get('all/employees', [PayrollController::class, 'getAllEmployee'])->name('hrm.payroll.get.allEmployee');
        Route::get('all/departments', [PayrollController::class, 'getAllDeparment'])->name('hrm.payroll.get.department');
        Route::get('all/designations', [PayrollController::class, 'getAllDesignation'])->name('hrm.payroll.get.designations');
    });

    Route::group(['prefix' => 'dashboard'], function () {

        Route::get('/', [DashboardController::class, 'index'])->name('hrm.dashboard.index');
        Route::get('user/count/table', [DashboardController::class, 'userCountTable'])->name('hrm.dashboard.user.count.table');
        Route::get('today/attr/table', [DashboardController::class, 'todayAttTable'])->name('hrm.dashboard.today.attr.table');
        Route::get('leave/table', [DashboardController::class, 'leaveTable'])->name('hrm.dashboard.leave.table');
        Route::get('upcoming/holidays', [DashboardController::class, 'upcomingHolidays'])->name('hrm.dashboard.upcoming.holidays');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'payrolls'], function () {

            Route::get('/', [PayrollReportController::class, 'payrollReport'])->name('reports.payroll');
            Route::get('print', [PayrollReportController::class, 'payrollReportPrint'])->name('reports.payroll.print');
        });

        Route::group(['prefix' => 'payroll/payments'], function () {

            Route::get('/', [PayrollPaymentReportController::class, 'payrollPaymentReport'])->name('reports.payroll.payment');
            Route::get('print', [PayrollPaymentReportController::class, 'payrollPaymentReportPrint'])->name('reports.payroll.payment.print');
        });

        Route::group(['prefix' => 'attendances'], function () {

            Route::get('/', [AttendanceReportController::class, 'attendanceReport'])->name('reports.attendance');
            Route::get('print', [AttendanceReportController::class, 'attendanceReportPrint'])->name('reports.attendance.print');
        });
    });
});

// HRM Dashboard, Need Help,  and Profile View Page Routes
Route::group(['prefix' => 'pages'], function () {

    Route::get('dashboard', fn () => view('dashboard.hrm_dashboard'));
});
