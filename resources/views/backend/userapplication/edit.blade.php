@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Application Form</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('userapplication.list', $id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-short"></i>
                        Back
                    </a>
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
                    <li class="breadcrumb-item active">Application </li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">

                            <form action="{{ route('user-application.update', $application->id) }}" class="form-horizontal"
                                enctype="multipart/form-data" method="post">
                                @method('PUT')
                                @csrf

                                @if (!empty($transitions) && $transitions->isNotEmpty())
                                    <div class="mb-3 text-start col-3">
                                        <label for="transition_id" class="col-form-label fw-bold text-left ">transition Node
                                        </label>
                                        <select name="transition_id" id="transition_id" class="form-control">
                                            @foreach (@$transitions as $transition)
                                                <option value="{{ $transition->id }}">
                                                    {{ $transition->condition }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="mb-3 text-start col-3">
                                        @foreach (@$transitions as $transition)
                                            <button type="submit"
                                                class="btn btn-primary">{{ $transition->condition }}</button>
                                        @endforeach
                                    </div> --}}
                                @endif

                                {{-- @if (@$filteredTasks)
                                    <input type="text" name="task_id" id="" value="{{ $filteredTasks->id }}">
                                @endif --}}
                                {{-- <div class="mb-3 text-start col-3">
                                    <label for="message-text" class="col-form-label fw-bold text-left ">transition Node
                                    </label>
                                    <select name="transition" id="" class="form-control">
                                        @foreach (@$transitions as $transition)
                                            <option value="{{ $transition->id }}">
                                                {{ $transition->condition }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                @foreach ($fields as $item)
                                    @if ($item->type == 'date')
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                            <input type="{{ $item->type }}" class="form-control"
                                                name="{{ $item->name }}" value="{{ old($item->name) }}"
                                                @if ($item->requiredfield == 1) required @endif>

                                        </div>
                                    @endif

                                    @if ($item->type == 'attachment')
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                            <input type="file" class="form-control" name="{{ $item->name }}"
                                                @if ($item->requiredfield == 1) required @endif>

                                        </div>
                                    @endif

                                    @if ($item->type == 'images')
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                            <input type="file" class="form-control" name="{{ $item->name }}"
                                                @if ($item->requiredfield == 1) required @endif>

                                        </div>
                                    @endif

                                    @if ($item->type == 'ip_address')
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                            <input type="text" class="form-control" name="{{ $item->name }}"
                                                minlength="7" maxlength="15" size="15" value="{{ old($item->name) }}"
                                                pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$"
                                                @if ($item->requiredfield == 1) required @endif>

                                        </div>
                                    @endif

                                    @if ($item->type == 'number')
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                            <input type="number" class="form-control" name="{{ $item->name }}"
                                                value="{{ old($item->name) }}"
                                                @if ($item->requiredfield == 1) required @endif>

                                        </div>
                                    @endif

                                    @if ($item->type == 'text')
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label>
                                            <input type="text" class="form-control" name="{{ $item->name }}"
                                                value="{{ old($item->name, $requestData[$item->name] ?? '') }}"
                                                @if ($item->requiredfield == 1) required @endif>

                                        </div>
                                    @endif


                                    @if ($item->type == 'value_list')
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper(str_replace('_', ' ', $item->name)) }}</label><br>


                                            @if ($item->valuelisttype == 'dropdown')
                                                @php
                                                    $valuelist = json_decode($item->valuelistvalue);
                                                @endphp
                                                <select name="{{ $item->name }}" class="form-control" type="checkbox"
                                                    id="" @if ($item->requiredfield == 1) required @endif>
                                                    <option value="">Select Option</option>
                                                    @foreach ($valuelist as $item1)
                                                        <option value="{{ $item1 }}">
                                                            {{ $item1 }}</option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            @if ($item->valuelisttype == 'radio')
                                                @php
                                                    $valuelist = json_decode($item->valuelistvalue);
                                                @endphp

                                                @foreach ($valuelist as $item1)
                                                    <input name="{{ $item->name }}" value="{{ $item1 }}"
                                                        id="nonr" class="form-check-input" type="radio"
                                                        {{ old($item->name) == $item1 ? 'checked' : '' }}
                                                        @if ($item->requiredfield == 1) required @endif>
                                                    <label for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
                                                @endforeach
                                            @endif

                                            @if ($item->valuelisttype == 'checkinput')
                                                @php
                                                    $valuelist = json_decode($item->valuelistvalue);
                                                @endphp
                                                @foreach ($valuelist as $item1)
                                                    <input type="checkbox" class="form-check-input"
                                                        name="{{ $item->name }}[{{ $item1 }}]"
                                                        {{ old($item->name) == $item1 ? 'checked' : '' }}
                                                        @if ($item->requiredfield == 1) required @endif>
                                                    <label for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
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
                                                    <label for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
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
                                                    <label for="{{ $item1 }}">{{ strtoupper($item1) }}</label><br>
                                                @endforeach
                                            @endif

                                        </div>
                                    @endif

                                    @if ($item->type == 'user_group_list')
                                        {{-- {{ dd($item) }} --}}
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1"
                                                class="form-label">{{ strtoupper($item->name) }}</label>
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
                                                                <h5 class="modal-title" id="exampleModalLabel">Select
                                                                    Groups
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="mb-3 text-start">
                                                                    <label for="message-text"
                                                                        class="col-form-label fw-bold text-left ">Groups
                                                                        <small>(ctrl + click) multiple
                                                                            select</small> </label>
                                                                    <select name="{{ $item->name }}[user_list][]"
                                                                        id="" class="form-control" multiple>
                                                                        @foreach ($users as $item2)
                                                                            <option value="{{ $item2->id }}">
                                                                                {{ $item2->name }}
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
                                                                <h5 class="modal-title" id="exampleModalLabel">Select
                                                                    Groups
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="mb-3 text-start">
                                                                    <label for="message-text"
                                                                        class="col-form-label fw-bold text-left ">Groups
                                                                        <small>(ctrl + click) multiple
                                                                            select</small> </label>
                                                                    <select name="{{ $item->name }}[group_list][]"
                                                                        id="" class="form-control" multiple>
                                                                        @foreach ($groups as $item1)
                                                                            <option value="{{ $item1->id }}">
                                                                                {{ $item1->name }}
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

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>

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
