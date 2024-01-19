@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Notifications</h6>
                {{-- <a href="{{ route('notifications.create') }}"> <button class="btn btn-primary">Add Notification</button></a> --}}
                <a href="{{ route('create.notification') }}"> <button class="btn btn-primary">Add
                        Notification</button></a>
            </div>

            <div class="table-responsive">
                <table class="table  table-striped  text-start align-middle table-bordered table-hover mb-0  ">
                    <thead>
                        <tr class="text-white " style="background-color: #009CFF;">
                            <th scope="col">Notification Name</th>
                            <th scope="col">Application Name</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (@$notifications as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('notifications.edit', $item->id) }}"> {{ $item->name ?? '-' }}</a>

                                </td>
                                @php
                                    if ($item->user_id) {
                                        $user = App\Models\User::find($item->user_id);
                                        $username = $user->name;
                                    } else {
                                        $username = 'none';
                                    }
                                @endphp
                                <td>{{ $item->application->name ?? '-' }}</td>
                                <td>{{ $username }}</td>

                                <td>{{ $item->created_at->toDateString() }}</td>
                                <td>{{ $item->updated_at->toDateString() }}</td>
                                <td class="d-flex justify-content-around">
                                    <a class="btn btn-sm btn-primary" href="{{ route('notifications.edit', $item->id) }}">
                                        Edit</a>

                                    <form action="{{ route('notifications.destroy', $item->id) }}" method="post">
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
