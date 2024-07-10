@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <main id="main" class="main">
        <div class="pagetitle">
            <div class="row">
                <div class="col-md-6">
                    <h1>Application Indexing List</h1>
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
                    <li class="breadcrumb-item active">Application</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Form List</h6>

                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                        data-bs-target="#exampleModa" data-bs-whatever="@mdo">Indexing</button>
                                    @if ($roles->isEmpty())
                                        <p>No roles found for this application.</p>
                                    @else
                                        {{-- @foreach ($roles as $role)
                                            Role ID: {{ $role->id }} <br>
                                            @if (\Str::contains($role->permissions_list, 'create'))
                                                <button type="button" class="btn btn-primary">
                                                    <a href="{{ route('user-application.edit', $id) }}"
                                                        style="color:aliceblue">new</a>
                                                </button>
                                            @endif
                                            @if (\Str::contains($role->permissions_list, 'import'))
                                                <button type="button" class="btn btn-success ">
                                                    <a href="{{ route('show.form', $id) }}"
                                                        style="color:aliceblue">Import</a>
                                                </button>
                                            @endif
                                        @endforeach --}}
                                        @foreach ($uniquePermissions as $permission)
                                            @if ($permission == 'create')
                                                <a href="{{ route('user-application.edit', $id) }}"
                                                    class="btn btn-primary mx-2" style="color:aliceblue">New</a>
                                            @endif
                                            @if ($permission == 'import')
                                                <a href="{{ route('show.form', $id) }}" class="btn btn-success"
                                                    style="color:aliceblue">Import</a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="modal fade" id="exampleModa" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('userapplication.index.save') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Fields</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3 text-start">
                                                    <label for="recipient-name" class="col-form-label fw-bold  ">Show Field
                                                        In
                                                        indexing</label>
                                                    <select name="order[]" id="" class="form-control" multiple>
                                                        @for ($i = 0; $i < count($fields); $i++)
                                                            <option value="{{ $fields[$i]->id }}">{{ $fields[$i]->name }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <input type="hidden" value="{{ $application->id }}" name="application_id">
                                                <input type="hidden" value="{{ auth()->id() }}" name="userid">

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>

                                            </div>


                                        </div>
                                    </form>
                                </div>
                            </div>

                            <table class="table datatable">
                                <thead>
                                    <tr class="text-dark">
                                        @if ($index != null)
                                            @for ($j = 0; $j < count($fields); $j++)
                                                @if (in_array($fields[$j]->id, $index))
                                                    <th>{{ $fields[$j]->name }}</th>
                                                @endif
                                            @endfor
                                        @else
                                            <th scope="col">Created At</th>
                                        @endif

                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forms as $item)
                                        <tr>
                                            @if ($index != null)
                                                @for ($k = 0; $k < count($fields); $k++)
                                                    @if (in_array($fields[$k]->id, $index))
                                                        @php
                                                            $data = json_decode($item->data, true);
                                                            $sanitizedFieldName = str_replace(
                                                                ' ',
                                                                '_',
                                                                $fields[$k]->name,
                                                            );
                                                        @endphp
                                                        @if (array_key_exists($sanitizedFieldName, $data) && isset($data[$sanitizedFieldName]))
                                                            @if (is_array($data[$sanitizedFieldName]))
                                                                <td>Value List/ User Group List</td>
                                                            @else
                                                                <td>{{ $data[$sanitizedFieldName] }}</td>
                                                            @endif
                                                        @else
                                                            <td>No Data</td>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @else
                                                <td>{{ $item->created_at }}</td>
                                            @endif
                                            <td class="d-flex">
                                                {{-- @foreach ($roles as $role)
                                                    @if (\Str::contains($role->permissions_list, 'update'))
                                                        <button type="button" class="btn btn-primary">
                                                            <a href="{{ route('userapplication.edit', $item->id) }}"
                                                                style="color:aliceblue">edit</a>
                                                        </button>
                                                    @endif
                                                    @if (\Str::contains($role->permissions_list, 'delete'))
                                                        <form action="{{ route('user-application.destroy', $item->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button onclick="return confirm('Are You Sure ?')"
                                                                style="color:aliceblue"
                                                                class="btn btn-danger">delete</button>
                                                        </form>
                                                    @endif
                                                @endforeach --}}
                                                @foreach ($uniquePermissions as $permission)
                                                    @if ($permission == 'update')
                                                        <a href="{{ route('userapplication.edit', $item->id) }}"
                                                            class="btn btn-primary btn-sm mx-2"><i class="bi bi-pencil"></i>
                                                            Edit</a>
                                                    @endif
                                                    @if ($permission == 'delete')
                                                        <form action="{{ route('user-application.destroy', $item->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button onclick="return confirm('Are You Sure ?')"
                                                                type="submit" class="btn btn-danger btn-sm"><i
                                                                    class="bi bi-trash"></i>
                                                                Delete</button>
                                                        </form>
                                                    @endif
                                                @endforeach
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
