@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Add Role</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('role.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-short"></i>
                        Back</a>
                </div>
            </div>
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade in show col-md-12 mt-2">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade in show col-md-12 mt-2">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Role</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Reports -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body pt-3">
                                    <nav>
                                        <div class="nav nav-tabs  nav-pills" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-home" type="button" role="tab"
                                                aria-controls="nav-home" aria-selected="true">
                                                General
                                            </button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-profile" type="button" role="tab"
                                                aria-controls="nav-profile" aria-selected="false">
                                                Rights
                                            </button>
                                            <button class="nav-link" id="nav-group-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-group" type="button" role="tab"
                                                aria-controls="nav-group" aria-selected="false">
                                                Groups
                                            </button>

                                        </div>
                                    </nav>
                                    <form action="{{ route('role.store') }}" class="form-horizontal" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="tab-content" id="nav-tabContent">

                                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                                aria-labelledby="nav-home-tab">
                                                <h4 class="my-4">General Information</h4>
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Name</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" id="name" aria-describedby="namehelp" required>
                                                    @error('name')
                                                        <label id="name-error" class="error text-danger"
                                                            for="name">{{ $message }}</label>
                                                    @enderror
                                                    <div id="namehelp" class="form-text">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Description</label>
                                                    <input type="text"
                                                        class="form-control @error('description') is-invalid @enderror"
                                                        name="description" id="description"
                                                        aria-describedby="descriptionhelp" required>
                                                    @error('description')
                                                        <label id="description-error" class="error text-danger"
                                                            for="description">{{ $message }}</label>
                                                    @enderror
                                                    <div id="descriptionhelp" class="form-text">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="attachment" class="form-label">Attachment</label>
                                                    <input type="file"
                                                        class="form-control @error('attachment') is-invalid @enderror"
                                                        name="attachment" id="attachment">
                                                    @error('attachment')
                                                        <label id="attachment-error" class="error text-danger"
                                                            for="attachment">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                                <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                                            </div>
                                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                                aria-labelledby="nav-profile-tab">
                                                <table class="table datatable">
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
                                                                    <a
                                                                        href="{{ route('application.edit', $application->id) }}">
                                                                        {{ $application->name }}</a>
                                                                </td>

                                                                @foreach ($permissions as $permission)
                                                                    <td>
                                                                        <input type="checkbox"
                                                                            name="permission[{{ $application->id }}][]"
                                                                            value="{{ $permission->id }}"
                                                                            id="{{ $permission->id }}">
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane fade" id="nav-group" role="tabpanel"
                                                aria-labelledby="nav-group-tab">
                                                <div>

                                                    <div class="my-3">
                                                        {{-- <label for="exampleInputEmail1" class="form-label">{{ strtoupper($item->name) }}</label> --}}
                                                        <div class="usergrouplist">
                                                            <div class="d-flex  mb-2">
                                                                {{-- <div class="col-md-6 addusers">
                                                                    <button type="button" class="btn btn-primary text-end"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#exampleModalusers"
                                                                        data-bs-whatever="@mdo">Add
                                                                        Users</button>
                                                                    <div class="col-md-10">
                                                                        <div class="mb-3">
                                                                            <label for="users">Selected
                                                                                Users</label>
                                                                            <select name="user_list[]" id=""
                                                                                class="form-control" multiple>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div> --}}

                                                                <div class="col-md-12 addgroups">
                                                                    <button type="button"
                                                                        class="btn btn-primary text-end"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#exampleModalgroups"
                                                                        data-bs-whatever="@mdo">Add
                                                                        Groups</button>
                                                                    <div class="col-12 mt-3">
                                                                        <div class="mb-3">
                                                                            <label for="users">Selected
                                                                                Groups</label>
                                                                            <select name="group_list[]" id=""
                                                                                class="form-control" multiple
                                                                                style="height: 200px">
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade" id="exampleModalusers" tabindex="-1"
                                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">
                                                                                Add Users</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <label
                                                                                        for="filter">Users&nbsp;</label><input
                                                                                        id="filter" type="text"
                                                                                        class="filter form-control"
                                                                                        placeholder="Search Users">
                                                                                    <br />
                                                                                    <div class="mb-3 mt-3 text-start">


                                                                                        <div id="mdi"
                                                                                            style="max-height: 10%; overflow:auto;">
                                                                                            @foreach ($users as $item)
                                                                                                <span><input
                                                                                                        class="talents_idmd-checkbox"
                                                                                                        onchange="dragdrop1(this.value, this.id);"
                                                                                                        type="checkbox"
                                                                                                        id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                                        value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                                            @endforeach
                                                                                        </div>


                                                                                    </div>
                                                                                </div>
                                                                                {{-- <div class="col-md-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="users">Selected
                                                                                            Users</label>
                                                                                        <select name="user_list[]"
                                                                                            id=""
                                                                                            class="form-control" multiple>
                                                                                        </select>
                                                                                    </div>
                                                                                </div> --}}
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="modal fade" id="exampleModalgroups"
                                                                tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">Add Group</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <label
                                                                                        for="filter">Groups&nbsp;</label><input
                                                                                        id="filter" type="text"
                                                                                        class="filter form-control"
                                                                                        placeholder="Search Groups">
                                                                                    <br />
                                                                                    <div class="mb-3 mt-3 text-start">

                                                                                        <div id="mdi"
                                                                                            style="max-height: 10%; overflow:auto;">
                                                                                            @foreach ($groups as $item)
                                                                                                <span><input
                                                                                                        class="talents_idmd-checkbox"
                                                                                                        onchange="dragdrop(this.value, this.id);"
                                                                                                        type="checkbox"
                                                                                                        id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                                        value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                                            @endforeach
                                                                                        </div>


                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <button type="submit" class="btn btn-primary mt-5">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Left side columns -->
                </div>
        </section>
    </main><!-- End #main -->
    <!-- Recent Sales End -->
    <script>
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
