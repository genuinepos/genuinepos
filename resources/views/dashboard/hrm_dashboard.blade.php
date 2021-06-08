@extends('layout.master')
@section('title', 'HRM Dashboard - ')
    @push('stylesheets')
        <style>
            .leave_application table.display thead th {
                padding: 0px 10px 0px 10px;
                border-top: none;
                border-bottom: none;
            }

            .leave_application .dataTables_wrapper {
                border-bottom: none;
                border-top: none;
                -webkit-box-shadow: none;
            }

            /* .leave_application .dataTables_filter {
                                width: 95% !important;
                            }

                            .leave_application .dataTables_filter input {
                                width: 100% !important;
                            } */

        </style>
    @endpush

@section('content')
    <section class="mt-5 pt-2">
        <div class="card pt-3 px-4 mt-1">
            <div class="card-title mt-4 ps-4">
                <h1 class="text-start text-primary pl-5">
                    <i class="fas fa-anchor"></i>
                    <span class="">HRM</span> Dashboard
                </h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form_element">
                            <div class="section-header d-flex justify-content-between align-items-center px-3">
                                <h6>
                                    <span class="fas fa-users"></span>
                                    Users
                                </h6>
                                <p class="">
                                    <span class="badge bg-secondary text-white">
                                        4324
                                    </span>
                                </p>
                            </div>
                            <div class="widget_content">
                                <div class="mtr-table">
                                    <div class="table-responsive">
                                        <table id="attendance_table"
                                            class="display data__table data_tble stock_table compact" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Department</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Branch Manger</td>
                                                    <td>125</td>
                                                </tr>
                                                <tr>
                                                    <td>HRM</td>
                                                    <td>23</td>
                                                </tr>
                                                <tr>
                                                    <td>CRM</td>
                                                    <td>15</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>215</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>215</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>215</td>
                                                </tr>
                                                <tr>
                                                    <td>HRM</td>
                                                    <td>23</td>
                                                </tr>
                                                <tr>
                                                    <td>CRM</td>
                                                    <td>15</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>215</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>215</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>215</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form_element">
                            <div class="section-header d-flex justify-content-between align-items-center px-3">
                                <h6>
                                    <span class="fas fa-user-check"></span>
                                    Today's Attendance
                                </h6>
                                {{-- <h6 class="">4324</h6> --}}
                            </div>
                            <div class="widget_content">
                                <div class="mtr-table">
                                    <div class="table-responsive">
                                        <table id="users_table" class="display data__table data_tble stock_table compact"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Clock-in Time</th>
                                                    <th>Clock-out Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form_element">
                            <div class="section-header d-flex justify-content-between align-items-center px-3">
                                <h6>
                                    <span class="far fa-file-alt"></span>
                                    Leave Applications
                                </h6>
                            </div>
                            <div class="widget_content">
                                <div class="mtr-table">
                                    <div class="table-responsive leave_application">
                                        <table id="leave_application_table"
                                            class="display data__table data_tble stock_table compact " width="100%">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        {{-- Application Links --}}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="mx-2 mt-5">
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            James Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@push('scripts')
    <script>
        const usersTable = $('#users_table').DataTable({
            dom: "Bfrtip",
            buttons: ["excel", "pdf", "print"],
            pageLength: 5,
        });

        const attendanceTable = $('#attendance_table').DataTable({
            dom: "Bfrtip",
            buttons: ["excel", "pdf", "print"],
            pageLength: 5,
        });
        const leaveApplicationTable = $('#leave_application_table').DataTable({
            dom: "Bfrtip",
            pageLength: 5,
            ordering: false,
            info: false,
            // searching: false,
        });

    </script>
@endpush
