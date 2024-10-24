@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Edit Group</h1>
                </div>
                <div class="col-md-6 text-end"><a href="{{ route('group.index') }}" class="btn btn-secondary"><i
                            class="bi bi-arrow-left-short"></i> Back</a></div>
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
                    <li class="breadcrumb-item active">Edit Group</li>
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
                                    <form action="{{ route('group.update', $group->id) }}" class="form-horizontal"
                                        method="post">
                                        @method('PUT')
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" id="name" aria-describedby="namehelp"
                                                value="{{ $group->name }}" required>
                                            @error('name')
                                                <label id="name-error" class="error text-danger"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                            <div id="namehelp" class="form-text">
                                            </div>
                                        </div>

                                        <div class="mb-3 text-start">
                                            <div class="d-flex justify-content-between mb-2">
                                                <label for="message-text"
                                                    class="col-form-label fw-bold text-left form-label ">Users</label>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add
                                                    Users</button>

                                            </div>

                                            <select id="" class="form-control " multiple disabled>
                                                @foreach ($selectedusers as $item)
                                                    <option selected>
                                                        {{ $item->name . ' (' . $item->email . ' )' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- for modal --}}
                                        <div class="modal fade" id="exampleModal" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="filter">Users&nbsp;</label><input
                                                                    id="filter" type="text"
                                                                    class="filter form-control"
                                                                    placeholder="Search Username">
                                                                <br />
                                                                <div class="mb-3 mt-3 text-start">
                                                                    <div id="mdi"
                                                                        style="max-height: 10%; overflow:auto;">
                                                                        @foreach ($users as $item)
                                                                            <span><input class="talents_idmd-checkbox mx-2"
                                                                                    name="userids[]"
                                                                                    onchange="dragdrop(this.value, this.id);"
                                                                                    type="checkbox"
                                                                                    id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                    @if (in_array($item->id, array_column($selectedusers, 'id'))) checked @endif
                                                                                    value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-md-12 mt-5">
                                                                <div class="mb-3">
                                                                    <label for="users">Selected Users</label>
                                                                    <select name="userids[]" id=""
                                                                        class="form-control" multiple>
                                                                    </select>
                                                                </div>
                                                            </div> --}}

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- for modal --}}


                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label @error('status') is-invalid @enderror">Status</label>
                                            <select name="status" id=""
                                                class="form-control @error('status') is-invalid @enderror" required>
                                                <option value="1">Active</option>
                                                <option value="0">In Active</option>
                                            </select>
                                            @error('status')
                                                <label id="status-error" class="error text-danger"
                                                    for="status">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div><!-- End Left side columns -->
            </div>
        </section>
    </main><!-- End #main -->

    <!-- Recent Sales End -->






    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor1');
    </script>
    <script>
        var status = "{{ $group->status }}";
        var currentstatus = document.getElementsByName('status')[0];
        for (let index = 0; index < currentstatus.length; index++) {
            if (currentstatus[index].value == status) {
                currentstatus[index].selected = true;
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
