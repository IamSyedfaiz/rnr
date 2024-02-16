@extends('backend.layouts.app')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 ">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="example" class="display table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Count</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                @foreach ($filteredData as $key => $value)
                                    <td>{{ $key }}</td>
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
