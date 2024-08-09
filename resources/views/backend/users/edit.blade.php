@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
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
                                    <form action="{{ route('users.update', $user->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">First Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" id="name" aria-describedby="namehelp"
                                                value="{{ $user->name }}" required>
                                            @error('name')
                                                <label id="name-error" class="error text-danger"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                            <div id="namehelp" class="form-text">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Last Name</label>
                                            <input type="text"
                                                class="form-control @error('lastname') is-invalid @enderror" name="lastname"
                                                id="lastname" aria-describedby="lastnamehelp" value="{{ $user->lastname }}"
                                                required>
                                            @error('lastname')
                                                <label id="lastname-error" class="error text-danger"
                                                    for="lastname">{{ $message }}</label>
                                            @enderror
                                            <div id="lastnamehelp" class="form-text">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">User ID</label>
                                            <input type="text"
                                                class="form-control @error('custom_userid') is-invalid @enderror"
                                                name="custom_userid" id="custom_userid" aria-describedby="custom_useridhelp"
                                                value="{{ $user->custom_userid }}" required>
                                            @error('custom_userid')
                                                <label id="custom_userid-error" class="error text-danger"
                                                    for="custom_userid">{{ $message }}</label>
                                            @enderror
                                            <div id="custom_useridhelp" class="form-text">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Email</label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" aria-describedby="namehelp"
                                                value="{{ $user->email }}">
                                            @error('email')
                                                <label id="email-error" class="error text-danger"
                                                    for="email">{{ $message }}</label>
                                            @enderror
                                            <div id="namehelp" class="form-text">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label @error('mobile_no') is-invalid @enderror">Mobile
                                                No</label>
                                            <input type="text" class="form-control" id="name" name="mobile_no"
                                                aria-describedby="namehelp" value="{{ $user->mobile_no }}">
                                            @error('mobile_no')
                                                <label id="mobile_no-error" class="error text-danger"
                                                    for="mobile_no">{{ $message }}</label>
                                            @enderror
                                            <div id="namehelp" class="form-text">
                                            </div>
                                        </div>

                                        <div class="mb-3">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label @error('password') is-invalid @enderror">Password</label>
                                                    <input type="password" class="form-control" id="name"
                                                        name="password" aria-describedby="namehelp">
                                                    @error('password')
                                                        <label id="password-error" class="error text-danger"
                                                            for="password">{{ $message }}</label>
                                                    @enderror
                                                    <div id="namehelp" class="form-text">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label @error('repassword') is-invalid @enderror">Confirm-Password</label>
                                                    <input type="password" class="form-control" id="name"
                                                        name="password_confirmation" aria-describedby="namehelp">
                                                    @error('repassword')
                                                        <label id="repassword-error" class="error text-danger"
                                                            for="repassword">{{ $message }}</label>
                                                    @enderror
                                                    <div id="namehelp" class="form-text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <div class="col-md-6">
                                                <label for="exampleInputEmail1"
                                                    class="form-label @error('department') is-invalid @enderror">Status</label>
                                                <select name="status" id="" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">In-Active</option>
                                                </select>
                                                @error('status')
                                                    <label id="status-error" class="error text-danger"
                                                        for="status">{{ $message }}</label>
                                                @enderror
                                                <div id="namehelp" class="form-text">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <label for="remarks"
                                                class="form-label"><strong>SHHkey/Token/Certificate</strong></label>
                                            <textarea name="remarks" id="" cols="30" rows="4" class="form-control">{{ $user->remarks }}</textarea>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="exampleInputEmail1"
                                                class="form-label @error('department') is-invalid @enderror">Groups</label>

                                            <div class="col-md-4 showaddbtn mb-4">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                    data-bs-whatever="@mdo">Add Group</button>
                                            </div>
                                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Groups
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        {{-- <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="filter">Groups&nbsp;</label>
                                                                    <input id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Groups">
                                                                    <br />
                                                                    <div class="mb-3 mt-3 text-start">


                                                                        <div id="mdi"
                                                                            style="max-height: 10%; overflow:auto;">
                                                                            @foreach ($groups as $item)
                                                                                <span>
                                                                                    <input class="talents_idmd-checkbox"
                                                                                        onchange="dragdrop(this.value, this.id);"
                                                                                        @if (in_array($item->id, $groupids ?? [])) checked @endif
                                                                                        type="checkbox"
                                                                                        id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                        value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}
                                                                                </span><br>
                                                                            @endforeach
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="mb-3">
                                                                        <label for="users">Selected Groups</label>
                                                                        <select name="group_id[]" id=""
                                                                            class="form-control" multiple>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        {{-- 
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="filter">Groups&nbsp;</label>
                                                                    <input id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Groups">
                                                                    <br />
                                                                    <div class="mb-3 mt-3 text-start">
                                                                        <div id="mdi"
                                                                            style="max-height: 10%; overflow:auto;">
                                                                            @foreach ($groups as $item)
                                                                                <span>
                                                                                    <input class="talents_idmd-checkbox"
                                                                                        onchange="dragdrop(this.value, this.id);"
                                                                                        @if (in_array($item->id, $groupids ?? [])) checked @endif
                                                                                        type="checkbox"
                                                                                        id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                        value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}
                                                                                </span><br>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="mb-3">
                                                                        <label for="users">Selected Groups</label>
                                                                        <select name="group_id[]" id="selected-groups"
                                                                            class="form-control" multiple>
                                                                            @foreach ($groups as $item)
                                                                                @if (in_array($item->id, $groupids ?? []))
                                                                                    <option value="{{ $item->id }}"
                                                                                        id="{{ $item->id }}" selected>
                                                                                        {{ $item->name . ' ' . $item->lastname }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}

                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="filter">Groups&nbsp;</label>
                                                                    <input id="filter" type="text"
                                                                        class="filter form-control"
                                                                        placeholder="Search Groups">
                                                                    <br />
                                                                    <div class="mb-3 mt-3 text-start">
                                                                        <div id="mdi"
                                                                            style="max-height: 10%; overflow:auto;">
                                                                            @foreach ($groups as $item)
                                                                                <span>
                                                                                    <input class="talents_idmd-checkbox"
                                                                                        onchange="dragdrop(this.value, this.id);"
                                                                                        @if (in_array($item->id, $groupids ?? [])) checked @endif
                                                                                        type="checkbox"
                                                                                        id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                        value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}
                                                                                </span><br>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="mb-3">
                                                                        <label for="users">Selected Groups</label>
                                                                        <select name="group_id[]" id="selected-groups"
                                                                            class="form-control" multiple>
                                                                            @foreach ($groups as $item)
                                                                                @if (in_array($item->id, $groupids ?? []))
                                                                                    <option value="{{ $item->id }}"
                                                                                        id="option-{{ $item->id }}"
                                                                                        selected>
                                                                                        {{ $item->name . ' ' . $item->lastname }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
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

                                            <select id="" class="form-control" multiple>
                                                @if ($groupids != null)
                                                    @foreach ($groups as $item)
                                                        @if (in_array($item->id, $groupids))
                                                            <option value="">{{ $item->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('group_id')
                                                <label id="group_id-error" class="error text-danger"
                                                    for="group_id">{{ $message }}</label>
                                            @enderror
                                            <div id="namehelp" class="form-text">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div><!-- End Left side columns -->
            </div>
        </section>
        <!-- Recent Sales End -->
    </main>

    <script>
        var status = "{{ $user->status }}";
        var statuselement = document.getElementsByName('status')[0];

        for (let index = 0; index < statuselement.length; index++) {
            if (statuselement[index].value == status) {
                statuselement[index].selected = true;
            }

        }

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

        // function dragdrop(value, name) {
        //     if (document.getElementById(name).checked) {
        //         var userselect = document.getElementsByName('group_id[]')[0];
        //         var option = document.createElement('option');
        //         option.value = value;
        //         option.id = value;
        //         option.innerText = name;
        //         option.selected = true;
        //         userselect.appendChild(option);
        //     } else {
        //         var userselect = document.getElementsByName('group_id[]')[0];
        //         var removeoption = document.getElementById(value);
        //         userselect.removeChild(removeoption);
        //     }
        // }

        // function dragdrop(value, name) {
        //     var checkbox = document.getElementById(name);
        //     var userselect = document.getElementById('selected-groups');

        //     if (checkbox.checked) {
        //         if (!document.getElementById(value)) {
        //             var option = document.createElement('option');
        //             option.value = value;
        //             option.id = value;
        //             option.innerText = name;
        //             option.selected = true;
        //             userselect.appendChild(option);
        //         }
        //     } else {
        //         var removeoption = document.getElementById(value);
        //         if (removeoption) {
        //             userselect.removeChild(removeoption);
        //         }
        //     }
        // }

        // // Ensure existing selected groups are shown when the modal is loaded
        // document.addEventListener('DOMContentLoaded', function() {
        //     var groupIds = @json($groupids ?? []);
        //     var userselect = document.getElementById('selected-groups');

        //     groupIds.forEach(function(groupId) {
        //         var checkbox = document.querySelector('input[value="' + groupId + '"]');
        //         if (checkbox) {
        //             checkbox.checked = true;
        //             var name = checkbox.id;
        //             if (!document.getElementById(groupId)) {
        //                 var option = document.createElement('option');
        //                 option.value = groupId;
        //                 option.id = groupId;
        //                 option.innerText = name;
        //                 option.selected = true;
        //                 userselect.appendChild(option);
        //             }
        //         }
        //     });
        // });
        function dragdrop(value, name) {
            var checkbox = document.getElementById(name);
            var userselect = document.getElementById('selected-groups');

            if (checkbox.checked) {
                if (!document.getElementById('option-' + value)) {
                    var option = document.createElement('option');
                    option.value = value;
                    option.id = 'option-' + value;
                    option.innerText = name;
                    option.selected = true;
                    userselect.appendChild(option);
                }
            } else {
                var removeoption = document.getElementById('option-' + value);
                if (removeoption) {
                    userselect.removeChild(removeoption);
                }
            }
        }

        // Ensure existing selected groups are shown when the modal is loaded
        document.addEventListener('DOMContentLoaded', function() {
            var groupIds = @json($groupids ?? []);
            var userselect = document.getElementById('selected-groups');

            groupIds.forEach(function(groupId) {
                var checkbox = document.querySelector('input[value="' + groupId + '"]');
                if (checkbox) {
                    checkbox.checked = true;
                    var name = checkbox.id;
                    if (!document.getElementById('option-' + groupId)) {
                        var option = document.createElement('option');
                        option.value = groupId;
                        option.id = 'option-' + groupId;
                        option.innerText = name;
                        option.selected = true;
                        userselect.appendChild(option);
                    }
                }
            });
        });
    </script>
@endsection
