@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_expanse_form" action="{{ route('expanses.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5>Add Expense</h5>
                                            </div>
    
                                            <div class="col-6">
                                                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Voucher :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If you keep this field empty, The Voucher will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                                    <div class="col-8">
                                                        <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Ex Reference No" autofocus>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Ex. A/C :</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select required name="ex_account_id" class="form-control" id="ex_account_id">
                                                            @foreach ($expenseAccounts as $exAc)
                                                                <option value="{{ $exAc->id }}">
                                                                    {{ $exAc->name.' ('.App\Utils\Util::accountType($exAc->account_type).')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                     
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Expanse For :</b></label>
                                                    <div class="col-8">
                                                        <select name="admin_id" class="form-control" id="admin_id">
                                                            <option value="">None</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Attachment :</b> </label>
                                                    <div class="col-8">
                                                        <input type="file" name="attachment" class="form-control ">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Date :</b> <span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input required type="text" name="date" class="form-control changeable"
                                                            value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="datepicker">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0">
                                    <div class="heading_area">
                                        <p class="text-primary m-0 p-0 ps-1"><b>Descriptions</b></p>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="expense_description_table">
                                                    <div class="table-responsive">
                                                        <table class="table modal-table table-sm">
                                                            <tbody id="description_body">
                                                                <tr>
                                                                    <td id="index">
                                                                        <b><span class="serial">1</span></b>
                                                                        <input class="index-1" type="hidden" id="index">
                                                                    </td>
                                                                    <td>
                                                                        <select required name="category_ids[]" class="form-control" id="category_id">
                                                                            <option value="">Select Expense Category</option>
                                                                        </select>
                                                                    </td>
        
                                                                    <td>
                                                                        <input required type="number" name="amounts[]" step="any" class="form-control" id="amount" value="" placeholder="Amount">
                                                                    </td>
        
                                                                    <td>
                                                                        <div class="btn_30_blue" >
                                                                            <a id="addMore" href=""><i class="fas fa-plus-square"></i></a>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</b> </label>
                                                    <div class="col-8">
                                                        <input readonly class="form-control add_input" name="total_amount" type="number" data-name="Total amount" id="total_amount" value="0.00" step="any" placeholder="Total amount">
                                                        <span class="error error_total_amount"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Tax :</b> </label>
                                                    <div class="col-8">
                                                        <select name="tax" class="form-control" id="tax"></select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Net Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</b>  </label>
                                                    <div class="col-8">
                                                        <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @include('expanses.partials.expensePaymentSection')
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Set accounts in payment and payment edit form
        var ex_categories = '';
        function setExpanseCategory(){
            $.ajax({
                url:"{{route('expanses.all.categories')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(categories){
                    ex_categories = categories;
                    $.each(categories, function (key, category) {
                        $('#category_id').append('<option value="'+category.id+'">'+ category.name +' ('+category.code+')'+'</option>');
                    });
                }
            });
        }
        setExpanseCategory();

        // Set accounts in payment and payment edit form
        function setAdmin(){
            $.ajax({
                url:"{{ route('expanses.all.admins') }}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(admins){
                    $.each(admins, function (key, admin) {
                        var prefix = admin.prefix != null ? admin.prefix : '';
                        var last_name = admin.last_name != null ? admin.last_name : '';
                        $('#admin_id').append('<option value="'+admin.id+'">'+prefix+' '+admin.name+' '+last_name+'</option>');
                    });
                }
            });
        }
        setAdmin();

        function getTaxes(){
            $.ajax({
                url:"{{route('purchases.get.all.taxes')}}",
                async:false,
                type:'get',
                dataType: 'json',
                success:function(taxes){
                    taxArray = taxes;
                    $('#tax').append('<option value="">NoTax</option>');
                    $.each(taxes, function(key, val){
                        $('#tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                    });
                }
            });
        }
        getTaxes();

         // Calculate amount
         function calculateAmount() {
            var indexs = document.querySelectorAll('#index');
            indexs.forEach(function(index) {
                var className = index.getAttribute("class");
                var rowIndex = $('.' + className).closest('tr').index();
                $('.' + className).closest('tr').find('.serial').html(rowIndex + 1);
            });

            var amounts = document.querySelectorAll('#amount');
            totalAmount = 0;
            amounts.forEach(function(amount){
                totalAmount += parseFloat(amount.value ? amount.value : 0);
            });

            $('#total_amount').val(parseFloat(totalAmount).toFixed(2));
            var tax_percent = $('#tax').val() ? $('#tax').val() : 0;
            var tax_amount = parseFloat(totalAmount) / 100 * parseFloat(tax_percent);
            var netTotalAmount = parseFloat(totalAmount) + parseFloat(tax_amount); 
            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
            $('#paying_amount').val(parseFloat(netTotalAmount).toFixed(2));
            $('#loan_amount').val(parseFloat(netTotalAmount).toFixed(2));
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var totalDue = parseFloat(netTotalAmount) - parseFloat(payingAmount);
            $('#total_due').val(parseFloat(totalDue).toFixed(2));
        }

        $(document).on('input', '#amount',function () {
            calculateAmount();
        });

        $('#tax').on('change', function () {
            calculateAmount();
        });

        $('#paying_amount').on('input', function () {
            calculateAmount();
        });

        $(document).on('click', '#remove_btn', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateAmount();
        });

        var action = '';
        //Add purchase request by ajax
        $('#add_expanse_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.'); 
                return;
            }
            
            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    if(!$.isEmptyObject(data)){
                        toastr.success('Expense created successfully.'); 
                        $('.loan_amount_field').hide();
                        $('.extra_category').remove();
                        $('#add_expanse_form')[0].reset();
                        calculateAmount();
                        if (action == 'sale_and_print') {
                            $(data).printThis({
                                debug: false,                   
                                importCSS: true,                
                                importStyle: true,          
                                loadCSS: "{{asset('public/assets/css/print/purchase.print.css')}}",                      
                                removeInline: false, 
                                printDelay: 500, 
                                header: null,  
                                footer: null,
                            }); 
                        }
                    }
                },error: function(err) {
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    toastr.error('Please check again all form fields.',
                        'Some thing want wrong.');
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        var index = 1;
        $(document).on('click', '#addMore', function (e) {
           e.preventDefault();
           var html = '';
           html += '<tr class="extra_category">';
            html += '<td>';
            html += '<b><span class="serial">'+(index + 1)+'</span></b>';
            html += '<input class="index-'+(index + 1)+'" type="hidden" id="index">';
            html += '</td>';
            html += '<td>';
            html += '<select required name="category_ids[]" class="form-control">';
            html += '<option value="">Select Expense Category</option>';
                $.each(ex_categories, function (key, val) {
                    html += '<option value="'+val.id+'">'+val.name+' ('+val.code+')'+'</option>';
                });
            html += '</select>';
            html += '</td>';

            html += '<td>';
            html += '<input required type="number" name="amounts[]" step="any" class="form-control" id="amount" value="" placeholder="Amount">';
            html += '</td>';

            html += '<td>';
            html += '<a href="#" class="action-btn c-delete" id="remove_btn"><span class="fas fa-trash "></span></a>';
            html += '</td>';
            html += '</tr>';
            $('#description_body').append(html);
            calculateAmount();
            index++;
        });

        $('#payment_method').on('change', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });

        $('#is_loan').on('change', function () {
            if ($(this).is(':CHECKED', true)) {
                $('.loan_amount_field').show();
            } else {
                $('.loan_amount_field').hide();
            }
        });

        $(document).on('click', '.submit_button',function () {
            action = $(this).data('action');
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
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
            format: _expectedDateFormat,
        });
    </script>
@endpush
