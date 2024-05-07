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
                            @foreach (@$fields as $item)
                                @if ($item->type == 'date')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper($item->name) }}</label>
                                        <input type="date" class="form-control" name="{{ $item->name }}">
                                    </div>
                                @endif
                                @if ($item->type == 'attachment')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper($item->name) }}</label>
                                        <input type="file" class="form-control" name="{{ $item->name }}">
                                    </div>
                                @endif
                                @if ($item->type == 'images')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper($item->name) }}</label>
                                        <input type="file" class="form-control" name="{{ $item->name }}">
                                    </div>
                                @endif
                                @if ($item->type == 'ip_address')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper($item->name) }}</label>
                                        <input type="text" class="form-control" name="{{ $item->name }}"
                                            minlength="7" maxlength="15" size="15"
                                            pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$">
                                    </div>
                                @endif
                                @if ($item->type == 'number')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper($item->name) }}</label>
                                        <input type="number" class="form-control" name="{{ $item->name }}">
                                    </div>
                                @endif
                                @if ($item->type == 'text')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper($item->name) }}</label>
                                        <input type="text" class="form-control" name="{{ $item->name }}">
                                    </div>
                                @endif
                                @if ($item->type == 'value_list')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper($item->name) }}</label><br>
                                        @if ($item->valuelisttype == 'dropdown')
                                            @php
                                                $valuelist = json_decode($item->valuelistvalue);
                                            @endphp
                                            <select name="{{ $item->name }}" class="form-control" type="checkbox"
                                                id="">
                                                <option value="">Select Option</option>
                                                @foreach ($valuelist as $item1)
                                                    <option value="{{ $item1 }}">
                                                        {{ $item1 }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                        @if ($item->valuelisttype == 'radio')
                                            @php
                                                $valuelist = json_decode($item->valuelistvalue);
                                            @endphp

                                            @foreach ($valuelist as $item1)
                                                <input name="{{ $item->name }}" value="{{ $item1 }}"
                                                    id="nonr" class="form-check-input" type="radio">
                                                <label for="{{ $item1 }}">
                                                    {{ strtoupper($item1) }}</label><br>
                                            @endforeach
                                        @endif
                                        @if ($item->valuelisttype == 'checkinput')
                                            @php
                                                $valuelist = json_decode($item->valuelistvalue);
                                            @endphp
                                            @foreach ($valuelist as $item1)
                                                <input type="checkbox" class="form-check-input"
                                                    name="{{ $item->name }}[{{ $item1 }}]">
                                                <label for="{{ $item1 }}">
                                                    {{ strtoupper($item1) }}</label><br>
                                            @endforeach
                                        @endif
                                        @if ($item->valuelisttype == 'listbox')
                                            @php
                                                $valuelist = json_decode($item->valuelistvalue);
                                            @endphp
                                            @foreach ($valuelist as $item1)
                                                <input type="checkbox" class="form-check-input"
                                                    name="{{ $item->name }}[{{ $item1 }}]">
                                                <label for="{{ $item1 }}">
                                                    {{ strtoupper($item1) }}</label><br>
                                            @endforeach
                                        @endif
                                        @if (@$item->valuelisttype == 'valuepopup')
                                            @php
                                                $valuelist = json_decode(@$item->valuelistvalue);
                                            @endphp
                                            @foreach ($valuelist as $item1)
                                                <input type="checkbox" class="form-check-input"
                                                    name="{{ @$item->name }}[{{ $item1 }}]">
                                                <label for="{{ $item1 }}">
                                                    {{ strtoupper(@$item1) }}</label><br>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                                @if ($item->type == 'user_group_list')
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">
                                            {{ strtoupper(@$item->name) }}</label>
                                        <div class="usergrouplist">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div class="col-md-2 addusers">
                                                    <button type="button" class="btn btn-primary text-end"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                        data-bs-whatever="@mdo">Add Users</button>
                                                </div>
                                                <div class="col-md-2 addgroups">
                                                    <button type="button" class="btn btn-primary text-end"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModalgroups"
                                                        data-bs-whatever="@mdo">Add Groups</button>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="exampleModalusers" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Select Groups
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3 text-start">
                                                                <label for="message-text"
                                                                    class="col-form-label fw-bold text-left ">Groups
                                                                    <small>(ctrl + click) multiple select</small> </label>
                                                                <select name="{{ @$item->name }}[user_list][]"
                                                                    id="" class="form-control" multiple>
                                                                    @foreach ($users as $item2)
                                                                        <option value="{{ $item2->id }}">
                                                                            {{ $item2->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-dismiss="modal">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="exampleModalgroups" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Select Groups
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3 text-start">
                                                                <label for="message-text"
                                                                    class="col-form-label fw-bold text-left ">Groups
                                                                    <small>(ctrl + click) multiple select</small> </label>
                                                                <select name="{{ $item->name }}[group_list][]"
                                                                    id="" class="form-control" multiple>
                                                                    @foreach ($groups as $item1)
                                                                        <option value="{{ $item1->id }}">
                                                                            {{ $item1->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-dismiss="modal">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

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
