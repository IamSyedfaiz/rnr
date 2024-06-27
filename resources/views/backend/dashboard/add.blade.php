@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>
                        Dashboard</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-short"></i>
                        Back</a>
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
                    <li class="breadcrumb-item active">
                        Dashboard</li>
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
                                    <nav class="d-flex justify-content-between">
                                        <div class="nav nav-tabs  nav-pills" id="nav-tab" role="tablist">
                                            <button class="nav-link active  " id="nav-home-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-home" type="button" role="tab"
                                                aria-controls="nav-home" aria-selected="true">General
                                            </button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-profile" type="button" role="tab"
                                                aria-controls="nav-profile" aria-selected="false">Layout
                                            </button>
                                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-contact" type="button" role="tab"
                                                aria-controls="nav-contact" aria-selected="false">Access
                                            </button>
                                        </div>
                                    </nav>
                                    <form action="{{ route('dashboard.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{ auth()->id() }}" name="user_id">
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
                                                            value="{{ old('name') }}" required>
                                                        @error('name')
                                                            <label id="name-error" class="error text-danger" for="name">
                                                                {{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-6">
                                                        <label for="exampleInputEmail1" class="form-label">Alias</label>
                                                        <input type="text"
                                                            class="form-control @error('alias') is-invalid @enderror"
                                                            name="alias" id="alias" aria-describedby="aliashelp"
                                                            value="{{ old('alias') }}" required>
                                                        @error('alias')
                                                            <label id="alias-error" class="error text-danger" for="alias">
                                                                {{ $message }}</label>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3 col-6">
                                                        <label for="exampleInputEmail1" class="form-label">Type</label>
                                                        <select name="type" class="form-control">
                                                            <option value="Dashboard">Dashboards</option>
                                                        </select>
                                                        @error('type')
                                                            <label id="name-error" class="error text-danger"
                                                                for="name">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                    {{-- <div class="mb-3 col-6">
                                            <label for="exampleInputEmail1" class="form-label">ID</label>
                                            <input type="text" class="form-control " disabled>
                                        </div> --}}
                                                    <div class="mb-3 col-6">
                                                        <label for="exampleInputEmail1" class="form-label">status</label>
                                                        <select name="active" class="form-control">
                                                            <option value="Y">Active</option>
                                                            <option value="N">In-Active</option>
                                                        </select>
                                                        @error('active')
                                                            <label id="name-error" class="error text-danger"
                                                                for="active">{{ $message }}</label>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label">Descriptoin</label>
                                                        <Textarea name="description" rows="5" cols="5" class="form-control">{{ old('description') }}</Textarea>
                                                        @error('description')
                                                            <label id="type-error" class="error text-danger" for="type">
                                                                {{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <h4 class="my-4">Layout Design</h4>
                                                <div class="form-horizontal row">
                                                    <div class="mb-3 col-6">
                                                        <label for="columnLayout" class="form-label">Column Layout</label>
                                                        <select name="layout" id="columnLayout" class="form-control"
                                                            onchange="updateImage()">
                                                            <option value="50">Two column - 50/50</option>
                                                            <option value="100">Two column - 100</option>
                                                        </select>
                                                        @error('layout')
                                                            <label id="name-error" class="error text-danger"
                                                                for="layout">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-6">
                                                        <label for="preview" class="form-label">Preview</label>
                                                        <div id="preview">
                                                            <img id="previewImage"
                                                                src="{{ asset('public/backend/assets/img/50.png') }}"
                                                                alt="50">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                                aria-labelledby="nav-profile-tab">
                                                <div class="d-flex justify-content-between my-4">

                                                    <h4 class="">Layout</h4>
                                                    <div class="form-horizontal row ">
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#exampleModalReport"
                                                            data-bs-whatever="@mdo">
                                                            Select report</button>
                                                    </div>

                                                    <div class="modal fade" id="exampleModalReport" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Add
                                                                        Report</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table table-bordered" id="reportTable">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Report ID</th>
                                                                                <th>Report Name</th>
                                                                                <th>Application Name</th>
                                                                                <th>Select</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($reports as $index => $report)
                                                                                <tr>
                                                                                    <td>{{ $index + 1 }}</td>
                                                                                    <td>{{ $report->name }}</td>
                                                                                    <td>{{ $report->application->name }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="checkbox"
                                                                                            name="report_id[]"
                                                                                            class="reportCheckbox"
                                                                                            value="{{ $report->id }}">
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary"
                                                                        onclick="submitReports()">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <canvas id="reportsChart"></canvas> --}}
                                                <div class="mb-3" id="canvasContainer">
                                                    <div id="selectedReports" class="mt-3 row"></div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                                aria-labelledby="nav-contact-tab">
                                                <h4 class="my-4">Access</h4>
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-1">
                                                        <label for="display"
                                                            class="col-form-label fw-bold">Display</label>
                                                    </div>
                                                    <div class="col-2">
                                                        <input class="form-check-input" type="radio" value="PB"
                                                            name="access" id="access2">
                                                        <label class="form-check-label text-dark fw-bold" for="access1">
                                                            Public
                                                        </label>
                                                    </div>
                                                    <div class="col-9">
                                                        <span id="passwordHelpInline" class="form-text">
                                                            Allow all users in the system to have access to the dashboard
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row  align-items-center">
                                                    <div class="col-1">
                                                    </div>
                                                    <div class="col-2">
                                                        <input class="form-check-input" type="radio" value="PR"
                                                            name="access" id="access1">
                                                        <label class="form-check-label text-dark fw-bold" for="access1">
                                                            Private
                                                        </label>
                                                    </div>
                                                    <div class="col-9">
                                                        <span id="passwordHelpInline" class="form-text">
                                                            Allow only specific users and groups to this dashboard you can
                                                            also assign
                                                            access to this workspace based on application access rights
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="usergrouplist" style="display: none;">
                                                    <div class="row  mb-2">
                                                        <div class="col-md-6 addusers">
                                                            <button type="button"
                                                                class="btn btn-primary text-end mb-3 mt-3"
                                                                data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                                data-bs-whatever="@mdo">Add
                                                                Users</button>
                                                            <div class="mb-3">
                                                                <label for="users">Selected Users</label>
                                                                <select name="user_list[]" id=""
                                                                    class="form-control" multiple>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 addgroups">
                                                            <button type="button"
                                                                class="btn btn-primary text-end mb-3 mt-3"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalgroups"
                                                                data-bs-whatever="@mdo">Add
                                                                Groups</button>
                                                            <div class="mb-3">
                                                                <label for="users">Selected Groups</label>
                                                                <select name="group_list[]" id=""
                                                                    class="form-control" multiple>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="exampleModalusers" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Add
                                                                        Users</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="mb-3 text-start">
                                                                                <label for="filter">Users&nbsp;</label>
                                                                                {{-- <input
                                                                                    id="filter" type="text"
                                                                                    class="filter form-control"
                                                                                    placeholder="Search Users"> --}}
                                                                                <br />

                                                                                <div id="mdi"
                                                                                    style="max-height: 10%; overflow:auto;">
                                                                                    @foreach ($users as $item)
                                                                                        <span><input
                                                                                                class="talents_idmd-checkbox"
                                                                                                onchange="dragdrop1(this.value, this.id);"
                                                                                                type="checkbox"
                                                                                                id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                                value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                                    @endforeach
                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="exampleModalgroups" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Add
                                                                        Group</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="mb-3 text-start">
                                                                                <label for="filter">Groups&nbsp;</label>
                                                                                {{-- <input
                                                                                    id="filter" type="text"
                                                                                    class="filter form-control"
                                                                                    placeholder="Search Groups"> --}}
                                                                                <br />

                                                                                <div id="mdi"
                                                                                    style="max-height: 10%; overflow:auto;">
                                                                                    @foreach ($groups as $item)
                                                                                        <span><input
                                                                                                class="talents_idmd-checkbox"
                                                                                                onchange="dragdrop(this.value, this.id);"
                                                                                                type="checkbox"
                                                                                                id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                                value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                                    @endforeach
                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                                                </div>
                                                            </div>

                                                        </div>
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
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // Assuming reports is a JSON object containing all report data
        const reports = @json($reports);

        function submitReports() {
            let selectedReportsDiv = document.getElementById('selectedReports');
            let selectedReports = [];
            let checkboxes = document.querySelectorAll('.reportCheckbox:checked');

            checkboxes.forEach(function(checkbox) {
                selectedReports.push(checkbox.value);
            });

            if (selectedReports.length > 0) {
                fetchReportsDetails(selectedReports);
            } else {
                selectedReportsDiv.textContent = 'No reports selected.';
            }

            // Close the modal
            let modal = bootstrap.Modal.getInstance(document.getElementById('exampleModalReport'));
            modal.hide();
        }

        function defaultPalette() {
            return ['#FF5733', '#36A2EB', '#FFC300', '#4BC0C0', '#5F9EA0', '#FFA07A', '#20B2AA', '#8A2BE2', '#FF6347',
                '#4682B4'
            ];
        }

        function brightPalette() {
            return [
                '#FFA07A',
                '#9370DB',
                '#6A5ACD',
                '#FF1493',
                '#7FFF00',
                '#4BC0C0',
                '#FF4500',
                '#FFD700',
                '#32CD32',
                '#1E90FF',
                '#8A2BE2',
                '#00FF7F',
                '#FF1493',
                '#FF6347',
                '#00CED1'
            ];
        }

        function customPalette() {
            const colors = ['#FF2730', '#36A2EB', '#FFC300', '#4BC0C0'];
            const randomColors = [];
            const numColors = 4; // Number of random colors required

            for (let i = 0; i < numColors; i++) {
                const randomIndex = Math.floor(Math.random() * colors.length);
                randomColors.push(colors[randomIndex]);
            }

            return randomColors;
        }

        function dynamicColors() {
            return [
                '#FF6347',
                '#4682B4',
                '#FFD700',
                '#ADFF2F',
                '#4B0082',
                '#00CED1',
                '#FF4500',
                '#8A2BE2',
                '#FF69B4',
                '#00FF7F',
                '#DC143C',
                '#1E90FF',
                '#FFDAB9',
                '#9370DB',
                '#7FFF00',
            ];
        }

        function applySelectedColors() {
            var labelColors = {};
            var colorInputs = document.querySelectorAll('.label-color');
            colorInputs.forEach(function(input) {
                var label = input.dataset.label;
                var color = input.value;
                labelColors[label] = color;
            });

            updateChartColors(labelColors);
        }

        function updateChartColors(labelColors) {
            var selectedLabel = Object.keys(labelColors);
            var selectedColor = Object.values(labelColors);
            var datasets = myPieChart.data.datasets;
            var labels = myPieChart.data.labels;
            for (var i = 0; i < labels.length; i++) {
                if (labels[i] === selectedLabel[i]) {
                    datasets[0].backgroundColor[i] = selectedColor[i];
                } else {
                    datasets[0].backgroundColor[i] = '#d6d6d6';
                }
            }
            myPieChart.update();
        }

        function fetchReportsDetails(reportIds) {
            fetch('{{ route('get.report') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        reportIds: reportIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    displaySelectedReports(data);
                    // renderChart(data);
                })
                .catch(error => console.error('Error fetching report details:', error));
        }

        function displaySelectedReports(reports) {
            let selectedReportsDiv = document.getElementById('selectedReports');
            selectedReportsDiv.innerHTML = '';
            if (reports.length > 0) {
                reports.forEach(function(report) {
                    if (report.statistics_mode == 'Y') {

                        if (report.data_type == 'dataOnly') {
                            // Create a table element
                            let tableElement = document.createElement('table');
                            tableElement.className =
                                'table table-striped text-start align-middle table-bordered table-hover mt-5';
                            tableElement.id = 'dataOnly';

                            // Create table headers
                            let tableHeader = `
                            <thead>
                                <tr>
                                    <th>Application Name</th>
                                    <th>Count of Application Name</th>
                                </tr>
                            </thead>
                        `;
                            tableElement.innerHTML = tableHeader;

                            // Create table body
                            let tableBody = '<tbody>';
                            let countData = JSON.parse(report
                                .data); // Assuming countData is a JSON string in the report object

                            if (countData && Object.keys(countData).length > 0) {
                                for (let fieldName in countData) {
                                    tableBody += `
                                    <tr>
                                        <td>${fieldName}</td>
                                        <td>${countData[fieldName]}</td>
                                    </tr>
                                `;
                                }
                            } else {
                                tableBody += `
                                <tr>
                                    <td colspan="2">No data in the cart</td>
                                </tr>
                            `;
                            }
                            tableBody += '</tbody>';
                            tableElement.innerHTML += tableBody;

                            selectedReportsDiv.appendChild(tableElement);
                        } else {
                            let canvasElement = document.createElement('canvas');
                            canvasElement.id = 'chart-' + report.id;
                            canvasElement.width = 400;
                            canvasElement.height = 200;
                            selectedReportsDiv.appendChild(canvasElement);

                            // Render the chart
                            renderChart(report, 'chart-' + report.id)
                        }
                        console.log('statistics_mode hai');;

                    } else {
                        let reportData = JSON.parse(report.data);
                        let tableElement = createTableFromData(reportData, 'report-table-' + report.id);
                        selectedReportsDiv.appendChild(tableElement);
                    }

                });
            } else {
                selectedReportsDiv.textContent = 'No reports selected.';
            }
        }

        function createTableFromData(data, tableId) {
            // Create a table element
            let tableElement = document.createElement('table');
            tableElement.id = tableId;
            tableElement.className = 'table table-striped text-start align-middle table-bordered table-hover mt-5';
            // Create table headers
            let tableHeader = '<thead><tr>';
            for (let key in data) {
                tableHeader += `<th>${key}</th>`;
            }
            tableHeader += '</tr></thead>';

            // Create table body
            let tableBody = '<tbody>';
            // Determine the number of rows by finding the longest array in the values
            let numRows = Math.max(...Object.values(data).map(arr => arr.length));
            for (let i = 0; i < numRows; i++) {
                tableBody += '<tr>';
                for (let key in data) {
                    tableBody += `<td>${data[key][i] !== undefined ? data[key][i] : ''}</td>`;
                }
                tableBody += '</tr>';
            }
            tableBody += '</tbody>';

            // Set the innerHTML of the table
            tableElement.innerHTML = tableHeader + tableBody;

            return tableElement;
        }

        function getCustomColors(reportData) {
            // Check if labelColor is a string and parse it if necessary
            let labelColors = reportData.labelColor;
            if (typeof labelColors === 'string') {
                labelColors = JSON.parse(labelColors);
            }

            var customColors = [];
            var labels = Object.keys(reportData.data);

            for (var i = 0; i < labelColors.length; i++) {
                if (labelColors[labels[i]]) {
                    customColors.push(labelColors[labels[i]]);
                } else {
                    customColors.push('#d6d6d6');
                }
            }
            return customColors;
        }

        function renderChart(reportData, canvasId) {
            const ctx = document.getElementById(canvasId).getContext('2d');

            const reportsKaData = JSON.parse(reportData.data);
            const labels = Object.keys(reportsKaData);
            const data = Object.values(reportsKaData);
            const selectChart = reportData.selectChart || 'bar';


            var colors;
            if (reportData.selectedPalette === 'random') {
                colors = defaultPalette();
            } else if (reportData.selectedPalette === 'default') {
                colors = dynamicColors();
            } else if (reportData.selectedPalette === 'custom') {
                colors = getCustomColors(reportData);
            } else if (reportData.selectedPalette === 'bright') {
                colors = brightPalette();
            } else {
                colors = defaultPalette();
            }

            // Create a new chart
            new Chart(ctx, {
                type: selectChart,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Report Data',
                        data: data,
                        backgroundColor: colors,
                        borderWidth: reportData.borderWidth,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: reportData.legendPosition || 'top' // Default position is 'top'
                        }
                    }
                }
            });
        }
    </script>
    <script>
        function updateImage() {
            var select = document.getElementById('columnLayout');
            var previewImage = document.getElementById('previewImage');
            var canvasContainer = document.getElementById('canvasContainer');
            var selectedValue = select.value;

            if (selectedValue == '50') {
                previewImage.src = '{{ asset('public/backend/assets/img/50.png') }}';
                previewImage.alt = '50';
                canvasContainer.classList.remove('col-12');
                canvasContainer.classList.add('col-6');
            } else if (selectedValue == '100') {
                previewImage.src = '{{ asset('public/backend/assets/img/100.png') }}';
                previewImage.alt = '100';
                canvasContainer.classList.remove('col-6');
                canvasContainer.classList.add('col-12');
            }
        }

        // Initialize the image based on the default selection
        document.addEventListener('DOMContentLoaded', function() {
            updateImage();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="access"]').change(function() {
                if ($(this).attr('id') === 'access1') {
                    // Show the div for Personal Report
                    $('.usergrouplist').show();
                } else {
                    // Hide the div for Global Report
                    $('.usergrouplist').hide();
                }
            });
        });

        function dragdrop(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('group_list[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('group_list[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }

        function dragdrop1(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('user_list[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('user_list[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }
    </script>
@endsection
