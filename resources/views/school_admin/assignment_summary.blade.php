@include('school_admin.partial_headers')



@if(isset($summaryData))
<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Assignment Summary</h4>
                        @include('helpers.message_handler')
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="usersTable">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Class Name </th>
                                        <th>Total Homeworks</th>
                                        <th>Total Holiday packages</th>
                                        <th>Total Teachers</th>
                                        <th> Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($summaryData as $index => $summary)
                                        <?php
                                        $assignment_type = $summary['assignments_by_type'];
                                        $total_teachers = $assignment_type['home_work']['total_teachers'] + $assignment_type['holiday_package']['total_assignments'];
                                        ?>
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $summary['class_name'] }}</td>
                                            <td>{{ $assignment_type['home_work']['total_assignments'] }}</td>
                                            <td>{{ $assignment_type['holiday_package']['total_assignments'] }}</td>
                                            <td>{{ $total_teachers }}</td>
                                            <td>
                                                <form action="{{ route('classSummary') }}" method="GET">
                                                    @csrf
                                                    <input type="hidden" name="class_id" value="{{ $summary['class_id'] }}">
                                                    <button type="submit" class="btn btn-success">View</button>
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
    @include('helpers.copyright')
    <!-- partial -->
</div>
@endif

@if(isset($assignments))
<div class="main-panel" id="form-modal">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Assignments</h4>
                        @include('helpers.message_handler')
                        <form action="{{ route('assignmentSummary') }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-success">Back</button>
                        </form>
                        </p>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="classSummary">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Assignment Name</th>
                                        <th>Assignment Type</th>
                                        <th>Submission Date</th>
                                        <th>Assigned By</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assignments as $index => $assignment)
                                        @php
                                            $initial_val = $assignment->assignment_type;
                                            $assignment_type = str_replace('_', ' ', $initial_val);
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $assignment->name }}</td>
                                            <td>{{ $assignment_type }}</td>
                                            <td>{{ $assignment->submission_date }}</td>
                                            <td>{{ $assignment->sender->name }}</td>
                                            <td>{{ $assignment->class->name }}</td>
                                            <td>{{ $assignment->subject->name }}</td>
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
                    $paginated = $assignments; 
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>

        </div>

    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>
@endif

@include('school_admin.partial_footers')