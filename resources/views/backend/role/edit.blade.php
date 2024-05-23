@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="bg-light rounded h-100 p-4">
                {{-- <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-4">Role</h6>
                            </div>
                            <div>
                                <a href="{{ route('role.index') }}">
                                    <button type="button" class="btn btn-danger"><-back </button>
                                </a>
                            </div>

                        </div>


                        
                        <form action="{{ route('role.update', $role->id) }}" class="form-horizontal" method="post">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" aria-describedby="namehelp" value="{{ $role->name }}"
                                    required>
                                @error('name')
                                    <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>


                            <div>

                                <div class="mb-3">
                                    <div class="usergrouplist">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="col-md-3 addusers">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                    data-bs-whatever="@mdo">Add Users</button>

                                                @if ($selectedusers != [])
                                                    <div class="col-md-12 mt-3">
                                                        <select id="" class="form-control " multiple disabled>
                                                            @foreach ($selectedusers as $item)
                                                                <option selected>
                                                                    {{ @$item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-3 addgroups">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalgroups"
                                                    data-bs-whatever="@mdo">Add Groups</button>
                                                @if ($selectedgroups != [])
                                                    <div class="col-md-12 mt-3">
                                                        <select id="" class="form-control " multiple disabled>
                                                            @foreach ($selectedgroups as $item)
                                                                <option selected>
                                                                    {{ @$item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>


                                        </div>


                                        <div class="modal fade" id="exampleModalusers" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3 text-start">
                                                                    <label for="filter">Users&nbsp;</label><input
                                                                        id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Users">
                                                                    <br />

                                                                    <div id="mdi"
                                                                        style="max-height: 10%; overflow:auto;">
                                                                        @foreach ($users as $item)
                                                                            <span><input class="talents_idmd-checkbox"
                                                                                    onchange="dragdrop1(this.value, this.id);"
                                                                                    type="checkbox"
                                                                                    id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                    value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                        @endforeach
                                                                    </div>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="users">Selected Users</label>
                                                                    <select name="user_list[]" id=""
                                                                        class="form-control" multiple>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal fade" id="exampleModalgroups" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add Group</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3 text-start">
                                                                    <label for="filter">Groups&nbsp;</label><input
                                                                        id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Groups">
                                                                    <br />

                                                                    <div id="mdi"
                                                                        style="max-height: 10%; overflow:auto;">
                                                                        @foreach ($groups as $item)
                                                                            <span><input class="talents_idmd-checkbox"
                                                                                    onchange="dragdrop(this.value, this.id);"
                                                                                    type="checkbox"
                                                                                    id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                    value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                        @endforeach
                                                                    </div>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="users">Selected Groups</label>
                                                                    <select name="group_list[]" id=""
                                                                        class="form-control" multiple>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <input type="hidden" value="{{ auth()->id() }}" name="updated_by">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div> --}}


                <nav>
                    <div class="nav nav-tabs  nav-pills" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                            type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                            General
                        </button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                            Content
                        </button>
                    </div>
                </nav>
                <form action="{{ route('role.update', $role->id) }}" class="form-horizontal" method="post">
                    @csrf
                    @method('PUT')
                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <h4 class="my-4">General Information</h4>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" aria-describedby="namehelp" value="{{ $role->name }}"
                                    required>
                                @error('name')
                                    <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>


                            <div>

                                <div class="mb-3">
                                    <div class="usergrouplist">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="col-md-3 addusers">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                    data-bs-whatever="@mdo">Add Users</button>

                                                @if ($selectedusers != [])
                                                    <div class="col-md-12 mt-3">
                                                        <select id="" class="form-control " multiple disabled>
                                                            @foreach ($selectedusers as $item)
                                                                <option selected>
                                                                    {{ @$item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-3 addgroups">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalgroups"
                                                    data-bs-whatever="@mdo">Add Groups</button>
                                                @if ($selectedgroups != [])
                                                    <div class="col-md-12 mt-3">
                                                        <select id="" class="form-control " multiple disabled>
                                                            @foreach ($selectedgroups as $item)
                                                                <option selected>
                                                                    {{ @$item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>


                                        </div>


                                        <div class="modal fade" id="exampleModalusers" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3 text-start">
                                                                    <label for="filter">Users&nbsp;</label><input
                                                                        id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Users">
                                                                    <br />

                                                                    <div id="mdi"
                                                                        style="max-height: 10%; overflow:auto;">
                                                                        @foreach ($users as $item)
                                                                            <span><input class="talents_idmd-checkbox"
                                                                                    onchange="dragdrop1(this.value, this.id);"
                                                                                    type="checkbox"
                                                                                    id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                    value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                        @endforeach
                                                                    </div>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="users">Selected Users</label>
                                                                    <select name="user_list[]" id=""
                                                                        class="form-control" multiple>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal fade" id="exampleModalgroups" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add Group</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3 text-start">
                                                                    <label for="filter">Groups&nbsp;</label><input
                                                                        id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Groups">
                                                                    <br />

                                                                    <div id="mdi"
                                                                        style="max-height: 10%; overflow:auto;">
                                                                        @foreach ($groups as $item)
                                                                            <span><input class="talents_idmd-checkbox"
                                                                                    onchange="dragdrop(this.value, this.id);"
                                                                                    type="checkbox"
                                                                                    id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                    value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                        @endforeach
                                                                    </div>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="users">Selected Groups</label>
                                                                    <select name="group_list[]" id=""
                                                                        class="form-control" multiple>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            {{-- <h4 class="my-4">Template Design</h4> --}}
                            <table class="table text-start align-middle table-bordered table-hover mt-5">
                                <thead>
                                    <tr class="text-dark">
                                        <th scope="col">Application Name</th>
                                        <th scope="col">Import</th>
                                        <th scope="col">Create</th>
                                        <th scope="col">Read</th>
                                        <th scope="col">Update</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                        <tr>
                                            <td>
                                                <a href="{{ route('application.edit', $application->id) }}">
                                                    {{ $application->name }}</a>
                                            </td>

                                            @foreach ($permissions as $permission)
                                                <td>
                                                    @php
                                                        $isChecked = collect($existingPermissions)->contains(function (
                                                            $existingPermission,
                                                        ) use ($application, $permission) {
                                                            return $existingPermission['application_id'] ==
                                                                $application->id &&
                                                                $existingPermission['permission_id'] == $permission->id;
                                                        });
                                                    @endphp
                                                    <input type="checkbox"
                                                        id="{{ $application->id }}_{{ $permission->id }}"
                                                        name="permissions[{{ $application->id }}][]"
                                                        value="{{ $permission->id }}" {{ $isChecked ? 'checked' : '' }}>

                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary mt-5">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->


    <script>
        //script for js

        const filterEl = document.querySelector('#filter');
        const els = Array.from(document.querySelectorAll('#mdi > span'));
        const labels = els.map(el => el.textContent);
        const handler = value => {
            const matching = labels.map((label, idx, arr) => label.toLowerCase().includes(value.toLowerCase()) ? idx :
                null).filter(el => el != null);

            els.forEach((el, idx) => {
                if (matching.includes(idx)) {
                    els[idx].style.display = 'block';
                } else {
                    els[idx].style.display = 'none';
                }
            });
        };

        filterEl.addEventListener('keyup', () => handler.call(null, filterEl.value));


        function dragdrop(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('group_list[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('group_list[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }

        function dragdrop1(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('user_list[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('user_list[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }

        function dragdrop3(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('application_id[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('application_id[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }
    </script>
@endsection
