@extends('backend.layouts.app')
@section('content')
    <!-- Sale & Revenue Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1></h1>
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
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button text-dark fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                            aria-expanded="true" aria-controls="flush-collapseOne">
                                            Applications
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table id="example" class="display table" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    @foreach (@$fieldStatisticsNames as $fieldName)
                                                                        <th>{{ ucfirst($fieldName) }}</th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @for ($i = 0; $i < count($allData[$fieldStatisticsNames[0]]); $i++)
                                                                    <tr>
                                                                        @foreach ($fieldStatisticsNames as $fieldName)
                                                                            <td>
                                                                                {{-- Check if the key exists and is an array --}}
                                                                                @if (isset($allData[$fieldName][$i]) && is_array($allData[$fieldName][$i]))
                                                                                    {{-- Display all values associated with the current field name --}}
                                                                                    @foreach ($allData[$fieldName][$i] as $value)
                                                                                        {{ $value }}
                                                                                    @endforeach
                                                                                @else
                                                                                    {{-- Display the value directly if it's not an array --}}
                                                                                    {{ $allData[$fieldName][$i] }}
                                                                                @endif
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endfor
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Sale & Revenue End -->
@endsection
