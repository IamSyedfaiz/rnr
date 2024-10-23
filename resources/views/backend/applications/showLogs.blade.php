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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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
                        {{-- @foreach ($myLogs as $index => $log)
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
                        @endforeach --}}
                        {{-- @foreach ($myLogs as $uniqueNumber => $logs)
                            <div class="workflow-step">
                                @foreach ($logs as $index => $log)
                                    <div class="step-content my-3">
                                        <span class="step-circle">{{ $index + 1 }}</span>
                                        <span class="step-text">{{ $log->name }}</span>
                                    </div>


                                    @if (!$loop->last)
                                        <div class="arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach --}}

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($myLogs as $uniqueNumber => $logs)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $logs->first()->user->name ?? 'Unknown User' }}</td> <!-- Show user name -->
                                        <td>
                                            <!-- Button to trigger modal -->
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#logModal{{ $uniqueNumber }}">
                                                Show
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="logModal{{ $uniqueNumber }}" tabindex="-1" role="dialog"
                                        aria-labelledby="logModalLabel{{ $uniqueNumber }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="logModalLabel{{ $uniqueNumber }}">
                                                        {{ $logs->first()->user->name ?? 'Unknown User' }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Display log details inside the modal -->
                                                    @foreach ($logs as $index => $log)
                                                        <div class="step-content my-3">
                                                            <span class="step-circle">{{ $index + 1 }}</span>
                                                            <span class="step-text">{{ $log->name }}</span>
                                                            @if (!$loop->last)
                                                                <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                                                                <!-- Add arrow after each name -->
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
