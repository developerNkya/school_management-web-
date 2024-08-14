@include('school_admin.partial_headers')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manage Marks</h4>
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
                        
                            <!-- Classes and Subjects dropdowns to be displayed based on the selected exam -->
                            @if(request('exam_id'))
                            <div class="form-group">
                                <label for="classSelect">Select Class</label>
                                <select name="class_id" class="form-control" id="classSelect" required>
                                    <option value="">Select a class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ (request('class_id') == $class->id || $loop->first) ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="form-group">
                                <label for="subjectSelect">Select Subject</label>
                                <select name="subject_id" class="form-control" id="subjectSelect" required>
                                    <option value="">Select a subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        
                            <div class="submit-container" style="margin-top: 10%">
                                <button type="submit" class="btn btn-primary me-2">Manage</button>
                            </div>
                        </form>
                        
                        
                      

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a
                    href="/https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
                BootstrapDash.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
                reserved.</span>
        </div>
    </footer>
    <!-- partial -->
</div>
@include('school_admin.partial_footers')
