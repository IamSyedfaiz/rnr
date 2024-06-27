@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
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
                            <table class="table" id="example" style="width:100%">
                                <thead>
                                    <tr class="text-white" style="background-color: #009CFF;">
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('report.report.application', $item->id) }}">
                                                    {{ $item->name }}</a>
                                            </td>
                                            <td class="text-success">
                                                @if ($item->status == 1)
                                                    Active
                                                @else
                                                    In-Active
                                                @endif
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
    <!-- Recent Sales End -->
@endsection
