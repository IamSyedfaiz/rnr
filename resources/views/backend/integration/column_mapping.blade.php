@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Select Applications</h1>
                </div>
                <div class="col-md-6 text-end"><a href="{{ route('back') }}" class="btn btn-secondary"><i
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
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
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
                                    <form method="post" action="{{ route('process.import') }}" class="row">
                                        @csrf
                                        <input type="hidden" name="path" value="{{ $path }}">
                                        <input type="hidden" name="application_id" value="{{ $id }}">

                                        <!-- Mapping Section -->
                                        <h2 class="mt-2">Column Mapping:</h2>
                                        @if (session('errors'))
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach (session('errors') as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="row mb-5">
                                            <div class="col-6 bg-light rounded p-3">
                                                <label for="exampleInputEmail1" class="form-label">Import Type</label>
                                                <select class="form-control col-3" name="import_type" id="import_type">
                                                    <option value="create_new">Create New Records</option>
                                                    <option value="update_existing">Update Existing Records</option>
                                                </select>
                                            </div>
                                            <div class="col-6 bg-light rounded p-3" style="display: none;"
                                                id="lookupFieldsLabel">
                                                <label for="lookupFields" class="form-label">Lookup Fields</label>
                                                <select class="form-control col-3" name="lookup_field" id="lookupFields">
                                                    @foreach ($databaseColumns as $databaseColumn)
                                                        <option value="{{ $databaseColumn->name }}">
                                                            {{ $databaseColumn->name }} ({{ $databaseColumn->type }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <hr>

                                        @if ($databaseColumns->count() > 0)
                                            <ul class="row ">
                                                @foreach ($dataFromFirstSheet[0] as $index => $heading)
                                                    <div class="col-3 bg-light rounded p-3 container shadow">
                                                        <label for="exampleInputEmail1" class="form-label">
                                                            <strong>{{ $heading }}</strong>
                                                        </label>
                                                        <select class="form-control col-3"
                                                            name="column_mappings[{{ $index }}]">
                                                            <option value="">Select Database Column</option>
                                                            @foreach ($databaseColumns as $databaseColumn)
                                                                <option value="{{ $databaseColumn->name }}"
                                                                    @if (old("column_mappings.$index") == $databaseColumn) selected @endif>
                                                                    {{ $databaseColumn->name }}
                                                                    ({{ $databaseColumn->type }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>No database columns available.</p>
                                        @endif

                                        <div class="row bg-light rounded p-3 mt-3   shadow ">
                                            <!-- Excel Data Section -->
                                            <h2 class="col-3">Excel Data:</h2>
                                            <table border="1" class=" mx-2 mb-2 table table-striped">
                                                <thead>
                                                    <tr class="text-white" style="background-color: #009CFF;">
                                                        @foreach ($dataFromFirstSheet[0] as $heading)
                                                            <th>{{ $heading }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i = 1; $i < count($dataFromFirstSheet); $i++)
                                                        <tr>
                                                            @foreach ($dataFromFirstSheet[$i] as $value)
                                                                <td>{{ $value ?? 'Null Value' }}</td>
                                                            @endforeach
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>

                                            <!-- Submit Button -->
                                            @if ($databaseColumns->count() > 0)
                                                <button type="submit" class="col-4 btn btn-primary m-2">Process
                                                    Mapping</button>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div><!-- End Left side columns -->
            </div>
        </section>
    </main><!-- End #main -->

    <script>
        document.getElementById('import_type').addEventListener('change', function() {
            var lookupFieldsLabel = document.getElementById('lookupFieldsLabel');

            if (this.value === 'update_existing') {
                lookupFieldsLabel.style.display = 'block';
            } else {
                lookupFieldsLabel.style.display = 'none';
            }
        });
    </script>
@endsection
