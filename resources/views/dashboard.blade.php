<x-app-layout>
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- users list start -->
                <section class="app-user-list">
                    @if((auth()->user()->hasRole('user') && auth()->user()->status == 0))
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">Account Under Review:</h4>
                                    <p class="mb-0 p-1">
                                        Dear <b>{{auth()->user()->name}}</b>. Your account is under review currently, after confirmation the services will be activated. Please contact system administrator.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>

                                        <small>Today Orders</small>
                                        <h3 class="fw-bolder mb-75"><span>{{$todayOrders}}</span></h3>
                                    </div>
                                    <div class="avatar bg-light-primary p-50">
                                        <span class="avatar-content">
                                            <i data-feather="check-circle" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                                {{-- <div class="d-flex justify-content-between align-items-center p-2">
                                    <div>Completed Orders</div>
                                    <div>5</div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <small>Total {{ @date('F') }} Order</small>
                                        <h3 class="fw-bolder mb-75"><span>{{$currentMonthOrder}}</span></h3>
                                    </div>
                                    <div class="avatar bg-light-secondary p-50">
                                        <span class="avatar-content">
                                            <i data-feather="check-circle" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <small>Current Year Total Orders</small>
                                        <h3 class="fw-bolder mb-75"><span>{{$currentYearOrder}}</span></h3>
                                    </div>
                                    <div class="avatar bg-light-success p-50">
                                        <span class="avatar-content">
                                            <i data-feather="check-circle" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <small>Total Order Since: 2024</small>
                                        <h3 class="fw-bolder mb-75"><span>{{$since2024Order}}</span></h3>
                                    </div>
                                    <div class="avatar bg-light-warning p-50">
                                        <span class="avatar-content">
                                            <i data-feather="check-circle" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <small>Total Products</small>
                                        <h3 class="fw-bolder mb-75"><span>{{$totalProducts}}</span></h3>
                                    </div>
                                    <div class="avatar bg-light-info p-50">
                                        <span class="avatar-content">
                                            <i data-feather="archive" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <small>Total In Process Orders</small>
                                        <h3 class="fw-bolder mb-75"><span>{{$totalInProcessOrders}}</span></h3>
                                    </div>
                                    <div class="avatar bg-light-secondary p-50">
                                        <span class="avatar-content">
                                            <i data-feather="database" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <small>Total Returnable Bottles</small>
                                        <h3 class="fw-bolder mb-75"><span>{{$totalReturnableBottles}}</span></h3>
                                    </div>
                                    <div class="avatar bg-light-danger p-50">
                                        <span class="avatar-content">
                                            <i data-feather="arrow-left-circle" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users list ends -->

                <!-- Dashboard Analytics Start -->
                <section id="dashboard-analytics">
                    <div class="row match-height">
                        <!-- Greetings Card starts -->
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="card card-congratulations">
                                <div class="card-body text-center">
                                    <img src="{{ asset('images/elements/decore-left.png') }}"
                                        class="congratulations-img-left" alt="card-img-left" />
                                    <img src="{{ asset('images/elements/decore-right.png') }}"
                                        class="congratulations-img-right" alt="card-img-right" />
                                    <div class="avatar avatar-xl bg-primary shadow">
                                        <div class="avatar-content">
                                            <i data-feather="award" class="font-large-1"></i>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h2 class="mb-1 text-white">Congratulations on Your Account {{ Auth::user()->name }},</h2>
                                        <p class="card-text m-auto w-75">
                                            Welcome to Mum Valley Record Management System. Cheers to a seamless and well-managed user experience.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Greetings Card ends -->

                        <!-- Subscribers Chart Card starts -->
                        {{-- <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header flex-column align-items-start pb-0">
                                    <div class="avatar bg-light-primary p-50 m-0">
                                        <div class="avatar-content">
                                            <i data-feather="users" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder mt-1">92.6k</h2>
                                    <p class="card-text">Subscribers Gained</p>
                                </div>
                                <div id="gained-chart"></div>
                            </div>
                        </div> --}}
                        <!-- Subscribers Chart Card ends -->

                        <!-- Orders Chart Card starts -->
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                    <div class="row col-12">
                                        <div class="col-sm-4 d-flex flex-column flex-wrap text-left">
                                            <div class="card-header flex-column pb-0">
                                                <div class="avatar bg-light-primary p-50 m-0">
                                                    <div class="avatar-content">
                                                        <i data-feather="users" class="font-medium-5"></i>
                                                    </div>
                                                </div>
                                                <p class="card-text mb-0">Total Users</p>
                                                <h1 class="font-large-1 fw-bolder mt-0 mb-0">{{$totalUsers}} </h1>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-flex flex-column flex-wrap text-center">
                                            <div class="card-header flex-column pb-0">
                                                <div class="avatar bg-light-success p-50 m-0">
                                                    <div class="avatar-content">
                                                        <i data-feather="briefcase" class="font-medium-5"></i>
                                                    </div>
                                                </div>
                                                <p class="card-text mb-0">Total Paid Amount</p>
                                                <h1 class="font-large-1 fw-bolder mt-0 mb-0">{{$totalPaidAmount}} </h1>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-flex flex-column flex-wrap text-center">
                                            <div class="card-header flex-column pb-0">
                                                <div class="avatar bg-light-danger p-50 m-0">
                                                    <div class="avatar-content">
                                                        <i data-feather="alert-circle" class="font-medium-5"></i>
                                                    </div>
                                                </div>
                                                <p class="card-text mb-0">Total Pending Amount</p>
                                                <h1 class="font-large-1 fw-bolder mt-0 mb-0">{{$totalPendingAmount}} </h1>
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-4 col-12 d-flex flex-column flex-wrap text-center">
                                            <p class="card-text mb-0">Total Paid Amount</p>
                                            <h1 class="font-large-2 fw-bolder mt-0 mb-0">{{$transactionsSucceeded}} </h1>
                                        </div> --}}
                                        {{-- <div class="col-sm-4 col-12 d-flex flex-column flex-wrap text-center">
                                            <p class="card-text mb-0">Total Pending Amount</p>
                                            <h1 class="font-large-2 fw-bolder mt-0 mb-0">{{$transactionsSucceeded}} </h1>
                                        </div> --}}
                                    </div>
                                    
                                    {{-- <h2 class="fw-bolder mt-1"><span id="order-count"></span></h2>
                                    <p class="card-text">Orders Received</p> --}}
                                
                                <div class="card-body">
                                    <div class="row col-12">
                                        <div class="col-sm-4 d-flex flex-column flex-wrap text-center">
                                            <div class="card-header flex-column pb-0">
                                                <p class="card-text mb-50">Pending Invoices</p>
                                                <span class="font-large-1 fw-bold">{{$pendingInvoices}}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-flex flex-column flex-wrap text-center">
                                            <div class="card-header flex-column pb-0">
                                                <p class="card-text mb-50">Partial Paid Invoices</p>
                                                <span class="font-large-1 fw-bold">{{$partialPaidInvoices}}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-flex flex-column flex-wrap text-center">
                                            <div class="card-header flex-column pb-0">
                                                <p class="card-text mb-50">Paid Inovices</p>
                                                <span class="font-large-1 fw-bold">{{$paidInvoices}}</span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Orders Chart Card ends -->
                    </div>

                    {{-- <div class="row match-height">
                        <!-- Avg Sessions Chart Card starts -->
                        <div class="col-lg-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row pb-50">
                                        <div class="col-sm-6 col-12 d-flex justify-content-between flex-column order-sm-1 order-2 mt-1 mt-sm-0">
                                            <div class="mb-1 mb-sm-0">
                                                <h2 class="fw-bolder mb-25">2.7K</h2>
                                                <p class="card-text fw-bold mb-2">Avg Sessions</p>
                                                <div class="font-medium-2">
                                                    <span class="text-success me-25">+5.2%</span>
                                                    <span>vs last 7 days</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary">View Details</button>
                                        </div>
                                        <div class="col-sm-6 col-12 d-flex justify-content-between flex-column text-end order-sm-2 order-1">
                                            <div class="dropdown chart-dropdown">
                                                <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button" id="dropdownItem5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Last 7 Days
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownItem5">
                                                    <a class="dropdown-item" href="#">Last 28 Days</a>
                                                    <a class="dropdown-item" href="#">Last Month</a>
                                                    <a class="dropdown-item" href="#">Last Year</a>
                                                </div>
                                            </div>
                                            <div id="avg-sessions-chart"></div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-6 mb-2">
                                            <p class="mb-50">Goal: $100000</p>
                                            <div class="progress progress-bar-primary" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="50" aria-valuemax="100" style="width: 50%"></div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <p class="mb-50">Users: 100K</p>
                                            <div class="progress progress-bar-warning" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width: 60%"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-50">Retention: 90%</p>
                                            <div class="progress progress-bar-danger" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="70" aria-valuemax="100" style="width: 70%"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-50">Duration: 1yr</p>
                                            <div class="progress progress-bar-success" style="height: 6px">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="90" aria-valuemax="100" style="width: 90%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Avg Sessions Chart Card ends -->

                        <!-- Support Tracker Chart Card starts -->
                        <div class="col-lg-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between pb-0">
                                    <h4 class="card-title">Support Tracker</h4>
                                    <div class="dropdown chart-dropdown">
                                        <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button" id="dropdownItem4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Last 7 Days
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownItem4">
                                            <a class="dropdown-item" href="#">Last 28 Days</a>
                                            <a class="dropdown-item" href="#">Last Month</a>
                                            <a class="dropdown-item" href="#">Last Year</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                            <h1 class="font-large-2 fw-bolder mt-2 mb-0">163</h1>
                                            <p class="card-text">Tickets</p>
                                        </div>
                                        <div class="col-sm-10 col-12 d-flex justify-content-center">
                                            <div id="support-trackers-chart"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <div class="text-center">
                                            <p class="card-text mb-50">New Tickets</p>
                                            <span class="font-large-1 fw-bold">29</span>
                                        </div>
                                        <div class="text-center">
                                            <p class="card-text mb-50">Open Tickets</p>
                                            <span class="font-large-1 fw-bold">63</span>
                                        </div>
                                        <div class="text-center">
                                            <p class="card-text mb-50">Response Time</p>
                                            <span class="font-large-1 fw-bold">1d</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Support Tracker Chart Card ends -->
                    </div> --}}

                    {{-- <div class="row match-height">
                        <!-- Timeline Card -->
                        <div class="col-lg-4 col-12">
                            <div class="card card-user-timeline">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <i data-feather="list" class="user-timeline-title-icon"></i>
                                        <h4 class="card-title">User Timeline</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline ms-50">
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>12 Invoices have been paid</h6>
                                                <p>Invoices are paid to the company</p>
                                                <div class="d-flex align-items-center">
                                                    <img class="me-1" src="{{ asset('images/icons/json.png') }}" alt="data.json" height="23" />
                                                    <h6 class="more-info mb-0">data.json</h6>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>Client Meeting</h6>
                                                <p>Project meeting with Carl</p>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-50">
                                                        <img src="{{ asset('images/portrait/small/avatar-s-9.jpg') }}" alt="Avatar" width="38" height="38" />
                                                    </div>
                                                    <div class="more-info">
                                                        <h6 class="mb-0">Carl Roy (Client)</h6>
                                                        <p class="mb-0">CEO of Infibeam</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>Create a new project</h6>
                                                <p>Add files to new design folder</p>
                                                <div class="avatar-group">
                                                    <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Billy Hopkins" class="avatar pull-up">
                                                        <img src="{{ asset('images/portrait/small/avatar-s-9.jpg') }}" alt="Avatar" width="33" height="33" />
                                                    </div>
                                                    <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Amy Carson" class="avatar pull-up">
                                                        <img src="{{ asset('images/portrait/small/avatar-s-6.jpg') }}" alt="Avatar" width="33" height="33" />
                                                    </div>
                                                    <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Brandon Miles" class="avatar pull-up">
                                                        <img src="{{ asset('images/portrait/small/avatar-s-8.jpg') }}" alt="Avatar" width="33" height="33" />
                                                    </div>
                                                    <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Daisy Weber" class="avatar pull-up">
                                                        <img src="{{ asset('images/portrait/small/avatar-s-7.jpg') }}" alt="Avatar" width="33" height="33" />
                                                    </div>
                                                    <div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Jenny Looper" class="avatar pull-up">
                                                        <img src="{{ asset('images/portrait/small/avatar-s-20.jpg') }}" alt="Avatar" width="33" height="33" />
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>Update project for client</h6>
                                                <p class="mb-0">Update files as per new design</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--/ Timeline Card -->

                        <!-- Sales Stats Chart Card starts -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-start pb-1">
                                    <div>
                                        <h4 class="card-title mb-25">Sales</h4>
                                        <p class="card-text">Last 6 months</p>
                                    </div>
                                    <div class="dropdown chart-dropdown">
                                        <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Last 28 Days</a>
                                            <a class="dropdown-item" href="#">Last Month</a>
                                            <a class="dropdown-item" href="#">Last Year</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-inline-block me-1">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="circle" class="font-small-3 text-primary me-50"></i>
                                            <h6 class="mb-0">Sales</h6>
                                        </div>
                                    </div>
                                    <div class="d-inline-block">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="circle" class="font-small-3 text-info me-50"></i>
                                            <h6 class="mb-0">Visits</h6>
                                        </div>
                                    </div>
                                    <div id="sales-visit-chart" class="mt-50"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Sales Stats Chart Card ends -->

                        <!-- App Design Card -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-app-design">
                                <div class="card-body">
                                    <span class="badge badge-light-primary">03 Sep, 20</span>
                                    <h4 class="card-title mt-1 mb-75 pt-25">App design</h4>
                                    <p class="card-text font-small-2 mb-2">
                                        You can Find Only Post and Quotes Related to IOS like ipad app design, iphone app design
                                    </p>
                                    <div class="design-group mb-2 pt-50">
                                        <h6 class="section-label">Team</h6>
                                        <span class="badge badge-light-warning me-1">Figma</span>
                                        <span class="badge badge-light-primary">Wireframe</span>
                                    </div>
                                    <div class="design-group pt-25">
                                        <h6 class="section-label">Members</h6>
                                        <div class="avatar">
                                            <img src="{{ asset('images/portrait/small/avatar-s-9.jpg') }}" width="34" height="34" alt="Avatar" />
                                        </div>
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content">PI</div>
                                        </div>
                                        <div class="avatar">
                                            <img src="{{ asset('images/portrait/small/avatar-s-14.jpg') }}" width="34" height="34" alt="Avatar" />
                                        </div>
                                        <div class="avatar">
                                            <img src="{{ asset('images/portrait/small/avatar-s-7.jpg') }}" width="34" height="34" alt="Avatar" />
                                        </div>
                                        <div class="avatar bg-light-secondary">
                                            <div class="avatar-content">AL</div>
                                        </div>
                                    </div>
                                    <div class="design-planning-wrapper mb-2 py-75">
                                        <div class="design-planning">
                                            <p class="card-text mb-25">Due Date</p>
                                            <h6 class="mb-0">12 Apr, 21</h6>
                                        </div>
                                        <div class="design-planning">
                                            <p class="card-text mb-25">Budget</p>
                                            <h6 class="mb-0">$49251.91</h6>
                                        </div>
                                        <div class="design-planning">
                                            <p class="card-text mb-25">Cost</p>
                                            <h6 class="mb-0">$840.99</h6>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary w-100">Join Team</button>
                                </div>
                            </div>
                        </div>
                        <!--/ App Design Card -->
                    </div> --}}
                </section>
                <!-- Dashboard Analytics end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
    @push('page.js')
        <script src="{{ asset('vendors/js/charts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('js/scripts/pages/dashboard-analytics.js') }}"></script>
    @endpush
</x-app-layout>
