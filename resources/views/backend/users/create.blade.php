@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Create User</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-short"></i>
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
                    <li class="breadcrumb-item active">Create User</li>
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
                                    <form class="row g-3" action="{{ route('users.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-12">
                                            @if (Session::has('error'))
                                                <div class="alert alert-danger alert-dismissible fade in show col-md-12">
                                                    <strong>Error!</strong> {{ session('error') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            @endif

                                            @if (Session::has('success'))
                                                <div class="alert alert-success alert-dismissible fade in show col-md-12">
                                                    <strong>Success!</strong> {{ session('success') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            @endif
                                            <label for="inputNanme4" class="form-label">First Name</label>
                                            {{-- <input type="text" class="form-control" id="inputNanme4"> --}}
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" id="name" aria-describedby="namehelp" required>
                                            @error('name')
                                                <label id="name-error" class="error text-danger"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="inputNanme4" class="form-label">Last Name</label>
                                            <input type="text"
                                                class="form-control @error('lastname') is-invalid @enderror" name="lastname"
                                                id="lastname" aria-describedby="lastnamehelp" required>
                                            @error('lastname')
                                                <label id="lastname-error" class="error text-danger"
                                                    for="lastname">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="inputNanme4" class="form-label">User ID</label>
                                            <input type="text"
                                                class="form-control @error('lastname') is-invalid @enderror"
                                                name="custom_userid" id="lastname" aria-describedby="lastnamehelp"
                                                required>
                                            @error('lastname')
                                                <label id="lastname-error" class="error text-danger"
                                                    for="lastname">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="inputEmail4" class="form-label">Email</label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" aria-describedby="namehelp" required>
                                            @error('email')
                                                <label id="email-error" class="error text-danger"
                                                    for="email">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="inputNanme4" class="form-label">Mobile No.</label>
                                            <input type="text" class="form-control" id="name" name="mobile_no"
                                                aria-describedby="namehelp" required>
                                            @error('mobile_no')
                                                <label id="mobile_no-error" class="error text-danger"
                                                    for="mobile_no">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputPassword4" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="name" name="password"
                                                aria-describedby="namehelp">
                                            @error('password')
                                                <label id="password-error" class="error text-danger"
                                                    for="password">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputPassword4" class="form-label">Re-Password</label>
                                            <input type="password" class="form-control" id="name" name="repassword"
                                                aria-describedby="namehelp">
                                            @error('repassword')
                                                <label id="repassword-error" class="error text-danger"
                                                    for="repassword">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            {{-- <label for="inputNanme4" class="form-label">Group</label>
                                            <div id="checkboxes2">
                                                <div class="control">
                                                    <input class="input form-control" type="text" placeholder="Search"
                                                        id="search" />
                                                    <span class="icon is-small is-left">
                                                        <span class="searchIcon"></span>
                                                    </span>
                                                </div>
                                                @foreach ($groups as $item)
                                                    <span>
                                                        <label for="car" class="select_label w-100 my-1">
                                                            <input type="checkbox" value="{{ $item->id }}"
                                                                name="groups_id[]" id="car" />
                                                            {{ $item->name . ' ' . $item->lastname }}
                                                            <span class="select_label-icon"></span>
                                                        </label>
                                                @endforeach
                                            </div>
                                            <script type="text/javascript">
                                                const search = document.getElementById("search");
                                                const labels = document.querySelectorAll("#checkboxes2 > label");

                                                search.addEventListener("input", () => Array.from(labels).forEach((element) => element.style.display = element
                                                    .childNodes[1].id.toLowerCase().includes(search.value.toLowerCase()) ? "inline" : "none"))
                                            </script> --}}

                                        </div>
                                        {{-- <div class="col-md-6">
                                            <label for="inputNanme4" class="form-label">Selected Groups</label>
                                            <select class="form-control form-select" multiple disabled>
                                                <option>
                                                    Selected
                                                </option>
                                            </select>
                                        </div> --}}
                                        <div class="col-md-6">
                                            <label for="inputAddress" class="form-label">Status</label>

                                            <select name="status" id="" class="form-control form-select"
                                                required>
                                                <option value="">Select Status</option>
                                                <option selected value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">SHHkey/Token/Certificate</label>
                                            <textarea class="form-control textarea" name="remarks"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="exampleInputEmail1"
                                                class="form-label @error('department') is-invalid @enderror">Groups</label>

                                            <div class="col-md-6 showaddbtn">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add
                                                    Group</button>
                                            </div>
                                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">New Group
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="filter">Groups&nbsp;</label><input
                                                                        id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Groups">
                                                                    <br />
                                                                    <div class="mb-3 mt-5 text-start">

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
                                                                {{-- <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="users">Selected Groups</label>
                                                                            <select name="group_id[]" id=""
                                                                                class="form-control" multiple>
                                                                            </select>
                                                                        </div>
                                                                    </div> --}}
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            @error('group_id')
                                                <label id="group_id-error" class="error text-danger"
                                                    for="group_id">{{ $message }}</label>
                                            @enderror
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="users">Selected Groups</label>
                                                    <select name="group_id[]" id="" class="form-control"
                                                        multiple>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="namehelp" class="form-text">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div><!-- End Left side columns -->
            </div>
        </section>
    </main>
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
                var userselect = document.getElementsByName('group_id[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('group_id[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }
    </script>
@endsection
