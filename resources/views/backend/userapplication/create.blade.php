@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                {{-- <h6 class="mb-0">Application Create</h6> --}}

            </div>
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Application Create</h6>
                <form action="{{ route('application.store') }}" class="form-horizontal" enctype="multipart/form-data"
                    method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" aria-describedby="namehelp" required>
                        @error('name')
                            <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Status</label>
                        <select name="status" id="" class="form-control @error('status') is-invalid @enderror">
                            <option value="1">Active</option>
                            <option value="0">In Active</option>
                        </select>
                        @error('status')
                            <label id="status-error" class="error text-danger" for="status">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1"
                            class="form-label @error('description') is-invalid @enderror">Description</label>
                        <textarea class="form-control" name="description" id="editor1"></textarea>

                        @error('description')
                            <label id="description-error" class="error text-danger"
                                for="description">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="exampleInputEmail1"
                            class="form-label @error('attachments') is-invalid @enderror">Attachments</label>
                        <input type="file" class="form-control @error('attachments') is-invalid @enderror"
                            id="description" name="attachments[]" multiple>
                        @error('attachments')
                            <label id="attachments-error" class="error text-danger"
                                for="attachments">{{ $message }}</label>
                        @enderror
                    </div> --}}

                    <input type="hidden" value="{{ auth()->id() }}" name="user_id">

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor1');
    </script>
@endsection
