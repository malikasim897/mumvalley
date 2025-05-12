
@if ($message = Session::get('success'))
<script>
    var message = '{{$message}}';
    $(document).ready(function () {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            customClass: {
                confirmButton: 'btn btn-success'
            }
        });
    })
</script>
@endif