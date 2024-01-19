@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">
                    {{ strtoupper($application->name) }} Roles</h6>

                <a href="{{ route('multiplerole.edit', $application->id) }}">
                    <button type="button" class="btn btn-primary">Add Role</button>
                </a>

            </div>

            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            {{-- <th scope="col">Application Name</th> --}}
                            <th scope="col"> Name</th>
                            <th scope="col">Import</th>
                            <th scope="col">Create</th>
                            <th scope="col">Read</th>
                            <th scope="col">Update</th>
                            <th scope="col">Delete</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('role.edit', $item->id) }}">
                                        {{ $item->name }}</a>

                                </td>

                                <td>
                                    @if ($item->import == 1)
                                        Active
                                    @else
                                        In-Active
                                    @endif
                                </td>

                                <td>
                                    @if ($item->create == 1)
                                        Active
                                    @else
                                        In-Active
                                    @endif
                                </td>

                                <td>
                                    @if ($item->read == 1)
                                        Active
                                    @else
                                        In-Active
                                    @endif
                                </td>

                                <td>
                                    @if ($item->update == 1)
                                        Active
                                    @else
                                        In-Active
                                    @endif
                                </td>

                                <td>
                                    @if ($item->delete == 1)
                                        Active
                                    @else
                                        In-Active
                                    @endif
                                </td>
                                @php
                                    if ($item->updated_by) {
                                        $user = App\Models\User::find($item->updated_by);
                                        $username = $user->name;
                                    } else {
                                        $username = 'none';
                                    }
                                @endphp
                                <td>{{ $username }}</td>
                                <td>{{ $item->updated_at->toDateString() }}</td>
                                <td class="d-flex justify-content-betweenx">
                                    <a class="btn btn-sm btn-primary" href="{{ route('role.edit', $item->id) }}">Edit</a>

                                    <form action="{{ route('role.destroy', $item->id) }}" method="post">
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
