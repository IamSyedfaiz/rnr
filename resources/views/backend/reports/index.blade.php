@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-4 text-dark fw-bold">Reports</h6>
                        <h6 class="mb-4 text-primary fw-bold">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Add new
                            </button>
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="display" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Application</th>
                                    <th>Type</th>
                                    <th>Last Updated</th>
                                    <th>Updated By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td class="text-primary">{{ $report->name }}</td>
                                        <td>{{ $report->application->name }}</td>
                                        <td>{{ $report->permissions == 'P' ? 'Personal' : ($report->permissions == 'G' ? 'Global' : '--') }}
                                        </td>
                                        <td>{{ $report->created_at }}</td>
                                        <td>{{ $report->user->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('edit.chart', $report->id) }}"><i
                                                    class="bi bi-pencil text-primary"></i></a>
                                            {{-- <a href="{{ route('view.chart', $report->id) }}"><i
                                                    class="bi bi-eye-fill text-primary"></i></a> --}}
                                            <a href="{{ route('delete.report', $report->id) }}"><i
                                                    class="bi bi-trash-fill text-primary"></i></a>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark" id="exampleModalLabel">
                        Add New Report
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="bg-light rounded h-100 p-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-4 text-dark fw-bold">
                                    Available Applications
                                </h6>
                            </div>
                            <div class="table-responsive">
                                <form id="reportForm" action="{{ route('report.report.application') }}" method="GET">
                                    <!-- Other form fields if needed -->
                                    <input type="hidden" name="selectedApplication" id="selectedApplication">

                                    <table id="example2" class="display" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applications as $application)
                                                <tr>
                                                    <td class="text-primary">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="flexRadioDefault"
                                                                id="flexRadioDefault{{ $application->id }}"
                                                                data-application-id="{{ $application->id }}" />
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                {{ $application->name }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary">ok</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal end-->
    <script>
        document.querySelector('#exampleModal .btn-primary').addEventListener('click', function() {
            // Get the selected application ID
            var selectedApplication = document.querySelector('input[name="flexRadioDefault"]:checked');

            // Check if an application is selected
            if (selectedApplication) {
                var applicationId = selectedApplication.dataset.applicationId;

                // Set the value of the hidden input field
                document.getElementById('selectedApplication').value = applicationId;

                // Construct the URL with the selected application ID
                var formAction = "{{ route('report.report.application') }}";
                formAction += "?selectedApplication=" + applicationId;

                // Set the form action and submit the form
                document.getElementById('reportForm').action = formAction;
                document.getElementById('reportForm').submit();
            } else {
                // Handle the case when no application is selected
                alert('Please select an application.');
            }
        });
    </script>
@endsection
