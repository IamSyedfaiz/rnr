@extends('backend.layouts.app')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-start rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Field Create</h6>
                <a href="{{route('application.edit', $application->id)}}"><button class="btn btn-danger"><-back</button></a> 
            </div>
            <div class="bg-light rounded h-100 p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-4">Field </h6>

                    </div>

                    {{-- <div class="col-md-6 text-end">
                        <button class="btn btn-primary">
                            <a href="">
                                <-return back</a> </button>
                    </div> --}}

                </div>

                <form action="{{ route('field.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label"> <strong>Name</strong> </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" aria-describedby="namehelp">
                        @error('name')
                            <label id="name-error" class="error text-danger" for="name">{{ $message }}</label>
                        @enderror
                        <div id="namehelp" class="form-text">
                        </div>
                    </div>
                    <div class="class mb-3">
                        <label for="exampleInputEmail1" class="form-label"> <strong>Type</strong> </label>
                        <select name="type" id="" onclick="fieldtype(this.value);"
                            class="form-control @error('type') is-invalid @enderror" required>
                            <option value="date">Date</option>
                            <option value="attachment">Attachment</option>
                            <option value="images">Images</option>
                            <option value="ip_address">IP Address</option>
                            <option value="number">Numeric</option>
                            <option value="text">Text</option>
                            <option value="value_list">Value List</option>
                            <option value="user_group_list">User Group List</option>
                        </select>
                    </div>
                    <div class="class mb-3 row">

                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="form-label"> <strong>Status</strong> </label>
                            <select name="status" id=""
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>

                        </div>

                    </div>

                    <div class="mb-3">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Options
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">

                                            <input type="checkbox" id="" name="requiredfield" value="1">
                                            <label for=""> Make it required field</label><br>

                                            <input type="checkbox" id="" name="requireuniquevalue" value="1">
                                            <label for=""> Make it unique field</label><br>
                                
                                            <input type="checkbox" id="" name="keyfield" value="1">
                                            <label for=""> Make it key field</label><br>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Access
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">


                                        <input type="radio" name="access" value="public" required>
                                        <label for="">Public</label><br>

                                        <div class="row mb-2">
                                            <div class="col-md-10">
                                                <input type="radio" onclick="showgroupsname()" name="access"
                                                    value="private" required>
                                                <label for="">Private</label><br>
                                            </div>

                                        </div>
                                        <div class="col-md-12 d-none showaddbtn" style="width: 100%">

                                            <div class="d-flex justify-content-between">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-primary text-end"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalusers"
                                                    data-bs-whatever="@mdo">Add Users</button>

                                                </div>

                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-primary text-end"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        data-bs-whatever="@mdo">Add Groups</button>

                                                </div>

                                            </div>
                                        </div>

                                        {{-- <div class="d-none groupsname row">
                                            <div class="col-md-6">
                                                <select id="" class="form-control " multiple disabled>
                                                    @foreach ($selectedusers as $item)
                                                        <option selected>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select id="" class="form-control " multiple disabled>
                                                    @foreach ($selectedgroups as $item)
                                                        <option selected>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}



                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Type Configurations
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body changeconfigrations">

                                    </div>
                                    <div class="showvaluelist d-none">

                                            <div class="mb-3 valuelistvalue">
                                                <input type="text" name="valuelistvalue[]" class="form-control mb-2"
                                                    placeholder="Enter ValueList value">
                                            </div>
                                            
                                        <input type="button" class="btn btn-primary mb-2" onclick="addvaluelist()"
                                            value="add more">
                                        <input type="button" class="btn btn-danger mb-2" onclick="removevaluelist()"
                                            value="remove">

                                    </div>
                                </div>
                            </div>
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
                                                    <select name="users[]" id="" class="form-control" multiple>
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

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                    <select name="groups[]" id="" class="form-control" multiple>
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
                    <input type="hidden" value="{{ $application->id }}" name="application_id">

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->

    <script>

        function showgroupsname() {
            // var groupname = document.getElementsByClassName('groupsname')[0];
            // groupname.className = 'd-block groupsname';

            var showaddbtn = document.getElementsByClassName('showaddbtn')[0];
            showaddbtn.className = 'col-md-2 d-block showaddbtn';
        }

        function addvaluelist() {
            var listlength = document.getElementsByClassName('valuelistvalue');
            var valuelistvalue = document.getElementsByClassName('valuelistvalue')[listlength.length - 1];

            var input = document.createElement('input');
            input.name = 'valuelistvalue[]';
            input.className = 'form-control mb-2';
            input.required = true;
            input.placeholder = 'Enter ValueList value';

            valuelistvalue.appendChild(input);
        }

        function removevaluelist() {
            var valuelistinput = document.getElementsByName('valuelistvalue[]');
            if (valuelistinput.length > 1) {
                valuelistinput[valuelistinput.length - 1].remove();

            }
        }

        function fieldtype(value) {

            if (value == 'date') {
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";
                // console.log(accordion);
                var div = document.createElement('div');
                div.className = 'form-check';

                var input = document.createElement("input");
                input.type = "radio";
                input.name = "datetype";
                input.value = "date";
                input.required = true;
                input.className = "form-check-input";
                input.id = "form-check-input";

                var label = document.createElement('label');
                label.className = 'form-check-label';
                label.for = 'flexCheckDefault';
                label.innerText = 'Date';

                div.appendChild(input);
                div.appendChild(label);

                var div1 = document.createElement('div');
                div1.className = 'form-check';

                var input1 = document.createElement("input");
                input1.type = "radio";
                input1.name = "datetype";
                input1.required = true;
                input1.value = "datetime-local";
                input1.className = "form-check-input";

                var label1 = document.createElement('label');
                label1.className = 'form-check-label';
                label1.for = 'flexCheckDefault';
                label1.innerText = 'Date Time';

                div1.appendChild(input1);
                div1.appendChild(label1);

                accordion.appendChild(div);
                accordion.appendChild(div1);
                // console.log(input);

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");

                var accordian3 = document.getElementById('collapseThree');
                accordian3.classList.add("show");
            }

            if (value == 'attachment') {
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";
                var div = document.createElement('div');
                div.className = 'form-check d-flex justify-content-between';

                var select = document.createElement("select");
                select.className = 'form-select';
                select.name = 'attachmenttype';
                select.required = true;
                var array = ["pdf", "png", "jpg", "xls"];

                showoption = document.createElement('option');
                showoption.value = "";
                showoption.innerText = 'Select Extension';

                select.appendChild(showoption);

                for (var i = 0; i < array.length; i++) {
                    var option = document.createElement("option");
                    option.value = array[i];
                    option.text = array[i];
                    select.appendChild(option);
                }

                div.appendChild(select);

                var select1 = document.createElement("select");
                select1.className = 'form-select';
                select1.name = 'attachmentsize';
                select1.required = true;
                var array1 = ["1", "2", "5", "10"];

                showoption1 = document.createElement('option');
                showoption1.value = "";
                showoption1.innerText = 'Select Size';

                select1.appendChild(showoption1);

                for (var i = 0; i < array1.length; i++) {
                    var option1 = document.createElement("option");
                    option1.value = array1[i];
                    option1.text = array1[i];
                    select1.appendChild(option1);
                }

                div.appendChild(select1);
                accordion.appendChild(div);

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");

                var accordian3 = document.getElementById('collapseThree');
                accordian3.classList.add("show");
            }

            if (value == 'value_list') {
                document.getElementsByClassName('showvaluelist')[0].className = 'showvaluelist d-block m-5';
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";
                // console.log(accordion);
                var div = document.createElement('div');
                div.className = 'form-check';

                var input = document.createElement("input");
                input.type = "radio";
                input.name = "valuelisttype";
                input.value = "dropdown";
                input.className = "form-check-input";
                input.id = "form-check-input";

                var label = document.createElement('label');
                label.className = 'form-check-label';
                label.for = 'flexCheckDefault';
                label.innerText = 'Dropdown';

                div.appendChild(input);
                div.appendChild(label);

                //
                var div1 = document.createElement('div');
                div1.className = 'form-check';

                var input1 = document.createElement("input");
                input1.type = "radio";
                input1.name = "valuelisttype";
                input1.value = "radio";
                input1.className = "form-check-input";

                var label1 = document.createElement('label');
                label1.className = 'form-check-label';
                label1.for = 'flexCheckDefault';
                label1.innerText = 'Radio Button';

                div1.appendChild(input1);
                div1.appendChild(label1);

                //

                var div2 = document.createElement('div');
                div2.className = 'form-check';

                var input2 = document.createElement("input");
                input2.type = "radio";
                input2.name = "valuelisttype";
                input2.value = "checkinput";
                input2.className = "form-check-input";

                var label2 = document.createElement('label');
                label2.className = 'form-check-label';
                label2.for = 'flexCheckDefault';
                label2.innerText = 'Check Box';

                div2.appendChild(input2);
                div2.appendChild(label2);

                //

                var div3 = document.createElement('div');
                div3.className = 'form-check';

                var input3 = document.createElement("input");
                input3.type = "radio";
                input3.name = "valuelisttype";
                input3.value = "listbox";
                input3.className = "form-check-input";

                var label3 = document.createElement('label');
                label3.className = 'form-check-label';
                label3.for = 'flexCheckDefault';
                label3.innerText = 'List Box';

                div3.appendChild(input3);
                div3.appendChild(label3);
                //

                var div4 = document.createElement('div');
                div4.className = 'form-check';

                var input4 = document.createElement("input");
                input4.type = "radio";
                input4.name = "valuelisttype";
                input4.value = "valuepopup";
                input4.className = "form-check-input";

                var label4 = document.createElement('label');
                label4.className = 'form-check-label';
                label4.for = 'flexCheckDefault';
                label4.innerText = 'Values Popup';

                div4.appendChild(input4);
                div4.appendChild(label4);
                //

                accordion.appendChild(div);
                accordion.appendChild(div1);
                accordion.appendChild(div2);
                accordion.appendChild(div3);
                accordion.appendChild(div4);
                // console.log(input);

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");

                var accordian3 = document.getElementById('collapseThree');
                accordian3.classList.add("show");
            }

            if (value == 'user_group_list') {
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");

            }

            if (value == 'images') {
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");
            }

            if (value == 'ip_address') {
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");
            }

            if (value == 'number') {
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");
            }

            if (value == 'text') {
                var accordion = document.getElementsByClassName('changeconfigrations')[0];
                accordion.innerText = "";

                var accordian = document.getElementById('collapseOne');
                accordian.classList.add("show");

                var accordian2 = document.getElementById('collapseTwo');
                accordian2.classList.add("show");
            }

        }

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
                var userselect = document.getElementsByName('groups[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('groups[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }

        function dragdrop1(value, name) {
            // console.log(value);
            if (document.getElementById(name).checked) {
                var userselect = document.getElementsByName('users[]')[0];
                var option = document.createElement('option');
                option.value = value;
                option.id = value;
                option.innerText = name;
                option.selected = true;
                userselect.appendChild(option);
            } else {
                var userselect = document.getElementsByName('users[]')[0];
                var removeoption = document.getElementById(value);
                userselect.removeChild(removeoption);
            }
        }


    </script>
@endsection
