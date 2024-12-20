@include('school_admin.partial_headers')
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
                              <a href="{{ url('/assignment/add-assignment') }}" class="btn btn-primary btn-lg text-white mb-0 me-0"><i class="mdi mdi-eye"></i>view more</a>
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
                                  <th>File Size</th>
                                  <th>Download</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($assignments as $index => $assignment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $assignment->name }}</td>
                                    <td>{{ $assignment->sender->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($assignment->created_at)->format('F j, Y \a\t g:i A') }}</td>
                                    <td>{{ $assignment->submission_date }}</td>
                                    <td>{{ $assignment->file_size }}Mb</td>
                                    <td>
                                        <form action="{{ route('downloadFile') }}" method="post" name="downloadForm">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $assignment->id }}">
                                            <button class="btn btn-dark" type="button" onclick="confirmDownload(this)">
                                                <i class="fa fa-download" style="font-size:20px;color:white"></i>
                                            </button>
                                        </form>
                                    </td>                                    
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
                                <a href="{{ url('/school_admin/organize_events') }}" class="fw-bold text-primary">Show all <i class="mdi mdi-arrow-right ms-2"></i></a>
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
@include('school_admin.partial_footers')