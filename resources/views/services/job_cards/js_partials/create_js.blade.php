<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
<script>
    var productConfigurationItems = @json($productConfigurationItems);
    var defaultProblemsReportItems = @json($defaultProblemsReportItems);
    var defaultProductConditionItems = @json($defaultProductConditionItems);
    var defaultChecklist = "{{ $defaultChecklist }}";

    var input = document.querySelector('input[name="product_configuration"]');
    // init Tagify script on the above inputs
    tagify = new Tagify(input, {
        whitelist: productConfigurationItems,
        // maxTags: 10,
        dropdown: {
            maxItems: 20, // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0, // <- show suggestions on focus
            closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
        }
    });

    var input = document.querySelector('input[name="problems_report"]');
    // init Tagify script on the above inputs
    tagify = new Tagify(input, {
        whitelist: defaultProblemsReportItems,
        // maxTags: 10,
        dropdown: {
            maxItems: 20, // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0, // <- show suggestions on focus
            closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
        }
    });

    var input = document.querySelector('input[name="product_condition"]');
    // init Tagify script on the above inputs
    tagify = new Tagify(input, {
        whitelist: defaultProductConditionItems,
        // maxTags: 10,
        dropdown: {
            maxItems: 20, // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0, // <- show suggestions on focus
            closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
        }
    });

    $('#document').dropify({
        messages: {
            'default': "{{ __('Drag and drop a file here or click') }}",
            'replace': "{{ __('Drag and drop or click to replace') }}",
            'remove': "{{ __('Remove') }}",
            'error': "{{ __('Ooops, something wrong happended.') }}"
        }
    });

    $(document).ready(function() {
        function formatState(state) {
            if (!state.id) {
                return state.text; // optgroup
            }

            var icon = $(state.element).data('icon');
            var color = $(state.element).data('color');

            var $state = $(
                '<span><i class="' + icon + '" style="color:' + color + '"></i> ' + state.text + '</span>'
            );
            return $state;
        };

        $("#status_id").select2({
            templateResult: formatState,
            templateSelection: formatState
        });
    });

    $(document).on('change', '#brand_id', function() {

        var brand_id = $(this).val();

        $('#device_model_id').empty();
        $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

        var url = "{{ route('services.settings.device.models.by.brand') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                brand_id
            },
            success: function(models) {

                if (models.length > 0) {

                    $('#device_model_id').empty();
                    $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

                    $.each(models, function(key, model) {

                        $('#device_model_id').append('<option data-checklist="' + model.service_checklist + '" value="' + model.id + '">' + model.name + '</option>');
                    });
                }
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('change', '#device_id', function() {

        var brand_id = $(this).val();

        $('#device_model_id').empty();
        $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

        var url = "{{ route('services.settings.device.models.by.device') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                brand_id
            },
            success: function(models) {

                if (models.length > 0) {

                    $('#device_model_id').empty();
                    $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

                    $.each(models, function(key, model) {

                        $('#device_model_id').append('<option data-checklist="' + model.service_checklist + '" value="' + model.id + '">' + model.name + '</option>');
                    });
                }
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('change', '#device_model_id', function() {

        getCheckList();
    });

    function getCheckList() {

        var checklist = $('#device_model_id').find('option:selected').data('checklist');

        var __checkList = checklist ? checklist : defaultChecklist;

        if (__checkList) {

            var arr = __checkList.split('|').map(function(item) {
                return item.trim();
            });

            console.log(arr);
        }
    }
</script>

<script>
    $(document).on('click', '#addBrand', function(e) {
        e.preventDefault();

        var url = "{{ route('brands.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#brandAddOrEditModal').html(data);
                $('#brandAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#brand_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('click', '#addDevice', function(e) {
        e.preventDefault();

        var url = "{{ route('services.settings.devices.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#deviceAddOrEditModal').html(data);
                $('#deviceAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#device_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
            }
        });
    });

    $(document).on('click', '#addDeviceModel', function(e) {
        e.preventDefault();

        var url = "{{ route('services.settings.device.models.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#deviceModelAddOrEditModal').html(data);
                $('#deviceModelAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#device_model_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
            }
        });
    });
</script>
