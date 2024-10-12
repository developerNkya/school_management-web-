<div class="form-modal" id="password-manager" style="display: none;">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Update Password</h4>
                        <form class="forms-sample" method="POST" action="/passwords/alter-password">
                            @csrf
                            <input type="hidden" id="user_id" name="user_id">
                            <input type="hidden" id="page" name="page" value="">
                            <div class="form-group">
                                <label for="fullName">Full Name</label>
                                <input type="text" class="form-control read-only-grey" id="fullName" readonly>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="password" placeholder="Enter new password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="repeated" placeholder="Confirm new password" required>
                            </div>
                            <div class="submit-container">
                                <button type="submit" class="btn btn-primary">Change</button>
                                <button type="button" class="btn btn-light" onclick="disableModal()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
