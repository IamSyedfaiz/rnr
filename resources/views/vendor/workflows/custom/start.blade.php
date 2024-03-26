@extends('workflows::layouts.workflow_app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">

            <div class="bg-light rounded h-100 p-4">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        heo
                        <div class="col-md-12">
                            <div class="settings-footer text-right">
                                <button class="btn btn-default"
                                    onclick="closeSettings();">{{ __('workflows::workflows.Close') }}</button>
                                <button class="btn btn-success"
                                    onclick="saveFields({{ $element->id }}, '{{ $element->family }}');">{{ __('workflows::workflows.Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection
