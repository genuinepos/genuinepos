@extends('layout.master')
@push('stylesheets')

@endpush
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
                    <form id="payment_method_settings_form" action="{{ route('settings.payment.method.settings.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <p class="m-0 p-0"><b> {{ __("Shop/Business") }} </b>
                                @if (auth()->user()->branch_id)

                                    {{ auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code }}
                                @else

                                    {{ $generalSettings['business__shop_name'] }}
                                @endif
                            </p>
                            <div class="form_element rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="display table table-sm">
                                                        <thead>
                                                            <th class="text-start">@lang('menu.sl')</th>
                                                            <th class="text-start">@lang('menu.payment_method')</th>
                                                            <th class="text-start">@lang('menu.default_account')</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($methods as $method)
                                                                <tr>
                                                                    <td class="text-start">
                                                                        <b>{{ $loop->index + 1 }}.</b>
                                                                    </td>
                                                                    <td class="text-start">
                                                                        {{ $method->name }}
                                                                        <input type="hidden" name="method_ids[]" value="{{ $method->id }}">
                                                                    </td>
                                                                    <td class="text-start">
                                                                        <select name="account_ids[]" class="form-control">
                                                                            @foreach ($accounts as $ac)
                                                                                @php
                                                                                    $presettedAc = DB::table('payment_method_settings')
                                                                                    ->where('payment_method_id', $method->id)
                                                                                    ->where('branch_id', auth()->user()->branch_id)
                                                                                    ->where('account_id', $ac->id)
                                                                                    ->first();
                                                                                @endphp
                                                                                <option {{ $presettedAc ? 'SELECTED' : ''}} value="{{ $ac->id }}">
                                                                                    @php
                                                                                        $accountType = $ac->account_type == 1 ? ' (Type : Cash-In-Hand)' : ' (Type : Bank A/C)';
                                                                                        $acNo = $ac->account_number ? ', (A/c No : '.$ac->account_number.')' : ', (A/c No : N/A';
                                                                                        $bank = $ac->b_name ? ', (Bank : '.$ac->b_name.')' : ', (Bank : N/A)';
                                                                                    @endphp
                                                                                    {{ $ac->name . $accountType . $acNo . $bank}}
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
                                                                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>@lang('menu.loading')...</span> </button>
                                                                        <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tfoot>

                                                    </table>
                                                    {{-- @foreach ($methods as $method)
                                                        <div class="input-group mt-1">
                                                            <label class="col-4"><b>{{ $loop->index + 1 }}. {{ $method->name }} </b> </label>
                                                            <input type="hidden" name="method_ids[]" value="{{ $method->id }}">
                                                            <div class="col-8">
                                                                <select name="account_ids[]" class="form-control">
                                                                    @foreach ($accounts as $ac)
                                                                        @php
                                                                            $presettedAc = DB::table('payment_method_settings')
                                                                            ->where('payment_method_id', $method->id)
                                                                            ->where('branch_id', auth()->user()->branch_id)
                                                                            ->where('account_id', $ac->id)
                                                                            ->first();
                                                                        @endphp
                                                                        <option {{ $presettedAc ? 'SELECTED' : ''}} value="{{ $ac->id }}">
                                                                            @php
                                                                                $accountType = $ac->account_type == 1 ? ' (Type : Cash-In-Hand)' : ' (Type : Bank A/C)';
                                                                                $acNo = $ac->account_number ? ', (A/c No : '.$ac->account_number.')' : ', (A/c No : N/A';
                                                                                $bank = $ac->b_name ? ', (Bank : '.$ac->b_name.')' : ', (Bank : N/A)';
                                                                            @endphp
                                                                            {{ $ac->name . $accountType . $acNo . $bank}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endforeach --}}
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

                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
