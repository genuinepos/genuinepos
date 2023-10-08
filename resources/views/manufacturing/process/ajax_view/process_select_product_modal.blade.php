<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Choose Product") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form action="{{ route('manufacturing.process.create') }}" method="GET">
                <div class="form-group">
                    <label><b>{{ __("Select Product") }}</b> : <span class="text-danger">*</span></label>
                    <select required name="product_id" class="form-control select2" id="product_id" data-next="create_process">
                        <option value="">{{ __("Select Product") }}</option>
                        @foreach ($products as $product)
                            @php
                                $variant_name = $product->variant_name ? $product->variant_name : '';
                                $product_code = $product->variant_code ? $product->variant_code : $product->product_code;
                            @endphp
                            <option value="{{ $product->id . '-' . ($product->variant_id ? $product->variant_id : 'noid') }}">{{ $product->name.' '.$variant_name.' ('.$product_code.')' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide">
                                <i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span>
                            </button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="create_process" class="btn btn-sm btn-success submit_button">{{ __("Create Process") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });
</script>