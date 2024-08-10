@include('helpers.partials_headers')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="/#overview"
                                    role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel"
                            aria-labelledby="overview">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                        {{-- <div>
                              <p class="statistics-title">Newly Schoo</p>
                              <h3 class="rate-percentage">68.8</h3>
                              <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                            </div> --}}
                                        {{-- <div class="d-none d-md-block">
                              <p class="statistics-title">Avg. Time on Site</p>
                              <h3 class="rate-percentage">2m:35s</h3>
                              <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                            </div> --}}
                                        {{-- <div class="d-none d-md-block">
                              <p class="statistics-title">New Sessions</p>
                              <h3 class="rate-percentage">68.8</h3>
                              <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                            </div>
                            <div class="d-none d-md-block">
                              <p class="statistics-title">Avg. Time on Site</p>
                              <h3 class="rate-percentage">2m:35s</h3>
                              <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                            </div> --}}
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Add New School</h4>
                                            @include('helpers.message_handler')
                                            <form class="forms-sample material-form bordered" method="POST"
                                                action="{{ url('/super_admin/add-school') }}">
                                                @csrf

                                                <div class="form-group">
                                                    <input type="text" required="required" name="school_name"
                                                        id="school_name" />
                                                    <label for="school_name" class="control-label">School Name</label>
                                                    <i class="bar"></i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="text" required="required" name="school_location"
                                                        id="school_location" />
                                                    <label for="school_location" class="control-label">Location</label>
                                                    <i class="bar"></i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="text" required="required" name="owner_name"
                                                        id="owner_name" />
                                                    <label for="owner_name" class="control-label">Owner Name</label>
                                                    <i class="bar"></i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="text" required="required" name="owner_phone_no"
                                                        id="owner_phone_no" />
                                                    <label for="owner_phone_no" class="control-label">Owner Phone
                                                        No</label>
                                                    <i class="bar"></i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="email" required="required" name="owner_email"
                                                        id="owner_email" />
                                                    <label for="owner_email" class="control-label">Owner Email</label>
                                                    <i class="bar"></i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="password" required="required" name="new_password"
                                                        id="new_password" />
                                                    <label for="new_password" class="control-label">New Password</label>
                                                    <i class="bar"></i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="password" required="required" name="repeat_password"
                                                        id="repeat_password" />
                                                    <label for="repeat_password" class="control-label">Repeat
                                                        Password</label>
                                                    <i class="bar"></i>
                                                </div>

                                                <div class="button-container">
                                                    <button type="submit"
                                                        class="button btn btn-primary"><span>Submit</span></button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a
                    href="/https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
                BootstrapDash.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
                reserved.</span>
        </div>
    </footer>
    <!-- partial -->
</div>
@include('helpers.partials_footers')
