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
                        {{-- <a href="{{ route('group.create') }}" class="dropdown-item">New</a> --}}

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
            @endif

            @if (auth()->user()->role != 'admin')
                @php
                    $loggedinuser = auth()->id();
                    // dd($userid);
                    $application = App\Models\backend\Application::where('status', 1)
                        ->latest()
                        ->get();
                    
                    $userapplication = [];
                    $userid = [];
                    // dd($application[1]->rolestable()->first());
                    
                    for ($i = 0; $i < count($application); $i++) {
                        # code...
                        // dd($application[0]->rolestable()->first()->group_list);
                        // dd($application[1]->rolestable()->first() == null);
                        if ($application[$i]->rolestable()->first() != 'null' && $application[$i]->rolestable()->first() != null) {
                            echo is_null($application[$i]->rolestable()->first()->group_list);
                            if ($application[$i]->rolestable()->first()->group_list != 'null') {
                                # code...
                                array_push($userid, Helper::findusers($application[$i]->rolestable()->first()->group_list));
                            }
                    
                            if ($application[$i]->rolestable()->first()->user_list != 'null') {
                                # code...
                                array_push($userid, json_decode($application[$i]->rolestable()->first()->user_list));
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
                    $userapplication1 = App\Models\backend\Application::where(['access' => 'public', 'status' => 1])->get();
                    
                    // dd($userapplication, $userapplication1);
                    
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

                        @foreach ($userapplication1 as $item)
                            <a class="dropdown-item" href="{{ route('userapplication.list', $item->id) }}">
                                {{ $item->name }}</a>
                        @endforeach

                    </div>
                </div>
            @endif

        </div>
    </nav>
</div>
