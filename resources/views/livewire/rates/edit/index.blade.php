<style>
    #rateTable.show {
        display: block;
        height: 100vh;
    }
</style>
<div>
    <table class="table" id="rateTable">
        <thead>
            <tr>
                <th>weight in gram</th>
                <th>weight in kg</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @if (count($rate->datas) > 0)
                @foreach ($rate->datas as $key => $data)
                    <tr wire:key="item-{{ $rate['id'] }}">
                        <td>{{ intval($data->weight_in_gram) }}</td>
                        <td>{{ $data->weight_in_gram / 1000 }} kg</td>
                        <td>{{ number_format($data->value,2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">No Record Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@if (session()->has('errors'))
    @foreach (session('errors')['errors'] as $error)
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ $error }}'
            });
        </script>
    @endforeach
@endif
