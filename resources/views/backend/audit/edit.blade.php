@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Audit Details</h6>

            </div>
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Audit </h6>
                <form action="{{ route('audits.update', $audit->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" aria-describedby="namehelp" value="{{ $audit->name }}">
                        @error('name')
                            <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1"
                            class="form-label @error('compliance') is-invalid @enderror">Compliance</label>
                        <div class="d-flex justify-content-between">
                            <input type="file" class="form-control" id="name" name="compliance"
                                aria-describedby="namehelp">
                            <a href="{{ $audit->compliance }}" target="_blank">Compliance
                                Review</a>

                        </div>
                        @error('compliance')
                            <label id="compliance-error" class="error text-danger" for="compliance">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label @error('report_date') is-invalid @enderror">Report
                            Date</label>
                        <input type="date" class="form-control" id="name" name="report_date"
                            aria-describedby="namehelp" value="{{ $audit->report_date }}">
                        @error('report_date')
                            <label id="report_date-error" class="error text-danger"
                                for="report_date">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label @error('expiry_date') is-invalid @enderror">Expiry
                            Date</label>
                        <input type="date" class="form-control" id="name" name="expiry_date"
                            aria-describedby="namehelp" value="{{ $audit->expiry_date }}">
                        @error('expiry_date')
                            <label id="expiry_date-error" class="error text-danger"
                                for="expiry_date">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Share With</label>
                        <input type="email" class="form-control @error('sharewith') is-invalid @enderror" id="email"
                            name="sharewith" aria-describedby="namehelp" value="{{ $audit->sharewith }}">
                        @error('sharewith')
                            <label id="sharewith-error" class="error text-danger" for="sharewith">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->
@endsection
