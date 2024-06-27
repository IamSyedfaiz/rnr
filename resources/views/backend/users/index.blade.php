@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Users</h1>
                </div>
                <div class="col-md-6 text-end"><a href="{{ route('users.create') }}" class="btn btn-primary"><i
                            class="bi bi-person-plus-fill"></i> Add User</a></div>
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
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('users.edit', $item->id) }}">
                                                    {{ $item->name . ' ' . $item->lastname }}
                                                </a>
                                            </td>
                                            <td>{{ $item->email }}</td>

                                            <td>
                                                @if ($item->status == 1)
                                                    Active
                                                @else
                                                    In-Active
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->toDateString() }}</td>
                                            <td>{{ $item->updated_at->toDateString() }}</td>

                                            <td class="d-flex ">

                                                <a href="{{ route('users.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm mx-1"><i class="bi bi-pencil"></i>
                                                    Edit</a>

                                                <form action="{{ route('users.destroy', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are You Sure ?')"><i
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
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
