@extends('backend.layouts.app')
@section('content')

    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 ">
            <div class="col-sm-12">
                <div class="rounded h-100">
                    <div class="m-n2">
                        @php
                            $dataType = session('dataType');
                            $selectChart = session('selectChart');
                            $borderWidth = session('borderWidth');
                            $legendPosition = session('legendPosition');
                            $labelColor = session('labelColor');

                        @endphp
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
                                        <form action="{{ route('store.cert.report') }}" method="GET">
                                            @csrf
                                            <input type="hidden" name="application_id" value="{{ $applicationId }}">
                                            <input type="hidden" name="report_id" value="{{ @$reportId }}">
                                            <input type="hidden" name="dropdowns" value="{{ json_encode(@$dropdowns) }}">
                                            <input type="hidden" name="fieldIds" value="{{ json_encode(@$fieldIds) }}">
                                            <input type="hidden" name="fieldNames" value="{{ json_encode(@$fieldNames) }}">
                                            <input type="hidden" name="data" value="{{ json_encode($countData) }}">
                                            <input type="hidden" id="selectedPaletteInput" name="selectedPalette"
                                                value="">
                                            <input type="hidden" name="fieldStatisticsNames"
                                                value="{{ json_encode($fieldStatisticsNames) }}">
                                            <input type="hidden" name="statisticsMode" value="{{ $statisticsMode }}">
                                            <button type="submit" class="btn btn-outline-primary fw-bold">SAVE</button>
                                            @if ($reportId)
                                                <a href="{{ route('edit.chart', $reportId) }}"
                                                    class="btn btn-outline-primary fw-bold">MODIFY</a>
                                            @else
                                                <a href="{{ route('back.report.application', $applicationId) }}"
                                                    class="btn btn-outline-primary fw-bold">MODIFY</a>
                                            @endif
                                            {{-- <button type="button" class="btn btn-outline-primary fw-bold">MODIFY</button> --}}
                                            {{--   <button type="button" class="btn btn-outline-primary fw-bold">NEW
                                                REPORT</button>
                                            <button type="button" class="btn btn-outline-primary fw-bold">RELATED
                                                REPORTS</button> --}}
                                            <div class="row mt-5">
                                                <div class="col-2 bg-light rounded">
                                                    <label for="colorPicker">Select</label>

                                                    <select class="form-control col-3" name="data_type" id="chartType">
                                                        {{-- <option value=""><i class="bi bi-clipboard-data"></i> Chart And Data</option> --}}
                                                        <option value="dataOnly"
                                                            {{ $dataType == 'dataOnly' ? 'selected' : '' }}>
                                                            <i class="bi bi-table"></i> Data Only
                                                        </option>
                                                        <option value="chartOnly"
                                                            {{ $dataType == 'chartOnly' ? 'selected' : '' }}>
                                                            <i class="bi bi-bar-chart-line"></i>
                                                            Chart
                                                            Only
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-2 bg-light rounded" id="chartTypeDiv">
                                                    <label for="colorPicker">Select Chart:</label>

                                                    <select id="chartTypeSelect" class="form-control" name="selectChart"
                                                        onchange="updateChartType(this.value)">

                                                        <option value="line"
                                                            {{ $selectChart == 'line' ? 'selected' : '' }}>Single Line
                                                            Chart</option>
                                                        <option value="bar"
                                                            {{ $selectChart == 'bar' ? 'selected' : '' }}>Vertical Bar
                                                            Chart</option>
                                                        <option value="bar-horizontal"
                                                            {{ $selectChart == 'bar-horizontal' ? 'selected' : '' }}>
                                                            Horizontal Bar Chart</option>
                                                        <option value="pie"
                                                            {{ $selectChart == 'pie' ? 'selected' : '' }}>Pie Chart
                                                        </option>
                                                        <option value="doughnut"
                                                            {{ $selectChart == 'doughnut' ? 'selected' : '' }}>Doughnut
                                                            Chart</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-2" id="legendPositionShow">
                                                    <label for="legendPosition">Legend Position:</label>
                                                    <select class="form-control" name="legendPosition" id="legendPosition">
                                                        <option value="top"
                                                            {{ $legendPosition == 'top' ? 'selected' : '' }}>Position: top
                                                        </option>
                                                        <option value="right"
                                                            {{ $legendPosition == 'right' ? 'selected' : '' }}>Position:
                                                            right</option>
                                                        <option value="bottom"
                                                            {{ $legendPosition == 'bottom' ? 'selected' : '' }}>Position:
                                                            bottom</option>
                                                        <option value="left"
                                                            {{ $legendPosition == 'left' ? 'selected' : '' }}>Position:
                                                            left</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-5" id="colorPi">
                                                    <label for="colorPicker">Select Palette:</label>
                                                    <ul class="list-unstyled form-control" name="color">
                                                        <li class="init">Select Palette</li>
                                                        <li data-value="random"><span><img
                                                                    src="{{ asset('public/backend/dashmin/img/palette.png') }}"
                                                                    width="200" style="margin-right: 10px;">Bold</span>
                                                        </li>
                                                        <li data-value="default">
                                                            <span><img
                                                                    src="{{ asset('public/backend/dashmin/img/palette1.png') }}"
                                                                    width="200" style="margin-right: 10px;">Medium
                                                            </span>
                                                        </li>
                                                        <li data-value="bright">
                                                            <span><img
                                                                    src="{{ asset('public/backend/dashmin/img/palette.png') }}"
                                                                    width="200" style="margin-right: 10px;">Bright
                                                            </span>
                                                        </li>
                                                        <li data-value="custom"><span>Custom Palette</span></li>
                                                    </ul>
                                                </div>



                                                <!-- Canvas for the chart -->
                                                <!-- Border width dropdown menu -->
                                                <div class="form-group col-2" id="borderWi">
                                                    <label for="borderWidth">Select Border Width:</label>
                                                    <select id="borderWidth" class="form-control" name="borderWidth">
                                                        <option value="10">Standard</option>
                                                        <option value="20">Explode Smallest </option>
                                                        <option value="30">Explode Largest</option>
                                                    </select>
                                                </div>
                                                <canvas id="myChart"></canvas>
                                                <div class="mt-3">
                                                    <table class="table" id="dataOnly">
                                                        <thead>
                                                            <tr>
                                                                <th>Application Name</th>
                                                                <th>Count of Application Name</th>
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
                                                </div>
                                                <!-- Custom Color Picker Modal -->
                                                <div class="modal fade" id="customColorModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="customColorModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="customColorModalLabel">Custom
                                                                    Color Picker</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container mt-3" id="labelId">
                                                                    @foreach ($countData as $label => $value)
                                                                        <div class="form-row">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="colorInput_{{ $label }}">{{ $label }}
                                                                                    Color:</label>
                                                                                <input type="color" name="labelColor[]"
                                                                                    class="form-control label-color"
                                                                                    id="colorInput_{{ $label }}"
                                                                                    data-label="{{ $label }}"
                                                                                    value="#d6d6d6">
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal" id="CloseBtn">Close</button>
                                                                <button type="button" class="btn btn-primary "
                                                                    id="submitColorBtn">Apply
                                                                    Color</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Bootstrap-styled cart -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container for the chart -->


@endsection
@push('style')
    <style>
        ul.list-unstyled li:not(.init) {
            float: left;
            padding: 10px;
            width: 100%;
            display: none;
            background: #8e8e8e;
            color: #fff;
            margin-right: 10px;
        }

        ul li:not(.init):hover,
        ul li.selected:not(.init) {
            background: rgb(188, 193, 193);
            color: #000;
        }

        li.init {
            cursor: pointer;
        }
    </style>
@endpush
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".list-unstyled").hide();
            $('#closeCustomColorModal').click(function() {
                $('#customColorModal').modal('hide');
            });
            // Hide all elements by default
            $("#chartTypeDiv, #colorPi, #borderWi, #labelId, #submitColorBtn").hide();
            $("#myChart").hide();
            $("#legendPositionShow").hide();

            @if (!is_null($dataType))
                if ("{{ $dataType }}" === "chartOnly") {
                    $("#chartTypeDiv, #colorPi, #borderWi, #labelId, #submitColorBtn").show();
                    $("#myChart").show();
                    $("#legendPositionShow").show();
                    $(".list-unstyled").show();
                    $("#dataOnly").hide();
                } else if ("{{ $dataType }}" === "dataOnly") {
                    $("#chartTypeDiv, #colorPi, #borderWi, #labelId, #submitColorBtn").hide();
                    $("#myChart").hide();
                    $("#legendPositionShow").hide();
                    $("#dataOnly").show();
                }
            @endif

            // Add change event listener to chartType select
            $("#chartType").change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === "chartOnly") {
                    $("#chartTypeDiv, #colorPi, #borderWi, #labelId, #submitColorBtn").show();
                    $("#myChart").show();
                    $("#legendPositionShow").show();
                    $(".list-unstyled").show();
                    $("#dataOnly").hide();

                } else if (selectedValue === "dataOnly") {
                    $("#chartTypeDiv, #colorPi, #borderWi, #labelId, #submitColorBtn").hide();
                    $("#myChart").hide();
                    $("#legendPositionShow").hide();
                    $("#dataOnly").show();
                }
            });
        });
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
            // console.log(myPieChart);
            const options = {
                responsive: true,
                borderWidth: 3,
                onClick: (evt, activeElements, chart) => {
                    if (activeElements.length > 0) {
                        var datasetIndex = activeElements[0].datasetIndex;
                        var dataIndex = activeElements[0].index;
                        var labelId = chart.data.labels[dataIndex];
                        var datasetId = chart.data.datasets[datasetIndex].label;

                        function filterDataById(data, labelId) {
                            const entry = Object.entries(data).find(([key, value]) => key === labelId);
                            return entry ? {
                                [entry[0]]: entry[1]
                            } : null;
                        }


                        var filteredData = filterDataById(countData, labelId);
                        if (filteredData !== null) {
                            // console.log(filteredData);
                            var form = document.createElement('form');
                            form.method = 'GET';
                            form.action =
                                '{{ route('route.to.handle') }}';
                            var filteredDataInput = document.createElement('input');
                            filteredDataInput.type = 'hidden';
                            filteredDataInput.name = 'filteredData';
                            filteredDataInput.value = JSON.stringify(
                                filteredData);
                            form.appendChild(filteredDataInput);
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            console.log('No data found for labelId:', labelId);
                        }
                    }
                }
            };

            if (chartType === 'bar-horizontal') {
                options.indexAxis = 'y';
            }
            myPieChart = new Chart(ctxPie, {
                type: chartType === 'bar-horizontal' ? 'bar' : chartType,
                data: {
                    labels: Object.keys(countData),
                    datasets: [{
                        data: Object.values(countData),
                        backgroundColor: defaultPalette()
                    }]
                },
                options: options

            });
        }

        function updateChartType(chartType) {
            // Call createOrUpdateChart function with the selected chart type
            createOrUpdateChart(chartType);
        }

        // Call createOrUpdateChart function with the selected chart type on page load
        $(document).ready(function() {
            // Retrieve the selected chart type from the session
            var selectedChartType = "{{ $selectChart }}";
            if (selectedChartType) {
                // Call createOrUpdateChart function with the selected chart type
                createOrUpdateChart(selectedChartType);
            }
        });

        // Example usage:
        createOrUpdateChart('line');
        var positionShow = "{{ $legendPosition }}";
        if (positionShow && myPieChart) {
            myPieChart.options.plugins.legend.position = positionShow;
            console.log(myPieChart.options.plugins.legend.position)
            myPieChart.update();
        }
        document.getElementById('legendPosition').addEventListener('change', function() {
            var selectedPosition = this.value;
            if (myPieChart) {
                myPieChart.options.plugins.legend.position = selectedPosition;
                myPieChart.update();
            }
        });




        // Dropdown change event listener
        document.getElementById('chartTypeSelect').addEventListener('change', function() {
            var selectedType = this.value;
            createOrUpdateChart(selectedType);
        });
        document.getElementById('borderWidth').addEventListener('change', function() {
            var selectedWidth = parseInt(this.value);
            myPieChart.options.borderWidth = selectedWidth;
            myPieChart.update();
        });
        document.getElementById('submitColorBtn').addEventListener('click', function() {
            applySelectedColors();
            $('#customColorModal').modal('hide');
        });
        document.getElementById('CloseBtn').addEventListener('click', function() {
            $('#customColorModal').modal('hide');
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


        $("ul").on("click", ".init", function() {
            $(this).closest("ul").children('li:not(.init)').toggle();
        });

        var allOptions = $("ul").children('li:not(.init)');
        $("ul").on("click", "li:not(.init)", function() {
            allOptions.removeClass('selected');
            $(this).addClass('selected');
            var selected = $(this).find('span').html()
            $("ul").children('.init').html(selected);


            var selectedPalette = $(this).attr('data-value');
            $('#selectedPaletteInput').val(selectedPalette);
            var colors;
            // Set colors based on selected palette
            if (selectedPalette === 'random') {
                colors = defaultPalette(); // Default palette
            } else if (selectedPalette === 'default') {
                colors = dynamicColors(); // Random colors
            } else if (selectedPalette === 'custom') {
                // colors = customPalette();
                $('#customColorModal').modal('show');
                return;
            } else if (selectedPalette === 'bright') {
                colors = brightPalette();
            }
            // Update chart colors
            updateColors(colors);
            allOptions.toggle();
        });
    </script>
@endsection
