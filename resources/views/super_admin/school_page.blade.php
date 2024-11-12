@include('helpers.partials_headers')
<div class="main-panel" id="main-panel">

    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Schools</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick="showModal()">Add School +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>School Name</th>
                                        <th>initial</th>
                                        <th>Manager</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schools as $index => $school)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $school->school_name }}</td>
                                            <td>{{ $school->initial }}</td>
                                            <td>{{ $school->owner->name }}</td>
                                            <td>{{ $school->owner->email }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php
                    $paginated = $schools;
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>

        </div>

    </div>
    @include('helpers.copyright')
</div>

<div class="main-panel" id="form-modal" style="display: none">
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
                                                    <input type="text" required="required" name="school_initial"
                                                        id="school_initial" />
                                                    <label for="owner_name" class="control-label">Initial</label>
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
                                                <div class="submit-container" style="margin-top: 3%">
                                                    <button type="submit" class="button btn-primary me-2">Submit</button>
                                                    <button type="button" class="button btn btn-light" onclick="disableModal()">Cancel</button>
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
<script>
    function showModal() {
        document.getElementById('form-modal').style.display = 'block';
        document.getElementById('main-panel').style.display = 'none';
    }

    function disableModal() {
        document.getElementById('form-modal').style.display = 'none';
        document.getElementById('main-panel').style.display = 'block';
        event.preventDefault();
    }
</script>
