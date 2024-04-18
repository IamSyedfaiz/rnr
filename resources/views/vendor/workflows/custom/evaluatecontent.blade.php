@extends('workflows::layouts.workflow_app')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <form action="{{ route('evaluate.content') }}" class="form-horizontal" method="get">
                @csrf
                <h4 class="my-4">General Information</h4>
                <div class="form-horizontal row ">
                    <div class="col-12 row">
                        <div class="mb-3 col-4">
                            <label for="exampleInputEmail1" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required
                                value="{{ @$evaluateContent ? @$evaluateContent->name : '' }}">
                            <input type="hidden" name="application_id" value="{{ @$element->application_id }}">
                            <input type="hidden" name="workflow_id" value="{{ @$element->id }}">
                            <input type="hidden" name="task_id" value="{{ @$task->id }}">
                            <input type="hidden" name="id" value="{{ @$evaluateContent->id }}">
                            @error('name')
                                <label id="name-error" class="error text-danger" for="name">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="mb-3 col-4">
                            <label for="exampleInputEmail1" class="form-label">Alias</label>
                            <input type="text" class="form-control" name="alias" id="alias" required
                                value="{{ @$evaluateContent ? @$evaluateContent->alias : '' }}">
                            @error('alias')
                                <label id="name-error" class="error text-danger" for="name">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" name="active"
                                    {{ @$evaluateContent ? (@$evaluateContent->active == 'Y' ? 'checked' : '') : '' }}>
                                Active
                            </label>
                            <input type="hidden" name="active" value="{{ @$evaluateContent->active ? 'Y' : 'N' }}">
                        </div>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="exampleInputEmail1" class="form-label">Descriptoin</label>
                        <Textarea name="description" rows="5" cols="5" class="form-control">{{ @$evaluateContent ? @$evaluateContent->description : '' }}</Textarea>
                    </div>
                    <div class="col-12 row">
                        <div class="mb-3 col-4">
                            <label for="exampleInputEmail1" class="form-label">type</label>
                            <input type="text" class="form-control" name="type" id="type" required
                                value="{{ @$evaluateContent ? @$evaluateContent->type : '' }}">
                            @error('type')
                                <label id="name-error" class="error text-danger" for="name">
                                    {{ $message }}</label>
                            @enderror
                            <div id="namehelp" class="form-text">
                            </div>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="exampleInputEmail1" class="form-label">created_at</label>
                            <input type="text" class="form-control" readonly
                                value="{{ @$evaluateContent ? @$evaluateContent->created_at : '' }}">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="exampleInputEmail1" class="form-label">updated_at</label>
                            <input type="text" class="form-control" readonly
                                value="{{ @$evaluateContent ? @$evaluateContent->updated_at : '' }}">
                        </div>
                    </div>
                </div>
                <h4 class="my-4">Rule</h4>
                <div class="table-responsive mt-5">
                    <a class="btn btn-success mb-3" id="addRow"><i class="bi bi-plus-circle"></i> Add</a>
                    <table class="table  table-striped  text-start align-middle table-bordered table-hover mb-0"
                        id="dataTable">
                        <thead>
                            <tr class="text-white" style="background-color: #009CFF;">
                                <th scope="col">ID</th>
                                <th scope="col">FIELD NAME</th>
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
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Value"
                                        name="filter_value[]">
                                </td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if ($evaluateRules)
                    <div class="table-responsive mt-5">
                        <table class="table">
                            <thead>
                                <tr class="text-white " style="background-color: #009CFF;">
                                    <th scope="col">ID</th>
                                    <th scope="col">FIELD NAME</th>
                                    <th scope="col">OPERATOR</th>
                                    <th scope="col">VALUE</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($evaluateRules as $index => $filter)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $filter->field->name }}</td>
                                        <td> {{ [
                                            'C' => 'Contains',
                                            'DNC' => 'Does Not Contain',
                                            'E' => 'Equals',
                                            'DNE' => 'Does Not Equal',
                                            'CH' => 'Changed',
                                            'CT' => 'Changed To',
                                            'CF' => 'Changed From',
                                        ][$filter->filter_operator] ?? 'Unknown' }}
                                        </td>
                                        <td>{{ $filter->filter_value }}</td>
                                        <td><a href="{{ route('evaluateRules.destroy', $filter->id) }}"
                                                class="btn btn-danger">delete</a> </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mb-3 col-6">
                    <label for="exampleInputEmail1" class="form-label">Advanced Operator Logic</label>
                    <input type="text" class="form-control" name="advanced_operator_logic" value=""
                        id="advancedOperatorLogic" aria-describedby="advancedOperatorLogichelp">
                </div>
                <div class="col-md-12">
                    <div class="settings-footer text-right">
                        <button class="btn btn-default"
                            onclick="closeSettings();">{{ __('workflows::workflows.Close') }}</button>
                        {{-- <button class="btn btn-success"
                            onclick="saveFields({{ $element->id }}, '{{ $element->family }}');">{{ __('workflows::workflows.Save') }}</button> --}}
                        <button type="submit" class="btn btn-success">{{ __('workflows::workflows.Save') }}</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('input[name="active"]').change(function() {
                var isChecked = $(this).is(':checked');
                $('input[name="active"]').val(isChecked ? 'Y' : 'N');
            });
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
