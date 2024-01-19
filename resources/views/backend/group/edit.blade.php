@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                {{-- <h6 class="mb-0">Application Create</h6> --}}

            </div>

            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-4">Group Edit</h6>
                    <a href="{{ route('group.index') }}">
                        <button type="button" class="btn btn-danger"><-back</button>
                    </a>

                </div>
                <form action="{{ route('group.update', $group->id) }}" class="form-horizontal" method="post">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" aria-describedby="namehelp" value="{{ $group->name }}" required>
                        @error('name')
                            <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <label for="message-text" class="col-form-label fw-bold text-left form-label ">Users</label>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add Users</button>

                        </div>

                        <select id="" class="form-control " multiple disabled>
                            @foreach ($selectedusers as $item)
                                <option selected>
                                    {{ $item->name . ' (' . $item->email . ' )' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- for modal --}}
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-body">
        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3 text-start">
                                                    <label for="filter">Users&nbsp;</label><input id="filter" type="text"
                                                        class="filter form-control" placeholder="Search Username">
                                                    <br />
        
                                                    <div id="mdi" style="max-height: 10%; overflow:auto;">
                                                        @foreach ($users as $item)
                                                            <span><input class="talents_idmd-checkbox"
                                                                    onchange="dragdrop(this.value, this.id);" type="checkbox"
                                                                    id="{{ $item->name . ' ' . $item->lastname }}"
                                                                    value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                        @endforeach
                                                    </div>
        
        
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="users">Selected Users</label>
                                                    <select name="userids[]" id="" class="form-control" multiple>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
        
                                </div>
                        </div>
                    </div>
                    {{-- for modal --}}


                    <div class="mb-3">
                        <label for="exampleInputEmail1"
                            class="form-label @error('status') is-invalid @enderror">Status</label>
                        <select name="status" id="" class="form-control @error('status') is-invalid @enderror"
                            required>
                            <option value="1">Active</option>
                            <option value="0">In Active</option>
                        </select>
                        @error('status')
                            <label id="status-error" class="error text-danger" for="status">{{ $message }}</label>
                        @enderror
                    </div>

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
    <script>
        var status = "{{ $group->status }}";
        var currentstatus = document.getElementsByName('status')[0];
        for (let index = 0; index < currentstatus.length; index++) {
            if (currentstatus[index].value == status) {
                currentstatus[index].selected = true;
            }
        }

        const filterEl = document.querySelector('#filter');
        const els = Array.from(document.querySelectorAll('#mdi > span'));
        const labels = els.map(el => el.textContent);
        const handler = value => {
            const matching = labels.map((label, idx, arr) => label.toLowerCase().includes(value.toLowerCase()) ? idx :
                null).filter(el => el != null);

            els.forEach((el, idx) => {
                if (matching.includes(idx)) {
                    els[idx].style.display = 'block';
                } else {
                    els[idx].style.display = 'none';
                }
            });
        };

        filterEl.addEventListener('keyup', () => handler.call(null, filterEl.value));


        function dragdrop(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('userids[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('userids[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }
    </script>
@endsection
