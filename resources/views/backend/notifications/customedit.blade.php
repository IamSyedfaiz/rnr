@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">

            </div>

            <div class="bg-light rounded h-100 p-4">

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-4"> {{ strtoupper($notification->name) }} Role </h6>
                            </div>
                            <div>
                                <a href="{{ route('notifications.show', $notification->application_id) }}">
                                    <button type="button" class="btn btn-danger">
                                        <- Return</button>
                                </a>
                            </div>

                        </div>
                        <form action="{{ route('notifications.update', $notification->id) }}" class="form-horizontal"
                            method="post">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" value="{{$notification->name}}" aria-describedby="namehelp" required>
                                @error('name')
                                    <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Type</label>
                                <input type="text" class="form-control @error('type') is-invalid @enderror"
                                    name="type" value="{{$notification->type}}" id="type" aria-describedby="namehelp">
                                @error('type')
                                    <label id="type-error" class="error text-danger" for="type">{{ $message }}</label>
                                @enderror
                                <div id="typehelp" class="form-text">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Descriptoin</label>
                                <Textarea name="description" rows="5" cols="5" class="form-control">{{$notification->description}}</Textarea>
                                @error('type')
                                    <label id="type-error" class="error text-danger" for="type">{{ $message }}</label>
                                @enderror
                                <div id="typehelp" class="form-text">
                                </div>
                            </div>

                            <div>
                                <div class="mb-3">
                                    {{-- <label for="exampleInputEmail1" class="form-label">{{ strtoupper($item->name) }}</label> --}}
                                    <div class="usergrouplist">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="col-md-2 addusers">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                    data-bs-whatever="@mdo">Add Users</button>
                                            </div>

                                            <div class="col-md-2 addgroups">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalgroups"
                                                    data-bs-whatever="@mdo">Add Groups</button>
                                            </div>

                                        </div>

                                        <div class="col-md-12 d-flex justify-content-between">
                                            @if ($selectedusers != [])
                                                <div class="col-md-5">
                                                    <select id="" class="form-control " multiple disabled>
                                                        @foreach ($selectedusers as $item)
                                                            <option selected>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            @if ($selectedgroups != [])
                                                <div class="col-md-5">
                                                    <select id="" class="form-control " multiple disabled>
                                                        @foreach ($selectedgroups as $item)
                                                            <option selected>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>


                                        <div class="modal fade" id="exampleModalusers" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Select Users
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3 text-start">
                                                            <label for="message-text"
                                                                class="col-form-label fw-bold text-left ">Users
                                                                <small>(ctrl + click) multiple select</small> </label>
                                                            <select name="user_list[]" id=""
                                                                class="form-control" multiple>
                                                                @foreach ($users as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach


                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-dismiss="modal">Save</button>
                                                        {{-- <button type="button" class="btn btn-primary">Submit</button> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="exampleModalgroups" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Select Groups
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3 text-start">
                                                            <label for="message-text"
                                                                class="col-form-label fw-bold text-left ">Groups
                                                                <small>(ctrl + click) multiple select</small> </label>
                                                            <select name="group_list[]" id=""
                                                                class="form-control" multiple>
                                                                @foreach ($groups as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach


                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-dismiss="modal">Save</button>
                                                        {{-- <button type="button" class="btn btn-primary">Submit</button> --}}
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
                </div>

            </div>


        </div>
    </div>


    <!-- Recent Sales End -->






    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor1');
    </script>
    <script>
        var field = "{{ Session::get('field') }}";
        if (field == 'active') {
            document.getElementById('pills-home-tab').className = 'nav-link';
            document.getElementById('pills-profile-tab').className = 'nav-link active';
            document.getElementById('pills-home').className = 'tab-pane fade';
            document.getElementById('pills-profile').className = 'tab-pane fade show active';
        }
    </script>
@endsection
