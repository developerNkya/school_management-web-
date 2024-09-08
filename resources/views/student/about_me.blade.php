@include('student.partial_headers')
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-lg-6 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Student Information</h4>
                    <p class="card-description"> Below Include student's information
                    </p>
                    <div class="table-responsive pt-3">
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td> Full Name </td>
                            <td>{{$student_info->first_name.' '.$student_info->last_name}} </td>
                          </tr>
                          <tr>
                            <td> Registration No </td>
                            <td>{{$student_info->registration_no}} </td>
                          </tr>
                          <tr>
                            <td> Date of Birth </td>
                            <td>{{$student_info->date_of_birth}} </td>
                          </tr>
                          <tr>
                            <td>Gender </td>
                            <td>{{$student_info->gender}} </td>
                          </tr>
                          <tr>
                            <td> Blood Group </td>
                            <td>{{$student_info->blood_group}} </td>
                          </tr>
                          <tr>
                            <td>Nationality</td>
                            <td>{{$student_info->nationality}} </td>
                          </tr>
                          <tr>
                            <td>Class</td>
                            <td>{{$student_info->SchoolClass->name}} </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Parent Information</h4>
                    <p class="card-description"> Below Include parent's Information
                    </p>
                    <div class="table-responsive pt-3">
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td> Full Name </td>
                            <td>{{$student_info->parent_first_name.' '.$student_info->parent_last_name}} </td>
                          </tr>
                          <tr>
                            <td>Phone Number</td>
                            <td>{{$student_info->parent_phone}} </td>
                          </tr>
                          <tr>
                            <td>Email</td>
                            <td>{{$student_info->parent_email}} </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          @include('helpers.copyright')
          <!-- partial -->
        </div>
@include('helpers.partials_footers')