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
                    <input id="search" type="text" placeholder="Search..." class="form-control" />
                </div>
            </div>
        </div>
    </div>
    <table class="table" id="shipping_servicesTable">
        <thead>
            <tr>
                <th>Id#</th>
                <th>Shipping</th>
                <th>Sub Clas</th>
            </tr>
        </thead>
        <tbody>
            @if (count($shipping_services) > 0)
                @foreach ($shipping_services as $key => $shipping_service)
                    <tr wire:key="item-{{ $shipping_service->shipping_service_id }}" class="filterTable">
                        <td>{{ $shipping_service->shipping_service_id }}</td>
                        <td>{{ $shipping_service->shipping_service_name }}</td>
                        <td>{{ $shipping_service->service_subclass }}</td>
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
