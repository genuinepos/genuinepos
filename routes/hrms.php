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
use App\Http\Controllers\Report\PayrollPaymentReportController;

Route::group(['prefix' => 'hrm'], function () {
    // Designations route group
    Route::group(['prefix' => 'designations'], function () {

        Route::get('/', [DesignationController::class, 'index'])->name('hrm.designations');
        Route::get('/ajax-all-designation', [DesignationController::class, 'allDesignation'])->name('hrm.designations.all');
        Route::post('/hrm/designation/store', [DesignationController::class, 'storeDesignation'])->name('hrm.designations.store');
        Route::post('/hrm/designation/update', [DesignationController::class, 'updateDesignation'])->name('hrm.designations.update');
        Route::delete('hrm/delete/{designationId}', [DesignationController::class, 'deleteDesignation'])->name('hrm.designations.delete');
    });

    //Departments routes group
    Route::group(['prefix' => 'departments'], function () {

        Route::get('/', [DepartmentController::class, 'index'])->name('hrm.departments');
        Route::get('/ajax-all-department', [DepartmentController::class, 'alldepartment'])->name('hrm.departments.all');
        Route::post('/hrm/departments/store', [DepartmentController::class, 'storedepartment'])->name('hrm.departments.store');
        Route::post('/hrm/departments/update', [DepartmentController::class, 'updatedepartments'])->name('hrm.departments.update');
        Route::delete('hrm/delete/{departmentId}', [DepartmentController::class, 'deletedepartment'])->name('hrm.department.delete');
    });

    //Leave type routes group
    Route::controller(LeaveTypeController::class)->prefix('leave-types')->group(function () {

        Route::get('/', 'index')->name('hrm.leave.type.index');
        Route::post('store', 'store')->name('hrm.leave.type.store');
        Route::get('edit/{id}', 'edit')->name('hrm.leave.type.edit');
        Route::post('update/{id}', 'update')->name('hrm.leave.type.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.leave.type.delete');
    });

    //Holidays routes group
    Route::group(['prefix' => 'holidays'], function () {

        Route::get('/', [HolidayController::class, 'index'])->name('hrm.holidays');
        Route::get('/ajax-all-holidays', [HolidayController::class, 'allholidays'])->name('hrm.holidays.all');
        Route::post('/hrm/holidays/store', [HolidayController::class, 'storeholidays'])->name('hrm.holidays.store');
        Route::get('/hrm/holidays/edit/{id}', [HolidayController::class, 'edit'])->name('hrm.holidays.edit');
        Route::post('/hrm/holidays/update', [HolidayController::class, 'updateholiday'])->name('hrm.holidays.update');
        Route::delete('hrm/holidays/{id}', [HolidayController::class, 'deleteholidays'])->name('hrm.holidays.delete');
    });

    //Allowance & deduction routes group
    Route::group(['prefix' => 'allowance-deduction'], function () {

        Route::get('/', [AllowanceController::class, 'index'])->name('hrm.allowance');
        Route::get('/ajax-all-allowance', [AllowanceController::class, 'allallowance'])->name('hrm.allowance.all');
        Route::post('/hrm/allowance/store', [AllowanceController::class, 'storeallowance'])->name('hrm.allowance.store');
        Route::get('/ajax-all-employees', [AllowanceController::class, 'getemployee'])->name('hrm.get.all.employee');
        Route::get('edit/{alowanceId}', [AllowanceController::class, 'edit'])->name('hrm.allowance.edit');
        Route::post('/hrm/allowance/update', [AllowanceController::class, 'updateallowance'])->name('hrm.allowance.update');
        Route::delete('hrm/allowance/{id}', [AllowanceController::class, 'deleteallowance'])->name('hrm.allowance.delete');
    });

    //Leave  routes group
    Route::controller(LeaveController::class)->prefix('leaves')->group(function () {

        Route::get('/', [LeaveController::class, 'index'])->name('hrm.leaves.index');
        Route::get('department/employees/{departmentId}', [LeaveController::class, 'departmentEmployees']);
        Route::post('store', [LeaveController::class, 'store'])->name('hrm.leaves.store');
        Route::get('edit/{id}', [LeaveController::class, 'edit'])->name('hrm.leaves.edit');
        Route::post('update/{id}', [LeaveController::class, 'update'])->name('hrm.leaves.update');
        Route::delete('delete/{id}', 'delete')->name('hrm.leaves.delete');
    });

    //Leave  routes group
    Route::group(['prefix' => 'shift'], function () {

        Route::get('/', [ShiftController::class, 'index'])->name('hrm.attendance.shift');
        Route::get('/ajax-all-shift', [ShiftController::class, 'allshift'])->name('hrm.shift.all');
        Route::get('/hrm/shift/edit/{id}', [ShiftController::class, 'shiftEdit'])->name('hrm.shift.edit');
        Route::post('/hrm/shift/store', [ShiftController::class, 'storeshift'])->name('hrm.shift.store');
        Route::post('/hrm/shift/update', [ShiftController::class, 'updateShift'])->name('hrm.shift.update');
        Route::delete('/hrm/shift/delete/{id}', [ShiftController::class, 'deleteShift'])->name('hrm.shift.delete');
    });

    //Attendance  routes group
    Route::group(['prefix' => 'attendances'], function () {

        Route::get('/', [AttendanceController::class, 'index'])->name('hrm.attendance');
        Route::get('ajax-all-attendance', [AttendanceController::class, 'allAttendance'])->name('hrm.attendance.all');
        Route::get('get/user/attendance/row/{userId}', [AttendanceController::class, 'getUserAttendanceRow']);
        Route::post('store', [AttendanceController::class, 'storeAttendance'])->name('hrm.attendance.store');
        Route::get('edit/{attendanceId}', [AttendanceController::class, 'edit'])->name('hrm.attendance.edit');
        Route::post('update', [AttendanceController::class, 'update'])->name('hrm.attendance.update');
        Route::delete('delete/{attendanceId}', [AttendanceController::class, 'delete'])->name('hrm.attendance.delete');
    });

    //Attendance  routes group
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

    //Attendance
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
