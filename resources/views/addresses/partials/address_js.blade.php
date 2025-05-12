<script>
    $(document).ready(function() {
        $('#addressesList').on('change', function() {
            var addressId = $(this).val();
            var loadingContainer = $("#loadingContainer");
            if (addressId) {
                loadingContainer.show(); // Show loading HTML

                $.ajax({
                    url: "{{ route('addresses.address.list', ['id' => ':id']) }}"
                        .replace(':id', addressId),
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
                // $('#senderState').html('<option value="">Select Country First</option>');
            }
        });

        function populateAddressFields(data) {
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#email').val(data.email);
            $('#phone').val(data.phone.replace(/\s/g, ""));
            $('#receiverCountry').html('<option value="' + data.country_id +
                '">' + data.country_name + '</option>');
            $('#receiverState').html('<option value="' + data.state_id + '">' +
                data.state_code + '</option>');
            $('#address').val(data.address);
            $('#address2').val(data.address2);
            $('#city').val(data.city);
            $('#street_no').val(data.street_no);
            $('#zipcode').val(data.zipcode);
            $('#taxId').val(data.tax_id);
        }

        // get states for receipiants country
        $('#receiverCountry').on('change', function() {
            var selectedCountryId = $(this).val();
            var loadingContainer = $("#loadingContainer");
            if (selectedCountryId) {
                loadingContainer.show(); // Show loading HTML

                $.ajax({
                    url: "{{ route('addresses.country.states', ['countryId' => ':countryId']) }}"
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

    $("#addType").change(function(){
        taxTypeChange(this);
    });
    
    function taxTypeChange(thisType){
        $("#placeType").text($(thisType).val()=="individual"?"CPF":"CNPJ");
    }
    });
</script>
