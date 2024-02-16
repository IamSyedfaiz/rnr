@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4 shadow">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Add New </h6>
                <div class="tab-content" id="pills-tabContent">
                    <form action="{{ route('add.notification') }}" class="form-horizontal" method="GET">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Type</label>
                            <select name="type" class="form-control">
                                {{-- <option value="SRD">Scheduled Report Distribution</option> --}}
                                <option value="SN">Subscription Notification</option>
                                {{-- <option value="ODNT">On Demand Notification Temlate</option> --}}
                            </select>
                            @error('type')
                                <label id="name-error" class="error text-danger" for="name">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Application</label>
                            <select name="application_id" class="form-control">
                                <option value="">Select Application</option>
                                @foreach ($applications as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('application_id')
                                <label id="name-error" class="error text-danger" for="application_id">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
