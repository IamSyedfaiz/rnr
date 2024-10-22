@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <style>
        .workflow-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-wrap: wrap;
            /* Allows steps to wrap on smaller screens */
        }

        .workflow-step {
            display: flex;
            align-items: center;
            margin-right: 10px;
            position: relative;
        }

        .step-content {
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            /* You can change the background color */
            padding: 10px;
            border-radius: 50px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .step-circle {
            display: inline-block;
            background-color: #007bff;
            /* Step circle color */
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            margin-right: 10px;
            font-weight: bold;
        }

        .step-text {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .arrow {
            margin-left: 10px;
            font-size: 24px;
            color: #007bff;
            /* Arrow color */
            position: relative;
        }

        /* Arrow line between steps */
        .workflow-step:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            height: 2px;
            width: 50px;
            background-color: #007bff;
            /* Line color */
            z-index: 0;
        }

        .workflow-step:last-child:after {
            content: none;
            /* No line after the last step */
        }

        /* For responsiveness on smaller screens */
        @media (max-width: 768px) {
            .workflow-container {
                flex-direction: column;
                /* Stack steps vertically on smaller screens */
                align-items: flex-start;
            }

            .workflow-step {
                margin-bottom: 20px;
            }

            .workflow-step:after {
                width: 0;
                /* Remove the connecting line on mobile */
            }

            .arrow {
                display: none;
                /* Hide arrows on mobile */
            }
        }
    </style>
    <main id="main" class="main">

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-start rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    {{-- <h6 class="mb-0">Application Create</h6> --}}

                </div>
                <div class="bg-light rounded h-100 p-4">
                    {{-- <h6 class="mb-4">work flow path</h6>
                    @foreach ($myLogs as $log)
                        {{ $log->name }}---------->>
                    @endforeach --}}

                    <h6 class="mb-4">Workflow Path</h6>

                    <div class="workflow-container">
                        @foreach ($myLogs as $index => $log)
                            @if ($log->name === 'stop')
                                <div style="margin-top: 20px;"></div>
                                <div class="workflow-step"><br> </div>
                            @endif
                            <div class="workflow-step">
                                <div class="step-content my-3">
                                    <span class="step-circle">{{ $index + 1 }}</span>
                                    <span class="step-text">{{ $log->name }}</span>
                                </div>
                                @if (!$loop->last)
                                    <div class="arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
