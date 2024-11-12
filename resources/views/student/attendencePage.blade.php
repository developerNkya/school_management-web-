@include('student.partial_headers')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">My Attendance</h4>
                        @if(!isset($attendance) || !$attendance)
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Attendance Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendanceData as $index => $attendanceItem)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                              
                                                <td>{{ $attendanceItem->attendance->attendance_name }}</td>
                                                <td>
                                                    <form method="GET" action="{{ route('student.attendence') }}">
                                                        <input type="hidden" name="attendance_id" value="{{ $attendanceItem->attendence_id }}">
                                                        <button type="submit" class="btn btn-light">View</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(isset($attendance) && $attendanceRecords->count() > 0)
                            <h4 class="card-title">Attendance for {{ $attendance->attendance_name }}</h4>
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>From (Date)</th>
                                            <th>To (Date)</th>
                                            <th>No. Present</th>
                                            <th>%</th>
                                            <th>Total Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student->full_name }}</td>
                                                <td>{{ $date_from}}</td>
                                                <td>{{ $date_to }}</td>
                                                <td>{{ $number_present }}</td>
                                                <td>{{ $percentage }}%</td>
                                                <td>{{ $total_classes }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <a href="{{ route('student.attendence') }}" class="btn btn-primary mt-3">Back to All Attendance</a>
                        @else
                            <p>No attendance records available for this student.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('helpers.copyright')
</div>

@include('student.partial_footers')
