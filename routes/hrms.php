<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'hrm', 'namespace' => 'App\Http\Controllers\hrm'], function () {
    // Designations route group
    Route::group(['prefix' => 'designations'], function () {
        Route::get('/', 'DesignationController@index')->name('hrm.designations');
        Route::get('/ajax-all-desgination', 'DesignationController@all_designation')->name('hrm.designations.all');
        Route::post('/hrm/desgination/store', 'DesignationController@storedesignation')->name('hrm.designations.store');
        Route::post('/hrm/desgination/update', 'DesignationController@updatedesignation')->name('hrm.designations.update');
        Route::delete('hrm/delete/{designationId}', 'DesignationController@deletedesignation')->name('hrm.designations.delete');
    });

    //Departments routes group
    Route::group(['prefix' => 'departments'], function () {
        Route::get('/', 'DepartmentController@index')->name('hrm.departments');
        Route::get('/ajax-all-department', 'DepartmentController@alldepartment')->name('hrm.departments.all');
        Route::post('/hrm/departments/store', 'DepartmentController@storedepartment')->name('hrm.departments.store');
        Route::post('/hrm/departments/update', 'DepartmentController@updatedepartments')->name('hrm.departments.update');
        Route::delete('hrm/delete/{departmentId}', 'DepartmentController@deletedepartment')->name('hrm.department.delete');
    });

    //Leave type routes group
    Route::group(['prefix' => 'leavetype'], function () {
        Route::get('/', 'LeavetypeController@index')->name('hrm.leave.type');
        Route::get('/ajax-all-leavetypes', 'LeavetypeController@allleavtype')->name('hrm.leavetype.all');
        Route::post('/hrm/leavetype/store', 'LeavetypeController@storeleavetype')->name('hrm.leavetype.store');
        Route::post('/hrm/leavetype/update', 'LeavetypeController@updateleavetype')->name('hrm.leavetype.update');
        Route::delete('hrm/leavetype/{id}', 'LeavetypeController@deleteleavetype')->name('hrm.leavetype.delete');
    });

    //Holidays routes group
    Route::group(['prefix' => 'holidays'], function () {
        Route::get('/', 'HolidayController@index')->name('hrm.holidays');
        Route::get('/ajax-all-holidays', 'HolidayController@allholidays')->name('hrm.holidays.all');
        Route::post('/hrm/holidays/store', 'HolidayController@storeholidays')->name('hrm.holidays.store');
        Route::get('/ajax-all-branchget', 'HolidayController@getbranch')->name('hrm.get.all.branch');
        Route::post('/hrm/holidays/update', 'HolidayController@updateholiday')->name('hrm.holidays.update');
        Route::delete('hrm/holidays/{id}', 'HolidayController@deleteholidays')->name('hrm.holidays.delete');
    });

    //Allowance & deduction routes group
    Route::group(['prefix' => 'allowance-deduction'], function () {
        Route::get('/', 'AllowanceController@index')->name('hrm.allowance');
        Route::get('/ajax-all-allowance', 'AllowanceController@allallowance')->name('hrm.allowance.all');
        Route::post('/hrm/allowance/store', 'AllowanceController@storeallowance')->name('hrm.allowance.store');
        Route::get('/ajax-all-employees', 'AllowanceController@getemployee')->name('hrm.get.all.employee');
        Route::get('edit/{alowanceId}', 'AllowanceController@edit')->name('hrm.allowance.edit');
        Route::post('/hrm/allowance/update', 'AllowanceController@updateallowance')->name('hrm.allowance.update');
        Route::delete('hrm/allowance/{id}', 'AllowanceController@deleteallowance')->name('hrm.allowance.delete');
    });

    //Leave  routes group
    Route::group(['prefix' => 'leave'], function () {
        Route::get('/', 'LeaveController@index')->name('hrm.leave');
        Route::get('/ajax-all-leave', 'LeaveController@allleave')->name('hrm.leave.all');
        Route::post('/hrm/leave/store', 'LeaveController@storeleave')->name('hrm.leave.store');
        Route::get('/ajax-all-leavetype', 'LeaveController@getleavetype')->name('hrm.get.all.leavetype');
        Route::post('/hrm/leave/update', 'LeaveController@updateleave')->name('hrm.leave.update');
        Route::delete('hrm/leave/{id}', 'LeaveController@deleteleave')->name('hrm.leave.delete');
        Route::get('department/employees/{depId}', 'LeaveController@departmentEmployees');
    });

    //Leave  routes group
    Route::group(['prefix' => 'shift'], function () {
        Route::get('/', 'ShiftController@index')->name('hrm.attendance.shift');
        Route::get('/ajax-all-shift', 'ShiftController@allshift')->name('hrm.shift.all');
        Route::post('/hrm/shift/store', 'ShiftController@storeshift')->name('hrm.shift.store');
        Route::post('/hrm/shift/update', 'ShiftController@updateshift')->name('hrm.shift.update');
    });

    //Attendance  routes group
    Route::group(['prefix' => 'attendances'], function () {
        Route::get('/', 'AttendanceController@index')->name('hrm.attendance');
        Route::get('ajax-all-attendance', 'AttendanceController@allAttendance')->name('hrm.attendance.all');
        Route::get('get/user/attendance/row/{userId}', 'AttendanceController@getUserAttendanceRow');
        Route::post('store', 'AttendanceController@storeAttendance')->name('hrm.attendance.store');
        Route::get('edit/{attendanceId}', 'AttendanceController@edit')->name('hrm.attendance.edit');
        Route::post('update', 'AttendanceController@update')->name('hrm.attendance.update');
        Route::delete('delete/{attendanceId}', 'AttendanceController@delete')->name('hrm.attendance.delete');

        // Route::get('/ajax-all-leavetype', 'attendancecontroller@getleavetype')->name('hrm.get.all.leavetype');
        // Route::post('/hrm/shift/update', 'attendancecontroller@updateshift')->name('hrm.shift.update');
    });

    //Attendance  routes group
    Route::group(['prefix' => 'payrolls'], function () {
        Route::get('/', 'PayrollController@index')->name('hrm.payroll.index');
        Route::get('get', 'PayrollController@getPayrolls')->name('hrm.payroll.get.payrolls');
        Route::get('create', 'PayrollController@create')->name('hrm.payroll.create');
        Route::post('store', 'PayrollController@store')->name('hrm.payroll.store');
        Route::get('edit/{payrollId}', 'PayrollController@edit')->name('hrm.payrolls.edit');
        Route::post('update/{payrollId}', 'PayrollController@update')->name('hrm.payrolls.update');
        Route::get('show/{payrollId}', 'PayrollController@show')->name('hrm.payrolls.show');
        Route::delete('delete/{payrollId}', 'PayrollController@delete')->name('hrm.payrolls.delete');
        Route::get('payment/view/{payrollId}', 'PayrollController@paymentView')->name('hrm.payrolls.payment.view');
        Route::get('payment/{payrollId}', 'PayrollController@payment')->name('hrm.payrolls.payment');
        Route::post('add/payment/{payrollId}', 'PayrollController@addPayment')->name('hrm.payrolls.add.payment');
        Route::get('payment/details/{paymentId}', 'PayrollController@paymentDetails')->name('hrm.payrolls.payment.details');
        Route::delete('payment/delete/{paymentId}', 'PayrollController@paymentDelete')->name('hrm.payrolls.payment.delete');
        Route::get('payment/edit/{paymentId}', 'PayrollController@paymentEdit')->name('hrm.payrolls.payment.edit');
        Route::post('payment/update/{paymentId}', 'PayrollController@paymentUpdate')->name('hrm.payrolls.payment.update');
        Route::get('all/employees', 'PayrollController@getAllEmployee')->name('hrm.payroll.get.allEmployee');
        Route::get('all/departments', 'PayrollController@getAllDeparment')->name('hrm.payroll.get.department');
        Route::get('all/designations', 'PayrollController@getAllDesignation')->name('hrm.payroll.get.designations');
    });
});
