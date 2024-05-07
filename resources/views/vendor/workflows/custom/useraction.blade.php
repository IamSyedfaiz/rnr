@extends('workflows::layouts.workflow_app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
            </div>
            <div class="bg-light rounded h-100 p-4">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-4">User Action</h6>
                        </div>

                        <form action="{{ route('userAction.store') }}" class="form-horizontal" method="get">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <input type="hidden" value="{{ auth()->id() }}" name="userid">
                            <input type="hidden" value="{{ @$task->id }}" name="task_id">
                            <div class="col-md-12 mt-5">
                                <div class="settings-footer text-right">
                                    <button class="btn btn-default"
                                        onclick="closeSettings();">{{ __('workflows::workflows.Close') }}</button>
                                    <button type="submit"
                                        class="btn btn-success">{{ __('workflows::workflows.Save') }}</button>
                                </div>
                            </div>
                        </form>
                        <table class="table  table-striped  text-start align-middle table-bordered table-hover mb-0"
                            id="dataTable">
                            <thead>
                                <tr class="text-white" style="background-color: #009CFF;">
                                    <th scope="col">ID</th>
                                    <th scope="col">NAME</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="data-row">
                                    @foreach ($userAction as $key => $value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $value->name }}</td>
                                </tr>
                                @endforeach

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>
    </div>


    <!-- Recent Sales End -->



    {{-- <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor1');
    </script> --}}
@endsection
