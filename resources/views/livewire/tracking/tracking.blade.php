<div>
    <div class="card-body">
        <h4>Track Your Order</h4>
        <div class="row flex items-center justify-center">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 mx-auto">
                <div class="mb-1">
                    <input type="text" placeholder="Enter Tracking Number" class="form-control" wire:model.defer="trackingNumber">
                    <p class="fw-bolder text-danger">{{$error}}</p>
                </div>
            </div>
            <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                <button wire:click="trackOrder" class="btn btn-primary me-1">Search</button>
            </div>
        </div>
    </div>
    @if($order)
        <section id="accordion-without-arrow">
            <div class="row">
                <div class="col-sm-12">
                    <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                        <div class="card">
                            <div class="card-body">
                                <div id="accordionIcon" class="accordion accordion-without-arrow">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header text-body d-flex justify-content-between border border-gray-500 border-solid"
                                            id="accordionIconOne">
                                            <button type="button" class="accordion-button collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                aria-controls="accordionIcon-1">
                                                HD WHR#: {{ $order->wr_number }}
                                            </button>
                                            <button type="button" class="accordion-button collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                aria-controls="accordionIcon-1">
                                                Tracking#: <span>{{ $order->tracking_code }}</span>
                                            </button>
                                            <button type="button" class="accordion-button collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                aria-controls="accordionIcon-1">
                                                Piece: {{ $order->orderItems->count() }}
                                            </button>
                                            <button type="button" class="accordion-button collapsed"
                                                data-bs-toggle="collapse" data-bs-target="#accordionIcon-1"
                                                aria-controls="accordionIcon-1">
                                                Weight:{{ $order['weight'].' '.$order['unit'] ?? ''}}
                                            </button>
                                            <div >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-down-short text-primary" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4"/>
                                                </svg>
                                            </div>
                                        </h2>
                                        <div id="accordionIcon-1" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionIcon">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="main">
                                                            <ul>
                                                                @empty($trackingDetails->data->apiTrackings)
                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package font-medium-4"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step  @if ($lastStatusCode >= 70) active @endif  removestep">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Order<br> Placed</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck font-medium-4"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                                            </span>
                                                                        </div> 
                                                                        <div class="step @if ($lastStatusCode>= 72) active @endif">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Freight in <br>transit</p>
                                                                    </li>
                                                                    
                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <span class="avatar-content">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home font-medium-4"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                                                                </span>
                                                                            </span>
                                                                        </div> 
                                                                        <div class="step @if ($lastStatusCode >= 73 ) active @endif  ">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Received<br>Terminal</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive font-medium-4"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                                                                            </span>
                                                                        </div> 
                                                                        <div class="step  @if ($lastStatusCode >= 75) active @endif">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Processed<br>manifested</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target font-medium-4"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                                                            </span>
                                                                        </div> 
                                                                         <div class="step  @if ($lastStatusCode >=80) active @endif">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label mt-2">Post</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Received<br>by Correios</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search font-medium-4"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <span class="label mt-2">Customs<br>Clearance <br> Finalized</span>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck font-medium-4"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>                                                                                
                                                                            </span>
                                                                        </div>
                                                                        <div class="step">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <span class="label mt-2">In transit <br> to <br>Sao Paulo</span>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package font-medium-4"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <p class="label">Out for<br>delivery</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-codesandbox font-medium-4"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline><polyline points="7.5 19.79 7.5 14.6 3 12"></polyline><polyline points="21 12 16.5 14.6 16.5 19.79"></polyline><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <p class="label">parcels<br> delivered</p>
                                                                    </li>
                                                                    @else
                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package font-medium-4"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step   active   removestep">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Order<br> Placed</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck font-medium-4"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                                            </span>
                                                                        </div> 
                                                                        <div class="step  active ">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Freight in <br>transit</p>
                                                                    </li>
                                                                    
                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <span class="avatar-content">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home font-medium-4"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                                                                </span>
                                                                            </span>
                                                                        </div> 
                                                                        <div class="step   active ">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Received<br>Terminal</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive font-medium-4"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                                                                            </span>
                                                                        </div> 
                                                                        <div class="step   active ">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Processed<br>manifested</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target font-medium-4"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                                                            </span>
                                                                        </div> 
                                                                        <div class="step active">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label mt-2">Post</p>
                                                                    </li>
                                                                    
                                                                    {{-- Received By Correrios --}}

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step @if( $brazilStatusCode >= 90) active @endif"">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check uil font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        </div>
                                                                        <p class="label">Received<br>by Correios</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search font-medium-4"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step @if( $brazilStatusCode >= 100) active @endif">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <span class="label mt-2">Customs<br>Clearance <br> Finalized</span>
                                                                    </li>


                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck font-medium-4"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>                                                                                
                                                                            </span>
                                                                        </div>
                                                                        <div class="step">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <span class="label mt-2">In transit <br> to <br>Sao Paulo</span>
                                                                    </li>


                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package font-medium-4"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step @if( $brazilStatusCode >= 120) active @endif">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <p class="label">Out for<br>delivery</p>
                                                                    </li>

                                                                    <li>
                                                                        <div class="avatar bg-light-primary p-50">
                                                                            <span class="avatar-content">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-codesandbox font-medium-4"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline><polyline points="7.5 19.79 7.5 14.6 3 12"></polyline><polyline points="21 12 16.5 14.6 16.5 19.79"></polyline><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                                            </span>
                                                                        </div>
                                                                        <div class="step">
                                                                            <i data-feather="check" class="uil font-medium-2"></i>
                                                                        </div>
                                                                        <p class="label">parcels<br> delivered</p>
                                                                    </li>
                                                                @endempty
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-responsive w-100">
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Country</th>
                                                                    <th>Description</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($trackingDetails['hdTrackings'] as $tracking)
                                                                    <tr>
                                                                        <td>{{ date('Y-m-d H:i:s', strtotime($tracking['created_at'])) }} </td>
                                                                        <td>
                                                                            @if ($tracking['type'] == 'HD') US  @else BR @endif
                                                                        </td>
                                                                        <td> {{ $tracking['description'] }} </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    @include('layouts.livewire.loading')
</div>
