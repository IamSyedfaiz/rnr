@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    {{-- <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4 shadow">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Reports Listing</h6>
                <a href="{{ route('get.report.application') }}"> <button class="btn btn-primary">Add
                        New</button></a>
            </div>
            <div class="table-responsive">
                <table class="table  table-striped  text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-white" style="background-color: #009CFF;">
                            <th scope="col">Name</th>
                            <th scope="col">Solution</th>
                            <th scope="col">Application</th>
                            <th scope="col">Type</th>
                            <th scope="col">Last Updated</th>
                            <th scope="col">Updated By</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-4 text-dark fw-bold">Reports</h6>
                        <h6 class="mb-4 text-primary fw-bold">
                            <a href="{{ route('get.report.application') }}"> <button class="btn btn-primary">Add
                                    New</button></a>
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
                                <tr>
                                    <td class="text-primary">
                                        AWF Enrollment</td>
                                    <td>Admin</td>
                                    <td>Global</td>
                                    <td>8/29/2017 10:11 PM</td>
                                    <td>Administrator, System</td>
                                    <td>
                                        <i class="bi bi-pencil text-primary"></i>
                                        <i class="bi bi-trash-fill text-primary"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">
                                        AWF Enrollment</td>
                                    <td>Admin</td>
                                    <td>Global</td>
                                    <td>8/29/2017 10:11 PM</td>
                                    <td>Administrator, System</td>
                                    <td>
                                        <i class="bi bi-pencil text-primary"></i>
                                        <i class="bi bi-trash-fill text-primary"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->
    <!-- Recent Sales End -->
@endsection
