<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    var defaultProblemsReportItems = @json($defaultProblemsReportItems);
    var defaultChecklist = "{{ isset($defaultChecklist) ? $defaultChecklist : null }}";
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

    $("#brand_id").select2();
    $("#device_id").select2();
    $("#device_model_id").select2();

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

        getCheckList();

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

        var device_id = $(this).val();

        $('#device_model_id').empty();
        $('#device_model_id').append('<option data-checklist="" value="">' + "{{ __('Select Device Model') }}" + '</option>');

        getCheckList();

        var url = "{{ route('services.settings.device.models.by.device') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                device_id
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

        var device_model_id = $('#device_model_id').val();
        var checklist = $('#device_model_id').find('option:selected').data('checklist');

        var __checkList = checklist ? checklist : defaultChecklist;

        $('#check_list_area').empty();

        if (__checkList && device_model_id) {

            var arr = __checkList.split('|').map(function(item) {
                return item.trim();
            });

            arr.forEach(function(item, index) {

                if (item) {

                    var hrml = '';
                    hrml += '<div class="col-md-4">';
                    hrml += '<p class="fw-bold text-dark">' + item + '</p>';
                    hrml += '<div class="switch-toggle switch-candy">';
                    hrml += '<input id="' + index + '_yes" name="checklist[' + item + ']" type="radio" value="yes">';
                    hrml += '<label for="' + index + '_yes" class="text-success">✔</label>';

                    hrml += '<input id="' + index + '_no" name="checklist[' + item + ']" type="radio" value="no">';
                    hrml += '<label for="' + index + '_no" class="text-danger">❌</label>';

                    hrml += '<input id="' + index + '_na" name="checklist[' + item + ']" type="radio" checked value="na">';
                    hrml += '<label for="' + index + '_na">N/A</label>';
                    hrml += '<a></a>';
                    hrml += '</div>';
                    hrml += '</div>';
                    $('#check_list_area').append(hrml);
                }
            });
        } else {

            $('#check_list_area').append('<p>' + "{{ __('No Service Check List') }}" + '</p>');
        }
    }
</script>

<script>
    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var status = $('#status').val();
        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#' + nextId).focus().select();
        }
    });
</script>

<script>
    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('delivery_date'),
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('service_complete_date'),
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

    $(document).on('click', '#date_clear', function(e) {

        var id = $(this).data('clear_date_id');
        console.log(id);
        $('#'+id).val('');
    });
</script>
