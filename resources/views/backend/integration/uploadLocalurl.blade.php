@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>CSV Import</h1>
                </div>
                <div class="col-md-6 text-end"><button onclick="window.history.back();" class="btn btn-secondary"><i
                            class="bi bi-arrow-left-short"></i> Back</button></div>
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
                    <li class="breadcrumb-item active">CSV Import</li>
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
                                    <nav class=" mt-5">
                                        <div class="nav nav-tabs  nav-pills" id="nav-tab" role="tablist">
                                            <button class="nav-link active  " id="nav-home-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-home" type="button" role="tab"
                                                aria-controls="nav-home" aria-selected="true">GENERAL</button>

                                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-contact" type="button" role="tab"
                                                aria-controls="nav-contact" aria-selected="false">DATA MAP</button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-profile" type="button" role="tab"
                                                aria-controls="nav-profile" aria-selected="false">Key Field
                                                Definition</button>
                                            <button class="nav-link" id="nav-filter-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-filter" type="button" role="tab"
                                                aria-controls="nav-filter" aria-selected="false">RUN CONFIGURATION</button>
                                        </div>
                                    </nav>
                                    <form class="form-horizontal  mt-5" method="POST"
                                        action="{{ route('url.local.upload') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                                aria-labelledby="nav-home-tab">
                                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                    <label for="name" class="col-md-4 form-label">Name</label>
                                                    <div class="col-md-6">
                                                        <input id="name" type="text" class="form-control"
                                                            name="name" value="{{ @$dataUrls->name }}" required>
                                                        @if ($errors->has('name'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group{{ $errors->has('excel_file') ? ' has-error' : '' }}">
                                                    <label for="excel_file" class="col-md-4 form-label">URL</label>
                                                    <div class="col-md-6">
                                                        <input id="excel_file" type="text" class="form-control"
                                                            name="excel_file" value="{{ @$dataUrls->excel_file }}" required>
                                                        @if ($errors->has('excel_file'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('excel_file') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                                aria-labelledby="nav-profile-tab">
                                                <div class="col-6 bg-light rounded p-3">
                                                    <label for="keyFields" class="form-label">Key Field Definition</label>
                                                    <select class="form-control col-3" name="key_field">
                                                        @foreach ($databaseColumns as $databaseColumn)
                                                            <option value="{{ $databaseColumn->name }}">
                                                                {{ $databaseColumn->name }} ({{ $databaseColumn->type }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                                aria-labelledby="nav-contact-tab">

                                                <div id="exampleDiv"></div>

                                                <table id="csvDataTable" class="table">
                                                    <thead>
                                                        <!-- Assuming your CSV has two columns 'column1' and 'column2' -->
                                                        <th>No data found</th>
                                                        <!-- Add other headers as needed -->
                                                    </thead>
                                                    <tbody>
                                                        <!-- CSV data will be dynamically inserted here -->
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="tab-pane fade" id="nav-filter" role="tabpanel"
                                                aria-labelledby="nav-filter-tab">
                                                <div class="form-horizontal row ">
                                                    <div class="col-6">
                                                        <label for="time" class="form-label">Start Time</label>
                                                        <input type="time" name="start_time" class="form-control"
                                                            value="{{ @$dataUrls->start_time }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="day" class="form-label">Start Date</label>
                                                        <input type="date" name="start_day" class="form-control"
                                                            value="{{ @$dataUrls->start_day }}"
                                                            placeholder="Day of the month" min="1" max="31">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label">Recurring</label>
                                                        <select name="recurring" class="form-control"
                                                            id="recurringSelect">
                                                            <option value="">Select a day</option>
                                                            <option value="minutely">Minutely</option>
                                                            <option value="hourly">Hourly</option>
                                                            <option value="daily">Daily</option>
                                                            <option value="weekly">Weekly</option>
                                                            <option value="monthly">Monthly</option>
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group  mt-5">
                                            <div class="col-md-8 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="application_id" value="{{ $id }}">
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    </form>

                                </div>
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div><!-- End Left side columns -->
            </div>
        </section>
    </main><!-- End #main -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // When the DATA MAP tab is shown
            $('#nav-contact-tab').on('shown.bs.tab', function() {
                // Get values from the first tab
                var excelFile = $('#excel_file').val();

                $.ajax({
                    url: '{{ route('get.csv.data') }}', // Replace with your route to fetch CSV data
                    method: 'GET',
                    data: {
                        excelFile: excelFile
                    },
                    success: function(data) {
                        console.log(data[0]);
                        var databaseColumns = @json($databaseColumns);
                        var dropdowns = $('<ul class="row"></ul>');
                        // Clear existing dropdowns
                        $('#exampleDiv').empty();
                        data[0].forEach(function(heading, index) {
                            var dropdown = $(
                                '<div class="col-3 bg-light rounded p-3 container shadow"></div>'
                            );
                            dropdown.append(
                                '<label for="exampleInputEmail1" class="form-label"><strong>' +
                                heading + '</strong></label>');
                            var select = $(
                                '<select class="form-control col-3" name="column_mappings[' +
                                index +
                                ']"><option value="">Select Database Column</option></select>'
                            );
                            var defaultDatabaseColumn = databaseColumns[0];
                            select.append('<option value="' + defaultDatabaseColumn
                                .name + '">' +
                                defaultDatabaseColumn.name + ' (' +
                                defaultDatabaseColumn.type + ')</option>');

                            // Add other options based on the databaseColumns
                            databaseColumns.forEach(function(databaseColumn) {
                                if (databaseColumn !== defaultDatabaseColumn) {
                                    var option = $('<option></option>').attr(
                                        'value', databaseColumn.name).text(
                                        databaseColumn.name + ' (' +
                                        databaseColumn.type + ')');
                                    select.append(option);
                                }
                            });
                            dropdown.append(select);
                            dropdowns.append(dropdown);
                        });

                        $('#exampleDiv').append(dropdowns);






                        var table = $('#csvDataTable');

                        // Clear existing table content
                        table.empty();

                        // // Create table rows and append to the table
                        // data.forEach(function(row) {
                        //     var tr = $('<tr></tr>');

                        //     // Assuming your CSV has two columns 'column1' and 'column2'
                        //     tr.append('<td>' + row[0] + '</td>');
                        //     tr.append('<td>' + row[1] + '</td>');

                        //     // Add other columns as needed

                        //     table.append(tr);
                        // });
                        var thead = $('<thead></thead>');
                        var headerRow = $('<tr></tr>');
                        data[0].forEach(function(header) {
                            headerRow.append('<th>' + header + '</th>');
                        });
                        thead.append(headerRow);
                        table.append(thead);

                        // Create table body
                        var tbody = $('<tbody></tbody>');
                        for (var i = 1; i < data.length; i++) {
                            var dataRow = $('<tr></tr>');
                            data[i].forEach(function(cell) {
                                dataRow.append('<td>' + cell + '</td>');
                            });
                            tbody.append(dataRow);
                        }
                        table.append(tbody);
                    },
                    error: function(error) {
                        console.error('Error fetching CSV data:', error);
                    }
                });
            });
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
                } else if (selectedOption === 'minutely') {
                    timeInput.style.display = 'block';
                } else if (selectedOption === 'minutely') {
                    timeInput.style.display = 'block';
                }
            });
        });
    </script>
@endsection
