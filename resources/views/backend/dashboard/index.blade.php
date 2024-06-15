@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Dashboards</h6>
                <a href="{{ route('dashboard.create') }}"> <button class="btn btn-primary">Add
                        New</button></a>
            </div>
            <div class="table-responsive">
                <table id="example" class="table  table-striped  text-start align-middle table-bordered table-hover mb-0  ">
                    <thead>
                        <tr class="text-white " style="background-color: #009CFF;">
                            <th scope="col">Name</th>
                            <th scope="col">Active</th>
                            <th scope="col">Last Updated</th>
                            <th scope="col">Updated By</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (@$dashboards as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.edit', $item->id) }}"> {{ $item->name ?? '-' }}</a>
                                </td>
                                <td>{{ $item->active == 'Y' ? 'Yes' : 'No' }}</td>
                                <td>{{ $item->updated_at->toDateString() }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td class="d-flex justify-content-around">
                                    <a class="btn btn-sm btn-primary" href="{{ route('dashboard.edit', $item->id) }}">
                                        Edit</a>

                                    <form action="{{ route('dashboard.destroy', $item->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-sm btn-danger" onclick="return confirm('Are You Sure ?')"
                                            type="submit" value="Delete">
                                    </form>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->
@endsection
