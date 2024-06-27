@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <!-- Sale & Revenue Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Reports</h1>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-window-stack"></i> Add New
                    </button>
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
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <table class="table datatable">
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
                                    @foreach (@$reports as $report)
                                        <tr>
                                            <td class="text-primary">{{ @$report->name }}</td>
                                            <td>{{ @$report->application->name }}</td>
                                            <td>{{ @$report->permissions == 'P' ? 'Personal' : (@$report->permissions == 'G' ? 'Global' : '--') }}
                                            </td>
                                            <td>{{ @$report->created_at }}</td>
                                            <td>{{ @$report->user->name ?? '-' }}</td>
                                            <td>

                                                <a href="{{ route('edit.chart', @$report->id) }}"
                                                    class="btn btn-primary btn-sm mx-1"><i class="bi bi-pencil"></i>
                                                    Edit</a>
                                                {{-- <a href="{{ route('view.chart', @$report->id) }}"><i
                                                    class="bi bi-eye-fill text-primary"></i></a> --}}
                                                <a href="{{ route('delete.report', @$report->id) }}"
                                                    class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>
                                                    Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
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
