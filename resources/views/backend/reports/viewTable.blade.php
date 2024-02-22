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
                                        <form action="{{ route('store.cert.report') }}" method="GET">
                                            @csrf
                                            <input type="hidden" name="application_id" value="{{ $applicationId }}">
                                            <input type="hidden" name="report_id" value="{{ @$reportId }}">
                                            <input type="hidden" name="data" value="{{ json_encode($allData) }}">
                                            <input type="hidden" name="fieldStatisticsNames"
                                                value="{{ json_encode($fieldStatisticsNames) }}">
                                            <input type="hidden" name="statisticsMode" value="{{ $statisticsMode }}">
                                            <input type="hidden" name="dropdowns" value="{{ json_encode($dropdowns) }}">
                                            <input type="hidden" name="fieldNames" value="{{ json_encode($fieldNames) }}">


                                            <button type="submit" class="btn btn-outline-primary fw-bold">SAVE</button>
                                            @if ($reportId)
                                                <a href="{{ route('edit.chart', $reportId) }}"
                                                    class="btn btn-outline-primary fw-bold">MODIFY</a>
                                            @else
                                                <a href="{{ route('back.report.application', $applicationId) }}"
                                                    class="btn btn-outline-primary fw-bold">MODIFY</a>
                                            @endif
                                            {{-- <button type="button" class="btn btn-outline-primary fw-bold">NEW
                                                REPORT</button>
                                            <button type="button" class="btn btn-outline-primary fw-bold">RELATED
                                                REPORTS</button> --}}
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
@endsection
