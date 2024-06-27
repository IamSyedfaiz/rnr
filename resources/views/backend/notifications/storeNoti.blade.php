@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Notification</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('notifications.index') }}" class="btn btn-secondary"><i
                            class="bi bi-arrow-left-short"></i> Back</a>
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
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Notification</li>
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
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            {{-- <a href="{{ route('notifications.show') }}">
                                    <button type="button" class="btn btn-danger">
                                        <i class="bi bi-arrow-return-left"></i> Return</button>
                                </a> --}}
                                        </div>

                                    </div>
                                    <nav>
                                        <div class="nav nav-tabs  nav-pills" id="nav-tab" role="tablist">
                                            <button class="nav-link active  " id="nav-home-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-home" type="button" role="tab"
                                                aria-controls="nav-home" aria-selected="true">General</button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-profile" type="button" role="tab"
                                                aria-controls="nav-profile" aria-selected="false">Content</button>
                                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-contact" type="button" role="tab"
                                                aria-controls="nav-contact" aria-selected="false">Delivery</button>
                                            <button class="nav-link" id="nav-filter-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-filter" type="button" role="tab"
                                                aria-controls="nav-filter" aria-selected="false">Filter Criteria</button>
                                        </div>
                                    </nav>
                                    <form action="{{ route('notifications.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{ auth()->id() }}" name="updated_by">
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                                aria-labelledby="nav-home-tab">
                                                <h4 class="my-4">General Information</h4>
                                                <div class="form-horizontal row ">
                                                    <div class="mb-3 col-6">
                                                        <label for="exampleInputEmail1" class="form-label">Name</label>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" id="name" aria-describedby="namehelp"
                                                            required>
                                                        @error('name')
                                                            <label id="name-error" class="error text-danger" for="name">
                                                                {{ $message }}</label>
                                                        @enderror
                                                        <div id="namehelp" class="form-text">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-6">
                                                        <label for="exampleInputEmail1" class="form-label">Type</label>
                                                        <select name="type" class="form-control">
                                                            {{-- <option value="SRD"
                                                    {{ $type === 'SRD' ? 'selected' : '' }}>
                                                    Scheduled
                                                    Report Distribution</option> --}}
                                                            <option value="SN" {{ $type === 'SN' ? 'selected' : '' }}>
                                                                Subscription Notification</option>
                                                            {{-- <option value="ODNT"
                                                    {{ $type === 'ODNT' ? 'selected' : '' }}>On
                                                    Demand
                                                    Notification Template</option> --}}
                                                        </select>
                                                        @error('type')
                                                            <label id="name-error" class="error text-danger"
                                                                for="name">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-6">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label">Application</label>
                                                        <select name="application_id" class="form-control">
                                                            <option value="">Select Application</option>
                                                            @foreach ($applications as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $applicationId == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('application_id')
                                                            <label id="name-error" class="error text-danger"
                                                                for="application_id">{{ $message }}</label>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label">Descriptoin</label>
                                                        <Textarea name="description" rows="5" cols="5" class="form-control"></Textarea>
                                                        @error('type')
                                                            <label id="type-error" class="error text-danger" for="type">
                                                                {{ $message }}</label>
                                                        @enderror
                                                        <div id="typehelp" class="form-text">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="active"
                                                                {{ @$notification->active == 'Y' ? 'checked' : '' }}>
                                                            Active
                                                        </label>
                                                        <input type="hidden" name="active"
                                                            value="{{ @$notification->active ? 'Y' : 'N' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                                aria-labelledby="nav-profile-tab">
                                                <h4 class="my-4">Template Design</h4>
                                                <div class="form-horizontal row ">
                                                    <div class="col-6">
                                                        <div class="mb-3 ">
                                                            <label for="exampleInputEmail1"
                                                                class="form-label">Subject</label>
                                                            <input type="text"
                                                                class="form-control @error('subject') is-invalid @enderror"
                                                                name="subject" id="subject"
                                                                aria-describedby="subjecthelp" required>
                                                            @error('subject')
                                                                <label id="subject-error" class="error text-danger"
                                                                    for="subject">
                                                                    {{ $message }}</label>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1"
                                                                class="form-label">Body</label>
                                                            <Textarea name="body" rows="5" cols="5" class="form-control" id="bodyTextarea"></Textarea>
                                                            @error('type')
                                                                <label id="type-error" class="error text-danger"
                                                                    for="type">
                                                                    {{ $message }}</label>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="exampleInputEmail1" class="form-label">Select a
                                                            field</label>
                                                        <select class="form-control" id="fieldSelect"
                                                            onchange="updateTextareaContent()">
                                                            <option value="">Select field</option>
                                                            @foreach ($fields as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                                aria-labelledby="nav-contact-tab">
                                                <h4 class="my-4">Delivery Schudule</h4>
                                                <div class="form-horizontal row ">
                                                    <div class="col-6">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label">Recurring</label>
                                                        <select name="recurring" class="form-control"
                                                            id="recurringSelect">
                                                            <option value="">Select a day</option>
                                                            <option value="instantly">Instantly</option>
                                                            <option value="daily">Daily</option>
                                                            <option value="weekly">Weekly</option>
                                                            <option value="monthly">Monthly</option>
                                                            <option value="quarterly">Quarterly</option>
                                                            <option value="reminder">Reminder
                                                            </option>
                                                        </select>

                                                        @error('recurring')
                                                            <label id="name-error" class="error text-danger"
                                                                for="recurring">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                    <div id="timeInput" class="col-6" style="display: none;">
                                                        <label for="time" class="form-label">Select Time</label>
                                                        <input type="time" name="scheduled_time" class="form-control">
                                                    </div>
                                                    <div id="dayInput" class="col-6" style="display: none;">
                                                        <label for="day" class="form-label">Select Day</label>
                                                        <input type="number" name="scheduled_day" class="form-control"
                                                            placeholder="Day of the month" min="1" max="31">
                                                    </div>
                                                    <div id="weekDayInput" class="col-6" style="display: none;">
                                                        <label for="selectedDay" class="form-label">Select Day of the
                                                            Week</label>
                                                        <select name="selected_week_day" class="form-control">
                                                            <option value="">Select Day</option>
                                                            <option value="Sunday">Sunday</option>
                                                            <option value="Monday">Monday</option>
                                                            <option value="Tuesday">Tuesday</option>
                                                            <option value="Wednesday">Wednesday</option>
                                                            <option value="Thursday">Thursday</option>
                                                            <option value="Friday">Friday</option>
                                                            <option value="Saturday">Saturday</option>
                                                        </select>
                                                    </div>

                                                    <h4 class="mt-4">Email Recipients</h4>
                                                    <div class="usergrouplist">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <div class="col-md-2 addusers">
                                                                <button type="button" class="btn btn-primary text-end"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalusers"
                                                                    data-bs-whatever="@mdo">To Add Users</button>
                                                            </div>
                                                            <div class="col-md-2 addgroups">
                                                                <button type="button" class="btn btn-primary text-end"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalgroups"
                                                                    data-bs-whatever="@mdo">To Add Groups</button>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-12 d-flex justify-content-between">
                                                            @if (@$selectedusers != [])
                                                                <div class="col-md-5">
                                                                    <select id="" class="form-control " multiple
                                                                        disabled>
                                                                        @foreach (@$selectedusers as $item)
                                                                            <option selected>
                                                                                {{ $item->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            @if (@$selectedgroups != [])
                                                                <div class="col-md-5">
                                                                    <select id="" class="form-control " multiple
                                                                        disabled>
                                                                        @foreach (@$selectedgroups as $item)
                                                                            <option selected>
                                                                                {{ $item->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="modal fade " id="exampleModalusers" tabindex="-1"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Select Users
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="mb-3 text-start">
                                                                            <label for="message-text"
                                                                                class="col-form-label fw-bold text-left ">Users
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
                                                    <div class="usergrouplist">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <div class="col-md-2 addusers">
                                                                <button type="button" class="btn btn-primary text-end"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalusersCc"
                                                                    data-bs-whatever="@mdo">To Add Users CC</button>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-12 d-flex justify-content-between">
                                                            @if (@$selectedusersCc != [])
                                                                <div class="col-md-5">
                                                                    <select id="" class="form-control " multiple
                                                                        disabled>
                                                                        @foreach (@$selectedusersCc as $item)
                                                                            <option selected>
                                                                                {{ $item->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal fade " id="exampleModalusersCc" tabindex="-1"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Select Users
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="mb-3 text-start">
                                                                            <label for="message-text"
                                                                                class="col-form-label fw-bold text-left ">Users
                                                                                <small>(ctrl + click) multiple
                                                                                    select</small> </label>
                                                                            <select name="user_cc[]" id=""
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
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-filter" role="tabpanel"
                                                aria-labelledby="nav-filter-tab">
                                                <div class="table-responsive mt-5">
                                                    <a class="btn btn-success mb-3" id="addRow"><i
                                                            class="bi bi-plus-circle"></i>Add</a>
                                                    <table
                                                        class="table  table-striped  text-start align-middle table-bordered table-hover mb-0"
                                                        id="dataTable">
                                                        <thead>
                                                            <tr class="text-white" style="background-color: #009CFF;">
                                                                <th scope="col">ID</th>
                                                                <th scope="col">FIELD NAME</th>
                                                                <th scope="col">OPERATOR</th>
                                                                <th scope="col">VALUE</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="data-row">
                                                                <td>1</td>
                                                                <td>
                                                                    <select class="form-control" name="field_id[]">
                                                                        <option value="">Select field</option>
                                                                        @foreach ($fields as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="filter_operator[]">
                                                                        <option value="C">Contains</option>
                                                                        <option value="DNC">Does Not Contain</option>
                                                                        <option value="E">Equals</option>
                                                                        <option value="DNE">Does Not Equals</option>
                                                                        {{-- <option value="CH">Changed</option>
                                                            <option value="CT">Changed To</option>
                                                            <option value="CF">Changed From</option> --}}
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Value" name="filter_value[]">
                                                                </td>
                                                                <td>-</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="exampleInputEmail1" class="form-label">Advanced Operator
                                                        Logic</label>
                                                    <input type="text" class="form-control"
                                                        name="advanced_operator_logic" id="advancedOperatorLogic"
                                                        aria-describedby="advancedOperatorLogichelp">
                                                    @error('advancedOperatorLogic')
                                                        <label id="advancedOperatorLogic-error" class="error text-danger"
                                                            for="advancedOperatorLogic">
                                                            {{ $message }}</label>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-5">Submit</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('bodyTextarea');

        function updateTextareaContent() {
            var fieldSelect = document.getElementById('fieldSelect');
            var bodyTextarea = CKEDITOR.instances.bodyTextarea;

            if (fieldSelect.selectedIndex !== 0) {
                var selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
                var fieldName = selectedOption.text;

                // Append field name to the existing textarea content
                bodyTextarea.insertText('[field:' + fieldName + ']');
            }
        }

        $(document).ready(function() {
            $("#addRow").on("click", function() {
                var rowCount = $(".table-striped tbody tr").length + 1;
                var newRow = `<tr class="data-row">
                        <td class="row-id">${rowCount}</td>
                        <td>
                            <select class="form-control" name="field_id[]">
                                <option value="">Select field</option>
                                @foreach ($fields as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                          <select class="form-control" name="filter_operator[]">
                            <option value="C">Contains</option>
                                                            <option value="DNC">Does Not Contain</option>
                                                            <option value="E">Equals</option>
                                                            <option value="DNE">Does Not Equals</option>
                                                            // <option value="CH">Changed</option>
                                                            // <option value="CT">Changed To</option>
                                                            // <option value="CF">Changed From</option>
                                                    </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Value" name="filter_value[]">
                        </td>
                        <td><button class="btn btn-danger removeRow">Remove</button></td>
                    </tr>`;
                $(".table-striped tbody").append(newRow);
            });

            $(".table-striped").on("click", ".removeRow", function() {
                $(this).closest("tr").remove();
                updateRowIds();
            });

            function updateRowIds() {
                $(".table-striped tbody tr").each(function(index) {
                    $(this).find('.row-id').text(index + 1);
                });
            }

        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="active"]').change(function() {
                var isChecked = $(this).is(':checked');
                $('input[name="active"]').val(isChecked ? 'Y' : 'N');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var recurringSelect = document.getElementById('recurringSelect');
            var timeInput = document.getElementById('timeInput');
            var dayInput = document.getElementById('dayInput');
            var weekDayInput = document.getElementById('weekDayInput');

            recurringSelect.addEventListener('change', function() {
                timeInput.style.display = 'none';
                dayInput.style.display = 'none';
                weekDayInput.style.display = 'none';
                var selectedOption = recurringSelect.options[recurringSelect.selectedIndex].value;
                if (selectedOption === 'daily') {
                    timeInput.style.display = 'block';
                } else if (selectedOption === 'weekly') {
                    timeInput.style.display = 'block';
                    weekDayInput.style.display = 'block';
                } else if (selectedOption === 'monthly') {
                    timeInput.style.display = 'block';
                    dayInput.style.display = 'block';
                } else if (selectedOption === 'quarterly') {
                    timeInput.style.display = 'block';
                } else if (selectedOption === 'reminder') {
                    timeInput.style.display = 'block';
                }
            });
        });
    </script>
@endsection
