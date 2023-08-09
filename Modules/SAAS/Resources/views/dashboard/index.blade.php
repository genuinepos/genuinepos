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
