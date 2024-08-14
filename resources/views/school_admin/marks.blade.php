@include('school_admin.partial_headers')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manage Marks</h4>
                        @include('helpers.message_handler')
                        <form class="forms-sample" method="GET" action="{{ route('marks.index') }}">
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
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id || ($loop->first && !request('class_id')) ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="subjectSelect">Select Subject</label>
                                    <select name="subject_id" class="form-control" id="subjectSelect" required onchange="this.form.submit()">
                                        <option value="">Select a subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </form>

                        @if(request('exam_id') && request('class_id') && request('subject_id'))
                        <form method="POST" action="{{ route('marks.store') }}">
                            @csrf
                            <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
                            <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                            <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                        
                            <div class="table-responsive mt-4">
                                <h5>Subject: {{ $subject_name }} | Class: {{ $class_name }} | Exam: {{ $exam_name }}</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>ADM_NO</th>
                                            <th>{{ $subject_name }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student->first_name }}</td>
                                                <td>{{ $student->registration_no }}</td>
                                                <td>
                                                    <input type="number" name="marks[{{ $student->id }}]" class="form-control" 
                                                           value="{{ $marks[$student->id]->marks ?? '' }}" max="100">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="submit-container" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-primary me-2">Update Marks</button>
                            </div>
                        </form>
                        
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
        </div>
    </footer>
</div>

@include('school_admin.partial_footers')
