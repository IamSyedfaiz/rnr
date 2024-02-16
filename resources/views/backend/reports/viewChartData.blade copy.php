@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.min.css">
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
                                        <div class="row mt-2">
                                            <div class="col-6 mt-5">
                                                <div class="col-6 bg-light rounded p-3">
                                                    <select class="form-control col-3" name="data_type" id="chartType">
                                                        {{-- <option value=""><i class="bi bi-clipboard-data"></i> Chart And Data</option> --}}
                                                        <option value="dataOnly"><i class="bi bi-table"></i> Data Only
                                                        </option>
                                                        <option value="chartOnly"><i class="bi bi-bar-chart-line"></i>
                                                            Chart
                                                            Only</option>
                                                    </select>
                                                </div>

                                                <!-- Add dropdown menu -->
                                                <div class="form-group">
                                                    <label for="colorPicker">Select Color:</label>
                                                    <select id="colorPalette" class="form-control">
                                                        <option value="random">Random</option>
                                                        <option value="default">Default Palette</option>
                                                        <option value="custom">Custom Palette</option>
                                                    </select>
                                                </div>
                                                <!-- Canvas for the chart -->
                                                <!-- Border width dropdown menu -->
                                                <div class="form-group">
                                                    <label for="borderWidth">Select Border Width:</label>
                                                    <select id="borderWidth" class="form-control">
                                                        <option value="10">Standard</option>
                                                        <option value="20">Explode Smallest </option>
                                                        <option value="30">Explode Largest</option>
                                                    </select>
                                                </div>
                                                <select id="chartTypeSelect" class="form-control col-3">
                                                    <option value="line">Single Line Chart</option>
                                                    <option value="bar">Single Bar Chart</option>
                                                    <option value="pie">Pie Chart</option>
                                                    <option value="doughnut">Doughnut Chart</option>
                                                    <!-- Add more options for different chart types as needed -->
                                                </select>
                                                {{-- <div class="form-group mt-3">
                                                    <label for="labelSelector">Select Label:</label>
                                                    <select id="labelSelector" class="form-control">
                                                        @foreach ($countData as $label => $value)
                                                            <option value="{{ $label }}">{{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="colorInput">Label Color:</label>
                                                    <input type="color" class="form-control" id="colorInput">
                                                </div>
                                                <button type="button" class="btn btn-primary" id="submitColorBtn">Apply
                                                    Color</button> --}}
                                                <!-- Labels and color inputs -->
                                                <div class="container mt-3">
                                                    @foreach ($countData as $label => $value)
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label
                                                                    for="colorInput_{{ $label }}">{{ $label }}
                                                                    Color:</label>
                                                                <input type="color" class="form-control label-color"
                                                                    id="colorInput_{{ $label }}"
                                                                    data-label="{{ $label }}" value="#d6d6d6">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Apply Color button -->
                                                <button type="button" class="btn btn-primary" id="submitColorBtn">Apply
                                                    Color</button>



                                                <canvas id="myChart"></canvas>

                                                <div class="col-6 bg-light rounded p-3" id="chartTypeContainer">
                                                    <select class="form-control col-3 selectpicker" name="chart_type"
                                                        id="chartTypeDropdown">
                                                        <option value="line">Single Line Chart</option>
                                                        <option value="bar">Single Bar Chart</option>
                                                        <option value="pie">Pie Chart</option>
                                                        <option value="doughnut">Doughnut Chart</option>
                                                        @foreach ($countData as $key => $value)
                                                            <option value="{{ $key }}">{{ $key }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="">
                                                    <div class="">

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


    {{-- <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <!-- Include Bootstrap Colorpicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js">
    </script>
    <script>
        var ctxPie = document.getElementById('myChart').getContext('2d');
        var countData = @json($countData);

        function dynamicColors() {
            var colors = [];
            for (var i = 0; i < Object.keys(countData).length; i++) {
                colors.push('rgba(' + Math.floor(Math.random() * 256) + ', ' + Math.floor(Math.random() * 256) + ', ' +
                    Math.floor(Math.random() * 256) + ', 0.7)');
            }
            return colors;
        }

        function defaultPalette() {
            return ['#FF5733', '#36A2EB', '#FFC300', '#4BC0C0', '#FF6347', '#5F9EA0', '#FFA07A', '#20B2AA', '#8A2BE2',
                '#4682B4'
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

        function updateColors(colors) {
            var datasets = myPieChart.data.datasets;
            for (var i = 0; i < datasets.length; i++) {
                datasets[i].backgroundColor = colors;
            }
            myPieChart.update();
        }

        function updateClickedData(label, value) {
            var clickedDataElement = document.getElementById('clickedData');
            clickedDataElement.innerHTML = '<h4>Clicked Data:</h4><p><strong>Label:</strong> ' + label +
                ', <strong>Value:</strong> ' + value + '</p>';
        }
        var myPieChart; // Global variable to hold the chart instance

        // Function to create or update the chart based on the selected type
        function createOrUpdateChart(chartType) {
            if (myPieChart) {
                // If myPieChart exists, destroy it first
                myPieChart.destroy();
            }
            console.log(myPieChart);

            myPieChart = new Chart(ctxPie, {
                type: chartType,
                data: {
                    labels: Object.keys(countData),
                    datasets: [{
                        data: Object.values(countData),
                        backgroundColor: dynamicColors()
                    }]
                },
                options: {
                    responsive: true,
                    borderWidth: 3,
                    legend: {
                        display: false,
                    },
                    onClick: (evt, activeElements, chart) => {
                        if (activeElements.length > 0) {
                            var datasetIndex = activeElements[0].datasetIndex;
                            // console.log('datasetIndex:', datasetIndex);

                            var dataIndex = activeElements[0].index;
                            // console.log('dataIndex:', dataIndex);

                            // Retrieve the IDs based on datasetIndex and dataIndex
                            var labelId = chart.data.labels[dataIndex];
                            var datasetId = chart.data.datasets[datasetIndex].label;
                            // console.log('labelId:', labelId);
                            // console.log('datasetId:', datasetId);
                            function filterDataById(data, labelId) {
                                const entry = Object.entries(data).find(([key, value]) => key === labelId);
                                return entry ? {
                                    [entry[0]]: entry[1]
                                } : null;
                            }


                            var filteredData = filterDataById(countData, labelId);
                            if (filteredData !== null) {
                                console.log(filteredData);
                                // Send AJAX request to Laravel backend
                                // Create a form element
                                var form = document.createElement('form');
                                form.method = 'GET'; // Change the method if needed
                                form.action =
                                    '{{ route('route.to.handle') }}'; // Replace with the correct route URL

                                // Create a hidden input field to store the filtered data
                                var filteredDataInput = document.createElement('input');
                                filteredDataInput.type = 'hidden';
                                filteredDataInput.name = 'filteredData';
                                filteredDataInput.value = JSON.stringify(
                                    filteredData); // Fill in the filtered data

                                // Append the hidden input field to the form
                                form.appendChild(filteredDataInput);

                                // Append the form to the document body
                                document.body.appendChild(form);

                                // Submit the form
                                form.submit();


                            } else {
                                console.log('No data found for labelId:', labelId);
                            }


                        }
                    }


                }

            });
        }
        createOrUpdateChart('bar');

        // Dropdown change event listener
        document.getElementById('chartTypeSelect').addEventListener('change', function() {
            var selectedType = this.value;
            createOrUpdateChart(selectedType);
        });
        document.getElementById('colorPalette').addEventListener('change', function() {
            var selectedPalette = this.value;
            var colors;

            // Set colors based on selected palette
            if (selectedPalette === 'random') {
                colors = dynamicColors(); // Random colors
            } else if (selectedPalette === 'default') {
                colors = defaultPalette(); // Default palette
            } else if (selectedPalette === 'custom') {
                colors = customPalette();
            }

            // Update chart colors
            updateColors(colors);
        });
        document.getElementById('borderWidth').addEventListener('change', function() {
            var selectedWidth = parseInt(this.value);
            myPieChart.options.borderWidth = selectedWidth;
            myPieChart.update();
        });
        // document.getElementById('submitColorBtn').addEventListener('click', function() {
        //     var selectedLabel = document.getElementById('labelSelector').value;
        //     var selectedColor = document.getElementById('colorInput').value;

        //     // Update the color of the selected label in the chart
        //     updateLabelColor(selectedLabel, selectedColor);
        // });
        // Apply color button click event

        // // Function to update the color of a specific label in the chart
        // function updateLabelColor(selectedLabel, selectedColor) {
        //     var datasets = myPieChart.data.datasets;
        //     var labels = myPieChart.data.labels;

        //     // Loop through all labels
        //     for (var i = 0; i < labels.length; i++) {
        //         // If label matches selected label, update its color
        //         if (labels[i] === selectedLabel) {
        //             datasets[0].backgroundColor[i] = selectedColor;
        //         } else {
        //             // Otherwise, set color to gray
        //             datasets[0].backgroundColor[i] = '#d6d6d6';
        //         }
        //     }
        //     // Update the chart
        //     myPieChart.update();
        // }
        document.getElementById('submitColorBtn').addEventListener('click', function() {
            applySelectedColors();
        });

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
    </script>
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
