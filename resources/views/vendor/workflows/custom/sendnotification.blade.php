@extends('workflows::layouts.workflow_app')
@section('content')
    <!-- Recent Sales Start -->
    <div id="settings-overlay" class="settings-overlay">
        <div class="container">
            <form action="{{ route('saveMail') }}" class="form-horizontal" method="get">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Notifications</label>
                            <select name="notification" id="" class="form-control">
                                <option value="">Select Notification</option>
                                @foreach ($notifications as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="application_id" value="{{ $element->application_id }}">
                    <input type="hidden" name="workflow_id" value="{{ $element->id }}">
                    <div class="col-md-12">
                        <div class="settings-footer text-right">
                            <button class="btn btn-default"
                                onclick="closeSettings();">{{ __('workflows::workflows.Close') }}</button>
                            <button type="submit" class="btn btn-success">{{ __('workflows::workflows.Save') }}</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
