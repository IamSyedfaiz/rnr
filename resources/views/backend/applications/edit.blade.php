@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Edit Application</h1>
                </div>
                <div class="col-md-6 text-end"><a href="{{ route('application.index') }}" class="btn btn-secondary"><i
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
                    <li class="breadcrumb-item active">Edit Application</li>
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
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="pills-home-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-home" type="button"
                                                        role="tab" aria-controls="pills-home"
                                                        aria-selected="true">General</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link " id="pills-profile-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-profile" type="button" role="tab"
                                                        aria-controls="pills-profile" aria-selected="false">Fields</button>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class="card">
                                        <div class="card-body pt-3">
                                            <div class="d-flex align-items-center justify-content-between mb-4">
                                                <h6 class="mb-4">Application Edit</h6>
                                            </div>
                                            <form class="row g-3"
                                                action="{{ route('application.update', $application->id) }}"
                                                class="form-horizontal" enctype="multipart/form-data" method="post">
                                                @method('PUT')
                                                @csrf
                                                <div class="col-12">
                                                    <label for="inputNanme4" class="form-label">Name</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" id="name" aria-describedby="namehelp"
                                                        value="{{ $application->name }}" required>
                                                    @error('name')
                                                        <label id="name-error" class="error text-danger"
                                                            for="name">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputNanme4" class="form-label">Status</label>
                                                    <select name="status" id=""
                                                        class="form-control @error('status') is-invalid @enderror" required>
                                                        <option value="1">Active</option>
                                                        <option value="0">In-Active</option>
                                                    </select>
                                                    @error('status')
                                                        <label id="status-error" class="error text-danger"
                                                            for="status">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputAddress" class="form-label">Description</label>
                                                    <textarea class="form-control" name="description" id="editor1" required>{{ $application->description }}</textarea>

                                                    @error('description')
                                                        <label id="description-error" class="error text-danger"
                                                            for="description">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                                <div class="text-center">
                                                    <input type="hidden" value="{{ auth()->id() }}" name="updated_by">

                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body pt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="card-title">Attachments</h5>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal" data-bs-whatever="@mdo"> <i
                                                            class="bi bi-paperclip"></i>
                                                        Add New</button>

                                                </div>

                                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('application.update', $application->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">New
                                                                        Attachment
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3 text-start">
                                                                        <label for="recipient-name"
                                                                            class="col-form-label fw-bold  @error('name') is-invalid @enderror">File</label>
                                                                        <input type="file" class="form-control"
                                                                            id="description" name="attachments">
                                                                    </div>
                                                                    <input type="hidden" value="{{ $application->id }}"
                                                                        name="application_id">

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <!-- Table with stripped rows -->
                                                    <table class="table datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Size</th>
                                                                <th>Type</th>
                                                                <th>Created At</th>
                                                                <th>Updated At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($attachments as $item)
                                                                <tr>
                                                                    <td>{{ $item->name }}</td>
                                                                    <td>{{ $item->size }}</td>
                                                                    <td>{{ $item->type }}</td>
                                                                    <td>{{ $item->created_at->toDateString() }}
                                                                    </td>
                                                                    <td>{{ $item->updated_at->toDateString() }}
                                                                    </td>
                                                                    <td class="d-flex justify-content-between">

                                                                        <form
                                                                            action="{{ route('attachment.delete', $item->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <input class="btn btn-sm btn-danger"
                                                                                onclick="return confirm('Are You Sure ?')"
                                                                                type="submit" value="Delete">
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
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class="card">
                                        <div class="card-body pt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="card-title">Fields Table
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <a href="{{ route('field.show', $application->id) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-input-cursor-text"></i> Add Field
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="table-responsive ">
                                                <table
                                                    class="table text-start align-middle table-bordered table-hover mb-0 ">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Access</th>
                                                            <th scope="col">Updated By</th>
                                                            <th scope="col">Created At</th>
                                                            <th scope="col">Updated At</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="sortable">
                                                        @foreach ($fields as $item)
                                                            <tr class="ui-state-default {{ $item->id }} sortablearray "
                                                                id="{{ $item->forder }}">
                                                                <td><a
                                                                        href="{{ route('field.edit', $item->id) }}">{{ str_replace('_', ' ', $item->name) }}</a>
                                                                </td>
                                                                <td>{{ strtoupper($item->type) }}</td>
                                                                <td>
                                                                    @if ($item->status == 1)
                                                                        Active
                                                                    @else
                                                                        In-Active
                                                                    @endif
                                                                </td>

                                                                <td>{{ $item->access }}</td>

                                                                @php
                                                                    // dd($item->updated_by);
                                                                    if ($item->updated_by != null) {
                                                                        // dd('prateek',$item->updated_by, $item->updated_by == "null");
                                                                        $udpated = App\Models\User::find(
                                                                            $item->updated_by,
                                                                        );
                                                                        $udpatedby = $udpated->name;
                                                                    } else {
                                                                        $udpatedby = 'none';
                                                                    }
                                                                @endphp
                                                                <td>{{ $udpatedby }}</td>
                                                                <td>{{ $item->created_at->toDateString() }}</td>
                                                                <td>{{ $item->updated_at->toDateString() }}</td>
                                                                <td class="d-flex">
                                                                    <a href="{{ route('field.edit', $item->id) }}"
                                                                        class="btn btn-primary btn-sm mx-1"><i
                                                                            class="bi bi-pencil"></i> Edit</a>

                                                                    <form action="{{ route('field.destroy', $item->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-sm"
                                                                            onclick="return confirm('Are You Sure ?')"><i
                                                                                class="bi bi-trash"></i> Delete</button>

                                                                        {{-- <input class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Are You Sure ?')"
                                                                        type="submit" value="Delete"> --}}
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Reports -->
            </div>
        </section>
    </main>
@endsection


@section('script')
    <script>
        CKEDITOR.replace('editor1');
    </script>



    <script>
        function disableMe(event) {
            var button = document.getElementsByClassName('submitbtn')[0];
            button.className = "d-none";
            // console.log(button);
            // event.preventDefault();
        }

        $(function() {
            $("#sortable").sortable({
                connectWith: '.quadrants',
                cursor: 'move',
                dropOnEmpty: true,
                update: function(e, ui) {
                    var sortablearray = document.getElementsByClassName('sortablearray');
                    var newarray = [];
                    for (let index = 0; index < sortablearray.length; index++) {
                        console.log(sortablearray[index]);
                        newarray.push(sortablearray[index].id);
                    }
                    $.ajax({
                        url: "{{ route('change.forder') }}",
                        method: "POST",
                        data: {
                            '_token': "{{ csrf_token() }}",
                            "forderarray": newarray,
                            'application_id': "{{ $application->id }}"

                        },
                        success: function(response) {
                            console.log(response);

                        }
                    });

                }
            });
        });
        /* $(document).ready(function() {
            $('tbody').sortable({
                axis: 'y',
                stop: function(event, ui) {
                    var data = $(this).sortable('serialize');
                    $('span').text(data);
                    
                }
            });
        }); */

        var status = "{{ $application->status }}";
        var currentstatus = document.getElementsByName('status')[0];
        for (let index = 0; index < currentstatus.length; index++) {
            if (currentstatus[index].value == status) {
                currentstatus[index].selected = true;
            }
        }
        var field = "{{ Session::get('field') }}";
        if (field == 'active') {
            document.getElementById('pills-home-tab').className = 'nav-link';
            document.getElementById('pills-profile-tab').className = 'nav-link active';
            document.getElementById('pills-home').className = 'tab-pane fade';
            document.getElementById('pills-profile').className = 'tab-pane fade show active';
        }
    </script>
@endsection
