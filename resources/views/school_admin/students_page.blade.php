@include('school_admin.partial_headers')
<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Students</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Student +</button>
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
                                  @foreach($students as $student)
                                    <tr>
                                        <td>1</td>
                                        <td>{{$student->first_name.' '.$student->last_name}}</td>
                                        <td>{{$student->registration_no}}</td>
                                        <td>{{$student->class_id}}</td>
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

            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
                reserved.</span>
        </div>
    </footer>
    <!-- partial -->
</div>

<div class="form-modal" id="form-modal" style="display: none">
  @include('helpers.message_handler')
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
          <form id="studentForm"   class="forms-sample" method="POST" action="{{ route('add_student') }}" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
        
                <div class="step active">
                    <p class="text-center mb-4">Step 1: Student Information</p>
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input name="first_name" class="form-control" id="firstName" placeholder="e.g. John" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input name="last_name" class="form-control" id="lastName" placeholder="e.g. Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="registrationNo">Registration No</label>
                        <input name="registration_no" class="form-control" id="registrationNo" placeholder="e.g. 123456" required>
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
                        <label for="bloodGroup">Blood Group</label>
                        <select name="blood_group" class="form-control" id="bloodGroup" required>
                            <option value="">Select Blood Group</option>
                            <option value="O-">O-</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="O+">O+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input name="nationality" class="form-control" id="nationality" placeholder="e.g. American" required>
                    </div>
                    <div class="form-group">
                        <label for="city">Region</label>
                        <input name="city" class="form-control" id="city" placeholder="e.g. Springfield" required>
                    </div>
                    <div class="form-group">
                        <label for="passportPhoto">Valid Passport Size Photo</label>
                        <input type="file" name="passport_photo" class="form-control" id="passportPhoto" required>
                    </div>
                </div>
        
                <div class="step">
                    <p class="text-center mb-4">Step 2: Student Data</p>
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select name="class_id" class="form-control" id="class" required>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="form-group">
                        <label for="section">Section</label>
                        <select name="section_id" class="form-control" id="section" required>
                            @foreach($students as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="form-group">
                        <label for="parentFirstName">Parent First Name</label>
                        <input name="parent_first_name" class="form-control" id="parentFirstName" placeholder="e.g. Jane" required>
                    </div>
        
                    <div class="form-group">
                        <label for="parentLastName">Parent Last Name</label>
                        <input name="parent_last_name" class="form-control" id="parentLastName" placeholder="e.g. Doe" required>
                    </div>
        
                    <div class="form-group">
                        <label for="parentPhone">Parent Phone No</label>
                        <input name="parent_phone" class="form-control" id="parentPhone" placeholder="e.g. 1234567890" required>
                    </div>
        
                    <div class="form-group">
                        <label for="parentEmail">Parent Email</label>
                        <input name="parent_email" class="form-control" id="parentEmail" placeholder="e.g. parent@example.com" required>
                    </div>
                
                </div>
        
                <div class="form-footer d-flex">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)" style="margin-left:5%">Next</button>
                </div>
            </div>
        </form>
        
          
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

    // multi form::
    let currentTab = 0;
        showTab(currentTab);

        function showTab(n) {
            let x = document.getElementsByClassName("step");
            x[n].style.display = "block";
            let progress = (n / (x.length - 1)) * 100;
            document.querySelector(".progress-bar").style.width = progress + "%";
            document.querySelector(".progress-bar").setAttribute("aria-valuenow", progress);
            document.getElementById("prevBtn").style.display = n == 0 ? "none" : "inline";
            document.getElementById("nextBtn").innerHTML = n == x.length - 1 ? "Submit" : "Next";
        }

        function nextPrev(n) {
            let x = document.getElementsByClassName("step");
            if (n == 1 && !validateForm()) return false;
            x[currentTab].style.display = "none";
            currentTab += n;
            if (currentTab >= x.length) {
              document.getElementById("studentForm").submit()
                //post the data 
                // resetForm();
                // return false;
            }
            showTab(currentTab);
        }

        function validateForm() {
            let valid = true;
            let x = document.getElementsByClassName("step");
            let y = x[currentTab].getElementsByTagName("input");
            for (var i = 0; i < y.length; i++) {
                if (y[i].value == "") {
                    y[i].className += " invalid";
                    valid = false;
                }
            }
            return valid;
        }

        function resetForm() {
            let x = document.getElementsByClassName("step");
            for (var i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            let inputs = document.querySelectorAll("input");
            inputs.forEach(input => {
                input.value = "";
                input.className = "";
            });
            currentTab = 0;
            showTab(currentTab);
            document.querySelector(".progress-bar").style.width = "0%";
            document.querySelector(".progress-bar").setAttribute("aria-valuenow", 0);
            document.getElementById("prevBtn").style.display = "none";
        }
   
</script>
@include('school_admin.partial_footers')