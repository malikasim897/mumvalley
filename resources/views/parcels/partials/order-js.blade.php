<script>
    $(document).ready(function() {
        var loadingContainer = $("#loadingContainer");
        // get states for sender country
        $('#senderCountry').on('change', function() {
            var selectedCountryId = $(this).val();
            if (selectedCountryId) {
                loadingContainer.show(); // Show loading HTML

                $.ajax({
                    url: "{{ route('product.country.states', ['countryId' => ':countryId']) }}"
                        .replace(':countryId', selectedCountryId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        loadingContainer.hide(); // Hide loading HTML
                        var options = '<option value="">Select State</option>'
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i].id + '">' + data[i]
                                .code + '</option>';
                        }
                        $('#senderState').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        loadingContainer.hide(); // Hide loading HTML
                    }
                });
            } else {
                // Handle the case where no country is selected, you can clear the state dropdown or provide a default option.
                // $('#senderState').html('<option value="">Select Country First</option>');
            }
        });

        var selectedCountryId = $("#senderCountry").val();
        var selectedStateId = $("#senderState").val();
        if (selectedStateId == '') {
            if (selectedCountryId) {
                $.ajax({
                    url: "{{ route('product.country.states', ['countryId' => ':countryId']) }}".replace(
                        ':countryId', selectedCountryId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Select State</option>'
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i].id + '">' + data[i].code +
                                '</option>';
                        }
                        $('#senderState').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                // Handle the case where no country is selected, you can clear the state dropdown or provide a default option.
                $('#senderState').html('<option value="">Select Country First</option>');
            }
        }
        // get states for receipiants country
        $('#receiverCountry').on('change', function() {
            var selectedCountryId = $(this).val();
            if (selectedCountryId) {
                loadingContainer.show(); // Show loading HTML

                $.ajax({
                    url: "{{ route('product.country.states', ['countryId' => ':countryId']) }}"
                        .replace(':countryId', selectedCountryId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        loadingContainer.hide(); // Hide loading HTML
                        var options = '<option value="">Select State</option>';
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i].id + '">' + data[i]
                                .code + '</option>';
                        }
                        $('#receiverState').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        loadingContainer.hide(); // Hide loading HTML
                    }
                });
            } else {
                // Handle the case where no country is selected, you can clear the state dropdown or provide a default option.
                $('#receiverState').html('<option value="">Select Country First</option>');
            }
        });

        $('#addressList').on('change', function() {
            var addressId = $(this).val();
            var url = $(this).attr('data-url');
            if (addressId) {
                loadingContainer.show(); // Show loading HTML
                $.ajax({
                    url: url.replace(':id', addressId).replace("addresses.address.list",url),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        loadingContainer.hide(); // Hide loading HTML
                        populateAddressFields(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        loadingContainer.hide(); // Hide loading HTML
                    }
                });
            } else {
                // Handle the case where no country is selected, you can clear the state dropdown or provide a default option.
                $('#senderState').html('<option value="">Select Country First</option>');
            }
        });

        function populateAddressFields(data) {
            $("#addType").val(data.account_type);
            $('#placeType').val(data.account_type=="business" ? "CNPJ" : "CPF")
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#email').val(data.email);
            $('#phone').val(data.phone.replace(/\s/g, ""));
            $('#receiverCountry').html('<option value="' + data.country_id +
                '">' + data.country_name + '</option>');
            $('#receiverState').html('<option value="' + data.state_id + '">' +
                data.state_code + '</option>');
            $('#address').val(data.address);
            $('#address_2').val(data.address2);
            $('#city').val(data.city);
            $('#street_no').val(data.street_no);
            $('#zipcode').val(data.zipcode);
            $('#taxId').val(data.tax_id);
        }

        // Calculate and update total when either "quantity" or "value" changes
        $(document).on('change', '.quantity, .value', function() {
            var $repeaterRow = $(this).closest('.repeater-row');
            var quantity = parseFloat($repeaterRow.find('.quantity').val()) || 0;
            var value = parseFloat($repeaterRow.find('.value').val()) || 0;
            var total = quantity * value;
            var index = $repeaterRow.index();
            $repeaterRow.find('.staticTotal').val(parseFloat(total.toFixed(2)));
        });

        // get shipping service-rates
        // $(document).on('change', '#receiverState', function() {
        //     var country_id = $('#receiverCountry').val();
        //     var state_id = $(this).val();
        //     var orderId = '';
        //     var data = {
        //         country_id: country_id,
        //         state_id: state_id
        //     };
        // $.ajax({
        //     url: "{{ route('product.services.rates', ['orderId' => ':orderId']) }}".replace(':orderId', orderId),
        //     type: 'GET',
        //     dataType: 'json',
        //     data: data,
        //     success: function (response) {
        //         var options = '<option value="">Select Service</option>';
        //         if (response.data.length > 0)
        //         {
        //             for (var i = 0; i < response.data.length; i++) {
        //                 options += '<option value="' + response.data[i].id + '">' + response.data[i].shippingServices + '</option>';
        //             }
        //         }
        //         $('#shippingServices').html(options);
        //     },
        //     error: function (xhr, status, error) {
        //         console.error(error);
        //     }
        // });
        // });

        // get and set shipping service-name
        var select = $("#shippingServices");
        var hiddenInput = $("#service_name");
        var shippmentVal = $("#shippment_value");

        function updateServiceName() {
            var serviceName = select.find("option:selected").text();
            var serviceCost = select.find("option:selected").data("service-cost");
            
            if($("#freight_value").val() =="")
            {
                $("#freight_value").val(serviceCost);
            } 
            hiddenInput.val(serviceName);
            if (serviceCost) {
                shippmentVal.val(serviceCost);
            } else {
                shippmentVal.val(0);
            }
        }
        select.on("change", updateServiceName);
    });


    function placedOrderForm() {
        var formData = {};
        $('#senderDetailsForm, #recipientDetailsForm, #shippingItemsForm').each(function() {
            formData[$(this).attr('id')] = $(this).serialize();
        });
        formData['_token'] = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: '{{ route('product.order.placed') }}',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                console.log('Form data submitted successfully');
            },
            error: function(error) {
                console.error('Error submitting form data:', error);
            }
        });
    }

    function getSHCode() {
        $.ajax({
            url: "{{ route('product.shcodes') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var options = '<option value="">Select code</option>';
                for (var i = 0; i < data.length; i++) {
                    var parts = data[i].description.split("-------");
                    var truncatedDescription = parts[0];
                    options += '<option value="' + data[i].code + '">' + truncatedDescription + '</option>';
                }
                $('.sh_code').html(options);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    // get shipping services rates against country and state selection
    function getServicesWithRates() {
        // var country_id = $('#receiverCountry').val();
        // var state_id = $('#receiverState').val();
        var country_id = '';
        var state_id = '';
        var orderId = '';
        var data = {
            country_id: country_id,
            state_id: state_id
        };

        $.ajax({
            url: "{{ route('product.services.rates', ['orderId' => ':orderId']) }}".replace(':orderId',
                orderId),
            type: 'GET',
            dataType: 'json',
            data: data,
            success: function(response) {
                var options = '<option value="">Select Service</option>';
                if (response.data.length > 0) {
                    for (var i = 0; i < response.data.length; i++) {
                        options += '<option value="' + response.data[i].id + '">' + response.data[i]
                            .shippingServices + '</option>';
                    }
                }
                $('#shippingServices').html(options);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function restrictInputLength(inputElement, maxLength) {
        if (inputElement.value.length > maxLength) {
            inputElement.value = inputElement.value.substring(0, maxLength);
        }
    }

    $(function() {
        'use strict';

        // form repeater jquery
        $('.order-repeater, .repeater-default').repeater({
            show: function() {
                addContainGoods();
                $(this).slideDown();
                // getSHCode();
                // Feather Icons
                if (feather) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                }
            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });
        
        $('#contain_goods').change(function(){
            addContainGoods();
        });

        function addContainGoods(){
            let contain_goods=$("#contain_goods").val();
            $('.contain_goods').each(function(index) {
                 if(index!=0){
                    $('.contain_goods').eq(index).val(contain_goods);
                    var remove_contain_goods = contain_goods =="is_battery" ?  "is_perfume" : "is_battery" ;  
                    $("."+remove_contain_goods).eq(index).addClass("d-none");
                 }
            });     
        }
    });

    // handle item removal
    var removedItemIdsArray = [];
    $('.remove-item').click(function() {
        var itemId = $(this).data('item-id');
        removedItemIdsArray.push(itemId);
        $('#removedItemIds').val(removedItemIdsArray.join(','));
    });

    $("#addType").change(function(){
        taxTypeChange(this);
    });
    
    function taxTypeChange(thisType){
        $("#placeType").text($(thisType).val()=="individual"?"CPF":"CNPJ");
    }
</script>
