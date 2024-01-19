@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                {{-- <h6 class="mb-0">Application Create</h6> --}}

            </div>
            {{-- {{ dd(Session::get('genral'), Session::get('field')) }} --}}

            <div class="bg-light rounded h-100 p-4">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false">Fields</button>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                            aria-selected="false">Contact</button>
                    </li> --}}
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-4">Application Edit</h6>
                            <a href="{{ route('application.index') }}">
                                <button type="button" class="btn btn-danger"><-back</button>
                            </a>

                        </div>
                        <form action="{{ route('application.update', $application->id) }}" class="form-horizontal"
                            enctype="multipart/form-data" method="post">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" aria-describedby="namehelp"
                                    value="{{ $application->name }}" required>
                                @error('name')
                                    <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1"
                                    class="form-label @error('status') is-invalid @enderror">Status</label>
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
                            <div class="mb-3">
                                <label for="exampleInputEmail1"
                                    class="form-label @error('description') is-invalid @enderror">Description</label>
                                <textarea class="form-control" name="description" id="editor1" required>{{ $application->description }}</textarea>

                                @error('description')
                                    <label id="description-error" class="error text-danger"
                                        for="description">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>


                            <input type="hidden" value="{{ auth()->id() }}" name="updated_by">

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                        <div class="container-fluid pt-4 px-0">
                            <div class="bg-light text-center rounded ">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h6 class="mb-0">Attachments</h6>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-bs-whatever="@mdo">New</button>

                                </div>

                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('application.update', $application->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">New Attachment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3 text-start">
                                                        <label for="recipient-name"
                                                            class="col-form-label fw-bold  @error('name') is-invalid @enderror">File</label>
                                                        <input type="file" class="form-control" id="description"
                                                            name="attachments">
                                                    </div>
                                                    <input type="hidden" value="{{ $application->id }}"
                                                        name="application_id">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
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
                                                <th scope="col">Size</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col">Updated At</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attachments as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->size }}</td>
                                                    <td>{{ $item->type }}</td>
                                                    <td>{{ $item->created_at->toDateString() }}</td>
                                                    <td>{{ $item->updated_at->toDateString() }}</td>
                                                    <td class="d-flex justify-content-between">

                                                        <form action="{{ route('attachment.delete', $item->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are You Sure ?')" type="submit"
                                                                value="Delete">
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="container-fluid pt-4 px-4">
                            <div class="bg-light text-start rounded p-4">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                               

                                </div>
                                <div class="bg-light rounded h-100 p-4">
                                    <h6 class="mb-4">Attachments Create</h6>
                                    <form action="{{ route('application.update', $application->id) }}"
                                        class="form-horizontal" enctype="multipart/form-data" method="post">
                                        @method('PUT')
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label @error('attachments') is-invalid @enderror">Attachments</label>
                                            <input type="file"
                                                class="form-control @error('attachments') is-invalid @enderror"
                                                id="description" name="attachments">
                                            @error('attachments')
                                                <label id="attachments-error" class="error text-danger"
                                                    for="attachments">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        <input type="hidden" value="{{ $application->id }}" name="application_id">

                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>


                            </div>
                        </div> --}}
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="container-fluid pt-4 px-0">
                            <div class="bg-light text-center rounded">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h6 class="mb-0">Fields Table</h6>

                                    <a href="{{route('field.show', $application->id)}}">
                                        <button type="button" class="btn btn-primary">New</button>
                                    </a>
                                </div>
{{-- 
                                <div class="modal fade" id="exampleModa" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('field.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Field Create</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3 text-start">
                                                        <label for="recipient-name"
                                                            class="col-form-label fw-bold  ">Name</label>
                                                        <input type="text" class="form-control " id="description"
                                                            name="name" required>
                                                    </div>
                                                    <div class="class mb-3 text-start">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label fw-bold">Type</label>
                                                        <select name="type" id=""
                                                            class="form-control @error('type') is-invalid @enderror"
                                                            required>
                                                            <option value="date">Date</option>
                                                            <option value="attachment">Attachment</option>
                                                            <option value="images">Images</option>
                                                            <option value="ip_address">IP Address</option>
                                                            <option value="number">Numeric</option>
                                                            <option value="text">Text</option>
                                                            <option value="value_list">Value List</option>
                                                            <option value="user_group_list">User Group List</option>
                                                        </select>
                                                    </div>

                                                    <div class="class mb-3 text-start">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label fw-bold">Status</label>
                                                        <select name="status" id=""
                                                            class="form-control @error('status') is-invalid @enderror">

                                                            <option value="0" selected>In-Active</option>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" value="{{ $application->id }}"
                                                        name="application_id">
                                                    <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary submitbtn" onclick="disableMe(event)">Submit</button>
                                                    </div>

                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                </div> --}}


                                <div class="table-responsive">
                                    <table class="table text-start align-middle table-bordered table-hover mb-0">
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
                                                            href="{{ route('field.edit', $item->id) }}">{{ $item->name }}</a>
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
                                                            $udpated = App\Models\User::find($item->updated_by);
                                                            $udpatedby = $udpated->name;
                                                        } else {
                                                            $udpatedby = 'none';
                                                        }
                                                    @endphp
                                                    <td>{{ $udpatedby}}</td>
                                                    <td>{{ $item->created_at->toDateString() }}</td>
                                                    <td>{{ $item->updated_at->toDateString() }}</td>
                                                    <td class="d-flex justify-content-betweenx">

                                                        <a class="btn btn-sm btn-primary"
                                                            href="{{ route('field.edit', $item->id) }}">Edit</a>

                                                        <form action="{{ route('field.destroy', $item->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are You Sure ?')" type="submit"
                                                                value="Delete">
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
    </div>
@endsection


@section('script')
    <script>
        CKEDITOR.replace('editor1');
    </script>



    <script>
        function disableMe(event){
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
