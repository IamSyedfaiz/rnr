@extends('backend.layouts.app')
@section('content')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 ">
            <div class="col-sm-12">
                <div class="rounded h-100">
                    <div class="m-n2">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true"
                                        aria-controls="flush-collapseOne">
                                        Applications
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <a href="{{ route('view.save.report') }}"
                                            class="btn btn-primary m-2 fw-bold">SAVE</a>
                                        <button type="button" class="btn btn-outline-primary fw-bold">MODIFY</button>
                                        <button type="button" class="btn btn-outline-primary fw-bold">NEW REPORT</button>
                                        <button type="button" class="btn btn-outline-primary fw-bold">RELATED
                                            REPORTS</button>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="example" class="display table" style="width: 100%">
                                                        <thead>
                                                            <tr>
                                                                @foreach ($fieldStatisticsNames as $fieldName)
                                                                    <th>{{ ucfirst($fieldName) }}</th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @for ($i = 0; $i < count($allData['name']); $i++)
                                                                <tr>
                                                                    @foreach ($fieldStatisticsNames as $fieldName)
                                                                        <td>{{ $allData[$fieldName][$i] }}</td>
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
    </div>
    <!-- Sale & Revenue End -->
@endsection
