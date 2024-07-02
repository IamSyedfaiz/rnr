@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Notification</h1>
                </div>
                <div class="col-md-6 text-end"><a href="{{ route('create.notification') }}" class="btn btn-primary"><i
                            class="bi bi-person-plus-fill"></i> Add Notification</a></div>
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
                    <li class="breadcrumb-item active">Notification</li>
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
                                                <a href="{{ route('notifications.edit', $item->id) }}">
                                                    {{ $item->name ?? '-' }}</a>

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
                                            <td class="d-flex">

                                                <a href="{{ route('notifications.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm mx-1"><i class="bi bi-pencil"></i>
                                                    Edit</a>
                                                <form action="{{ route('notifications.destroy', $item->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button onclick="return confirm('Are You Sure ?')"
                                                        type="submit"class="btn btn-danger btn-sm"><i
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
    </main>
    <!-- Recent Sales End -->
@endsection
