@extends('backend.layouts.app')
@section('content')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <div class="rounded h-100">
                    <div class="m-n2">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            {{-- <div class="accordion-item">
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
                            </div> --}}
                            {{-- <div class="accordion-item mt-2">
                                <h2 class="accordion-header" id="flush-headingtwo">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="true"
                                        aria-controls="flush-collapsetwo">
                                        Keyword Search
                                        <i class="bi bi-exclamation-circle mx-1"></i>
                                    </button>
                                </h2>
                                <div id="flush-collapsetwo" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingtwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <form action="">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Search Criteria Here" aria-label="Username"
                                                            aria-describedby="basic-addon1" />
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control"
                                                            placeholder="Applications" aria-label="Recipient's username"
                                                            aria-describedby="basic-addon2">
                                                        <span class="input-group-text" id="basic-addon2">...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Statistics Mode
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> --}}
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
                                                <input type="hidden" name="application_id"
                                                    value="{{ $application->name }}">
                                                <input type="hidden" name="report_id" value="{{ $report->id }}">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        id="statisticsModeCheckbox" name="statistics_mode">
                                                    <label class="form-check-label" for="statisticsModeCheckbox">
                                                        Statistics Mode
                                                    </label>
                                                </div>

                                                <div class="d-flex align-items-center mb-4">
                                                    <h6 class="mb-0">Selected</h6>
                                                    <h6 class="mb-0">{{ $application->name }}</h6>
                                                    <button class="btn btn-danger mx-2" type="submit">Search</button>
                                                    <input type="hidden" name="application_id"
                                                        value="{{ $application->id }}">
                                                </div>
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
                                        <div class="row">
                                            <div class="col-3">
                                                <label class="form-label mx-2 fw-bold text-dark">Field To
                                                    Evaluate</label>

                                                <div class="input-group mb-3">
                                                    <label for="Find"
                                                        class="col-form-label mx-2 fw-bold text-dark">1</label>
                                                    <input type="text" class="form-control" placeholder=""
                                                        aria-label="Username" aria-describedby="basic-addon1" />
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label mx-2 fw-bold text-dark">Operator</label>

                                                <div class="input-group mb-3 ">
                                                    <input type="text" class="form-control" placeholder=""
                                                        aria-label="Username" aria-describedby="basic-addon1" />
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label mx-2 fw-bold text-dark">value(s)
                                                </label>

                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder=""
                                                        aria-label="Username" aria-describedby="basic-addon1" />
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label mx-2 fw-bold text-dark">Relation</label>
                                                <p class="fw-bold fs-5 text-dark mx-2">And</p>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label mx-2 fw-bold text-dark">Actions</label>
                                                <p class=" fs-5 text-primary mx-2"><i class="bi bi-x-circle-fill"></i>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <label class="form-label mx-2 fw-bold text-dark">Field To
                                                    Evaluate</label>

                                                <div class="input-group mb-3">
                                                    <label for="Find"
                                                        class="col-form-label mx-2 fw-bold text-dark">2</label>
                                                    <input type="text" class="form-control" placeholder=""
                                                        aria-label="Username" aria-describedby="basic-addon1" />
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label mx-2 fw-bold text-dark">Operator</label>

                                                <div class="input-group mb-3 ">
                                                    <input type="text" class="form-control" placeholder=""
                                                        aria-label="Username" aria-describedby="basic-addon1" />
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label mx-2 fw-bold text-dark">value(s)
                                                </label>

                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder=""
                                                        aria-label="Username" aria-describedby="basic-addon1" />
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label mx-2 fw-bold text-dark">Relation</label>
                                                <p class="fw-bold fs-5 text-dark mx-2">And</p>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label mx-2 fw-bold text-dark">Actions</label>
                                                <p class=" fs-5 text-primary mx-2"><i class="bi bi-x-circle-fill"></i>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5">


                                                <div class="input-group mb-3">
                                                    <label for="Find"
                                                        class="col-form-label mx-2 fw-bold text-dark">Advanced Operator
                                                        logic</label>
                                                    <input type="text" class="form-control" placeholder=""
                                                        aria-label="Username" aria-describedby="basic-addon1" />
                                                </div>
                                            </div>

                                            <div class="col-5">
                                                <label class="form-label mx-2 mt-1 fw-bold text-dark">Example(1 AND
                                                    2)OR 3
                                                </label>


                                            </div>


                                        </div>
                                        </form>
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
    </script>
@endsection
