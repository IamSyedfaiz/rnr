@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Form List</h6>
                {{-- {{ dd($roles) }} --}}

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModa"
                        data-bs-whatever="@mdo">Indexing</button>
                    @if ($roles->create == 1)
                        <button type="button" class="btn btn-primary">
                            <a href="{{ route('user-application.edit', $id) }}" style="color:aliceblue">new</a>
                        </button>
                    @endif

                    @if ($roles->import == 1)
                        {{-- <button type="button" class="btn btn-success ">
                            <a href="{{ route('csv.import', $id) }}" style="color:aliceblue">Import</a>
                        </button> --}}
                        <button type="button" class="btn btn-success ">
                            <a href="{{ route('show.form', $id) }}" style="color:aliceblue">Import</a>
                        </button>
                    @endif

                </div>
            </div>

            <div class="modal fade" id="exampleModa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('userapplication.index.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Fields</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3 text-start">
                                    <label for="recipient-name" class="col-form-label fw-bold  ">Show Field In
                                        indexing</label>
                                    <select name="order[]" id="" class="form-control" multiple>
                                        {{-- {{ dd($fields) }} --}}
                                        @for ($i = 0; $i < count($fields); $i++)
                                            <option value="{{ $fields[$i]->id }}">{{ $fields[$i]->name }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <input type="hidden" value="{{ $application->id }}" name="application_id">
                                <input type="hidden" value="{{ auth()->id() }}" name="userid">

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>

                            </div>


                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            @if ($index != null)
                                @for ($j = 0; $j < count($fields); $j++)
                                    {{-- {{ dd($fields[$j]->id, $index) }} --}}
                                    @if (in_array($fields[$j]->id, $index))
                                        {{-- {{ dd($fields[$j]) }} --}}
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
                                                // dd($data, $fields);
                                                // dd($data, $data['second']);
                                                // dd(array_key_exists('four', $data));
                                            @endphp

                                            @if (array_key_exists($fields[$k]->name, $data) && isset($data[$fields[$k]->name]))
                                                @if (is_array($data[$fields[$k]->name]))
                                                    <td>Value List/ User Group List</td>
                                                @else
                                                    <td>{{ $data[$fields[$k]->name] }}</td>
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
                                    @if ($roles->update == 1)
                                        <button class="btn btn-primary">
                                            <a href="{{ route('userapplication.edit', $item->id) }}"
                                                style="color:aliceblue">edit</a>
                                        </button>
                                    @endif

                                    @if ($roles->delete == 1)
                                        <form action="{{ route('user-application.destroy', $item->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are You Sure ?')" style="color:aliceblue"
                                                class="btn btn-danger">delete</button>
                                        </form>
                                    @endif

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
