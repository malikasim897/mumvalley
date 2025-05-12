@if ($errorMessage = Session::get('error'))
    <script>
        var errorMessage = '{{ $errorMessage }}';
        $(document).ready(function() {
            Swal.fire({
                title: 'Error',
                text: errorMessage,
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-danger'
                }
            });
        })
    </script>
@endif

{{--
@if (Session::get('error_message'))
<script>
$(document).ready(function() {
alert('hi');
});
</script>
    @foreach (session('error_message') as $field => $fieldErrors)
        @foreach ($fieldErrors as $error)
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        title: 'Error',
                        text: '{{ $error }}',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                });
            </script>
        @endforeach
    @endforeach
@endif --}}
