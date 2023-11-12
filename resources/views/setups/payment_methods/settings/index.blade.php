@extends('layout.master')
@push('stylesheets')

@endpush
@section('title', 'Payment Method Settings - ')

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-credit-card"></span>
                    <h6>{{ __("Payment Method Settings") }} </h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <section class="p-3">
            <div class="form_element rounded m-0">

                <div class="element-body px-4">
                    <form id="payment_method_settings_form" action="{{ route('payment.methods.settings.add.or.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <p class="m-0 p-0"><b> {{ __("Shop/Business") }} : </b>
                                @if (auth()?->user()?->branch?->parent_branch_id)

                                    {{ auth()?->user()?->branch?->parentBranch?->name. '(' .auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }}
                                @else

                                    @if (auth()?->user()?->branch)

                                        {{ auth()?->user()?->branch?->name . '(' . auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }}
                                    @else

                                        {{ $generalSettings['business__shop_name'] }}
                                    @endif
                                @endif
                            </p>
                            <div class="form_element rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="modal-table table table-sm">
                                                        <thead>
                                                            <th class="text-start">{{ __("S/L") }}</th>
                                                            <th class="text-start">{{ __("Payment Method") }}</th>
                                                            <th class="text-start">{{ __("Default Account") }}</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($paymentMethods as $method)
                                                                <tr>
                                                                    <td class="text-start">
                                                                        <b>{{ $loop->index + 1 }}.</b>
                                                                    </td>
                                                                    <td class="text-start">
                                                                        {{ $method->name }}
                                                                        <input type="hidden" name="payment_method_ids[]" value="{{ $method->id }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <select name="account_ids[]" id="account_id" class="form-control" autofocus>
                                                                            <option value="">{{ __("None") }}</option>
                                                                            @foreach ($accounts as $ac)
                                                                                @php
                                                                                    $methodSetting = $method?->paymentMethodSetting;
                                                                                @endphp

                                                                                @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                                                                    @continue
                                                                                @endif

                                                                                <option {{ isset($methodSetting) && $methodSetting->account_id == $ac->id ? 'SELECTED' : ''}} value="{{ $ac->id }}">
                                                                                    @php
                                                                                        $acNo = $ac->account_number ? ', A/c No : '.$ac->account_number : '';
                                                                                        $bank = $ac?->bank ? ', Bank : '.$ac?->bank?->name : '';
                                                                                    @endphp
                                                                                    {{ $ac->name . $acNo . $bank}}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td class="d-flex justify-content-end">
                                                                    <div class="btn-loading">
                                                                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __("Loading") }}...</span> </button>
                                                                        <button type="submit" class="btn btn-sm btn-success submit_button mt-1 mb-1">{{ __("Save Changes") }}</button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row mt-2">
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i> <strong>@lang('menu.loading')...</strong> </button>
                                            <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        // Add user by ajax
        $('#payment_method_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.loading_button').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.success(data);
                    $('#account_id').focus();
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('change keypress click', '#account_id', function(e) {

            var next = $(this).closest('tr').next();

            console.log({next});

            if (e.which == 0) {

                if (next.length > 0) {

                    next.find('#account_id').focus();
                }else {

                    $('.submit_button').focus();
                }
            }
        });
    </script>
@endpush
