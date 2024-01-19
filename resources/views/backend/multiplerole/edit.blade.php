@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">

            </div>

            <div class="bg-light rounded h-100 p-4">

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-4"> {{ strtoupper($application->name) }} Role </h6>
                            </div>
                            <div>
                                <a href="{{ route('role.index') }}">
                                    <button type="button" class="btn btn-danger">
                                        <-back </button>
                                </a>
                            </div>

                        </div>

                        <form action="{{ route('role.store') }}" class="form-horizontal" method="post">
                            @csrf

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" aria-describedby="namehelp" required>
                                @error('name')
                                    <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="checkbox" id="" name="import" value="1">
                                <label for=""> Import</label>
                            </div>

                            <div class="mb-3">
                                <input type="checkbox" id="" name="create" value="1">
                                <label for=""> Create</label>
                            </div>

                            <div class="mb-3">
                                <input type="checkbox" id="" name="read" value="1">
                                <label for=""> Read</label>
                            </div>

                            <div class="mb-3">
                                <input type="checkbox" id="" name="update" value="1">
                                <label for=""> Update</label>
                            </div>

                            <div class="mb-3">
                                <input type="checkbox" id="" name="delete" value="1">
                                <label for=""> Delete</label>
                            </div>

                            <div>

                                <div class="mb-3">
                                    {{-- <label for="exampleInputEmail1" class="form-label">{{ strtoupper($item->name) }}</label> --}}
                                    <div class="usergrouplist">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="col-md-2 addusers">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                    data-bs-whatever="@mdo">Add Users</button>
                                            </div>

                                            <div class="col-md-2 addgroups">
                                                <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalgroups"
                                                    data-bs-whatever="@mdo">Add Groups</button>
                                            </div>

                                        </div>

                                        <div class="modal fade" id="exampleModalusers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3 text-start">
                                                                        <label for="filter">Users&nbsp;</label><input id="filter" type="text"
                                                                            class="filter form-control" placeholder="Search Users">
                                                                        <br />
                            
                                                                        <div id="mdi" style="max-height: 10%; overflow:auto;">
                                                                            @foreach ($users as $item)
                                                                                <span><input class="talents_idmd-checkbox"
                                                                                        onchange="dragdrop1(this.value, this.id);" type="checkbox"
                                                                                        id="{{ $item->name . ' ' . $item->lastname }}"
                                                                                        value="{{ $item->id }}">{{ $item->name . ' ' . $item->lastname }}</span><br>
                                                                            @endforeach
                                                                        </div>
                            
                            
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="users">Selected Users</label>
                                                                        <select name="user_list[]" id="" class="form-control" multiple>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                            
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                                        </div>
                                                    </div>
                                             
                                            </div>
                                        </div>

                                        <div class="modal fade" id="exampleModalgroups" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Group</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3 text-start">
                                                                        <label for="filter">Groups&nbsp;</label><input id="filter" type="text"
                                                                            class="filter form-control" placeholder="Search Groups">
                                                                        <br />
                            
                                                                        <div id="mdi" style="max-height: 10%; overflow:auto;">
                                                                            @foreach ($groups as $item)
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
                                                                        <label for="users">Selected Groups</label>
                                                                        <select name="group_list[]" id="" class="form-control" multiple>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                    
                            
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                                        </div>
                                                    </div>
                                             
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <input type="hidden" value="{{ auth()->id() }}" name="updated_by">
                            <input type="hidden" value="{{ $application->id }}" name="application_id">


                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>

            </div>


        </div>
    </div>


    <!-- Recent Sales End -->

    <script>
        // var status = "{{ $application->status }}";
        // var currentstatus = document.getElementsByName('status')[0];
        // for (let index = 0; index < currentstatus.length; index++) {
        //     if (currentstatus[index].value == status) {
        //         currentstatus[index].selected = true;
        //     }
        // }
        // var field = "{{ Session::get('field') }}";
        // if (field == 'active') {
        //     document.getElementById('pills-home-tab').className = 'nav-link';
        //     document.getElementById('pills-profile-tab').className = 'nav-link active';
        //     document.getElementById('pills-home').className = 'tab-pane fade';
        //     document.getElementById('pills-profile').className = 'tab-pane fade show active';
        // }

        //script for js

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
                var userselect = document.getElementsByName('group_list[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('group_list[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }

        function dragdrop1(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('user_list[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('user_list[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }
    </script>
@endsection
