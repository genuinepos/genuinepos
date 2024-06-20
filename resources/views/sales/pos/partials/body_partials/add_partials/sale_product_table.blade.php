<style>
    .set-height {
        position: relative;
        height: {{ $saleScreenType == \App\Enums\SaleScreenType::ServicePosSale->value ? '442px!important' : '350px' }};
    }

    .tagify--focus {
        height: auto !important;
    }

    tags.tagify {
        min-width: 100%;
    }

    .tagify__input {
        min-width: 100%;
    }

    span.tagify__tag-text {
        font-size: 9px;
    }

    .tagify__input {
        display: inline-block;
        min-width: 110px;
        margin: 8px 2px;
        padding: var(--tag-pad);
        line-height: 5px;
        position: relative;
        white-space: pre-wrap;
        color: var(--input-color);
        box-sizing: border-box;
        overflow: hidden;
    }
</style>

@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="set-height">
    @if ($saleScreenType == \App\Enums\SaleScreenType::ServicePosSale->value)
        <div class="form-field-area px-2 py-1">
            <div class="row">
                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Delivery Date') }}</label>
                    <input type="text" name="delivery_date" class="form-control" id="delivery_date" value="{{ $jobCard && isset($jobCard->delivery_date_ts) ? date($dateFormat, strtotime($jobCard->delivery_date_ts)) : '' }}"  placeholder="{{ __('Delivery Date') }}">
                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Service Completed On') }}</label>
                    <input type="text" name="service_complete_date" class="form-control" id="service_complete_date" placeholder="{{ __('Service Completed On') }}">
                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Status') }}</label>
                    <select name="status_id" class="form-control" id="status_id">
                        <option value="">{{ __('Select Status') }}</option>
                        @foreach ($status as $status)
                            @php
                                $defaultStatus = isset($generalSettings['service_settings__default_status_id']) ? $generalSettings['service_settings__default_status_id'] : null;
                                $jobCardStatus = isset($jobCard) ? $jobCard->status_id : $defaultStatus;
                            @endphp
                            <option @selected($jobCardStatus == $status->id) value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Brand.') }}</label>
                    <select name="brand_id" class="form-control" id="brand_id">
                        <option value="">{{ __('Select Brand') }}</option>
                        @foreach ($brands as $brand)
                            <option @selected(isset($jobCard) && $jobCard?->brand_id == $brand->id) value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Device') }}</label>
                    <select name="device_id" class="form-control" id="device_id">
                        <option value="">{{ __('Select Device') }}</option>
                        @foreach ($devices as $device)
                            <option @selected(isset($jobCard) && $jobCard?->device_id == $device->id) value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Device Model') }}</label>
                    <select name="device_model_id" class="form-control" id="device_model_id">
                        <option value="">{{ __('Select Device Model') }}</option>
                        @foreach ($deviceModels as $deviceModel)
                            <option @selected(isset($jobCard) && $jobCard?->device_model_id == $deviceModel->id) data-checklist="{{ $deviceModel->service_checklist }}" value="{{ $deviceModel->id }}">{{ $deviceModel->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row align-items-end mt-1">
                <div class="col-md-4">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Serial No') }}</label>
                    <input type="text" name="serial_no" class="form-control" id="serial_no" value="{{ isset($jobCard) ? $jobCard->serial_no : '' }}" placeholder="{{ __('Serial No') }}">
                </div>

                <div class="col-md-4">
                    <button type="button" class="btn btn-sm btn-primary">{{ __('Pre Service Checklist') }}</button>
                </div>
            </div>

            <div class="row align-items-end mt-1">
                <div class="col-md-12">
                    <label class="fw-bold" style="font-size: 10px;">{{ __('Problem Reported By The Customer') }}</label>
                    <input name="problems_report" id="problems_report" value="{{ isset($jobCard) ? $jobCard->problems_report : '' }}">
                </div>
            </div>
        </div>
    @endif

    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table data__table modal-table table-sm sale-product-table">
            <thead>
                <tr>
                    <th style="font-size: 10px;">{{ __('S/L') }}</th>
                    <th style="font-size: 10px;">{{ __('Product') }}</th>
                    <th style="font-size: 10px;">{{ __('Qty/Weight') }}</th>
                    <th style="font-size: 10px;">{{ __('Unit') }}</th>
                    <th style="font-size: 10px;">{{ __('Price Inc. Tax') }}</th>
                    <th style="font-size: 10px;">{{ __('Subtotal') }}</th>
                    <th style="font-size: 10px;" class="text-start"><i class="fas fa-trash-alt"></i></th>
                </tr>
            </thead>

            <tbody id="product_list">

            </tbody>
        </table>
    </div>
</div>
