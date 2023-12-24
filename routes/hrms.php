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
use App\Http\Controllers\HRM\PayrollPaymentController;
use App\Http\Controllers\Report\AttendanceReportController;
use App\Http\Controllers\HRM\AllowanceAndDeductionController;
use App\Http\Controllers\HRM\Reports\PayrollReportController;
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
        Route::get('users/{id}', 'users')->name('hrm.departments.users');
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

    Route::controller(PayrollController::class)->prefix('payrolls')->group(function () {

        Route::get('/', 'index')->name('hrm.payrolls.index');
        Route::get('create', 'create')->name('hrm.payrolls.create');
        Route::get('show/{id}', 'show')->name('hrm.payrolls.show');
        Route::post('store',  'store')->name('hrm.payrolls.store');
        Route::get('edit/{id}', 'edit')->name('hrm.payrolls.edit');
        Route::post('update/{id}', 'update')->name('hrm.payrolls.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.payrolls.delete');
    });

    Route::controller(PayrollPaymentController::class)->prefix('payroll-payments')->group(function () {

        Route::get('/', 'index')->name('hrm.payroll.payments.index');
        Route::get('create/{payrollId}', 'create')->name('hrm.payroll.payments.create');
        Route::get('show/{id}', 'show')->name('hrm.payroll.payments.show');
        Route::post('store',  'store')->name('hrm.payroll.payments.store');
        Route::get('edit/{id}', 'edit')->name('hrm.payroll.payments.edit');
        Route::post('update/{id}', 'update')->name('hrm.payroll.payments.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.payroll.payments.delete');
    });

    Route::group(['prefix' => 'dashboard'], function () {

        Route::get('/', [DashboardController::class, 'index'])->name('hrm.dashboard.index');
        Route::get('user/count/table', [DashboardController::class, 'userCountTable'])->name('hrm.dashboard.user.count.table');
        Route::get('today/attr/table', [DashboardController::class, 'todayAttTable'])->name('hrm.dashboard.today.attr.table');
        Route::get('leave/table', [DashboardController::class, 'leaveTable'])->name('hrm.dashboard.leave.table');
        Route::get('upcoming/holidays', [DashboardController::class, 'upcomingHolidays'])->name('hrm.dashboard.upcoming.holidays');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(PayrollReportController::class)->prefix('payrolls')->group(function () {

            Route::get('/', 'index')->name('payroll.reports.index');
            Route::get('print', 'print')->name('payroll.reports.print');
        });

        Route::controller(PayrollPaymentReportController::class)->prefix('payroll/payments')->group(function () {

            Route::get('/', 'index')->name('reports.payroll.payments.index');
            Route::get('print', 'print')->name('reports.payroll.payments.print');
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
