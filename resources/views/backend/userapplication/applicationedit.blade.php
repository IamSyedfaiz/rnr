@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Application Indexing List</h1>
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
                    <li class="breadcrumb-item active">Application</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h6 class="mb-4">Application Form </h6>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <button type="button" class="btn btn-danger">
                                            <a href="{{ route('userapplication.list', $application->id) }}"
                                                style="color:aliceblue">
                                                <- back</a>
                                        </button>
                                    </div>
                                    <form action="{{ route('user-application.update', $application->id) }}"
                                        class="form-horizontal" enctype="multipart/form-data" method="post">
                                        @method('PUT')
                                        @csrf
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($fields as $item)
                                            @if ($item->type == 'date')
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                                    {{-- {{ dd($filledformdata, $item->name) }} --}}
                                                    @if (isset($filledformdata[str_replace(' ', '_', $item->name)]))
                                                        <input type="{{ $item->datetype }}" class="form-control"
                                                            name="{{ $item->name }}"
                                                            value="{{ $filledformdata[str_replace(' ', '_', $item->name)] }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @else
                                                        <input type="{{ $item->datetype }}" class="form-control"
                                                            name="{{ $item->name }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @endif

                                                </div>
                                            @endif

                                            @if ($item->type == 'attachment')
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>

                                                    @if (isset($filledformdata[str_replace(' ', '_', $item->name)]))
                                                        <a href="{{ asset('public/files/' . $filledformdata[str_replace(' ', '_', $item->name)]) }}"
                                                            target="_blank">(uploaded file)</a>
                                                    @endif
                                                    {{-- {{ dd($filledformdata, $item->name) }} --}}
                                                    @if (isset($item->name))
                                                        <input type="file" class="form-control"
                                                            name="{{ $item->name }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @else
                                                        <input type="file" class="form-control"
                                                            name="{{ $item->name }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @endif

                                                </div>
                                            @endif

                                            @if ($item->type == 'images')
                                                {{-- {{ dd($item->name) }} --}}
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                                    @if (isset($filledformdata[str_replace(' ', '_', $item->name)]))
                                                        <a href="{{ asset('public/files/' . $filledformdata[str_replace(' ', '_', $item->name)]) }}"
                                                            target="_blank">(uploaded file)</a>
                                                    @endif

                                                    @if (isset($item->name))
                                                        <input type="file" class="form-control"
                                                            name="{{ $item->name }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @else
                                                        <input type="file" class="form-control"
                                                            name="{{ $item->name }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @endif


                                                </div>
                                            @endif

                                            @if ($item->type == 'ip_address')
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                                    @if (isset($filledformdata[str_replace(' ', '_', $item->name)]))
                                                        <input type="text" class="form-control"
                                                            name="{{ $item->name }}" minlength="7" maxlength="15"
                                                            size="15"
                                                            pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$"
                                                            value="{{ $filledformdata[str_replace(' ', '_', $item->name)] }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @else
                                                        <input type="text" class="form-control"
                                                            name="{{ $item->name }}" minlength="7" maxlength="15"
                                                            size="15"
                                                            pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @endif

                                                </div>
                                            @endif

                                            @if ($item->type == 'number')
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                                    @if ($filledformdata[str_replace(' ', '_', $item->name)])
                                                        <input type="number" class="form-control"
                                                            name="{{ $item->name }}"
                                                            value="{{ $filledformdata[str_replace(' ', '_', $item->name)] }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @else
                                                        <input type="number" class="form-control"
                                                            name="{{ $item->name }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @endif

                                                </div>
                                            @endif

                                            @if ($item->type == 'text')
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                                    @if (isset($filledformdata[str_replace(' ', '_', $item->name)]))
                                                        <input type="text" class="form-control"
                                                            name="{{ $item->name }}"
                                                            value="{{ $filledformdata[str_replace(' ', '_', $item->name)] }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @else
                                                        <input type="text" class="form-control"
                                                            name="{{ $item->name }}" value="{{ old($item->name) }}"
                                                            @if ($item->requiredfield == 1) required @endif>
                                                    @endif

                                                </div>
                                            @endif


                                            @if ($item->type == 'value_list')
                                                {{-- {{ dd($item->valuelisttype) }} --}}
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label><br>


                                                    @if ($item->valuelisttype == 'dropdown')
                                                        @php
                                                            $valuelist = json_decode($item->valuelistvalue);
                                                        @endphp
                                                        <select name="{{ $item->name }}" class="form-control"
                                                            type="checkbox" id=""
                                                            @if ($item->requiredfield == 1) required @endif>
                                                            <option value="">Select Option</option>
                                                            @foreach ($valuelist as $item1)
                                                                <option value="{{ $item1 }}">{{ $item1 }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif

                                                    @if ($item->valuelisttype == 'radio')
                                                        @php
                                                            $valuelist = json_decode($item->valuelistvalue);
                                                        @endphp

                                                        @foreach ($valuelist as $item1)
                                                            <input name="{{ $item->name }}"
                                                                value="{{ $item1 }}" id="nonr"
                                                                class="form-check-input" type="radio"
                                                                @if ($item->requiredfield == 1) required @endif>
                                                            <label
                                                                for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
                                                        @endforeach
                                                    @endif

                                                    @if ($item->valuelisttype == 'checkinput')
                                                        @php
                                                            $valuelist = json_decode($item->valuelistvalue);
                                                        @endphp
                                                        @foreach ($valuelist as $item1)
                                                            <input type="checkbox" class="form-check-input"
                                                                name="{{ $item->name }}[{{ $item1 }}]"
                                                                @if ($item->requiredfield == 1) required @endif>
                                                            <label
                                                                for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
                                                        @endforeach
                                                    @endif

                                                    @if ($item->valuelisttype == 'listbox')
                                                        @php
                                                            $valuelist = json_decode($item->valuelistvalue);
                                                        @endphp
                                                        @foreach ($valuelist as $item1)
                                                            <input type="checkbox" class="form-check-input"
                                                                name="{{ $item->name }}[{{ $item1 }}]"
                                                                @if ($item->requiredfield == 1) required @endif>
                                                            <label
                                                                for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
                                                        @endforeach
                                                    @endif

                                                    @if ($item->valuelisttype == 'valuepopup')
                                                        @php
                                                            $valuelist = json_decode($item->valuelistvalue);
                                                        @endphp
                                                        @foreach ($valuelist as $item1)
                                                            <input type="checkbox" class="form-check-input"
                                                                name="{{ $item->name }}[{{ $item1 }}]"
                                                                @if ($item->requiredfield == 1) required @endif>
                                                            <label
                                                                for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
                                                        @endforeach
                                                    @endif

                                                </div>
                                            @endif

                                            @if ($item->type == 'user_group_list')
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label">{{ strtoupper($item->name) }}</label>
                                                    <div class="usergrouplist">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <div class="col-md-2 addusers">
                                                                <button type="button" class="btn btn-primary text-end"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalusers"
                                                                    data-bs-whatever="@mdo">Add Users</button>
                                                            </div>

                                                            <div class="col-md-2 addgroups">
                                                                <button type="button" class="btn btn-primary text-end"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalgroups"
                                                                    data-bs-whatever="@mdo">Add Groups</button>
                                                            </div>

                                                        </div>

                                                        {{-- <div class="row">
                                                    <div class="groupsname col-md-6">
                                                        <select id="" class="form-control " multiple disabled>
                                                            @if ($field->type == 'user_group_list' && $userlist != null)
                                                                @foreach ($userlist as $item)
                                                                    <option selected>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
        
                                                    </div>
        
                                                    <div class="groupsname col-md-6">
                                                        <select id="" class="form-control " multiple disabled>
                                                            @if ($field->type == 'user_group_list' && $grouplist != null)
                                                                @foreach ($grouplist as $item)
                                                                    <option selected>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
        
                                                    </div>
                                                </div> --}}


                                                        <div class="modal fade" id="exampleModalusers" tabindex="-1"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Select Groups
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="mb-3 text-start">
                                                                            <label for="message-text"
                                                                                class="col-form-label fw-bold text-left ">Groups
                                                                                <small>(ctrl + click) multiple
                                                                                    select</small> </label>
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
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Select Groups
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="mb-3 text-start">
                                                                            <label for="message-text"
                                                                                class="col-form-label fw-bold text-left ">Groups
                                                                                <small>(ctrl + click) multiple
                                                                                    select</small> </label>
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
                                            @endif
                                        @endforeach


                                        <input type="hidden" value="{{ auth()->id() }}" name="userid">
                                        <input type="hidden" value="{{ $id }}" name="formdataid">

                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Recent Sales End -->






    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor1');
    </script>
@endsection
