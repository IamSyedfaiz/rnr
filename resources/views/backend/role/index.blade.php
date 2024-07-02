@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Roles & Permissions</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('multiplerole.create') }}" class="btn btn-primary">
                        <i class="bi bi-list-check"></i> Add Role
                    </a>
                </div>
            </div>
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade in show col-md-12 mt-2">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade in show col-md-12 mt-2">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Roles & Permissions</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <table class="table datatable">
                                <thead>
                                    <tr class="text-dark">
                                        <th scope="col"> Name</th>
                                        <th scope="col">Groups</th>
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
                                                @php
                                                    // Decode the JSON group_list
                                                    $groupIds = json_decode($item->group_list);
                                                    // Fetch the group names
                                                    $groupNames = $groupIds
                                                        ? \App\Models\backend\Group::whereIn('id', $groupIds)->pluck(
                                                            'name',
                                                        )
                                                        : null;
                                                @endphp

                                                @if ($groupNames && $groupNames->isNotEmpty())
                                                    {{ $groupNames->implode(', ') }}
                                                @else
                                                    No groups
                                                @endif
                                            </td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->updated_at->toDateString() }}</td>
                                            <td class="d-flex justify-content-betweenx">

                                                <a href="{{ route('role.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm mx-1"><i class="bi bi-pencil"></i>
                                                    Edit</a>


                                                <form action="{{ route('role.destroy', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are You Sure ?')" type="submit"><i
                                                            class="bi bi-trash"></i>
                                                        Delete</button>
                                                    {{-- <input class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are You Sure ?')" type="submit"
                                                        value="Delete"> --}}
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->
    <!-- Recent Sales End -->
@endsection
