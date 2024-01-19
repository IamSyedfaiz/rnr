@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">User Edit</h6>
                <a href="{{route('users.index')}}"><button class="btn btn-danger"><-back</button></a> 
            </div>
            <div class="bg-light rounded h-100 p-4">
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" aria-describedby="namehelp" value="{{ $user->name }}" required>
                        @error('name')
                            <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname"
                            id="lastname" aria-describedby="lastnamehelp" value="{{ $user->lastname }}" required>
                        @error('lastname')
                            <label id="lastname-error" class="error text-danger" for="lastname">{{ $message }}</label>
                        @enderror
                        <div id="lastnamehelp" class="form-text">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">User ID</label>
                        <input type="text" class="form-control @error('custom_userid') is-invalid @enderror"
                            name="custom_userid" id="custom_userid" aria-describedby="custom_useridhelp"
                            value="{{ $user->custom_userid }}" required>
                        @error('custom_userid')
                            <label id="custom_userid-error" class="error text-danger"
                                for="custom_userid">{{ $message }}</label>
                        @enderror
                        <div id="custom_useridhelp" class="form-text">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" aria-describedby="namehelp" value="{{ $user->email }}">
                        @error('email')
                            <label id="email-error" class="error text-danger" for="email">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label @error('mobile_no') is-invalid @enderror">Mobile
                            No</label>
                        <input type="text" class="form-control" id="name" name="mobile_no"
                            aria-describedby="namehelp" value="{{ $user->mobile_no }}">
                        @error('mobile_no')
                            <label id="mobile_no-error" class="error text-danger" for="mobile_no">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>

                    <div class="mb-3">

                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"
                                    class="form-label @error('password') is-invalid @enderror">Password</label>
                                <input type="password" class="form-control" id="name" name="password"
                                    aria-describedby="namehelp">
                                @error('password')
                                    <label id="password-error" class="error text-danger"
                                        for="password">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="exampleInputEmail1"
                                    class="form-label @error('repassword') is-invalid @enderror">Re-Password</label>
                                <input type="password" class="form-control" id="name" name="password_confirmation"
                                    aria-describedby="namehelp">
                                @error('repassword')
                                    <label id="repassword-error" class="error text-danger"
                                        for="repassword">{{ $message }}</label>
                                @enderror
                                <div id="namehelp" class="form-text">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mb-3 row">

                        <div class="col-md-6">
                            <label for="exampleInputEmail1"
                                class="form-label @error('department') is-invalid @enderror">Groups</label>
                            
                                <div class="col-md-4 showaddbtn">
                                    <button type="button" class="btn btn-primary text-end"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        data-bs-whatever="@mdo">Add Group</button>
                                </div>
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">New Group</h5>
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
                                                                <select name="group_id[]" id="" class="form-control" multiple>
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
                                {{-- {{dd($groupids, $groups, $groupids != null)}} --}}
                            <select id="" class="form-control" multiple>
                                @if ($groupids != null)
                                    @foreach ($groups as $item)
                                        @if (in_array($item->id, $groupids))
                                        <option value="">{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            @error('group_id')
                                <label id="group_id-error" class="error text-danger"
                                    for="group_id">{{ $message }}</label>
                            @enderror
                            <div id="namehelp" class="form-text">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="exampleInputEmail1"
                                class="form-label @error('department') is-invalid @enderror">Status</label>
                            <select name="status" id="" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                            @error('group_id')
                                <label id="group_id-error" class="error text-danger"
                                    for="group_id">{{ $message }}</label>
                            @enderror
                            <div id="namehelp" class="form-text">
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12 mb-2">
                        <label for="remarks" class="form-label"><strong>SHHkey/Token/Certificate</strong></label>
                        <textarea name="remarks" id="" cols="30" rows="4" class="form-control">{{ $user->remarks }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->

    <script>
        var status = "{{ $user->status }}";
        var statuselement = document.getElementsByName('status')[0];

        for (let index = 0; index < statuselement.length; index++) {
            if (statuselement[index].value == status) {
                statuselement[index].selected = true;
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
                var userselect = document.getElementsByName('group_id[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('group_id[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }
    </script>
@endsection
