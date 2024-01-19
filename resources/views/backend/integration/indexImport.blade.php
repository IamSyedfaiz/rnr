@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Select Applications</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-striped  text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-white"  style="background-color: #009CFF;">
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('show.url', $item->id) }}"> {{ $item->name }}</a>
                                </td>
                                <td>
                                    @if ($item->status == 1)
                                        Active
                                    @else
                                        In-Active
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
