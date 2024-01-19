@extends('backend.layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">CSV Import</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('url.upload') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="application_id" value="{{ $id }}">

                            <div class="form-group{{ $errors->has('excel_file') ? ' has-error' : '' }}">
                                <label for="excel_file" class="col-md-4 control-label">CSV file to import</label>
                                <div class="col-md-6">
                                    <input id="excel_file" type="text" class="form-control" name="excel_file" required>
                                    @if ($errors->has('excel_file'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('excel_file') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="header" checked> File contains header row?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Parse CSV
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="userid" value="{{ auth()->id() }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
