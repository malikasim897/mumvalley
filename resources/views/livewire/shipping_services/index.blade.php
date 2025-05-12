<style>
    #shipping_servicesTable.show {
        display: block;
        height: 100vh;
    }
</style>
<div>
    <div class="content-header d-flex">
        <div class="content-header-left col-md-9 col-12">
            <div class="col-xl-4 col-md-6 col-12">
                <div class="my-1 mx-1">
                    <input wire:model.live="search" type="text" placeholder="Search..." class="form-control" />
                </div>
            </div>
        </div>
    </div>
    <table class="table" id="shipping_servicesTable">
        <thead>
            <tr>
                <th>Id#</th>
                <th>Name</th>
                {{-- <th>Max length</th>
                <th>Max width </th>
                <th>Min width </th>
                <th>Min length</th>
                <th>All Side Sum</th>
                <th>Contains Battery</th>
                <th>Contains Perfume</th>
                <th>Contains Flammable</th> --}}
                <th>Sub Clas</th>
            </tr>
        </thead>
        <tbody>
            @if (sizeof($shipping_services) > 0)
                @foreach ($shipping_services as $key => $shipping_service)
                    <tr wire:key="item-{{ $shipping_service['id'] }}">
                        <td>{{ $shipping_service['id'] }}</td>
                        <td>{{ $shipping_service['name'] }}</td>
                        {{-- <td>{{ $shipping_service['max_length_allowed'] }}</td>
                        <td>{{ $shipping_service['max_width_allowed'] }}</td>
                        <td>{{ $shipping_service['min_width_allowed'] }}</td>
                        <td>{{ $shipping_service['min_length_allowed'] }}</td>
                        <td>{{ $shipping_service['max_sum_of_all_sides'] }}</td>
                        <td>{{ $shipping_service['contains_battery_charges'] }}</td>
                        <td>{{ $shipping_service['contains_perfume_charges'] }}</td>
                        <td>{{ $shipping_service['contains_flammable_liquid_charges'] }}</td> --}}
                        <td>{{ $shipping_service['service_sub_class'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">No Record Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $shipping_services->links() }}
    @include('layouts.livewire.loading')
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


