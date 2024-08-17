@include('student.partial_headers')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Examinations</h4>
                        @if(!isset($exam) || !$exam)
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Exam Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($exams as $index => $exam)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $exam->name }}</td>
                                                <td>
                                                    <form method="GET" action="{{ route('student.marks') }}">
                                                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                                                        <button type="submit" class="btn btn-light">View Marks</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(isset($exam) && $marks->count() > 0)
                            <h4 class="card-title">Marks for {{ $exam->name }}</h4>
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>ADM_NO</th>
                                            @foreach ($subjects as $subject)
                                                <th>{{ $subject->subjects->name }}</th>
                                            @endforeach
                                            <th>Average</th>
                                            <th>Position</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student->first_name }}</td>
                                                <td>{{ $student->registration_no }}</td>
                                                @foreach ($subjects as $subject)
                                                    @php
                                                        $studentMarks = $marks->get($student->id)->firstWhere('subject_id', $subject->subjects->id);
                                                    @endphp
                                                    <td>{{ $studentMarks->marks ?? 'N/A' }}</td>
                                                    {{-- <td>{{$studentMarks}}</td> --}}
                                                @endforeach
                                                <td>{{ number_format($student->average, 2) }}</td>
                                                <td>{{ $student->position }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('student.marks') }}" class="btn btn-primary mt-3">Back to All Examinations</a>
                        @else
                            <p>No marks available for this exam.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
        </div>
    </footer>
</div>

@include('student.partial_footers')
