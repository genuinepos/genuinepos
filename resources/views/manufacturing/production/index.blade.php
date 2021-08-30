@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/backend/asset/css/select2.min.css"/>
@endpush
@section('title', 'All Process - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul>
                                        <li>
                                            <a href="{{ route('manufacturing.process.index') }}" class="text-white"><i class="fas fa-dumpster-fire"></i> <b>Process</b></a>
                                        </li>

                                        <li>
                                            <a href="{{ route('manufacturing.productions.index') }}" class="text-white"><i class="fas fa-shapes text-primary"></i> <b>Production</b></a>
                                        </li>
                                     
                                        <li>
                                            <a href="{{ route('manufacturing.settings.index') }}" class="text-white"><i class="fas fa-sliders-h"></i> <b>Settings</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-file-alt"></i> <b>Manufacturing Report</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-name">
                                <div class="col-md-12">
                                    <form action="" method="get" class="px-2">
                                        <div class="form-group row">
                                            @if ($addons->branches == 1)
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-3">
                                                        <label><strong>Business Location :</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control submit_able" id="branch_id" autofocus>
                                                            <option value="">All</option>
                                                            <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            @endif
                                            
                                            <div class="col-md-3">
                                                <label><strong>Date Range :</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input readonly type="text" name="date_range" id="date_range"
                                                        class="form-control daterange submit_able_input"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6"><h6>Productions</h6></div>

                                    @if (auth()->user()->permission->manufacturing['menuf_add'] == '1') 
                                        <div class="col-md-6">
                                            <div class="btn_30_blue float-end">
                                                <a href="{{ route('manufacturing.productions.create') }}"><i class="fas fa-plus-square"></i> Add</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
    
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <form id="update_product_cost_form" action="">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th data-aSortable="false">
                                                            <input class="all" type="checkbox" name="all_checked"/>
                                                        </th>
                                                        <th class="text-black">Actions</th>
                                                        <th class="text-black">Date</th>
                                                        <th class="text-black">Business Location</th>
                                                        <th class="text-black">Product</th>
                                                        <th class="text-black">Quantity</th>
                                                        <th class="text-black">Total Cost</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
    
                                @if (auth()->user()->permission->manufacturing['menuf_delete'] == '1')
                                    <form id="deleted_form" action="" method="post">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
   
@endpush