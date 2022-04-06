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
            <form id="edit_expanse_form" action="{{ route('expanses.update', $expense->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Edit Expense</h5>
                                                </div>
    
                                                <div class="col-md-6">
                                                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                                        class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Voucher :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Ex Reference No" value="{{ $expense->invoice_id }}" autofocus>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Expense A/C :</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select required name="ex_account_id" class="form-control" id="ex_account_id">
                                                            @foreach ($expenseAccounts as $exAc)
                                                                <option {{ $exAc->id == $expense->expense_account_id ? 'SELECTED' : '' }} value="{{ $exAc->id }}">
                                                                    {{ $exAc->name.' ('.App\Utils\Util::accountType($exAc->account_type).')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                     
                                            <div class="col-md-6">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Expense Date :</b> </label>
                                                    <div class="col-8">
                                                        <input required type="text" name="date" class="form-control datepicker changeable"
                                                            value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime( $expense->date)) }}" id="datepicker">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Expanse For :</b></label>
                                                    <div class="col-8">
                                                        <select name="admin_id" class="form-control" id="admin_id">
                                                            <option value="">None</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $user->id == $expense->admin_id ? 'SELECTED' : '' }} value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
                                                            @endforeach
                                                        </select>
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
                                                                @foreach ($expense->expense_descriptions as $description)
                                                                    <tr>
                                                                        <td id="index">
                                                                            <b><span class="serial">{{ $loop->index + 1 }}</span></b>
                                                                            <input class="index-{{ $loop->index + 1 }}" type="hidden" id="index">
                                                                            <input type="hidden" name="description_ids[]" id="description_id" value="{{ $description->id }}">
                                                                        </td>
                                                                        <td>
                                                                            <select required name="category_ids[]" class="form-control" id="category_id">
                                                                                <option value="">Select Expense Category</option>
                                                                                @foreach ($categories as $category)
                                                                                    <option {{ $category->id == $description->expense_category_id ? 'SELECTED' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
            
                                                                        <td>
                                                                            <input required type="number" name="amounts[]" step="any" class="form-control" id="amount" placeholder="Amount" value="{{ $description->amount }}">
                                                                        </td>
            
                                                                        <td>
                                                                            @if ($loop->index == 0)
                                                                                <div class="btn_30_blue" >
                                                                                    <a id="addMore" href=""><i class="fas fa-plus-square"></i></a>
                                                                                </div>
                                                                            @else
                                                                                <a href="#" class="action-btn c-delete" id="remove_btn"><span class="fas fa-trash "></span></a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
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
                                                        <select name="tax" class="form-control" id="tax">
                                                            <option value="0.00">NoTax</option>
                                                            @foreach ($taxes as $tax)
                                                                <option {{ $tax->tax_percent == $tax->tax_percent ? 'SELECTED' : '' }} value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Net Total : </b>  </label>
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

                <section class="">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="submit-area py-3 mb-4">
                                <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
                            </div>
                        </div>
                    </div>
                </section>
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
                }
            });
        }
        setExpanseCategory();

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
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var totalDue = parseFloat(netTotalAmount) - parseFloat(payingAmount);
            $('#total_due').val(parseFloat(totalDue).toFixed(2));
        }
        calculateAmount();

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

        //Add purchase request by ajax
        $('#edit_expanse_form').on('submit', function(e){
            e.preventDefault();
            
            $('.loading_button').show();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
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

            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){

                    if(!$.isEmptyObject(data.errorMsg)){

                        toastr.error(data.errorMsg,'ERROR'); 
                        $('.loading_button').hide();
                    }

                    if(!$.isEmptyObject(data.successMsg)){

                        $('.loading_button').hide();
                        toastr.success(data.successMsg); 
                        window.location = "{{route('expanses.index')}}";
                    }
                }
            });
        });

        var index = 1;
        $(document).on('click', '#addMore', function (e) {
            e.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<b><span class="serial">'+(index + 1)+'</span></b>';
            html += '<input class="index-'+(index + 1)+'" type="hidden" id="index">';
            html += '<input type="hidden" name="description_ids[]" id="description_id" value="">';
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