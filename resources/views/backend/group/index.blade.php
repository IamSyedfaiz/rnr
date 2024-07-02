@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    {{-- <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Group</h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                    data-bs-whatever="@mdo">Add Group</button>

            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('group.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Group</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3 text-start">
                                    <label for="message-text" class="col-form-label fw-bold text-left ">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 text-start">
                                            <label for="filter">Users&nbsp;</label><input id="filter" type="text"
                                                class="filter form-control" placeholder="Search Username">
                                            <br />

                                            <div id="mdi" style="max-height: 10%; overflow:auto;">
                                                @foreach ($users as $item)
                                                    <span><input class="talents_idmd-checkbox"
                                                            onchange="dragdrop(this.value, this.id);" type="checkbox"
                                                            id="{{ $item->name . ' ' . $item->lastname }}"
                                                            value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                @endforeach
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="users">Selected Users</label>
                                            <select name="userids[]" id="" class="form-control" multiple>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-start">
                                    <label for="message-text" class="col-form-label fw-bold text-left ">Status</label>
                                    <select name="status" id="" class="form-control ">
                                        <option value="1">Active</option>
                                        <option value="0">In Active</option>
                                    </select>
                                </div>
                            </div>




                            <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('group.edit', $item->id) }}">{{ $item->name }}</a>
                                </td>
                                <td>
                                    @if ($item->status == 1)
                                        Active
                                    @else
                                        InActive
                                    @endif
                                </td>
                                @php
                                    if ($item->user_id) {
                                        $user = App\Models\User::find($item->user_id);
                                        $username = $user->name;
                                    } else {
                                        $username = 'none';
                                    }
                                @endphp
                                <td>{{ $username }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td class="d-flex justify-content-betweenx"><a class="btn btn-sm btn-primary"
                                        href="{{ route('group.edit', $item->id) }}">Edit</a>

                                    <form action="{{ route('group.destroy', $item->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-sm btn-danger" onclick="return confirm('Are You Sure ?')"
                                            type="submit" value="Delete">
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    <!-- Recent Sales End -->

    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Groups</h1>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        data-bs-whatever="@mdo">
                        <i class="bi bi-layers"></i> Add Group
                    </button>
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
                    <li class="breadcrumb-item active">Groups</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Created By</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groups as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('group.edit', $item->id) }}">{{ $item->name }}</a>
                                            </td>
                                            <td>
                                                @if ($item->status == 1)
                                                    Active
                                                @else
                                                    InActive
                                                @endif
                                            </td>
                                            @php
                                                if ($item->user_id) {
                                                    $user = App\Models\User::find($item->user_id);
                                                    $username = $user->name;
                                                } else {
                                                    $username = 'none';
                                                }
                                            @endphp
                                            <td>{{ $username }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td class="d-flex">
                                                <a href="{{ route('group.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm mx-2"><i class="bi bi-pencil"></i>
                                                    Edit</a>

                                                {{-- <a class="btn btn-sm btn-primary"
                                                    href="{{ route('group.edit', $item->id) }}">Edit</a> --}}

                                                <form action="{{ route('group.destroy', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are You Sure ?')" type="submit"
                                                        class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>
                                                        Delete</button>
                                                    {{-- <input class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are You Sure ?')" type="submit"
                                                        value="Delete"> --}}
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('group.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-start">
                            <label for="message-text" class="col-form-label fw-bold text-left ">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 text-start">
                                    <label for="filter">Users&nbsp;</label><input id="filter" type="text"
                                        class="filter form-control" placeholder="Search Username">
                                    <br />

                                    <div id="mdi" style="max-height: 10%; overflow:auto;">
                                        @foreach ($users as $item)
                                            <span><input class="talents_idmd-checkbox"
                                                    onchange="dragdrop(this.value, this.id);" type="checkbox"
                                                    id="{{ $item->name . ' ' . $item->lastname }}"
                                                    value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="users">Selected Users</label>
                                    <select name="userids[]" id="" class="form-control" multiple>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="message-text" class="col-form-label fw-bold text-left ">Status</label>
                            <select name="status" id="" class="form-control ">
                                <option value="1">Active</option>
                                <option value="0">In Active</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



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
                var userselect = document.getElementsByName('userids[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('userids[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }
    </script>
@endsection
