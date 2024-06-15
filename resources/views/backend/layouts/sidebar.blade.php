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
            <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
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
                            class="fa fa-exclamation-triangle me-2"></i>Dashboard</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('dashboard.index') }}" class="dropdown-item">View All</a>
                    </div>
                </div>
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
                            class="fa fa-exclamation-triangle me-2"></i>Dashboard</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('dashboard.index') }}" class="dropdown-item">View All</a>
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
            @else
                {{-- @php
                    $roles = App\Models\backend\Role::all();
                    $allApplicationsIds = [];
                    foreach ($roles as $role) {
                        $userIdsJson = $role->user_list;
                        $userIdsArray = json_decode($userIdsJson, true);

                        $groupsIdsJson = $role->group_list;
                        $groupsIdsArray = Helper::findusers($role->group_list);
                        $mergedIdsArray = [];

                        if ($userIdsArray !== null) {
                            $mergedIdsArray = array_merge($mergedIdsArray, $userIdsArray);
                        }

                        if ($groupsIdsArray !== null) {
                            $mergedIdsArray = array_merge($mergedIdsArray, $groupsIdsArray);
                        }
                        if ($role->application_id) {
                            $applicationsIdsJson = $role->application_id;
                            $applicationsIdsArray = json_decode($applicationsIdsJson, true);
                            $useridfound = false;
                            if (in_array(auth()->id(), $mergedIdsArray)) {
                                $useridfound = true;
                            }

                            if ($useridfound) {
                                $allApplicationsIds = array_merge($allApplicationsIds, $applicationsIdsArray);
                            }
                        } else {
                            $applications = [];
                        }
                    }
                    $uniqueApplicationsIds = array_unique($allApplicationsIds);
                    if (!empty($uniqueApplicationsIds)) {
                        $applications = App\Models\backend\Application::whereIn('id', $uniqueApplicationsIds)->get();
                    } else {
                        $applications = [];
                    }
                    $loggedinuser = auth()->id();
                    $userapplication = [];
                    $userId = [];
                @endphp --}}
                @php
                    $user = Auth::user();
                    $userId = $user->id;
                    // $userRoles = App\Models\backend\Role::whereJsonContains('user_list', (string) $userId)
                    //     ->with('permissions')
                    //     ->get();
                    // $groupRoles = App\Models\backend\Group::whereJsonContains('userids', (string) $userId)->first();

                    // $userRoles = App\Models\backend\Role::whereJsonContains('group_list', (string) $groupRoles->id)
                    //     ->with('permissions')
                    //     ->get();
                    // Fetch roles directly assigned to the user
                    $directRoles = App\Models\backend\Role::whereJsonContains('user_list', (string) $userId)
                        ->with('permissions.applications')
                        ->get();

                    // Fetch groups the user belongs to
                    $groupIds = App\Models\backend\Group::whereJsonContains('userids', (string) $userId)
                        ->pluck('id')
                        ->toArray();

                    // Fetch roles associated with these groups
                    $groupRoles = App\Models\backend\Role::where(function ($query) use ($groupIds) {
                        foreach ($groupIds as $groupId) {
                            $query->orWhereJsonContains('group_list', (string) $groupId);
                        }
                    })
                        ->with('permissions.applications')
                        ->get();

                    // Combine both direct roles and group roles
                    $allRoles = $directRoles->merge($groupRoles);

                    // logger($allRoles);
                    // logger($groupRoles);
                    // logger($userId);
                    $applications = [];
                    foreach ($allRoles as $permission) {
                        foreach ($permission->applications as $application) {
                            if (!isset($applications[$application->id])) {
                                $applications[$application->id] = $application;
                            }
                        }
                    }
                @endphp

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fa fa-tasks me-2"></i>Applications</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        @if (count($applications) === 0)
                            <a class="dropdown-item disabled" href="#">No applications</a>
                        @else
                            @foreach ($applications as $item)
                                <a class="dropdown-item" href="{{ route('userapplication.list', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            @endforeach
                        @endif

                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Management Reporting</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('get.view') }}" class="dropdown-item">Reports Listing</a>

                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Dashboard</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('dashboard.index') }}" class="dropdown-item">View All</a>
                    </div>
                </div>
            @endif

        </div>
    </nav>
</div>
