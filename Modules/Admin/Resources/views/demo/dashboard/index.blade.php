<x-admin::admin-layout>
    <div class="dashboard-breadcrumb mb-30">
        <h2>CRM Dashboard</h2>
        <div class="input-group dashboard-filter">
            <input type="text" class="form-control" name="basic" id="dashboardFilter" readonly>
            <label for="dashboardFilter" class="input-group-text"><i class="fa-light fa-calendar-days"></i></label>
        </div>
    </div>
    <div class="row mb-30">
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                <div class="d-flex justify-content-between align-items-center mb-20">
                    <div class="right">
                        <div class="part-icon text-light rounded">
                            <span><i class="fa-light fa-user-plus"></i></span>
                        </div>
                    </div>
                    <div class="left">
                        <h3 class="fw-normal">134,152</h3>
                    </div>
                </div>
                <div class="progress-box">
                    <p class="d-flex justify-content-between mb-1">Active Client <small>+116.24%</small></p>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                <div class="d-flex justify-content-between align-items-center mb-20">
                    <div class="right">
                        <div class="part-icon text-light rounded">
                            <span><i class="fa-light fa-user-secret"></i></span>
                        </div>
                    </div>
                    <div class="left">
                        <h3 class="fw-normal">134,152</h3>
                    </div>
                </div>
                <div class="progress-box">
                    <p class="d-flex justify-content-between mb-1">Active Admin <small>56.24%</small></p>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                <div class="d-flex justify-content-between align-items-center mb-20">
                    <div class="right">
                        <div class="part-icon text-light rounded">
                            <span><i class="fa-light fa-money-bill"></i></span>
                        </div>
                    </div>
                    <div class="left">
                        <h3 class="fw-normal">134,152</h3>
                    </div>
                </div>
                <div class="progress-box">
                    <p class="d-flex justify-content-between mb-1">Total Expenses <small>+16.24%</small></p>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-warning" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                <div class="d-flex justify-content-between align-items-center mb-20">
                    <div class="right">
                        <div class="part-icon text-light rounded">
                            <span><i class="fa-light fa-file"></i></span>
                        </div>
                    </div>
                    <div class="left">
                        <h3 class="fw-normal">134,152</h3>
                    </div>
                </div>
                <div class="progress-box">
                    <p class="d-flex justify-content-between mb-1">Running Projects <small>+16.24%</small></p>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-danger" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="panel chart-panel-1">
                <div class="panel-header">
                    <h5>Balance Overview</h5>
                    <div class="btn-box">
                        <button class="btn btn-sm btn-outline-primary">Week</button>
                        <button class="btn btn-sm btn-outline-primary">Month</button>
                        <button class="btn btn-sm btn-outline-primary">Year</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="balanceOverview" class="chart-dark"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-header">
                    <h5>Recent Projects</h5>
                    <div class="btn-box">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 recent-project-table">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Progress</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span>Office Management</span>
                                        <span class="d-block">9 tasks completed</span>
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 85%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Office Management</span>
                                        <span class="d-block">9 tasks completed</span>
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 85%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Office Management</span>
                                        <span class="d-block">9 tasks completed</span>
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 85%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Office Management</span>
                                        <span class="d-block">9 tasks completed</span>
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 85%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Office Management</span>
                                        <span class="d-block">9 tasks completed</span>
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 85%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Office Management</span>
                                        <span class="d-block">9 tasks completed</span>
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 85%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Upcoming Activities</h5>
                    <div class="btn-box">
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-activity">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="activity-box">
                                            <div class="date-box">
                                                <span>14</span>
                                                <span>Feb</span>
                                            </div>
                                            <div class="part-txt">
                                                <span>Meeting for campaign with sales team</span>
                                                <span>12:00am - 03:30pm</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-box justify-content-end">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-2.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-3.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-4.png') }}" alt="image">
                                            </div>
                                            <div class="avatar bg-primary rounded-circle d-flex justify-content-center align-items-center text-white">6</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="activity-box">
                                            <div class="date-box">
                                                <span>14</span>
                                                <span>Feb</span>
                                            </div>
                                            <div class="part-txt">
                                                <span>Meeting for campaign with sales team</span>
                                                <span>12:00am - 03:30pm</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-box justify-content-end">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-2.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-3.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-4.png') }}" alt="image">
                                            </div>
                                            <div class="avatar bg-primary rounded-circle d-flex justify-content-center align-items-center text-white">6</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="activity-box">
                                            <div class="date-box">
                                                <span>14</span>
                                                <span>Feb</span>
                                            </div>
                                            <div class="part-txt">
                                                <span>Meeting for campaign with sales team</span>
                                                <span>12:00am - 03:30pm</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-box justify-content-end">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-2.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-3.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-4.png') }}" alt="image">
                                            </div>
                                            <div class="avatar bg-primary rounded-circle d-flex justify-content-center align-items-center text-white">6</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="activity-box">
                                            <div class="date-box">
                                                <span>14</span>
                                                <span>Feb</span>
                                            </div>
                                            <div class="part-txt">
                                                <span>Meeting for campaign with sales team</span>
                                                <span>12:00am - 03:30pm</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-box justify-content-end">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-2.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-3.png') }}" alt="image">
                                            </div>
                                            <div class="avatar">
                                                <img src="{{ asset('modules/admin/images/avatar-4.png') }}" alt="image">
                                            </div>
                                            <div class="avatar bg-primary rounded-circle d-flex justify-content-center align-items-center text-white">6</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Pending Works</h5>
                </div>
                <div class="panel-body p-0">
                    <table class="table table-hover pending-task-table" tabindex="1">
                        <tr>
                            <td>
                                <div class="task-box">
                                    <span>Database tools</span>
                                    <span>Jul 25, 2017 for Alimul Alrazy</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-end">
                                    <span class="badge bg-primary px-2">Processing</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="task-box">
                                    <span>Technologycal tools</span>
                                    <span>Jul 25, 2017 for Alimul Alrazy</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-end">
                                    <span class="badge bg-success px-2">Completed</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="task-box">
                                    <span>Transaction</span>
                                    <span>Jul 25, 2017 for Alimul Alrazy</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-end">
                                    <span class="badge bg-danger px-2">On hold</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="task-box">
                                    <span>Training tools</span>
                                    <span>Jul 25, 2017 for Alimul Alrazy</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-end">
                                    <span class="badge bg-primary px-2">Processing</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="task-box">
                                    <span>Private chat module</span>
                                    <span>Jul 25, 2017 for Alimul Alrazy</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-end">
                                    <span class="badge bg-success px-2">Completed</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="task-box">
                                    <span>Appointment booking with</span>
                                    <span>Jul 25, 2017 for Alimul Alrazy</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-end">
                                    <span class="badge bg-primary px-2">Processing</span>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7">
            <div class="panel">
                <div class="panel-header">
                    <h5>Invoices</h5>
                    <a class="btn btn-sm btn-primary" href="#">View All</a>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table invoice-table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Client</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#INV-0001</td>
                                    <td>Hazel Nutt</td>
                                    <td>9 Aug 2018</td>
                                    <td>$240</td>
                                    <td>
                                        <span class="d-block text-end">
                                            <span class="badge bg-primary px-2">Partially Paid</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#INV-0002</td>
                                    <td>Hazel Nutt</td>
                                    <td>9 Aug 2018</td>
                                    <td>$240</td>
                                    <td>
                                        <span class="d-block text-end">
                                            <span class="badge bg-success px-2">Paid</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#INV-0003</td>
                                    <td>Hazel Nutt</td>
                                    <td>9 Aug 2018</td>
                                    <td>$240</td>
                                    <td>
                                        <span class="d-block text-end">
                                            <span class="badge bg-primary px-2">Partially Paid</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#INV-0004</td>
                                    <td>Hazel Nutt</td>
                                    <td>9 Aug 2018</td>
                                    <td>$240</td>
                                    <td>
                                        <span class="d-block text-end">
                                            <span class="badge bg-success px-2">Paid</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#INV-0005</td>
                                    <td>Hazel Nutt</td>
                                    <td>9 Aug 2018</td>
                                    <td>$240</td>
                                    <td>
                                        <span class="d-block text-end">
                                            <span class="badge bg-primary px-2">Partially Paid</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#INV-0006</td>
                                    <td>Hazel Nutt</td>
                                    <td>9 Aug 2018</td>
                                    <td>$240</td>
                                    <td>
                                        <span class="d-block text-end">
                                            <span class="badge bg-success px-2">Paid</span>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <h5>My Tasks</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add Task <i class="fa-light fa-plus"></i></button>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table task-table table-hover">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Web design & development
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Logo design
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Meeting with client
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Laravel devloper interview
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Client support
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Factory visit
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Landing page design
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input me-2" type="checkbox">
                                                Important meeting
                                            </label>
                                        </div>
                                    </td>
                                    <td>15 Sep, 2022</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="btn-box px-lg-3 px-2 mx-xl-1 m-lg-0 mx-1 py-2">
                        <a href="#" class="view-all-task text-white fs-14 text-decoration-underline">Show More</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Notice Board</h5>
                    <a class="btn btn-sm btn-primary" href="#">View All</a>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table notice-board-table table-hover">
                            <thead>
                                <tr>
                                    <th>Notice</th>
                                    <th>Published By</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>New Notice</td>
                                    <td>Mr. Alrazy</td>
                                    <td>20th April 2020</td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Notice</td>
                                    <td>Mr. Alrazy</td>
                                    <td>20th April 2020</td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Notice</td>
                                    <td>Mr. Alrazy</td>
                                    <td>20th April 2020</td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Notice</td>
                                    <td>Mr. Alrazy</td>
                                    <td>20th April 2020</td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Notice</td>
                                    <td>Mr. Alrazy</td>
                                    <td>20th April 2020</td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Notice</td>
                                    <td>Mr. Alrazy</td>
                                    <td>20th April 2020</td>
                                    <td>
                                        <div class="btn-box d-flex justify-content-end gap-3">
                                            <button class="btn-flush"><i class="fa-light fa-eye"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-pen"></i></button>
                                            <button class="btn-flush"><i class="fa-light fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Works Deadlines</h5>
                    <a class="btn btn-sm btn-primary" href="#">View All</a>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table deadline-table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Last Contacted</th>
                                    <th>Sales Representative</th>
                                    <th>Status</th>
                                    <th>Deal Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Absternet LLC</td>
                                    <td>Sep 20, 2021</td>
                                    <td>Donald Risher</td>
                                    <td><span class="badge bg-primary-subtle px-2 rounded">Deal Won</span></td>
                                    <td>125K</td>
                                </tr>
                                <tr>
                                    <td>Absternet LLC</td>
                                    <td>Sep 20, 2021</td>
                                    <td>Donald Risher</td>
                                    <td><span class="badge bg-primary-subtle px-2 rounded">Deal Won</span></td>
                                    <td>125K</td>
                                </tr>
                                <tr>
                                    <td>Absternet LLC</td>
                                    <td>Sep 20, 2021</td>
                                    <td>Donald Risher</td>
                                    <td><span class="badge bg-primary-subtle px-2 rounded">Deal Won</span></td>
                                    <td>125K</td>
                                </tr>
                                <tr>
                                    <td>Absternet LLC</td>
                                    <td>Sep 20, 2021</td>
                                    <td>Donald Risher</td>
                                    <td><span class="badge bg-primary-subtle px-2 rounded">Deal Won</span></td>
                                    <td>125K</td>
                                </tr>
                                <tr>
                                    <td>Absternet LLC</td>
                                    <td>Sep 20, 2021</td>
                                    <td>Donald Risher</td>
                                    <td><span class="badge bg-primary-subtle px-2 rounded">Deal Won</span></td>
                                    <td>125K</td>
                                </tr>
                                <tr>
                                    <td>Absternet LLC</td>
                                    <td>Sep 20, 2021</td>
                                    <td>Donald Risher</td>
                                    <td><span class="badge bg-primary-subtle px-2 rounded">Deal Won</span></td>
                                    <td>125K</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::admin-layout>

<!-- Add Task Modal Start -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="addTaskModalLabel">Add New Task</h1>
                <button type="button" class="btn btn-sm btn-icon btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"><i class="fa-light fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Start Date</label>
                        <input type="text" class="form-control form-control-sm date-picker" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Priority</label>
                        <select class="form-control form-control-sm">
                            <option value="0">Low</option>
                            <option value="1">Medium</option>
                            <option value="2">High</option>
                            <option value="3">Urgent</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control form-control-sm" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Add Task Modal End -->