@extends('workflows::layouts.workflow_app')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                {{-- <div class="col-md-12">
                    <div class="settings-footer text-right">
                        <button class="btn btn-default"
                            onclick="closeSettings();">{{ __('workflows::workflows.Close') }}</button>
                        <button class="btn btn-success"
                            onclick="saveFields({{ $element->id }}, '{{ $element->family }}');">{{ __('workflows::workflows.Save') }}</button>
                    </div>
                </div> --}}
                <div class="table-responsive mt-5">
                    <a class="btn btn-success mb-3" id="addRow"><i class="bi bi-plus-circle"></i>Add</a>
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
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mb-3 col-6">
                    <label for="exampleInputEmail1" class="form-label">Advanced Operator Logic</label>
                    <input type="text" class="form-control" name="advanced_operator_logic" value=""
                        id="advancedOperatorLogic" aria-describedby="advancedOperatorLogichelp">
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
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
