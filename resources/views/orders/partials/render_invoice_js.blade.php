<script>
    $(document).ready(function() {
        $(document).on('click', '.getInvoice', function() {
            var id = $(this).data('id');
            var loadingContainer = $("#loadingContainer");
            loadingContainer.show();
            
            $.ajax({
                url: "{{ route('orders.invoice', ['id' => ':id']) }}".replace(':id', id),
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    loadingContainer.hide();
                    $('#modalData').html(data.view);
                    feather.replace(); // Re-initialize feather icons after injecting new HTML
                    $('#viewOrderModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    loadingContainer.hide();
                }
            });
        });


        $(document).on('click', '.viewInvoice', function() {
            var id = $(this).data('id');
            var type = $(this).data('type'); // Get the type from the data attribute
            var loadingContainer = $("#loadingContainer");
            loadingContainer.show();

            $.ajax({
                url: "{{ route('transactions.invoice', ['id' => ':id', 'type' => ':type']) }}"
                    .replace(':id', id)
                    .replace(':type', type), // Replace both ID and type in the URL
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    loadingContainer.hide();
                    $('#modalData').html(data.view);
                    feather.replace();
                    $('#viewInvoiceModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    loadingContainer.hide();
                }
            });
        });


        $('.viewStorageInvoice').on('click', function() {
            var id = $(this).data('id');
            var type = $(this).data('type'); // Get the type from the data attribute
            var loadingContainer = $("#loadingContainer");
            loadingContainer.show();

            $.ajax({
                url: "{{ route('storage.invoice', ['id' => ':id', 'type' => ':type']) }}"
                    .replace(':id', id)
                    .replace(':type', type), // Replace both ID and type in the URL
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    loadingContainer.hide();
                    $('#modalData').html(data.view);
                    feather.replace();
                    $('#viewStorageInvoiceModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    loadingContainer.hide();
                }
            });
        });
    });

    function deleteOrder($id) {
        Swal.fire({
            text: 'Are you sure you want to delete this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-2'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $('#delete-form' + $id).submit();
            }
        });
    }
</script>
