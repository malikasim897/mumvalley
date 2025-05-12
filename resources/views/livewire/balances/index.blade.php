
<div>
    <div class="content-header-left col-12">
        <div class="d-flex justify-content-between  mx-2  my-2">
            <div>
                <div class="d-flex">
                    <div class="me-2">
                        <select id="orderPerPage" class="form-control mt-1 text-start px-2" wire:model.live="orderPerPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                   </div>
                    <div>
                        <input wire:model.live="search" type="text" placeholder="Search..." class="form-control mt-1" />
                    </div>
                </div>
            </div>
            <div>
                <form action="{{route('export.balance')}}" method="post">
                    <div class="row">
                        @csrf
                        <div class="col-md-3 mt-1">
                            <select name="userId" class="select2  mt-1 form-select select2-hidden-accessible" id="">
                                @foreach ($users as $key => $user){
                                    <option value="{{$user->id}}">{{$user->name}} | {{$user->po_box_number}}</option>
                                    }
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Start Date</label>
                            <input type="date" value="{{ date("Y-m-01") }}"  class="form-control flatpickr-basic flatpickr-input active" name="started">
                        </div>
                        <div class="col-md-3">
                            <label >End Date</label>
                            <input type="date" value="{{ date("Y-m-d") }}" class="form-control flatpickr-basic flatpickr-input active" name="ended">
                        </div >
                        <div class="col-md-3">
                            <input type="submit" value="Download" class="btn mt-2 btn-primary btn-sm">
                        </div>
                    </div>
                </form>
            </div>

            <div>
                <a href="/deposits" class="btn btn-primary mt-2 ">Add deposit</a>
            </div>
        </div>
    </div>
    <table class="table" id="depositsTable">
        <thead>
            <tr>
                <th>Invoice#</th>
                <th>User</th>
                <th>Amount</th>
                <th>Current</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @if ($deposits->count() > 0)
                @foreach ($deposits as $key => $deposit)
                    <tr wire:key="item-{{ $deposit->id }}" class="filterTable">
                        <td>{{ $deposit->uuid }}</td>
                        <td>{{ optional($deposit->user)->name . " | ". optional($deposit->user)->po_box_number ?? "--"   }}</td>
                        <td>
                        <span class="badge rounded-pill {{ $deposit->is_credit?'badge-light-success':'badge-light-danger' }} me-1">{{  ($deposit->is_credit?"+":"-"). $deposit->amount  }}</span>
                        </td>
                        <td>{{ $deposit->balance }}</td>
                        <td>{{ class_basename($deposit->depositable) }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $deposit->is_credit?'badge-light-success':'badge-light-danger' }} me-1">{{  $deposit->is_credit? 'Credit':'Debit'  }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($deposit->created_at)->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">No Record Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $deposits->links() }}
    @include('layouts.livewire.loading')
</div>
@if(session()->has('errors'))
    @foreach(session('errors')['errors'] as $error)
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ $error }}'
            });
        </script>
    @endforeach
@endif
