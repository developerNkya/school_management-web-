@include('student.partial_headers')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @php
                         $assignment = $assignment_type == 'home_work' ? 'Home Works' : 'Holiday Packages'
                         @endphp
                        <h4 class="card-title">All {{$assignment}}</h4>
                        <div class="table-responsive  pt-3">
                            @include('helpers.message_handler')
                            <table class="table table-bordered">
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
                                            <td>{{ \Carbon\Carbon::parse($assignment->created_at)->format('F j, Y \a\t g:i A') }}
                                            </td>
                                            <td>{{ $assignment->submission_date }}</td>
                                            <td>{{ $assignment->file_size }}Mb</td>
                                            <td>
                                                <form action="{{ route('downloadFile') }}" method="post"
                                                    name="downloadForm">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $assignment->id }}">
                                                    <button class="btn btn-dark" type="button"
                                                        onclick="confirmDownload(this)">
                                                        <i class="fa fa-download"
                                                            style="font-size:20px;color:white"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    $paginated = $assignments
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>
        </div>
        @include('helpers.copyright')
    </div>

    @include('student.partial_footers')
