@extends('workflows::layouts.workflow_app')
@section('content')
<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
  <div class="bg-light text-start rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      {{-- <h6 class="mb-0">Application Create</h6> --}}

    </div>
    {{-- {{ dd(Session::get('genral'), Session::get('field')) }} --}}

    <div class="bg-light rounded h-100 p-4">
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-4">Transition</h6>
            <button type="button" class="btn btn-danger">
              <a href="{{route('workflow.show', $application->id)}}" style="color:aliceblue">
                <- back</a>
            </button>
          </div>

          <form action="{{route('updatecontent.save')}}" class="form-horizontal" enctype="multipart/form-data" method="post">
            @csrf

            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Name</label>
              <input type="text" class="form-control" name="name" value="{{$content->name}}">

            </div>


            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Value</label>
                <input type="text" class="form-control" name="value" value="{{$content->value}}" placeholder="Value">
              </div>


            <input type="hidden" value="{{ auth()->id() }}" name="userid">
            <input type="hidden" value="{{ $task->id }}" name="taskid">

            <button type="submit" class="btn btn-primary">Submit</button>
          </form>

        </div>
      </div>

    </div>


  </div>
</div>


<!-- Recent Sales End -->



{{-- <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor1');
    </script> --}}
@endsection