
<?php
$value = request()->session()->get('user_name', '');  
$role = request()->session()->get('role_id', '');  
?>

@include($role !== 3 ? 'school_admin.partial_headers' : 'student.partial_headers')

<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Change Password</h4>
                        @include('helpers.message_handler')
                        <form class="forms-sample" method="POST" action="/passwords/alter-password">
                            <div class="form-group">
                                <label for="exampleInputUsername1">New Password</label>
                                <input type="password" name="password" class="form-control" id="123"
                                    placeholder="eg. 123">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputUsername1">Repeat Password</label>
                                <input type="password"  name="repeated" class="form-control" id="123"
                                    placeholder="eg. 123">
                            </div>
                            <div class="submit-container" style="margin-top: 10%">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>

@include($role !== 3 ? 'school_admin.partial_footers' : 'helpers.partials_footers')
