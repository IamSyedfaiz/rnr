@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Application Indexing List</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('userapplication.list', $id) }}" class="btn btn-secondary"><i
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
                            <form action="{{ route('userapplication.index.save') }}" method="post">
                                @csrf
                                {{-- {{ dd($indexing) }} --}}
                                <div class="table-responsive">
                                    @if ($indexing != 'notfound')
                                        @foreach ($fields as $item)
                                            @php
                                                $index = json_decode($indexing->order);
                                                // $i++;
                                                // dd($index);
                                            @endphp
                                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                                <div class="d-flex">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th scope="col">{{ $item->name }}</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <input type="text" name="order[]" value="{{ $index[$i] }}"
                                                            class="form-control" required>
                                                        <input type="hidden" name="update" value="{{ $i }}">
                                                    </tbody>
                                                </div>
                                            </table>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        @foreach ($fields as $item)
                                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                                <div class="d-flex">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th scope="col">{{ $item->name }}</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <input type="text" name="order[]" class="form-control" required>

                                                    </tbody>

                                                </div>
                                            </table>
                                        @endforeach
                                    @endif
                                </div>
                                <input type="hidden" name="userid" value="{{ auth()->id() }}">
                                <input type="hidden" name="application_id" value="{{ $id }}">
                                <input type="submit" value="Submit" class="btn btn-primary pull-right">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Recent Sales End -->
@endsection
