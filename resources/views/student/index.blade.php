@include('student.partial_headers')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12">
        <div class="home-tab">
          <div class="d-sm-flex align-items-center justify-content-between border-bottom">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
              </li>
            </ul>
          </div>
          <div class="tab-content tab-content-basic">
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
              <div class="row">
                <div class="col-sm-6">
                  <div class="statistics-details d-flex align-items-center justify-content-between">
                    <div>
                      <p class="statistics-title">Class</p>
                      <h3 class="rate-percentage">{{$student_info->SchoolClass->name}}</h3>
                    </div>
                    <div>
                      <p class="statistics-title">Gender</p>
                      <h3 class="rate-percentage">{{$student_info->gender}}</h3>
                    </div>
                    <div>
                      <p class="statistics-title">Academic Year</p>
                      <h3 class="rate-percentage">N/A</h3>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-8 d-flex flex-column">
                  <div class="row flex-grow">
                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                      <div class="card card-rounded">
                        <div class="card-body">
                          <div class="d-sm-flex justify-content-between align-items-start">
                            <div>
                              <h4 class="card-title card-title-dash">Assignments</h4>
                              <p class="card-subtitle card-subtitle-dash">Below are some of your Assignments</p>
                            </div>
                            <div>
                              <a href="{{ url('/school_admin/view-suggestions') }}" class="btn btn-primary btn-lg text-white mb-0 me-0"><i class="mdi mdi-eye"></i>view more</a>
                            </div>
                          </div>

                         
                          <div class="table-responsive  mt-1">
                            @include('helpers.message_handler')
                            <table class="table select-table">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Title</th>
                                  <th>Assigned By</th>
                                  <th>Created</th>
                                  <th>Deadline</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 d-flex flex-column">
                  <div class="row flex-grow">
                    <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                      <div class="card card-rounded">
                        <div class="card-body card-rounded">
                          <h4 class="card-title  card-title-dash">Upcoming Events</h4>
                          @foreach ($events as $index=>$event)
                          <div class="list align-items-center border-bottom py-2">
                            <div class="wrapper w-100">
                              <p class="mb-2 fw-medium">{{$event->name}}</p>
                              <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                  <i class="mdi mdi-calendar text-muted me-1"></i>
                                  <p class="mb-0 text-small text-muted">{{$event->event_date}}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                          @endforeach
                          <div class="list align-items-center pt-3">
                            <div class="wrapper w-100">
                              <p class="mb-0">
                                <a href="{{ url('/student/events') }}" class="fw-bold text-primary">Show all <i class="mdi mdi-arrow-right ms-2"></i></a>
                              </p>
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
        </div>
      </div>
    </div>
  </div>
  @include('helpers.copyright')
</div>
@include('helpers.partials_footers')