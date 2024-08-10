@include('school_admin.partial_headers')
<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Classes</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Class +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Class Name </th>
                                        <th> Total Students </th>
                                        <th> Total Teachers </th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($classes as $class)
                                    <tr>
                                        <td>1</td>
                                        <td>{{$class->name}}</td>
                                        <td>{{$class->name}}</td>
                                        <td>{{$class->name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a
                    href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
                BootstrapDash.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
                reserved.</span>
        </div>
    </footer>
    <!-- partial -->
</div>

<div class="form-modal" id="form-modal" style="display: none">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Add Student</h4>
              <form class="forms-sample" method="POST" action="{{ url('school_admin/add_student') }}">
                @csrf <!-- Include this to protect against CSRF attacks -->
                <div class="form-group">
                  <label for="firstName">First Name</label>
                  <input name="first_name" class="form-control" id="firstName" placeholder="e.g. John" required>
                </div>
                <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input name="last_name" class="form-control" id="lastName" placeholder="e.g. Doe" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Registration No</label>
                    <input name="registration_no" class="form-control" id="lastName" placeholder="e.g. Doe" required>
                  </div>
                <div class="form-group">
                  <label for="dob">Date of Birth</label>
                  <input type="date" name="date_of_birth" class="form-control" id="dob" required>
                </div>
                <div class="form-group">
                  <label for="gender">Gender</label>
                  <select name="gender" class="form-control" id="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="nationality">Nationality</label>
                  <input name="nationality" class="form-control" id="nationality" placeholder="e.g. American" required>
                </div>
                <div class="form-group">
                  <label for="address">Address</label>
                  <input name="address" class="form-control" id="address" placeholder="e.g. 123 Main St, Apt 4B" required>
                </div>
                <div class="form-group">
                  <label for="city">City</label>
                  <input name="city" class="form-control" id="city" placeholder="e.g. Springfield" required>
                </div>
                <div class="form-group">
                  <label for="phoneNumber">Phone Number</label>
                  <input name="phone_number" class="form-control" id="phoneNumber" placeholder="e.g. (555) 123-4567" required>
                </div>
                <div class="form-group">
                    <label for="class_name">Class</label>
                    <select name="class-name" class="form-control" id="class_name" required>
                        @foreach($classes as $class)
                      <option value="{{$class->id}}">{{$class->name}}</option>
                      @endforeach
                    </select>
                  </div>
                <div class="form-group">
                  <label for="medicalConditions">Medical Conditions</label>
                  <textarea name="medical_conditions" class="form-control" id="medicalConditions" rows="3" placeholder="e.g. Allergies, asthma"></textarea>
                </div>
                <div class="form-group">
                  <label for="emergencyContact">Emergency Contact</label>
                  <input name="emergency_contact" class="form-control" id="emergencyContact" placeholder="e.g. Jane Doe - (555) 987-6543" required>
                </div>
                <div class="form-group">
                  <label for="previousSchool">Previous School</label>
                  <input name="previous_school" class="form-control" id="previousSchool" placeholder="e.g. Springfield Elementary">
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button type="button" class="btn btn-light" onclick="disableModal()">Cancel</button>
              </form>
            </div>
          </div>
          
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->
  <!-- partial:../../partials/_footer.html -->
  <footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
      <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
      <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
    </div>
  </footer>
  <!-- partial -->
</div>


<script>
    function showModal() {
        // alert("Welcome to the javaTpoint.com");
        document.getElementById( 'form-modal' ).style.display = 'block';
        document.getElementById( 'main-panel' ).style.display = 'none';
    }

    function disableModal() {
        // alert("Welcome to the javaTpoint.com");
        document.getElementById( 'form-modal' ).style.display = 'none';
        document.getElementById( 'main-panel' ).style.display = 'block';
        event.preventDefault();
    }

   
</script>
@include('school_admin.partial_footers')
