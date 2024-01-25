@extends('backend.layouts.app')
@section('content')

    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 ">
            <div class="col-sm-12">
                <div class="rounded h-100">
                    <div class="m-n2">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true"
                                        aria-controls="flush-collapseOne">
                                        Applications
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <a href="{{ route('view.save.report') }}"
                                            class="btn btn-primary m-2 fw-bold">SAVE</a>
                                        <button type="button" class="btn btn-outline-primary fw-bold">MODIFY</button>
                                        <button type="button" class="btn btn-outline-primary fw-bold">NEW REPORT</button>
                                        <button type="button" class="btn btn-outline-primary fw-bold">RELATED
                                            REPORTS</button>
                                        <div class="row mt-2">
                                            <div class="col-12 mt-5">
                                                <div class="col-6 bg-light rounded p-3">
                                                    <select class="form-control col-3" name="chart_type" id="chartType">
                                                        {{-- <option value=""><i class="bi bi-clipboard-data"></i> Chart And Data</option> --}}
                                                        <option value="dataOnly"><i class="bi bi-table"></i> Data Only
                                                        </option>
                                                        <option value="chartOnly"><i class="bi bi-bar-chart-line"></i> Chart
                                                            Only</option>
                                                    </select>
                                                </div>

                                                <div class="col-6 bg-light rounded p-3" id="chartTypeContainer">
                                                    <select class="form-control col-3 selectpicker" name="chart_type"
                                                        id="chartTypeDropdown">
                                                        <option value="line">Single Line Chart</option>
                                                        <option value="bar">Single Bar Chart</option>
                                                        <option value="pie">Pie Chart</option>
                                                        <option value="doughnut">Doughnut Chart</option>
                                                    </select>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">

                                                        <table class="table" id="dataOnly">
                                                            <thead>
                                                                <tr>
                                                                    <th>Field Name</th>
                                                                    <th>Count</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (isset($countData))
                                                                    @foreach ($countData as $fieldName => $count)
                                                                        <tr>
                                                                            <td>{{ $fieldName }}</td>
                                                                            <td>{{ $count }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="2">No data in the cart</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                        <!-- Chart Start -->
                                                        <div class="container-fluid pt-4 px-4" id="chartOnly">
                                                            <div class="row g-4">
                                                                <div class="col-sm-12 col-xl-6" id="line">
                                                                    <div class="bg-light rounded h-100 p-4">
                                                                        <h6 class="mb-4">Single Line Chart</h6>
                                                                        <canvas id="line-chart1"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-xl-6" id="salse">
                                                                    <div class="bg-light rounded h-100 p-4">
                                                                        <h6 class="mb-4">Multiple Line Chart</h6>
                                                                        <canvas id="salse-revenue1"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-xl-6" id="bar">
                                                                    <div class="bg-light rounded h-100 p-4">
                                                                        <h6 class="mb-4">Single Bar Chart</h6>
                                                                        <canvas id="bar-chart1"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-xl-6" id="worldwide">
                                                                    <div class="bg-light rounded h-100 p-4">
                                                                        <h6 class="mb-4">Multiple Bar Chart</h6>
                                                                        <canvas id="worldwide-sales1"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-xl-6" id="pie">
                                                                    <div class="bg-light rounded h-100 p-4">
                                                                        <h6 class="mb-4">Pie Chart</h6>
                                                                        <canvas id="pie-chart1"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-xl-6" id="doughnut">
                                                                    <div class="bg-light rounded h-100 p-4">
                                                                        <h6 class="mb-4">Doughnut Chart</h6>
                                                                        <canvas id="doughnut-chart1"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Chart End -->
                                                    </div>
                                                </div>
                                                <!-- End of Bootstrap-styled cart -->
                                            </div>
                                        </div>
                                    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            // Hide all charts initially
            $('#line, #salse, #bar, #worldwide, #pie, #doughnut').hide();

            // Initially hide the chart type dropdown
            $('#chartTypeContainer').hide();

            $('#chartType').change(function() {
                var selectedValue = $(this).val();

                // Hide the chart type dropdown by default
                $('#chartTypeContainer').hide();

                if (selectedValue === 'chartOnly') {
                    // If 'Chart Only' is selected, show the chart type dropdown
                    $('#chartTypeContainer').show();
                    $('#dataOnly').hide();
                } else {
                    // If 'Data Only' is selected, hide all charts
                    $('#line, #salse, #bar, #worldwide, #pie, #doughnut')
                        .hide();
                    $('#dataOnly').show();

                }
            });

            $('#chartTypeDropdown').change(function() {
                var selectedChart = $(this).val();

                // Hide all charts
                $('#line, #salse, #bar, #worldwide, #pie, #doughnut')
                    .hide();

                // Show the selected chart
                $('#' + selectedChart).show();
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var countData = @json($countData);

            // Create line chart
            var ctxLine = document.getElementById('line-chart1').getContext('2d');

            var myLineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: Object.keys(countData),
                    datasets: [{
                        backgroundColor: [
                            'rgba(0, 156, 255, .7)',
                            'rgba(0, 156, 255, .6)',
                            'rgba(0, 156, 255, .5)',
                            'rgba(0, 156, 255, .4)',
                            'rgba(0, 156, 255, .3)'
                        ],
                        data: Object.values(countData)
                    }]
                },
                options: {
                    responsive: true
                }
            });
            // Create salse-revenue chart
            var ctxSalse = document.getElementById('salse-revenue1').getContext('2d');
            var mySalseChart = new Chart(ctxSalse, {
                type: 'line',
                data: {
                    labels: Object.keys(countData),
                    datasets: [{
                        label: 'Count',
                        data: Object.values(countData),
                        backgroundColor: '#009CFF',
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            var barCtx = document.getElementById('bar-chart1').getContext('2d');
            var myChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(countData),
                    datasets: [{
                        label: 'Count',
                        data: Object.values(countData),
                        backgroundColor: '#009CFF',
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            var ctxPie = document.getElementById('pie-chart1').getContext('2d');
            var myPieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: Object.keys(countData),
                    datasets: [{
                        backgroundColor: [
                            'rgba(0, 156, 255, .7)',
                            'rgba(0, 156, 255, .6)',
                            'rgba(0, 156, 255, .5)',
                            'rgba(0, 156, 255, .4)',
                            'rgba(0, 156, 255, .3)'
                        ],
                        data: Object.values(countData)
                    }]
                },
                options: {
                    responsive: true
                }
            });

            var pieCtx = document.getElementById('doughnut-chart1').getContext('2d');
            var myChart = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(countData),
                    datasets: [{
                        label: 'Count',
                        data: Object.values(countData),
                        backgroundColor: '#009CFF',
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            // Repeat the process for other charts...
        });
    </script>
@endsection
