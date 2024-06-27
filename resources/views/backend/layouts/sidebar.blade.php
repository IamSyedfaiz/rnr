{{-- <div class="sidebar pe-4 pb-3">
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

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa fa-tasks me-2"></i>Applications</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('application.index') }}" class="dropdown-item">View All</a>

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Groups</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('group.index') }}" class="dropdown-item">View All</a>

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Roles Permission</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('role.index') }}" class="dropdown-item">View All</a>

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
                            class="fa fa-exclamation-triangle me-2"></i>Logs</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ url('logs') }}" class="dropdown-item">View All</a>

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>MFA</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="#" class="dropdown-item">View All</a>

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                            class="fa fa-exclamation-triangle me-2"></i>Notifications</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('notifications.index') }}" class="dropdown-item">View All</a>

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
                @php
                    $user = Auth::user();
                    $userId = $user->id;
                    $directRoles = App\Models\backend\Role::whereJsonContains('user_list', (string) $userId)
                        ->with('permissions.applications')
                        ->get();

                    $groupIds = App\Models\backend\Group::whereJsonContains('userids', (string) $userId)
                        ->pluck('id')
                        ->toArray();

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
</div> --}}
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">

            @if (auth()->user()->role == 'admin')
                <a href="{{ route('backend.home') }}" class="nav-link">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="{{ route('user.backend.home') }}" class="nav-link">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        </li>

        @if (auth()->user()->role == 'admin')

            {{-- <div class="nav-item dropdown">
                <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                        class="fa fa-exclamation-triangle me-2"></i>Dashboard</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('dashboard.index') }}" class="dropdown-item">View All</a>
                </div>
            </div> --}}
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('application.index') }}">
                    <i class="bi bi-window-stack"></i>
                    <span>Applications</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('group.index') }}">
                    <i class="bi bi-layers"></i>
                    <span>Groups</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('role.index') }}">
                    <i class="bi bi-list-check"></i>
                    <span>Roles & Permissions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('logs') }}">
                    <i class="bi bi-book"></i>
                    <span>Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#integration" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-link-45deg"></i>
                    <span>Integration</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="integration" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                    <li>
                        <a href="{{ route('data.feed') }}">
                            <i class="bi bi-circle"></i><span>Data Feed</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('data.imports') }}">
                            <i class="bi bi-circle"></i><span>Data Imports</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('notifications.index') }}">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('get.view') }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Reporting</span>
                </a>
            </li>
        @else
            @php
                $user = Auth::user();
                $userId = $user->id;
                $directRoles = App\Models\backend\Role::whereJsonContains('user_list', (string) $userId)
                    ->with('permissions.applications')
                    ->get();

                $groupIds = App\Models\backend\Group::whereJsonContains('userids', (string) $userId)
                    ->pluck('id')
                    ->toArray();

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

            {{-- <div class="nav-item dropdown">
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
            </div> --}}


            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#applications" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-link-45deg"></i>
                    <span>Applications</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="applications" class="nav-content collapse " data-bs-parent="#sidebar-nav">


                    @if (count($applications) === 0)
                        <li>
                            <a href="#">
                                <i class="bi bi-circle"></i><span>No applications</span>
                            </a>
                        </li>
                    @else
                        @foreach ($applications as $item)
                            <li>
                                <a href="{{ route('userapplication.list', $item->id) }}">
                                    <i class="bi bi-circle"></i><span> {{ $item->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('get.view') }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Reporting</span>
                </a>
            </li>
            {{-- <div class="nav-item dropdown">
                <a href="#" class="nav-item nav-link dropdown-toggle " data-bs-toggle="dropdown"><i
                        class="fa fa-exclamation-triangle me-2"></i>Dashboard</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('dashboard.index') }}" class="dropdown-item">View All</a>
                </div>
            </div> --}}
        @endif


    </ul>

</aside>
