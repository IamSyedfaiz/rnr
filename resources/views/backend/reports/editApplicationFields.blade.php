@extends('backend.layouts.app')
@section('content')
    <!-- Sale & Revenue Start -->
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
                            @php
                                $applicationId = session('applicationId');
                                $statisticsMode = session('statisticsMode');
                                $dropdowns = session('dropdowns');
                                $fieldNames = session('fieldNames');
                                $fieldStatisticsNames = session('fieldStatisticsNames');
                                $dropdownFieldIds = session('dropdownFieldIds');
                                $filterOperators = session('filterOperators');
                                $filterValues = session('filterValues');
                                $advancedOperatorLogic = session('advancedOperatorLogic');
                                $fieldIds = session('fieldIds');
                                $fieldCounter = 0;

                            @endphp
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <form action="{{ route('search.report') }}" method="GET">
                                    <input type="hidden" name="report_id" value="{{ @$id }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button text-dark fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                aria-expanded="true" aria-controls="flush-collapseOne">
                                                Search
                                                <span class="fw-normal mx-1"> Applications</span>
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <button type="submit" class="btn btn-primary m-2 fw-bold">
                                                    SEARCH
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item mt-2">
                                        <h2 class="accordion-header" id="flush-headingtwo">
                                            <button class="accordion-button text-dark fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapsethree"
                                                aria-expanded="true" aria-controls="flush-collapsethree">
                                                Fields to Display
                                                <i class="bi bi-exclamation-circle mx-1"></i>
                                            </button>
                                        </h2>
                                        <div id="flush-collapsethree" class="accordion-collapse collapse show"
                                            aria-labelledby="flush-headingthree" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">

                                                <div class="row">
                                                    <div class="bg-light rounded p-4 col-6">
                                                        <table class="table" id="example" style="width:100%">
                                                            <thead>
                                                                <tr class="text-white" style="background-color: #009CFF;">
                                                                    <th scope="col">{{ $application->name }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($fields as $item)
                                                                    <tr>
                                                                        <td class="field-row"
                                                                            data-field-name="{{ $item->name }}"
                                                                            data-field-id="{{ $item->id }}">
                                                                            <a href="#"
                                                                                class="field-link">{{ $item->name }}</a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="bg-light rounded p-4 col-6" id="fieldData">
                                                        <input type="hidden" name="application_id" id="selectedApplication"
                                                            value="{{ $application->id }}">
                                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="1" id="statisticsModeCheckbox"
                                                                    name="statistics_mode"
                                                                    {{ session('statisticsMode') ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="statisticsModeCheckbox">
                                                                    Statistics Mode
                                                                </label>
                                                            </div>
                                                            <input type="hidden" name="application_id"
                                                                value="{{ $application->id }}">
                                                        </div>
                                                        <h5>Select Field</h5>
                                                        <div id="statisticsModeDiv"
                                                            style="{{ session('statisticsMode') ? '' : 'display: none;' }}">
                                                            @if (is_array($fieldNames) || is_object($fieldNames))
                                                                @foreach ($fieldNames as $key => $fieldName)
                                                                    @if (isset($fieldName))
                                                                        <div class="row added-field">
                                                                            <div class="mb-3 col-4">
                                                                                <select name="dropdowns[]"
                                                                                    class="form-control">
                                                                                    <option value="group_by">Group By
                                                                                    </option>
                                                                                    <option value="count_of">Count of
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <input type="text" class="form-control"
                                                                                    name="fieldNames[]"
                                                                                    value="{{ $fieldName }}">
                                                                                <!-- Add field ID here if needed -->
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ route('remove.from.session', $fieldName) }}"
                                                                                    class="btn btn-danger">
                                                                                    <i class="bi bi-x-circle"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div id="otherDiv"
                                                            style="{{ session('statisticsMode') ? 'display: none;' : '' }}">
                                                            @if (is_array($fieldStatisticsNames) || is_object($fieldStatisticsNames))
                                                                @foreach ($fieldStatisticsNames as $key => $fieldStatisticsName)
                                                                    @if (isset($fieldStatisticsName))
                                                                        <div class="row added-field p-2">
                                                                            <div class="col-6">
                                                                                <input type="text" class="form-control"
                                                                                    name="fieldStatisticsNames[]"
                                                                                    value="{{ $fieldStatisticsName }}">
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ route('remove.from.session.normal', $fieldStatisticsName) }}"
                                                                                    class="btn btn-danger">
                                                                                    <i class="bi bi-x-circle"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item mt-2">
                                        <h2 class="accordion-header" id="flush-headingfour">
                                            <button class="accordion-button text-dark fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapsefour"
                                                aria-expanded="true" aria-controls="flush-collapsefour">
                                                Filters
                                                <i class="bi bi-exclamation-circle mx-1"></i>
                                            </button>
                                        </h2>
                                        <div id="flush-collapsefour" class="accordion-collapse collapse show"
                                            aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div>
                                                    <div class="table-responsive mt-5">
                                                        <a class="btn btn-success mb-3" id="addRow"><i
                                                                class="bi bi-plus-circle"></i>Add</a>
                                                        <table
                                                            class="table  table-striped  text-start align-middle table-bordered table-hover mb-0"
                                                            id="dataTable">
                                                            <thead>
                                                                <tr class="text-white" style="background-color: #009CFF;">
                                                                    <th scope="col">ID</th>
                                                                    <th scope="col">Field To Evaluate</th>
                                                                    <th scope="col">OPERATOR</th>
                                                                    <th scope="col">VALUE</th>
                                                                    <th scope="col">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (empty($fieldIds))
                                                                    <!-- Render default row if $fieldIds is empty -->
                                                                    <tr class="data-row">
                                                                        <td>1</td>
                                                                        <td>
                                                                            <select class="form-control"
                                                                                name="field_id[]">
                                                                                <option value="">Select field
                                                                                </option>
                                                                                @foreach ($fields as $item)
                                                                                    <option value="{{ $item->id }}">
                                                                                        {{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"
                                                                                name="filter_operator[]">
                                                                                <option value="C">Contains</option>
                                                                                <option value="DNC">Does Not Contain
                                                                                </option>
                                                                                <option value="E">Equals</option>
                                                                                <option value="DNE">Does Not Equals
                                                                                </option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Value" name="filter_value[]">
                                                                        </td>
                                                                        <td>-</td>
                                                                    </tr>
                                                                @else
                                                                    <!-- Render rows based on $fieldIds -->
                                                                    @foreach ($fieldIds as $key => $fieldId)
                                                                        @if (isset($fieldId))
                                                                            <tr class="data-row">
                                                                                <td class="row-id">{{ $key + 1 }}
                                                                                </td>
                                                                                <td>
                                                                                    <select class="form-control"
                                                                                        name="field_id[]">
                                                                                        <option value="">Select field
                                                                                        </option>
                                                                                        @foreach ($fields as $item)
                                                                                            <option
                                                                                                value="{{ $item->id }}"
                                                                                                {{ $item->id == $fieldId ? 'selected' : '' }}>
                                                                                                {{ $item->name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <select class="form-control"
                                                                                        name="filter_operator[]">
                                                                                        <option value="C"
                                                                                            {{ isset($filterOperators[$key]) && $filterOperators[$key] == 'C' ? 'selected' : '' }}>
                                                                                            Contains</option>
                                                                                        <option value="DNC"
                                                                                            {{ isset($filterOperators[$key]) && $filterOperators[$key] == 'DNC' ? 'selected' : '' }}>
                                                                                            Does Not Contain</option>
                                                                                        <option value="E"
                                                                                            {{ isset($filterOperators[$key]) && $filterOperators[$key] == 'E' ? 'selected' : '' }}>
                                                                                            Equals</option>
                                                                                        <option value="DNE"
                                                                                            {{ isset($filterOperators[$key]) && $filterOperators[$key] == 'DNE' ? 'selected' : '' }}>
                                                                                            Does Not Equals</option>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        placeholder="Value"
                                                                                        name="filter_value[]"
                                                                                        value="{{ isset($filterValues[$key]) ? $filterValues[$key] : '' }}">
                                                                                </td>
                                                                                <td><button
                                                                                        class="btn btn-danger removeRow">Remove</button>
                                                                                </td>
                                                                            </tr>
                                                                        @else
                                                                            <tr class="data-row">
                                                                                <td>1</td>
                                                                                <td>
                                                                                    <select class="form-control"
                                                                                        name="field_id[]">
                                                                                        <option value="">Select field
                                                                                        </option>
                                                                                        @foreach ($fields as $item)
                                                                                            <option
                                                                                                value="{{ $item->id }}">
                                                                                                {{ $item->name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <select class="form-control"
                                                                                        name="filter_operator[]">
                                                                                        <option value="C">Contains
                                                                                        </option>
                                                                                        <option value="DNC">Does Not
                                                                                            Contain
                                                                                        </option>
                                                                                        <option value="E">Equals
                                                                                        </option>
                                                                                        <option value="DNE">Does Not
                                                                                            Equals
                                                                                        </option>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        placeholder="Value"
                                                                                        name="filter_value[]">
                                                                                </td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @endif

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="mb-3 col-6">
                                                        <label for="exampleInputEmail1" class="form-label">Advanced
                                                            Operator
                                                            Logic</label>
                                                        <input type="text" class="form-control"
                                                            name="advanced_operator_logic" id="advancedOperatorLogic"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            value="{{ $advancedOperatorLogic ? $advancedOperatorLogic : '' }}"
                                                            aria-describedby="advancedOperatorLogichelp">
                                                        @error('advancedOperatorLogic')
                                                            <label id="advancedOperatorLogic-error" class="error text-danger"
                                                                for="advancedOperatorLogic">
                                                                {{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#statisticsModeCheckbox').change(function() {
                if ($(this).is(':checked')) {
                    $('#statisticsModeDiv').show();
                    $('#otherDiv').hide();
                } else {
                    $('#statisticsModeDiv').hide();
                    $('#otherDiv').show();
                }
            });
            var fieldCounter = 0;
            var fieldInputCounter = 0;
            $('.field-link').click(function(e) {
                e.preventDefault();
                var fieldName = $(this).closest('.field-row').data('field-name');
                var fieldId = $(this).closest('.field-row').data('field-id');
                fieldCounter++;
                fieldInputCounter++;
                var fieldDetails = `<div class="row added-field" data-remove-id="${fieldCounter}" data-field-id="${fieldId}">
            <div class="mb-3 col-4">
                <select name="dropdowns[]" class="form-control">
                    <option value="group_by">Group By</option>
                     <option value="count_of">Count of</option> 
                </select>
            </div>
            <div class="col-6">
                <input type="text" class="form-control" name="fieldNames[]" value="${fieldName}">
            </div>
            <div class="col-2">
                <button class="removeFieldStatisticsMode btn btn-danger" data-remove-id="${fieldCounter}"><i class="bi bi-x-circle"></i> </button>
            </div>
            </div>`;

                var fieldInputDetails = `<div class="row added-field p-2" data-remove-id="${fieldInputCounter}" data-field-id="${fieldId}">
            <div class="col-6">
                <input type="text" class="form-control" name="fieldStatisticsNames[]" value="${fieldName}">
                <input type="hidden" name="fieldStatisticsIds[]" value="${fieldId}">
            </div>
            <div class="col-2">
                <button class="removeFieldOtherDiv btn btn-danger" data-remove-id="${fieldInputCounter}"><i class="bi bi-x-circle"></i> </button>
            </div>
            </div>`;
                if ($('#statisticsModeCheckbox').is(':checked')) {
                    $('#statisticsModeDiv').append(fieldDetails);
                } else {
                    $('#otherDiv').append(fieldInputDetails);
                }
            });
            $('#statisticsModeDiv').on('click', '.removeFieldStatisticsMode', function(e) {
                e.preventDefault();
                var removeId = $(this).data('remove-id');
                $('.added-field[data-remove-id="' + removeId + '"]').remove();

            });
            $('#otherDiv').on('click', '.removeFieldOtherDiv', function(e) {
                e.preventDefault();
                var removeId = $(this).data('remove-id');
                $('.added-field[data-remove-id="' + removeId + '"]').remove();
            });
        });


        $(document).ready(function() {
            $("#addRow").on("click", function() {
                var rowCount = $(".table-striped tbody tr").length + 1;
                var newRow = `<tr class="data-row">
                        <td class="row-id">${rowCount}</td>
                        <td>
                            <select class="form-control" name="field_id[]">
                                <option value="">Select field</option>
                                @foreach ($fields as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                          <select class="form-control" name="filter_operator[]">
                            <option value="C">Contains</option>
                                                            <option value="DNC">Does Not Contain</option>
                                                            <option value="E">Equals</option>
                                                            <option value="DNE">Does Not Equals</option>
                                                    </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Value" name="filter_value[]">
                        </td>
                        <td><button class="btn btn-danger removeRow">Remove</button></td>
                    </tr>`;
                $(".table-striped tbody").append(newRow);
            });

            $(".table-striped").on("click", ".removeRow", function() {
                $(this).closest("tr").remove();
                updateRowIds();
            });

            function updateRowIds() {
                $(".table-striped tbody tr").each(function(index) {
                    $(this).find('.row-id').text(index + 1);
                });
            }

        });
    </script>
@endsection
