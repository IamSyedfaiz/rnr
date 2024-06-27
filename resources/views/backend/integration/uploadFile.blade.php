@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>CSV Import </h1>
                </div>
                <div class="col-md-6 text-end"><a href="{{ route('data.feed') }}" class="btn btn-secondary"><i
                            class="bi bi-arrow-left-short"></i> Back</a></div>
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
                                    <form class="form-horizontal" method="POST" action="{{ route('import.upload') }}"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="application_id" value="{{ $id }}">
                                        <div class="form-group{{ $errors->has('excel_file') ? ' has-error' : '' }}">
                                            <label for="excel_file" class="col-md-4 control-label">CSV file to
                                                import</label>

                                            <div class="col-md-6">
                                                <input id="excel_file" type="file" class="form-control" name="excel_file"
                                                    required>

                                                @if ($errors->has('excel_file'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('excel_file') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="header" checked> File contains header
                                                        row?
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-8 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary">
                                                    Parse CSV
                                                </button>
                                            </div>
                                        </div>
                                        {{-- <input type="hidden" name="application_id" value="{{ $id }}"> --}}
                                        <input type="hidden" name="userid" value="{{ auth()->id() }}">

                                    </form>
                                </div>
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div><!-- End Left side columns -->
            </div>
        </section>
    </main><!-- End #main -->
@endsection
