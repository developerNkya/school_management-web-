@include('school_admin.partial_headers')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tabulation</h4>
                        @include('helpers.message_handler')
                        <form class="forms-sample" method="GET" action="{{ route('admin.tabulation') }}">
                            @csrf
                            <div class="form-group">
                                <label for="examSelect">Select Exam</label>
                                <select name="exam_id" class="form-control" id="examSelect" required onchange="this.form.submit()">
                                    <option value="">Select an exam</option>
                                    @foreach ($exams as $exam)
                                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                            {{ $exam->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
            
                            @if(request('exam_id'))
                                <div class="form-group">
                                    <label for="classSelect">Select Class</label>
                                    <select name="class_id" class="form-control" id="classSelect" required onchange="this.form.submit()">
                                        <option value="">Select a class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </form>
            
                        @if(request('exam_id') && request('class_id'))
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Name</th>
                                        <th>ADM_NO</th>
                                        @foreach ($subjects as $subject)
                                            <th>{{ $subject->name }}</th>
                                        @endforeach
                                        <th>Average</th>
                                        <th>Position</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->full_name }}</td>
                                            <td>{{ $student->registration_no }}</td>
                                            @foreach ($subjects as $subject)
                                            @php
                                                $studentMarks = collect($marks[$student->id])->firstWhere('subject_id', $subject->id);
                                            @endphp
                                            <td>{{ $studentMarks->marks ?? 'N/A' }}</td>
                                            @endforeach
                                            <td>{{ number_format($student->average, 2) }}</td>
                                            <td>{{ $student->position }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>              
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    @include('helpers.message_handler')
</div>

@include('school_admin.partial_footers')
