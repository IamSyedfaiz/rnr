@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.min.css">
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1></h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('get.view') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-short"></i>
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
                    <li class="breadcrumb-item active">Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button text-dark fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                            aria-expanded="true" aria-controls="flush-collapseOne">
                                            Applications
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <div class="row mt-5">
                                                <div class="col-2 bg-light rounded">
                                                    <label for="colorPicker">Select</label>

                                                    <select class="form-control col-3" name="data_type" id="chartType">
                                                        {{-- <option value=""><i class="bi bi-clipboard-data"></i> Chart And Data</option> --}}
                                                        <option value="dataOnly"><i class="bi bi-table"></i> Data Only
                                                        </option>
                                                        <option value="chartOnly"><i class="bi bi-bar-chart-line"></i>
                                                            Chart
                                                            Only</option>
                                                    </select>
                                                </div>
                                                <div class="col-2 bg-light rounded" id="chartTypeDiv">
                                                    <label for="colorPicker">Select Chart:</label>

                                                    <select id="chartTypeSelect" class="form-control" name="selectChart">
                                                        <option value="line">Single Line Chart</option>
                                                        <option value="bar">Single Bar Chart</option>
                                                        <option value="pie">Pie Chart</option>
                                                        <option value="doughnut">Doughnut Chart</option>
                                                    </select>
                                                </div>

                                                <!-- Add dropdown menu -->
                                                {{-- <div class="form-group col-2" id="colorPi">
                                                <label for="colorPicker">Select Color:</label>
                                                <select id="colorPalette" class="form-control">
                                                    <option value="random">Random</option>
                                                    <option value="default">Default Palette</option>
                                                    <option value="custom">Custom Palette</option>
                                                </select>
                                            </div> --}}
                                                <div class="form-group col-5" id="colorPi">
                                                    <label for="colorPicker">Select Palette:</label>
                                                    <ul class="list-unstyled form-control">
                                                        <li class="init">Select Palette</li>
                                                        <li data-value="random"><span><img
                                                                    src="{{ asset('public/backend/assets/img/palette.png') }}"
                                                                    width="200" style="margin-right: 10px;">Bold</span>
                                                        </li>
                                                        <li data-value="default">
                                                            <span><img
                                                                    src="{{ asset('public/backend/assets/img/palette1.png') }}"
                                                                    width="200" style="margin-right: 10px;">Medium
                                                            </span>
                                                        </li>
                                                        <li data-value="Bright">
                                                            <span><img
                                                                    src="{{ asset('public/backend/assets/img/palette.png') }}"
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
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
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
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary "
                                                                    id="submitColorBtn">Apply
                                                                    Color</button>
                                                            </div>
                                                        </div>
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
        </section>
    </main>
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

            // Add change event listener to chartType select
            $("#chartType").change(function() {
                var selectedValue = $(this).val();

                if (selectedValue === "chartOnly") {
                    $("#chartTypeDiv, #colorPi, #borderWi, #labelId, #submitColorBtn").show();
                    $("#myChart").show();
                    $(".list-unstyled").show();
                    $("#dataOnly").hide();

                } else if (selectedValue === "dataOnly") {
                    $("#chartTypeDiv, #colorPi, #borderWi, #labelId, #submitColorBtn").hide();
                    $("#myChart").hide();
                    $("#dataOnly").show();


                }
            });
        });
    </script>
    <script>
        var ctxPie = document.getElementById('myChart').getContext('2d');
        var countData = @json($countData);

        // function dynamicColors() {
        //     var colors = [];
        //     for (var i = 0; i < Object.keys(countData).length; i++) {
        //         colors.push('rgba(' + Math.floor(Math.random() * 256) + ', ' + Math.floor(Math.random() * 256) + ', ' +
        //             Math.floor(Math.random() * 256) + ', 0.7)');
        //     }
        //     return colors;
        // }

        function dynamicColors() {
            return [
                '#FF6347', // Tomato
                '#4682B4', // Steel Blue
                '#FFD700', // Gold
                '#ADFF2F', // Green Yellow
                '#4B0082', // Indigo
                '#00CED1', // Dark Turquoise
                '#FF4500', // Orange Red
                '#8A2BE2', // Blue Violet
                '#FF69B4', // Hot Pink
                '#00FF7F', // Spring Green
                '#DC143C', // Crimson
                '#1E90FF', // Dodger Blue
                '#FFDAB9', // Peach Puff
                '#9370DB', // Medium Purple
                '#7FFF00', // Chartreuse
            ];
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
        //         document.getElementById('colorPalette').addEventListener('change', function() {
        //             var selectedPalette = this.value;
        //             var colors;
        // alert(selectedPalette);
        //             // Set colors based on selected palette
        //             if (selectedPalette === 'random') {
        //                 colors = dynamicColors(); // Random colors
        //             } else if (selectedPalette === 'default') {
        //                 colors = defaultPalette(); // Default palette
        //             } else if (selectedPalette === 'custom') {
        //                 // colors = customPalette();
        //                 $('#customColorModal').modal('show');
        //                 return;
        //             }

        //             // Update chart colors
        //             updateColors(colors);
        //         });
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
            var colors;
            // Set colors based on selected palette
            if (selectedPalette === 'random') {
                colors = dynamicColors(); // Random colors
            } else if (selectedPalette === 'default') {
                colors = defaultPalette(); // Default palette
            } else if (selectedPalette === 'custom') {
                // colors = customPalette();
                $('#customColorModal').modal('show');
                return;
            }
            // Update chart colors
            updateColors(colors);
            allOptions.toggle();
        });
    </script>
@endsection
