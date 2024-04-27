<script>
      //this for radio check wise user data with email to datatable
        var table = $('.data_tbl').DataTable();
        function handleRadioChange(radio) {
            var selectedValue = radio.value;
                $.ajax({
                url: "{{ route('menual-sms.edit', ['menual_sm' => ':menualId']) }}".replace(':menualId', selectedValue),
                type: "GET",
                dataType: "json",
                success: function(data) {
                    table.clear().draw();
                    data.forEach(function(item) {
                        var row = [
                            '<input name="mobile[]" type="checkbox" value="' + item.phone + '">',
                            item.phone
                        ];
                        table.row.add(row).draw();
                    });
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
        $('input[type=radio]').on('change', function() {
            handleRadioChange(this);
        });
    

    //this for dat bind when select body dropdown
    function bindDataToForm(data) {
        Object.values(window.editors).forEach(editor => {
            editor.setData(data.body);
        });
    }

    //this for when change select body from select
    function handlebody(select) {
        var selectedValue = select.value;
            $.ajax({
            url: "{{ route('menual-sms.show', ['menual_sm' => ':menualId']) }}".replace(':menualId', selectedValue),
            type: "GET",
            dataType: "json",
            success: function(data) {
                bindDataToForm(data);
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }

    //this for select all checkbox
    function selects(){  
                var ele=document.getElementsByName('mobile[]');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=true;  
                }  
            }  

     //this for deselect all checkbox
    function deSelect(){  
                var ele=document.getElementsByName('mobile[]');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=false;  
                      
                }  
    }    


     // this for add more mobile input  
      $("#addMoreButton").click(function() {
        var mobileField = '<div class="input-group mb-2">' +
                            '<input style="margin-top:8px" required="" type="text" name="mobile[]" class="form-control add_input" data-name="To" placeholder="Mobile">' +
                            '<div class="input-group-append">' +
                                '<button class="btn btn-outline-danger deleteEmail" type="button"><i class="fas fa-trash"></i></button>' +
                            '</div>' +
                        '</div>';
        $("#smsContainer").append(mobileField);
    });

    // this for delete email input 
    $(document).on("click", ".deleteEmail", function() {
        $(this).closest(".input-group").remove();
    });

    //this for send email
    $(document).on('submit', '#add_data', function(event) {
      event.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: "{{ route('menual-sms.store') }}",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                if(response.status=='error'){
                    toastr.error(response.message);
                    return false;
                }
                $('#add_data')[0].reset();
                toastr . success(response . message);
            },
            error: function(xhr, status, error) {
                var errorData = JSON.parse(xhr.responseText);
                var errorMessage = "";
                if (errorData.errors) {
                    Object.keys(errorData.errors).forEach(function(key) {
                        var errorMessages = errorData.errors[key];
                        errorMessages.forEach(function(message) {
                            errorMessage += message + "<br>";
                        });
                    });
                } else {
                    errorMessage = errorData.message;
                }
                toastr.error(errorMessage);
            }
        });
   });
</script>


