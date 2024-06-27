@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Select Applications</h1>
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
                    <li class="breadcrumb-item active">
                        Integration</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Reports -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body pt-3">
                                    <table class="table datatable">
                                        <thead>
                                            <tr class="text-white" style="background-color: #009CFF;">
                                                <th scope="col">Name</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applications as $item)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('show.form', $item->id) }}">
                                                            {{ $item->name }}</a>
                                                    </td>
                                                    <td class="text-success">
                                                        @if ($item->status == 1)
                                                            Active
                                                        @else
                                                            In-Active
                                                        @endif
                                                    </td>
                                                    <td><a href="{{ route('show.form', $item->id) }}"
                                                            class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i>
                                                            Select</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div><!-- End Left side columns -->
            </div>
        </section>
    </main><!-- End #main -->
    <!-- Recent Sales End -->
@endsection
