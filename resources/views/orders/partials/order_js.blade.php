<style>
    .small-text {
        font-size: 12px; /* You can adjust the font size as needed */
    }
</style>

<script>
    $(document).ready(function() {
        $.get("{{ route('get-order-count') }}", function(data) {
            // if (data.count > 0) {
                $('#order-count').text(data.count);
                $('#today-order').text(data.today);
                $('#today-current-month').text(data.currentMonth);
                $('#current-year-order').text(data.currentYear);
                $('#since-2020-order').text(data.since2020);
            // } else {
            //     $('#order-count, #today-order, #today-current-month, #current-year-order, #since-2020-order').text('not received any order yet').addClass('small-text');
            // }
        });
    });
</script>
