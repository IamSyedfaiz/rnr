<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">

        @if (auth()->user()->role == 'admin')
            <a href="{{ route('backend.home') }}" class="navbar-brand mx-4 mb-3">
                <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
            </a>
        @else
            <a href="{{ route('user.backend.home') }}" class="navbar-brand mx-4 mb-3">
                <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
            </a>
        @endif

        <div class="d-flex align-items-center ms-4 mb-4">
            {{-- <div class="position-relative">
                <img class="rounded-circle" src="{{ asset('public/backend/dashmin/img/user.jpg') }}" alt=""
                    style="width: 40px; height: 40px;">
                <div
                    class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                </div>
            </div> --}}
            {{-- <div class="ms-3">
                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                <span>Admin</span>
            </div> --}}
        </div>
        <div class="navbar-nav w-100">
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('backend.home') }}" class="nav-item nav-link"><i
                        class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            @else
                <a href="{{ route('user.backend.home') }}" class="nav-item nav-link"><i
                        class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            @endif


            @if (auth()->user()->role == 'admin')
                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-user me-2"></i>Users</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('users.index') }}" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('users.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa fa-tasks me-2"></i>Applications</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('application.index') }}" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('application.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Groups</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('group.index') }}" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('group.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Roles Permission</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('role.index') }}" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('group.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Integration</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="#" class="dropdown-item">View All</a>
                        <a href="{{ route('data.feed') }}" class="dropdown-item">Data Feed</a>
                        <a href="{{ route('data.imports') }}" class="dropdown-item">Data Imports</a>

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Customer Dashboard</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="#" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('group.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Logs</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ url('logs') }}" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('group.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>MFA</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="#" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('group.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Notifications</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('notifications.index') }}" class="dropdown-item">View All</a>
                        {{-- <a href="{{ route('group.create') }}" class="dropdown-item">New</a> --}}

                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Management Reporting</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('get.view') }}" class="dropdown-item">Reports Listing</a>

                    </div>
                </div>
            @endif

            @if (auth()->user()->role != 'admin')
                @php
                    $loggedinuser = auth()->id();
                    // dd($userid);
                    $application = App\Models\backend\Application::where('status', 1)
                        // ->latest()
                        ->get();

                    $userapplication = [];
                    $userid = [];
                    // dd($application[1]->rolestable()->first());

                    for ($i = 0; $i < count($application); $i++) {
                        # code...
                        if ($application[$i]->rolestable()->get() != 'null' && $application[$i]->rolestable()->get() != null) {
                            $rolestablearray = $application[$i]->rolestable()->get();

                            for ($k = 0; $k < count($rolestablearray); $k++) {
                                if ($rolestablearray[$k]->group_list != 'null') {
                                    # code...
                                    array_push($userid, Helper::findusers($rolestablearray[$k]->group_list));
                                }
                                // dd(json_decode($rolestablearray[0]->user_list));
                                if ($rolestablearray[$k]->user_list != 'null') {
                                    # code...
                                    array_push($userid, json_decode($rolestablearray[$k]->user_list));
                                }
                            }

                            $useridfound = 'false';
                            // dd(in_array(auth()->id(), $userid[2]));
                            for ($j = 0; $j < count($userid); $j++) {
                                if (in_array(auth()->id(), $userid[$j])) {
                                    $useridfound = 'true';
                                }
                            }
                            // dd($useridfound);

                            if ($useridfound == 'true') {
                                array_push($userapplication, $application[$i]);
                            }
                        }
                    }

                @endphp

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa fa-tasks me-2"></i>Applications</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        {{-- <a href="{{ route('user-application.index') }}" class="dropdown-item">View All</a> --}}
                        {{-- <a href="{{ route('application.create') }}" class="dropdown-item">New</a> --}}
                        @foreach ($userapplication as $item)
                            <a class="dropdown-item" href="{{ route('userapplication.list', $item->id) }}">
                                {{ $item->name }}</a>
                        @endforeach

                    </div>
                </div>
                {{-- <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Management Reporting</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('get.view') }}" class="dropdown-item">Reports Listing</a>

                    </div>
                </div> --}}
            @endif

        </div>
    </nav>
</div>
