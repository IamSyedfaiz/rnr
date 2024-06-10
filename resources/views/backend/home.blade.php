 @extends('backend.layouts.app')
 @section('content')
     <!-- Sales Chart Start -->
     <div class="container-fluid pt-4 px-4">
         <div class="row g-4">
             @foreach (@$dashboards as $dashboard)
                 <div class=" {{ $dashboard->layout == '100' ? 'col-sm-12 col-xl-12' : 'col-sm-12 col-xl-6' }}">
                     <div class="bg-light text-center rounded p-4">
                         <div class="d-flex align-items-center justify-content-between mb-4">
                             <h6 class="mb-0">{{ $dashboard->name }}</h6>
                             <a href="">Show All</a>
                         </div>
                         @foreach ($dashboard->reports as $report)
                             <canvas id="chart-{{ $dashboard->id }}-{{ $report->id }}" data-report='{{ $report->data }}'
                                 data-type="{{ $report->selectChart }}" data-legend="{{ $report->legendPosition }}"
                                 data-palette="{{ $report->selectedPalette }}" data-labelColors="{{ $report->labelColor }}"
                                 class="chart-canvas">
                             </canvas>
                         @endforeach
                     </div>
                 </div>
             @endforeach
         </div>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

                 var labelColorsString = chart.getAttribute('data-labelColors');
                 //  console.log(labelColors);
                 //  console.log(selectedPalette);

                 var colors;
                 if (selectedPalette === 'random') {
                     colors = defaultPalette();
                 } else if (selectedPalette === 'default') {
                     colors = customPalette();
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
                             borderColor: colors,
                             borderWidth: 1
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
     <!-- Sales Chart End -->
 @endsection
