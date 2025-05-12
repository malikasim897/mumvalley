<script>
    $(document).ready(function() {
        $('#country').on('change', function() {
            var countryId = this.value;
            var loadingContainer = $("#loadingContainer");
            loadingContainer.show();
            $.ajax({
                url: '/get-states/' + countryId,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    loadingContainer.hide();
                    var options = '<option value="">Select State</option>'
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '">' + data[i].code +
                            '</option>';
                    }
                    $("#state-dd").html(options);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    loadingContainer.hide();
                }
            });
        });

        var countryId = $("#country").val();
        var stateId = $("#state-dd").val();
        if (stateId == null) {
            $.ajax({
                url: '/get-states/' + countryId,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    var options = '<option value="">Select State</option>'
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '">' + data[i].code +
                            '</option>';
                    }
                    $("#state-dd").html(options);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        var accountUploadImg = $('#account-upload-img'),
            accountUploadBtn = $('#account-upload'),
            accountUploadError = $('#account-upload-error'),
            accountResetBtn = $('#account-reset'),
            accountUserImage = $('.uploadedImage'),
            accountUserImageReset = $('.uploadedImageReset');

        if (accountUserImage) {
            var resetImage = accountUserImage.attr('src');
            accountUploadBtn.on('change', function(e) {

                console.log('here');
                var reader = new FileReader(),
                    files = e.target.files;
                reader.onload = function() {
                    if (accountUploadImg) {
                        accountUploadImg.attr('src', reader.result);
                        //accountUploadBtn.attr('value', reader.result);
                    }
                };
                reader.readAsDataURL(files[0]);
            });

            accountResetBtn.on('click', function() {
                accountUserImage.attr('src', resetImage);
                accountUploadBtn.val(null);
            });
        }
    });
</script>
