@extends('backend.layouts.app')
@section('content')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <div class="rounded h-100">
                    <form action="{{ route('store.report') }}" class="m-n2" method="POST">
                        @csrf
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true"
                                        aria-controls="flush-collapseOne">
                                        Applications: Save Report
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <button type="submit" class="btn btn-primary m-2 fw-bold">
                                            save
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mt-2">
                                <h2 class="accordion-header" id="flush-headingtwo">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="true"
                                        aria-controls="flush-collapsetwo">
                                        Report Information
                                    </button>
                                </h2>
                                <div id="flush-collapsetwo" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingtwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <form action="">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">Name:</label>
                                                        <input type="text" class="form-control" placeholder=""
                                                            aria-label="Username" aria-describedby="basic-addon1" />
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">Alias:</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">Type:Personal</label>

                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">ID:</label>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input-group mb-3">
                                                        <label for="name"
                                                            class="col-form-label fw-bold mx-2">Description</label>
                                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name" class="col-form-label fw-bold mx-2">Created
                                                            By:</label>

                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <label for="name" class="col-form-label fw-bold mx-2">Last
                                                            Update:</label>

                                                    </div>
                                                </div>
                                            </div>

                                    </div>


                                </div>
                            </div>
                            <div cflass="accordion-item mt-2">
                                <h2 class="accordion-header" id="flush-headingtwo">
                                    <button class="accordion-button text-dark fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapsethree" aria-expanded="true"
                                        aria-controls="flush-collapsethree">
                                        Report Type

                                    </button>
                                </h2>
                                <div id="flush-collapsethree" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingthree" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>
                                                    <i class="bi bi-exclamation-circle-fill text-primary"></i> Choose the
                                                    type of report to
                                                    save. If the report is saved as Global, select the appropriate
                                                    permissions.

                                                </h6>
                                            </div>
                                            <div class="row g-3 align-items-center">
                                                <div class="col-auto">
                                                    <label for="permissions"
                                                        class="col-form-label fw-bold">Permissions</label>
                                                </div>
                                                <div class="col-auto">
                                                    <input class="form-check-input" type="radio"
                                                        name="flexRadioDefault" id="flexRadioDefault1">
                                                    <label class="form-check-label text-dark fw-bold"
                                                        for="flexRadioDefault1">
                                                        Personal Report
                                                    </label>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="passwordHelpInline" class="form-text">
                                                        if a report is designated as "personal", it is accessible only by
                                                        you
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row  align-items-center">
                                                <div class="col-1 mx-2">
                                                </div>
                                                <div class="col-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="flexRadioDefault" id="flexRadioDefault1">
                                                    <label class="form-check-label text-dark fw-bold"
                                                        for="flexRadioDefault1">
                                                        Global Report
                                                    </label>
                                                </div>
                                                <div class="col-8">
                                                    <span id="passwordHelpInline" class="form-text">
                                                        If a report is designated as "global" you can grant access rights to
                                                        specific users
                                                        and/or groups. By default, global reports are made available to all
                                                        application users
                                                        through the built-in "Everyone" group.
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-center">
                                                <div class="col-auto">
                                                    <label for="permissions" class="col-form-label fw-bold">iView
                                                        Caching</label>
                                                </div>
                                                <div class="col-auto">
                                                    <input class="form-check-input" type="radio"
                                                        name="flexRadioDefault" id="flexRadioDefault1">
                                                    <label class="form-check-label text-dark fw-bold"
                                                        for="flexRadioDefault1">
                                                        Enable iView Caching
                                                    </label>
                                                </div>

                                            </div>
                                            <div class="row  align-items-center">
                                                <div class="col-12 mx-2">
                                                    <div class="row g-3 align-items-center">
                                                        <div class="col-auto">
                                                            <label for="inputPassword6" class="col-form-label">cache
                                                                Duration</label>
                                                        </div>
                                                        <div class="col-auto d-flex mx-2">
                                                            <input type="password" id="inputPassword6"
                                                                class="form-control" placeholder="30">
                                                            <select id="disabledSelect" class="form-select">
                                                                <option>Minutes</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-auto">
                                                            <span id="passwordHelpInline" class="form-text">
                                                                Must be 8-20 characters long.
                                                            </span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-outline-primary">Reset
                                                                To Default</button>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row g-3 align-items-center">
                                                <div class="col-auto">
                                                    <label for="permissions" class="col-form-label fw-bold">Refresh
                                                        Rate</label>
                                                </div>
                                                <div class="col-auto">
                                                    <select id="disabledSelect" class="form-select">
                                                        <option>Do Not Refresh</option>
                                                        <option>Refresh</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <h6 class="text-danger">*Required</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection
