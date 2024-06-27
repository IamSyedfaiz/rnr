@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Notification</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('notifications.index') }}" class="btn btn-secondary"><i
                            class="bi bi-arrow-left-short"></i> Back</a>
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
                    <li class="breadcrumb-item active">Notification</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <div class="tab-content" id="pills-tabContent">
                                <form action="{{ route('add.notification') }}" class="form-horizontal" method="GET">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Type</label>
                                        <select name="type" class="form-control">
                                            {{-- <option value="SRD">Scheduled Report Distribution</option> --}}
                                            <option value="SN">Subscription Notification</option>
                                            {{-- <option value="ODNT">On Demand Notification Temlate</option> --}}
                                        </select>
                                        @error('type')
                                            <label id="name-error" class="error text-danger" for="name">
                                                {{ $message }}</label>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Application</label>
                                        <select name="application_id" class="form-control">
                                            <option value="">Select Application</option>
                                            @foreach ($applications as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('application_id')
                                            <label id="name-error" class="error text-danger" for="application_id">
                                                {{ $message }}</label>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Continue</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
