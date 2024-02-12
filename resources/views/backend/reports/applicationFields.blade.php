@extends('backend.layouts.app')
@section('content')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <div class="rounded h-100">
                    <div class="m-n2">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true"
                                        aria-controls="flush-collapseOne">
                                        Search
                                        <span class="fw-normal mx-1"> Applications</span>
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <button type="button" class="btn btn-primary m-2 fw-bold">
                                            SEARCH
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mt-2">
                                <h2 class="accordion-header" id="flush-headingtwo">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapsethree" aria-expanded="true"
                                        aria-controls="flush-collapsethree">
                                        Fields to Display
                                        <i class="bi bi-exclamation-circle mx-1"></i>
                                    </button>
                                </h2>
                                <div id="flush-collapsethree" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingthree" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">

                                        <div class="row">
                                            <div class="bg-light text-center rounded p-4 col-6">
                                                <table class="table" id="example" style="width:100%">
                                                    <thead>
                                                        <tr class="text-white" style="background-color: #009CFF;">
                                                            <th scope="col">{{ $application->name }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($fields as $item)
                                                            <tr>
                                                                <td class="field-row" data-field-name="{{ $item->name }}"
                                                                    data-field-id="{{ $item->id }}">
                                                                    <a href="#"
                                                                        class="field-link">{{ $item->name }}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <form action="{{ route('search.report') }}" method="GET"
                                                class="bg-light rounded p-4 col-6" id="fieldData">
                                                <input type="hidden" name="application_id" value="{{ $application->id }}">
                                                <input type="hidden" name="report_id" value="{{ @$report->id }}">


                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="statisticsModeCheckbox" name="statistics_mode">
                                                        <label class="form-check-label" for="statisticsModeCheckbox">
                                                            Statistics Mode
                                                        </label>
                                                    </div>
                                                    {{-- <h6 class="mb-0">Selected</h6>
                                                    <h6 class="mb-0">{{ $application->name }}</h6> --}}
                                                    <button class="btn btn-danger mx-2" type="submit">Search</button>
                                                    <input type="hidden" name="application_id"
                                                        value="{{ $application->id }}">
                                                </div>
                                                <h5>Select Field</h5>
                                                <div id="statisticsModeDiv" style="display: none;"></div>
                                                <div id="otherDiv"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mt-2">
                                <h2 class="accordion-header" id="flush-headingfour">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="true"
                                        aria-controls="flush-collapsefour">
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
                                                        <tr class="data-row">
                                                            <td>1</td>
                                                            <td>
                                                                <select class="form-control" name="field_id[]">
                                                                    <option value="">Select field</option>
                                                                    @foreach (@$fields as $item)
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
                                                                    <option value="CH">Changed</option>
                                                                    <option value="CT">Changed To</option>
                                                                    <option value="CF">Changed From</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Value" name="filter_value[]">
                                                            </td>
                                                            <td>-</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="exampleInputEmail1" class="form-label">Advanced Operator
                                                    Logic</label>
                                                <input type="text" class="form-control" name="advanced_operator_logic"
                                                    id="advancedOperatorLogic"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->
    <!-- Recent Sales Start -->
    {{-- <div class="container-fluid pt-4 px-4 row">
        <div class="bg-light text-center rounded p-4 col-6">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Fields to Display</h6>
            </div>
            <table class="table" id="example" style="width:100%">
                <thead>
                    <tr class="text-white" style="background-color: #009CFF;">
                        <th scope="col">{{ $application->name }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fields as $item)
                        <tr>
                            <td class="field-row" data-field-name="{{ $item->name }}"
                                data-field-id="{{ $item->id }}">
                                <a href="#" class="field-link">{{ $item->name }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <form action="{{ route('search.report') }}" method="GET" class="bg-light text-center rounded p-4 col-6"
            id="fieldData">
            <div class="d-flex align-items-center mb-4">
                <h6 class="mb-0">Selected</h6>
                <h6 class="mb-0">{{ $application->name }}</h6>
                <button class="btn btn-danger" type="submit">Search</button>
                <input type="hidden" name="application_id" value="{{ $application->id }}">
            </div>



        </form>
    </div> --}}
    <!-- Recent Sales End -->
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
                    <option value="group_by">Group by</option>
                    <option value="count_of">Count of</option>
                </select>
            </div>
            <div class="col-6">
                <input type="text" class="form-control" name="fieldNames[]" value="${fieldName}">
                <input type="hidden" name="fieldIds[]" value="${fieldId}">
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
                                                            <option value="CH">Changed</option>
                                                            <option value="CT">Changed To</option>
                                                            <option value="CF">Changed From</option>
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
