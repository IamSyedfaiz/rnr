@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Report</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('get.view') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-short"></i> Back</a>
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
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <table id="example" class="display table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        @foreach ($filteredData as $key => $value)
                                            <td>{{ $key }}</td>
                                            <td>{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
