@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Application Indexing List</h6>
                {{-- {{ dd($roles) }} --}}

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <button type="button" class="btn btn-danger">
                        <a href="{{ route('userapplication.list', $id) }}" style="color:aliceblue">
                            <- back</a>
                    </button>

                </div>
            </div>

            <form action="{{ route('userapplication.index.save') }}" method="post">
                @csrf
                {{-- {{ dd($indexing) }} --}}
                <div class="table-responsive">
                    @if ($indexing != 'notfound')
                        @foreach ($fields as $item)
                            @php
                                $index = json_decode($indexing->order);
                                // $i++;
                                // dd($index);
                            @endphp
                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                <div class="d-flex">
                                    <thead>
                                        <tr class="text-dark">
                                            <th scope="col">{{ $item->name }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <input type="text" name="order[]" value="{{ $index[$i] }}"
                                            class="form-control" required>

                                        <input type="hidden" name="update" value="{{ $i }}">

                                    </tbody>

                                </div>
                            </table>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @else
                        @foreach ($fields as $item)
                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                <div class="d-flex">
                                    <thead>
                                        <tr class="text-dark">
                                            <th scope="col">{{ $item->name }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <input type="text" name="order[]" class="form-control" required>

                                    </tbody>

                                </div>
                            </table>
                        @endforeach
                    @endif
                </div>
                <input type="hidden" name="userid" value="{{ auth()->id() }}">
                <input type="hidden" name="application_id" value="{{ $id }}">
                <input type="submit" value="Submit" class="btn btn-primary pull-right">
            </form>
        </div>
    </div>
    <!-- Recent Sales End -->
@endsection
