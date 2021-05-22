@extends('layout.master')
@section('content')

<br><br><br>
{{-- @php 
    $categories=DB::table('categories')->get();
    $brands=DB::table('brands')->get();
    $products=DB::table('products')->get();
    $suppliers=DB::table('suppliers')->get();
    $customers=DB::table('customers')->get();
    $branches=DB::table('branches')->get();
    $bank_accounts=DB::table('accounts')->get();
    $warehouses=DB::table('warehouses')->get();

    $latest_sale=DB::table('sales')->join('branches','sales.branch_id','branches.id')->select('branches.name','sales.*')->where('sales.status',1)->orderBy('sales.id','DESC')->limit(9)->get();

    $today_sale=DB::table('sales')->where('date',date('d-m-Y'))->sum('total_payable_amount');
    $total_sale=DB::table('sales')->sum('paid');

    $today_due=DB::table('sales')->where('date',date('d-m-Y'))->sum('due');
    $total_due=DB::table('sales')->sum('due');

    $today_purchase=DB::table('purchases')->where('date',date('d-m-Y'))->sum('total_purchase_amount');
    $total_purchase=DB::table('purchases')->sum('total_purchase_amount');
    $today_expense=DB::table('expanses')->where('date',date('d-m-Y'))->sum('net_total_amount');
    $total_expense=DB::table('expanses')->sum('net_total_amount');

    $recent_customer=DB::table('customers')->orderBy('id','DESC')->limit(8)->get();
    $product_qty=DB::table('products')->join('units','products.unit_id','units.id')->select('products.*','units.code_name')->orderBy('quantity','ASC')->limit(10)->get();
@endphp




    <div class="content d-flex flex-column flex-column-fluid mt-4" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Dashboard</h5>

                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <a href="#" class="btn btn-clean btn-sm font-weight-bold font-size-base mr-1">Today</a>
                    <a href="#" class="btn btn-clean btn-sm font-weight-bold font-size-base mr-1">Month</a>
                    <a href="#" class="btn btn-clean btn-sm font-weight-bold font-size-base mr-1">Year</a>
                    <!--end::Actions-->
                    <!--begin::Daterange-->
                    <a href="#" class="btn btn-sm btn-light font-weight-bold mr-2" id="kt_dashboard_daterangepicker" data-toggle="tooltip" title="Select dashboard daterange" data-placement="left">
                        <span class="text-muted font-size-base font-weight-bold mr-2" id="kt_dashboard_daterangepicker_title">Today</span>
                        <span class="text-primary font-size-base font-weight-bolder" id="kt_dashboard_daterangepicker_date">Aug 16</span>
                    </a>
                    <!--end::Daterange-->

                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <!-- Golmenu area -->
            <div class="menu_popup_area">
                <div class="menu_close">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="" id="close_popup_btn" class="ml-4"><i class="fas fa-times text-muted"></i></a>
                        </div>
                    </div>
                </div>
                <div class="menu_list_area">
                    
                </div>
            </div>
            <!-- Golmenu area end-->
            <div class="container">
                <!--begin::Dashboard-->
                <!--begin::Row-->
                <div class="row mt-5 pt-5"></div>
                <div class="row mt-5 pt-5"></div>
                <div class="row">
                    <div class="col-lg-6 col-xxl-4">
                        <!--begin::Mixed Widget 1-->
                        <div class="card card-custom bg-gray-100 card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 bg-info py-5">
                                <h3 class="card-title font-weight-bolder text-white">Today's Sales Reports</h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body p-0 position-relative overflow-hidden">
                                <!--begin::Chart-->
                                <div id="kt_mixed_widget_1_chart" class="card-rounded-bottom bg-info" style="height:10px">
                                    
                                </div>
                                <!--end::Chart-->
                                <!--begin::Stats-->
                                <div class="card-spacer">
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col bg-light-success px-6 py-8 rounded-xl mr-7 mb-7">
                                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                               <div class="font-weight-bolder font-size-h3"> {{ $today_sale }} {{ json_decode($generalSettings->business, true)['currency'] }} </div>
                                            </span>
                                            <a href="#" class="text-success font-weight-bold font-size-h6">Today Sales</a>
                                        </div>
                                        <div class="col bg-light-success px-6 py-8 rounded-xl mb-7">
                                            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                               <div class="font-weight-bolder font-size-h3">{{ $total_sale }} {{ json_decode($generalSettings->business, true)['currency'] }}</div>
                                            </span>
                                            <a href="#" class="text-success font-weight-bold font-size-h6 mt-2">Total Sale</a>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col bg-light-info px-6 py-8 rounded-xl mr-7 mb-7">
                                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                               <div class="font-weight-bolder font-size-h3"> {{ $today_purchase }} {{ json_decode($generalSettings->business, true)['currency'] }} </div>
                                            </span>
                                            <a href="#" class="text-info font-weight-bold font-size-h6">Today Purchase</a>
                                        </div>
                                        <div class="col bg-light-info px-6 py-8 rounded-xl mb-7">
                                            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                               <div class="font-weight-bolder font-size-h3">{{ $total_purchase }} {{ json_decode($generalSettings->business, true)['currency'] }}</div>
                                            </span>
                                            <a href="#" class="text-info font-weight-bold font-size-h6 mt-2">Total Purchase</a>
                                        </div>
                                    </div>
                                    <!--end::Row-->

                                     <div class="row m-0">
                                        <div class="col bg-light-warning px-6 py-8 rounded-xl mr-7 mb-7">
                                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                               <div class="font-weight-bolder font-size-h3">{{ $today_due }} {{ json_decode($generalSettings->business, true)['currency'] }}</div>
                                            </span>
                                            <a href="#" class="text-warning font-weight-bold font-size-h6">Today Due</a>
                                        </div>
                                        <div class="col bg-light-warning px-6 py-8 rounded-xl mb-7">
                                            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                                <div class="font-weight-bolder font-size-h3">{{ $total_due }} {{ json_decode($generalSettings->business, true)['currency'] }}</div>
                                            </span>
                                            <a href="#" class="text-warning font-weight-bold font-size-h6 mt-2">Total Due</a>
                                        </div>
                                    </div>
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col bg-light-danger px-6 py-8 rounded-xl mr-7">
                                            <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                                <div class="font-weight-bolder font-size-h3">{{ $today_expense }} {{ json_decode($generalSettings->business, true)['currency'] }}</div>
                                            </span>
                                            <a href="#" class="text-danger font-weight-bold font-size-h6 mt-2">Today Expense</a>
                                        </div>
                                        <div class="col bg-light-danger px-6 py-8 rounded-xl">
                                            <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                                                <div class="font-weight-bolder font-size-h3">{{ $total_expense }} {{ json_decode($generalSettings->business, true)['currency'] }}</div>
                                            </span>
                                            <a href="#" class="text-danger font-weight-bold font-size-h6 mt-2">Total Expense</a>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Mixed Widget 1-->
                    </div>
                    <div class="col-lg-6 col-xxl-4">
                        <!--begin::List Widget 9-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-4">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="font-weight-bolder text-dark">Latest Success Sale</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-4">
                                <!--begin::Timeline-->
                                <div class="timeline timeline-6 mt-3">
                                   
                                    @foreach($latest_sale as $row)
                                    <!--begin::Item-->
                                    <div class="timeline-item align-items-start">
                                        <!--begin::Label-->
                                        <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">{{ $row->name }}</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="fa fa-genderless text-success icon-xl"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Desc-->
                                        <div class="timeline-content font-weight-bolder font-size-lg text-dark-75 pl-3">Invoice( {{ $row->invoice_id }}) | amount-  
                                        <a href="#" class="text-primary">  {{ $row->total_payable_amount }} {{ json_decode($generalSettings->business, true)['currency'] }}</a></div>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Item-->
                                    @endforeach
                                  
                                </div>
                                <!--end::Timeline-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                        <!--end: List Widget 9-->
                    </div>
                    <div class="col-lg-6 col-xxl-4">
                        <div class="row">
                            <div class="col-xl-6">
                                <!--begin::Tiles Widget 2-->
                                <div class="card card-custom bg-success gutter-b" style="height: 130px">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column p-0" style="position: relative;">
                                        <!--begin::Stats-->
                                        <div class="flex-grow-1 card-spacer-x pt-6">
                                            <div class="text-inverse-success font-weight-bold">Total categories</div>
                                            <div class="text-inverse-success font-weight-bolder font-size-h3">{{ count($categories) }}</div>
                                        </div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Tiles Widget 2-->
                               <div class="card card-custom bg-warning gutter-b" style="height: 130px">
                                   <!--begin::Body-->
                                   <div class="card-body d-flex flex-column p-0" style="position: relative;">
                                       <!--begin::Stats-->
                                       <div class="flex-grow-1 card-spacer-x pt-6">
                                           <div class="text-inverse-warning font-weight-bold">Total Brands</div>
                                           <div class="text-inverse-warning font-weight-bolder font-size-h3">{{ count($brands) }}</div>
                                       </div>
                                   </div>
                                   <!--end::Body-->
                               </div>
                            </div>
                            <div class="col-xl-6">
                                <!--begin::Tiles Widget 4-->
                                <div class="card card-custom gutter-b" style="height: 130px">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column">
                                        <!--begin::Stats-->
                                        <div class="flex-grow-1">
                                            <div class="text-dark-50 font-weight-bold">Total Products</div>
                                            <div class="font-weight-bolder font-size-h3">{{ count($products) }}</div>
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Tiles Widget 4-->
                                <!--begin::Tiles Widget 5-->
                                <div class="card card-custom bg-info gutter-b" style="height: 130px">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column p-0" style="position: relative;">
                                        <!--begin::Stats-->
                                        <div class="flex-grow-1 card-spacer-x pt-6">
                                            <div class="text-inverse-info font-weight-bold">Total Suppliers</div>
                                            <div class="text-inverse-info font-weight-bolder font-size-h3">{{ count($suppliers) }}</div>
                                        </div>
                                        <!--end::Stats-->
                                    <div class="resize-triggers"><div class="expand-trigger"><div style="width: 196px; height: 131px;"></div></div><div class="contract-trigger"></div></div></div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Tiles Widget 5-->
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-xl-6">
                                begin::Tiles Widget 2
                                <div class="card card-custom bg-danger gutter-b" style="height: 130px">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column p-0" style="position: relative;">
                                        <!--begin::Stats-->
                                        <div class="flex-grow-1 card-spacer-x pt-6">
                                            <div class="text-inverse-danger font-weight-bold">Total Customers</div>
                                            <div class="text-inverse-danger font-weight-bolder font-size-h3">{{ count($customers) }}</div>
                                        </div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <!--begin::Tiles Widget 4-->
                                <div class="card card-custom gutter-b" style="height: 130px">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column">
                                        <!--begin::Stats-->
                                        <div class="flex-grow-1">
                                            <div class="text-dark-50 font-weight-bold">Total Branch</div>
                                            <div class="font-weight-bolder font-size-h3">{{ count($branches) }}</div>
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Tiles Widget 4--> 
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-xl-6">
                                <!--begin::Tiles Widget 2-->
                                <div class="card card-custom bg-success gutter-b" style="height: 130px">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column p-0" style="position: relative;">
                                        <!--begin::Stats-->
                                        <div class="flex-grow-1 card-spacer-x pt-6">
                                            <div class="text-inverse-success font-weight-bold">Bank Accounts</div>
                                            <div class="text-inverse-success font-weight-bolder font-size-h3">{{ count($bank_accounts) }}</div>
                                        </div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <!--begin::Tiles Widget 4-->
                                <div class="card card-custom bg-primary gutter-b" style="height: 130px">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column">
                                        <!--begin::Stats-->
                                        <div class="flex-grow-1">
                                            <div class="text-inverse-success text-white-50 font-weight-bold">Warehouse</div>
                                            <div class="text-inverse-success font-size-h3">{{ count($warehouses) }}</div>
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Tiles Widget 4--> 
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bolder text-dark">Recent Customers</h3>
                                <h3 class="card-title font-weight-bolder text-dark" style="float: right;">Opening Balance</h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-2">
                                @foreach($recent_customer as $row)
                                <!--begin::Item-->
                                <div class="d-flex align-items-center mb-10">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40 symbol-light-success mr-5">
                                        <span class="symbol-label">
                                            <img src="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/media/svg/avatars/009-boy-4.svg" class="h-75 align-self-end" alt="" />
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Text-->
                                    <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                        <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{ $row->name }}</a>
                                        <span class="text-muted">{{ $row->phone }}</span>
                                    </div>
                                    <!--end::Text-->
                                    <!--begin::Dropdown-->
                                    <div class="dropdown dropdown-inline ml-2" data-placement="left">
                                        
                                       {{ $row->opening_balance }} {{ json_decode($generalSettings->business, true)['currency'] }}
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <!--end::Item-->
                               @endforeach 
                            </div>
                            <!--end::Body-->
                        </div>
                     
                    </div>
                    <div class="col-xxl-8 order-2 order-xxl-1">
                        <!--begin::Advance Table Widget 2-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Stock Alert</span>
                                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Product Stock ALert</span>
                                </h3>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <div class="tab-content mt-5" id="myTabTables11">
                                    <div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
                                        <!--begin::Table-->
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-vertical-center">
                                                <thead>
                                                    <tr>
                                                        <th class="p-0"></th>
                                                        <th class="p-0 ">Product Name</th>
                                                        <th class="p-0 ">Unit Price</th>
                                                        <th class="p-0 ">Quantity</th>
                                                        <th class="p-0 ">Stock Alert</th>
                                                      
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($product_qty as $row)
                                                    <tr>
                                                        <td class="pl-0 py-4">
                                                            <div class="symbol symbol-50 symbol-light mr-1">
                                                                <span class="symbol-label">
                                                                    <img src="public/uploads/product/thumbnail/{{ $row->thumbnail_photo }}" class="h-50 align-self-center" alt="" />
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="pl-0">
                                                            <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $row->name }}</a>
                                                            <div>
                                                                <span class="font-weight-bolder">code: </span>
                                                                <a class="text-muted font-weight-bold text-hover-primary" href="#">{{ $row->product_code }}</a>
                                                            </div>
                                                        </td>
                                                        <td class="pl-0">
                                                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $row->product_price }} {{ json_decode($generalSettings->business, true)['currency'] }}</span>
                                                        </td>
                                                        <td class="pl-0">
                                                            <span class="text-dark font-weight-500">{{ $row->quantity }} {{ $row->code_name }}</span>
                                                        </td>
                                                        <td class="pl-0">
                                                            <span class="label label-lg label-light-danger label-inline">{{ $row->alert_quantity }} {{ $row->code_name }}</span>
                                                        </td>
                                          
                                                    </tr>
                                                    @endforeach
                                                        
                                                </tbody>

                                            </table>

                                        </div>
                                    
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Tap pane-->
                                </div>
                            </div>
                            <!--end::Body-->

                        </div>
                        <!--end::Advance Table Widget 2-->

                    </div>
                    

                <!--end::Dashboard-->
            </div>
            <!--end::Container-->

            <div class="row">
                <div class="col-xl-6 col-lg-6 card">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Sales payment due</span>
                        </h3>
                        <div class="card-toolbar">
                        </div>
                    </div>
                   <!--begin::Body-->
                   <div class="card-body pt-2 pb-0 mt-n3">
                       <div class="tab-content mt-5" id="myTabTables11">
                           <div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
                               <!--begin::Table-->
                               <div class="table-responsive">
                                   <table class="table table-borderless table-vertical-center">
                                       <thead>
                                           <tr>
                                             
                                               <th class="p-0 ">Customer</th>
                                               <th class="p-0 ">Invoice</th>
                                               <th class="p-0 ">Due</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                       
                                           <tr>    
                                               <td class="pl-0">
                                                   <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Example Name</a>
                                               </td>
                                               <td class="pl-0">
                                                   <span class="text-dark-75 font-weight-bolder d-block font-size-lg">1198111</span>
                                               </td>
                                               <td class="pl-0">
                                                   <span class="label label-lg label-light-danger label-inline">89293.00</span>
                                               </td>
                                           </tr>
                                       </tbody>

                                   </table>

                               </div>
                           
                               <!--end::Table-->
                           </div>
                           <!--end::Tap pane-->
                       </div>
                   </div>
                   <!--end::Body-->
                </div>
                <div class="col-xl-6 col-lg-6 card">
                   <!--begin::Body-->
                   <div class="card-header border-0 pt-5">
                       <h3 class="card-title align-items-start flex-column">
                           <span class="card-label font-weight-bolder text-dark">Purchase payment due</span>
                       </h3>
                       <div class="card-toolbar">
                       </div>
                   </div>
                   <div class="card-body pt-2 pb-0 mt-n3">
                       <div class="tab-content mt-5" id="myTabTables11">
                           <div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
                               <!--begin::Table-->
                               <div class="table-responsive">
                                   <table class="table table-borderless table-vertical-center">
                                       <thead>
                                           <tr>
                                               <th class="p-0 ">Customer</th>
                                               <th class="p-0 ">Reference Number</th>
                                               <th class="p-0 ">Due</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <tr>
                                               <td class="pl-0">
                                                   <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Example Name</a>
                                               </td>
                                               <td class="pl-0">
                                                   <span class="text-dark-75 font-weight-bolder d-block font-size-lg">1919119912</span>
                                               </td>
                                               <td class="pl-0">
                                                    <span class="label label-lg label-light-danger label-inline">89293.00</span>
                                               </td>
                                           </tr>    
                                       </tbody>
                                   </table>
                               </div>
                           
                               <!--end::Table-->
                           </div>
                           <!--end::Tap pane-->
                       </div>
                   </div>
                   <!--end::Body-->
                </div>
            </div>
            <br>
            <div class="container card pb-5 mb-5">
                <div class="card-body">
                    <h1>Sales Graphs</h1>

                   
                </div>
            </div>
        </div>
        <!--end::Entry-->
    </div>  --}}
               <div class="body-woaper">
                <div class="container-fluid">
                    <div class="row">
                        <div class=" border-class">
                            <div class="main__content">
                                <!-- =====================================================================BODY CONTENT================== -->
                                <div class="sec-name">
                                    <div class="name-head">
                                        <span class="fas fa-desktop"></span>
                                        <h5>Dashboard</h5>
                                    </div>
                                    <div class="search-input">
                                        <form action="">
                                            <div class="input-group">
                                                <div class="form-outline col-sm-10">
                                                    <input id="search-input" type="search" placeholder="Search" class=" rounded-start form-control search-bar" />
                                                </div>
                                                <button id="search-button" type="button" class="search-button col-sm-2  rounded-end">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- =========================================top section button=================== -->
                            <div class="main-button-sec">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/chart.png" alt=""></span>
                                                    <p>
                                                        Analytics
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/note.png" alt=""></span>

                                                    <p>
                                                        Notes
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <span class="notify-grin">30</span>
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/user.png" alt=""></span>

                                                    <p>
                                                        User
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <span class="notify">30</span>
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/setting.png" alt=""></span>

                                                    <p>
                                                        Setting
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/task.png" alt=""></span>

                                                    <p>
                                                        Task list
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <span class="notify-grin">30</span>
                                                <a href="" class="bar-link"> <span>
                                                    <span><img src="{{asset('public')}}/backend/asset/img/archive.png" alt=""></span>
                                                    <p>
                                                        Archive
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/contact.png" alt=""></span>
                                                    <p>
                                                        Contact
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <span class="notify">30</span>
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/exploer.png" alt=""></span>

                                                    <p>
                                                        Explorer
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/folder.png" alt=""></span>

                                                    <p>
                                                        Media
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/event.png" alt=""></span>
                                                    <p>
                                                        Event
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <span class="notify">30</span>
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/bulb.png" alt=""></span>
                                                    <p>
                                                        Support
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                                            <div class="switch_bar">
                                                <a href="" class="bar-link">
                                                    <span><img src="{{asset('public')}}/backend/asset/img/bank.png" alt=""></span>
                                                    <p>
                                                        Order List
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- =========================================top section button end=================== -->

                            <!-- =========================================CHART===================== -->
                            <div class="chart-section">
                                <div class="row">
                                    <div id="chart">
                                    </div>
                                </div>
                            </div>
                            <!-- =================================================================BODY CONTENT END================== -->
                        </div>
                    </div>
                </div>
            </div>
@endsection
