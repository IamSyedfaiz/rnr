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
                            {{-- <div class="row mb-5">
                                <div class="col-6">
                                    <select class="form-control" name="key">
                                        <option value="">Select field</option>
                                        @foreach ($fields as $item)
                                            <option value="{{ $item->name }}">
                                                {{ $item->name }}
                                                {{ $item->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" placeholder="Value" name="value">
                                </div>
                            </div> --}}
                            <div class="row mb-5">
                                <div class="col-6">
                                    <select class="form-control" name="key" id="fieldTypeSelector">
                                        <option value="">Select field</option>
                                        @foreach ($fields as $item)
                                            <option value="{{ $item->name }}" data-type="{{ $item->type }}"
                                                data-valuelist="{{ $item->valuelistvalue }}">
                                                {{ $item->name }} ({{ $item->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6" id="inputContainer">
                                    <input type="text" class="form-control" placeholder="Value" name="value"
                                        id="dynamicInput">
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fieldTypeSelector').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const selectedType = selectedOption.data('type');
                const valueList = selectedOption.data("valuelist");
                const inputContainer = $('#inputContainer');
                const fileLinkContainer = $('#fileLinkContainer');

                inputContainer.empty();
                fileLinkContainer.empty();


                switch (selectedType) {
                    case 'date':
                        inputField =
                            `<input type="date" class="form-control" name="value" id="dynamicInput">`;
                        break;

                    case 'attachment':
                        inputField =
                            `<input type="file" class="form-control" name="value" id="dynamicInput">`;
                        fileLinkContainer.html(
                            `<a href="#" id="uploadedFileLink" class="text-primary d-none">View Uploaded File</a>`
                        );
                        break;

                    case 'images':
                        inputField =
                            `<input type="file" class="form-control" name="value" id="dynamicInput" accept="image/*">`;
                        fileLinkContainer.html(
                            `<a href="#" id="uploadedImageLink" class="text-primary d-none">View Uploaded Image</a>`
                        );
                        break;

                    case 'ip_address':
                        inputField =
                            `<input type="text" class="form-control" name="value" placeholder="Enter IP Address" id="dynamicInput">`;
                        break;

                    case 'number':
                        inputField =
                            `<input type="number" class="form-control" name="value" placeholder="Enter a number" id="dynamicInput">`;
                        break;

                    case 'text':
                        inputField =
                            `<input type="text" class="form-control" name="value" placeholder="Enter text" id="dynamicInput">`;
                        break;

                    case 'value_list':

                        if (valueList.length > 0) {
                            inputField = `<select class="form-control" name="value" id="dynamicInput">`;
                            valueList.forEach((value) => {
                                inputField += `<option value="${value}">${value}</option>`;
                            });
                            inputField += `</select>`;
                        } else {
                            inputField = `<div class="text-danger">Invalid value list</div>`;
                        }
                        break;


                    case 'user_group_list':
                        inputField = `
                            <select class="form-control" name="value" id="dynamicInput">
                                <option value="Group 1">Group 1</option>
                                <option value="Group 2">Group 2</option>
                                <option value="Group 3">Group 3</option>
                            </select>`;
                        break;

                    default:
                        inputField =
                            `<input type="text" class="form-control" name="value" placeholder="Enter value" id="dynamicInput">`;
                        break;
                }

                inputContainer.append(inputField);

                if (selectedType === 'attachment' || selectedType === 'images') {
                    $('#dynamicInput').on('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const fileUrl = URL.createObjectURL(file);
                            const linkElement = selectedType === 'images' ?
                                $('#uploadedImageLink') :
                                $('#uploadedFileLink');

                            linkElement.attr('href', fileUrl).removeClass('d-none').text(
                                `View Uploaded ${selectedType === 'images' ? 'Image' : 'File'}`);
                        }
                    });
                }
            });
        });
    </script>
@endsection
