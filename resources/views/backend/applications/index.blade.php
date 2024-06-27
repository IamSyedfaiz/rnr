@extends('backend.layouts.app')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Applications</h1>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                        <i class="bi bi-window-stack"></i> Add Application
                    </button>
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
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Applications</li>
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
                                        <th>Status</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('application.edit', $item->id) }}">
                                                    {{ $item->name }}</a>

                                            </td>
                                            <td>
                                                @if ($item->status == 1)
                                                    Active
                                                @else
                                                    In-Active
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->toDateString() }}</td>
                                            <td>{{ $item->updated_at->toDateString() }}</td>
                                            <td class="d-flex justify-content-betweenx">

                                                <a href="{{ route('application.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                                <a href="{{ route('workflow.show', $item->id) }}"
                                                    class="btn btn-primary btn-sm mx-2"><i class="bi bi-bezier2 "></i>
                                                    Workflow</a>
                                                {{-- <a href="" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>
                                                Delete</a>
                                                
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('application.edit', $item->id) }}">Edit</a> --}}

                                                <form action="{{ route('application.destroy', $item->id) }}" method="post">
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
                                                {{-- <a class="btn btn-sm btn-success"
                                                    href="{{ route('workflow.show', $item->id) }}">Workflow</a> --}}
                                                {{-- <a class="btn btn-sm btn-success mx-2"
                                                href="{{ route('custom-workflow.show', $item->id) }}">CustomWorkflow</a> --}}
                                                {{-- @if ($item->workFlow)
                                                <a class="btn btn-sm btn-success mx-2"
                                                    href="{{ route('triggerButtonShow', @$item->workFlow->id) }}">CustomWorkflow</a>
                                            @endif --}}
                                                {{-- @php
                                                $model = the42coders\Workflows\Workflow::find($item->id);
        
                                            @endphp --}}
                                                {{-- <button>
                                                {!! the42coders\Workflows\Triggers\ButtonTrigger::renderButtonByWorkflowId($model->id, $model) !!}
                                            </button> --}}
                                                {{-- <a href="{{ route('workflow.index') }}">Workflow</a> --}}
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
    <div class="modal fade" id="basicModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('application.store') }}" method="POST">
                        @csrf
                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Application Name</label>
                            <input type="text" name="name" class="form-control" id="recipient-name" required>
                        </div>
                        <div class="col-12">
                            <label for="inputAddress" class="form-label">Status</label>
                            <select name="status" id=""
                                class="form-control @error('status') is-invalid @enderror form-select">
                                <option value="1">Active</option>
                            </select>
                        </div>
                        <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Recent Sales End -->
@endsection
