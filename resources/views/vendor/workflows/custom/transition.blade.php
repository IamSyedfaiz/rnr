@extends('workflows::layouts.workflow_app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="bg-light rounded h-100 p-4">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-4">Transition</h6>
                        </div>

                        <form action="{{ route('transition.store') }}" class="form-horizontal" enctype="multipart/form-data"
                            method="post">
                            @csrf
                            <input type="text" value="{{ auth()->id() }}" name="user_id">
                            <input type="text" name="application_id" value="{{ @$element->application_id }}">
                            <input type="text" name="workflow_id" value="{{ @$element->id }}">
                            <input type="text" name="parent_id" value="{{ @$node_id_out }}">
                            <input type="text" name="child_id" value="{{ @$node_id_in }}">
                            <div class="my-3">
                                <input type="text" name="condition" class="form-control">
                            </div>
                            {{-- <div class="mb-3">
                                <select class="form-control" name="task_id">
                                    @foreach ($tasks as $index => $task)
                                        <option value="{{ $task->id }}">{{ $task->name }} ({{ $index + 1 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="settings-footer text-right">
                                <button class="btn btn-default"
                                    onclick="closeSettings();">{{ __('workflows::workflows.Close') }}</button>
                                <button type="submit"
                                    class="btn btn-success">{{ __('workflows::workflows.Save') }}</button>
                            </div>
                        </form>
                        <div class="table-responsive mt-5">
                            <table class="table  table-striped  text-start align-middle table-bordered table-hover mb-0"
                                id="dataTable">
                                <thead>
                                    <tr class="text-white" style="background-color: #009CFF;">
                                        <th scope="col">ID</th>
                                        <th scope="col">Transition NAME</th>
                                        <th scope="col">Transition NODE</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transitions as $index => $transition)
                                        <tr class="data-row">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transition->parentTask->name }} -> {{ $transition->childTask->name }}
                                            </td>
                                            <td>{{ $transition->condition }}</td>
                                            <td>
                                                <a href="{{ route('transition.destroy', $transition->id) }}"
                                                    class="btn btn-danger">
                                                    delete
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
