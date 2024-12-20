@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Dashboard</h1>
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
                <div class="col-md-6 text-end">
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">
                        Add Dashboard</a>
                </div>
            </div>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                @foreach (@$dashboards as $dashboard)
                    <div class=" {{ $dashboard->layout == '100' ? 'col-sm-12 col-xl-12' : 'col-sm-12 col-xl-6' }}">
                        <div class="card">
                            <div class="card-body pb-0">
                                <h5 class="card-title">{{ $dashboard->name }}</h5>
                                @foreach ($dashboard->reports as $report)
                                    @if ($report->statistics_mode == 'Y')
                                        @if ($report->data_type == 'dataOnly')
                                            @php
                                                $countData = json_decode($report->data, true);
                                            @endphp
                                            <table class="table mb-3">
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
                                        @else
                                            <canvas id="chart-{{ $dashboard->id }}-{{ $report->id }}"
                                                data-report='{{ $report->data }}' data-type="{{ $report->selectChart }}"
                                                data-legend="{{ $report->legendPosition }}"
                                                data-palette="{{ $report->selectedPalette }}"
                                                data-borderWidth="{{ $report->borderWidth }}"
                                                data-labelColors="{{ $report->labelColor }}" class="chart-canvas">
                                            </canvas>
                                        @endif
                                    @else
                                        @php
                                            $allData = json_decode($report->data, true);
                                            $fieldStatisticsNames = json_decode($report->fieldStatisticsNames, true);
                                        @endphp
                                        <table class="display table" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    @foreach ($fieldStatisticsNames as $fieldName)
                                                        <th>{{ ucfirst($fieldName) }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @for ($i = 0; $i < count($allData[$fieldStatisticsNames[0]]); $i++)
                                                    <tr>
                                                        @foreach ($fieldStatisticsNames as $fieldName)
                                                            <td>
                                                                @if (isset($allData[$fieldName][$i]) && is_array($allData[$fieldName][$i]))
                                                                    @foreach ($allData[$fieldName][$i] as $value)
                                                                        {{ $value }}
                                                                    @endforeach
                                                                @else
                                                                    {{ $allData[$fieldName][$i] }}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    <script>
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

        function getCustomColors(reportData) {
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
        document.addEventListener('DOMContentLoaded', function() {
            var charts = document.querySelectorAll('.chart-canvas');
            charts.forEach(function(chart) {
                var ctx = chart.getContext('2d');
                var reportDataString = chart.getAttribute('data-report');
                var reportDataObject = JSON.parse(reportDataString);

                var labels = Object.keys(reportDataObject);
                var data = Object.values(reportDataObject);
                var type = chart.getAttribute('data-type') || 'bar';
                var legendPosition = chart.getAttribute('data-legend') || 'top';
                var selectedPalette = chart.getAttribute('data-palette') || 'default';
                var selectedBorderWidth = chart.getAttribute('data-borderWidth') || 'default';

                var labelColorsString = chart.getAttribute('data-labelColors');
                //  console.log(labelColors);
                //  console.log(selectedPalette);

                var colors;
                if (selectedPalette === 'random') {
                    colors = defaultPalette();
                } else if (selectedPalette === 'default') {
                    colors = dynamicColors();
                } else if (selectedPalette === 'custom') {
                    var labelColorsObject = JSON.parse(labelColorsString);
                    var colors = [];
                    //  console.log(labels);
                    for (var i = 0; i < labelColorsObject.length; i++) {
                        colors.push(labelColorsObject[i]);
                    }
                } else if (selectedPalette === 'bright') {
                    colors = brightPalette();
                } else {
                    colors = defaultPalette();
                }

                new Chart(ctx, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Report Data',
                            data: data,
                            backgroundColor: colors,
                            borderWidth: selectedBorderWidth,
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
                                position: legendPosition
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
