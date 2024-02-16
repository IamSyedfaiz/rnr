@extends('backend.layouts.app')
@section('content')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <div class="rounded h-100">
                    <form action="{{ route('store.report') }}" class="m-n2" method="POST">
                        @csrf
                        <input type="hidden" value="{{ auth()->id() }}" name="user_id">
                        <input type="hidden" value="{{ @$applicationId }}" name="report_id">
                        <input type="hidden" value="{{ @$data }}" name="data">
                        <input type="hidden" value="{{ @$dropdowns }}" name="dropdowns">
                        <input type="hidden" value="{{ @$fieldNames }}" name="fieldNames">
                        <input type="hidden" value="{{ @$statisticsMode }}" name="statisticsMode">
                        <input type="hidden" value="{{ @$fieldStatisticsNames }}" name="fieldStatisticsNames">

                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true"
                                        aria-controls="flush-collapseOne">
                                        Applications: Save Report
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <button type="submit" class="btn btn-primary m-2 fw-bold">
                                            save
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mt-2">
                                <h2 class="accordion-header" id="flush-headingtwo">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="true"
                                        aria-controls="flush-collapsetwo">
                                        Report Information
                                    </button>
                                </h2>
                                <div id="flush-collapsetwo" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingtwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <form action="">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">Name:</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ old('name', $report->name ?? '') }}" name="name" />
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">Alias:</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name" class="col-form-label fw-bold mx-2">Type:
                                                            {{ @$report->permissions == 'P' ? 'Personal' : (@$report->permissions == 'G' ? 'Global' : '--') }}</label>

                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">ID:</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">Description</label>
                                                        <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ old('description', @$report->description ?? '') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name" class="col-form-label fw-bold mx-2">Created
                                                            By: {{ @$report->created_at }}</label>

                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name" class="col-form-label fw-bold mx-2">Last
                                                            Update: {{ @$report->updated_at }}</label>

                                                    </div>
                                                </div>
                                            </div>

                                    </div>


                                </div>
                            </div>
                            <div cflass="accordion-item mt-2">
                                <h2 class="accordion-header" id="flush-headingtwo">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapsethree"
                                        aria-expanded="true" aria-controls="flush-collapsethree">
                                        Report Type

                                    </button>
                                </h2>
                                <div id="flush-collapsethree" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingthree" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>
                                                    <i class="bi bi-exclamation-circle-fill text-primary"></i> Choose the
                                                    type of report to
                                                    save. If the report is saved as Global, select the appropriate
                                                    permissions.

                                                </h6>
                                            </div>
                                            <div class="row g-3 align-items-center">
                                                <div class="col-auto">
                                                    <label for="permissions"
                                                        class="col-form-label fw-bold">Permissions</label>
                                                </div>
                                                <div class="col-auto">
                                                    <input class="form-check-input" type="radio" value="P"
                                                        {{ @$report->permissions == 'P' ? 'checked' : '' }}
                                                        name="flexRadioDefault" id="flexRadioDefault2">
                                                    <label class="form-check-label text-dark fw-bold"
                                                        for="flexRadioDefault1">
                                                        Personal Report
                                                    </label>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="passwordHelpInline" class="form-text">
                                                        if a report is designated as "personal", it is accessible only by
                                                        you
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row  align-items-center">
                                                <div class="col-1 mx-2">
                                                </div>
                                                <div class="col-2">
                                                    <input class="form-check-input" type="radio" value="G"
                                                        {{ @$report->permissions == 'G' ? 'checked' : '' }}
                                                        name="flexRadioDefault" id="flexRadioDefault1">
                                                    <label class="form-check-label text-dark fw-bold"
                                                        for="flexRadioDefault1">
                                                        Global Report
                                                    </label>
                                                </div>
                                                <div class="col-8">
                                                    <span id="passwordHelpInline" class="form-text">
                                                        If a report is designated as "global" you can grant access rights to
                                                        specific users
                                                        and/or groups. By default, global reports are made available to all
                                                        application users
                                                        through the built-in "Everyone" group.
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mb-3" id="toggleableDiv"
                                                @if (@$report->permissions == 'G') style="display: block;" @else style="display: none;" @endif>

                                                <div class="row g-3 align-items-center">
                                                    <div class="col-auto">
                                                        <label for="permissions"
                                                            class="col-form-label fw-bold">Defult</label>
                                                    </div>
                                                    <div class="col-auto">
                                                        <input class="form-check-input" type="radio" value="E"
                                                            checked name="radioDefault" id="radioDefault2">
                                                        <label class="form-check-label text-dark fw-bold"
                                                            for="radioDefault1">
                                                            everyOne
                                                        </label>
                                                    </div>
                                                    <div class="col-2">
                                                        <input class="form-check-input" type="radio" value="U"
                                                            name="radioDefault" id="radioDefault1">
                                                        <label class="form-check-label text-dark fw-bold"
                                                            for="radioDefault1">
                                                            users
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="usergrouplist" style="display: none;">
                                                    <div class="d-flex mb-2">
                                                        <div class="col-md-2 addusers">
                                                            <button type="button" class="btn btn-primary text-end"
                                                                data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                                data-bs-whatever="@mdo">Add Users</button>
                                                        </div>

                                                        <div class="col-md-2 addgroups">
                                                            <button type="button" class="btn btn-primary text-end"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalgroups"
                                                                data-bs-whatever="@mdo">Add Groups</button>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-12 d-flex">
                                                        @if ($selectedusers != [])
                                                            <div class="col-md-5">
                                                                <select id="" class="form-control " multiple
                                                                    disabled>
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
                                                                <select id="" class="form-control " multiple
                                                                    disabled>
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
                                                                    <h5 class="modal-title" id="exampleModalLabel">Select
                                                                        Users
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="mb-3 text-start">
                                                                        <label for="message-text"
                                                                            class="col-form-label fw-bold text-left ">Users
                                                                            <small>(ctrl + click) multiple select</small>
                                                                        </label>
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
                                                                    <h5 class="modal-title" id="exampleModalLabel">Select
                                                                        Groups
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="mb-3 text-start">
                                                                        <label for="message-text"
                                                                            class="col-form-label fw-bold text-left ">Groups
                                                                            <small>(ctrl + click) multiple select</small>
                                                                        </label>
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
                                            {{-- <div class="row g-3 align-items-center">
                                                <div class="col-auto">
                                                    <label for="permissions" class="col-form-label fw-bold">iView
                                                        Caching</label>
                                                </div>
                                                <div class="col-auto">
                                                    <input class="form-check-input" type="radio"
                                                        name="flexRadioDefault" id="flexRadioDefault1">
                                                    <label class="form-check-label text-dark fw-bold"
                                                        for="flexRadioDefault1">
                                                        Enable iView Caching
                                                    </label>
                                                </div>

                                            </div>
                                            <div class="row  align-items-center">
                                                <div class="col-12 mx-2">
                                                    <div class="row g-3 align-items-center">
                                                        <div class="col-auto">
                                                            <label for="inputPassword6" class="col-form-label">cache
                                                                Duration</label>
                                                        </div>
                                                        <div class="col-auto d-flex mx-2">
                                                            <input type="password" id="inputPassword6"
                                                                class="form-control" placeholder="30">
                                                            <select id="disabledSelect" class="form-select">
                                                                <option>Minutes</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-auto">
                                                            <span id="passwordHelpInline" class="form-text">
                                                                Must be 8-20 characters long.
                                                            </span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-outline-primary">Reset
                                                                To Default</button>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div> --}}
                                            {{-- <div class="row g-3 align-items-center">
                                                <div class="col-auto">
                                                    <label for="permissions" class="col-form-label fw-bold">Refresh
                                                        Rate</label>
                                                </div>
                                                <div class="col-auto">
                                                    <select id="disabledSelect" class="form-select">
                                                        <option>Do Not Refresh</option>
                                                        <option>Refresh</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <h6 class="text-danger">*Required</h6> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('input[name="flexRadioDefault"]').change(function() {
                if ($(this).attr('id') === 'flexRadioDefault1') {
                    // Show the div for Personal Report
                    $('#toggleableDiv').show();
                } else {
                    // Hide the div for Global Report
                    $('#toggleableDiv').hide();
                }
            });
            $('input[name="radioDefault"]').change(function() {
                if ($(this).attr('id') === 'radioDefault1') {
                    // Show the div for Personal Report
                    $('.usergrouplist').show();
                } else {
                    // Hide the div for Global Report
                    $('.usergrouplist').hide();
                }
            });
        });
    </script>
@endsection
