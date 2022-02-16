@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Cash Flow Statements - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">

                        <div class="sec-name">
                            <div class="name-head">
                                <span class="far fa-money-bill-alt"></span>
                                <h5>Cash Flow Statements</h5>
                            </div>

                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <form id="filter_cash_flow" action="{{ route('accounting.filter.cash.flow') }}" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="f_branch_id" autofocus>
                                                                <option SELECTED value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>From Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>To Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mt-3">
                                                            <a href="#" class="btn btn-sm btn-primary float-end " id="print_report"><i class="fas fa-print "></i> Print</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">

                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>All Cash Flow Statements</h6>
                                </div>
                            </div>

                            <div class="widget_content mt-2">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="table modal-table table-sm table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="aiability_area">
                                                    <table class="table table-sm">
                                                        <tbody>
                                                            {{-- Cash Flow from operations --}}
                                                            <tr>
                                                                <th class="text-start" colspan="2">
                                                                    <strong>CASH FLOW FROM OPERATIONS :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                   <em>Net Profit Before Tax :</em> 
                                                                </td>

                                                                <td class="text-start">
                                                                   <em>0.00</em> 
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                   <em>Customer Balance : </em>  
                                                                </td>

                                                                <td class="text-start">
                                                                     <em>- 0.00</em>    
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                   <em>Current Stock Value : </em> 
                                                                </td>

                                                                <td class="text-start">
                                                                    <em>0.00</em>    
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>Current Asset :</em>  
                                                                </td>

                                                                <td class="text-start">
                                                                     <em>0.00</em>    
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                   <em>Current Liability :</em>  
                                                                </td>

                                                                <td class="text-start">
                                                                    <em>0.00</em>    
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                   <em>Tax Payable :</em>  
                                                                </td>

                                                                <td class="text-start">
                                                                    <em>0.00</em>     
                                                                </td>
                                                            </tr>

                                                            <tr class="bg-info">
                                                                <td class="text-start text-white">
                                                                    <b>Total Operations : </b>  
                                                                </td>

                                                                <td class="text-start text-white">
                                                                    <b>0.00</b>  
                                                                </td>
                                                            </tr>
                                                        
                                                            {{-- Cash Flow from investing --}}
                                                            <tr>
                                                                <th class="text-start" colspan="2">
                                                                    <strong>CASH FLOW FROM INVESTING :</strong>
                                                                </th>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>FIXED ASSET :</em> 
                                                                </td>
                                                                <td class="text-start">0.00</td>
                                                            </tr>

                                                            <tr class="bg-info">
                                                                <td class="text-start text-white">
                                                                    <b><em>Total Investing :</em>  </b>  
                                                                </td>

                                                                <td class="text-start text-white">
                                                                    <b><em>0.00</em> </b>  
                                                                </td>
                                                            </tr> 

                                                            {{-- Cash Flow from financing --}}
                                                            <tr>
                                                                <th class="text-start" colspan="2">
                                                                    <strong>CASH FLOW FROM FINANCING :</strong>
                                                                </th>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>Capital A/C :</em> 
                                                                </td>
                                                                <td class="text-start">0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>Loan And Advance :</em> 
                                                                </td>
                                                                <td class="text-start">0.00</td>
                                                            </tr>

                                                            <tr class="bg-info">
                                                                <td class="text-start text-white">
                                                                    <b><em>Total financing :</em>  </b>  
                                                                </td>

                                                                <td class="text-start text-white">
                                                                    <b><em>0.00</em> </b>  
                                                                </td>
                                                            </tr> 
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="bg-secondary">
                                                                <th class="text-start text-white"><strong>Total Cash Flow : ({{ json_decode($generalSettings->business, true)['currency'] }} )</strong> </th>
                                                                <th class="text-start text-white">
                                                                    <span class="total_cash_flow">0.00</span>
                                                                </th>    
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    function getCashFlow() {
       $('.data_preloader').show();
       $.ajax({
           url:"{{ route('accounting.all.cash.flow') }}",
           success:function(data){
               $('#data-list').html(data);
               $('.data_preloader').hide();
           }
       });
    }
    getCashFlow();

    // //Print purchase Payment report
    // $(document).on('click', '#print_report', function (e) {
    //     e.preventDefault();
    //     var url = "{{ route('accounting.print.cash.flow') }}";
    //     var transaction_type = $('#transaction_type').val();
    //     var from_date = $('.from_date').val();
    //     var to_date = $('.to_date').val();
    //     $.ajax({
    //         url:url,
    //         type:'get',
    //         data: {transaction_type, from_date, to_date},
    //         success:function(data) {
    //             $(data).printThis({
    //                 debug: false,
    //                 importCSS: true,
    //                 importStyle: true,
    //                 loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",
    //                 removeInline: false,
    //                 printDelay: 700,
    //                 header: null,
    //             });
    //         }
    //     });
    // });
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker2'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY',
    });
</script>
@endpush
