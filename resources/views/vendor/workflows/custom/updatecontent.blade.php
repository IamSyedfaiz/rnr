@extends('workflows::layouts.workflow_app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                {{-- <h6 class="mb-0">Application Create</h6> --}}

            </div>

            <div class="bg-light rounded h-100 p-4">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-4">Update Content</h6>
                        </div>

                        <form action="{{ route('updateContent.store') }}" class="form-horizontal" method="get">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ @$element->application_id }}">
                            <input type="hidden" name="workflow_id" value="{{ @$element->id }}">
                            <input type="hidden" name="task_id" value="{{ @$task->id }}">
                            {{-- <input type="hidden" value="{{ auth()->id() }}" name="user_id"> --}}
                            {{-- <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name">
                            </div> --}}
                            <div class="row mb-5">
                                <div class="col-6">
                                    <select class="form-control" name="key">
                                        <option value="">Select field</option>
                                        @foreach ($fields as $item)
                                            <option value="{{ $item->name }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" placeholder="Value" name="value">
                                </div>
                            </div>

                            <table class="table  table-striped  text-start align-middle table-bordered table-hover mb-0"
                                id="dataTable">
                                <thead>
                                    <tr class="text-white" style="background-color: #009CFF;">
                                        <th scope="col">ID</th>
                                        <th scope="col">FIELD NAME</th>
                                        <th scope="col">VALUE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="data-row">
                                        @foreach ($data as $key => $value)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $key }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                    @endforeach

                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-md-12 mt-5">
                                <div class="settings-footer text-right">
                                    <button class="btn btn-default"
                                        onclick="closeSettings();">{{ __('workflows::workflows.Close') }}</button>
                                    <button type="submit"
                                        class="btn btn-success">{{ __('workflows::workflows.Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
