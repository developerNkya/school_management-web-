@include('school_admin.partial_headers')

<div class="main-panel" id="main-panel">
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="card-title">Track Student Attendance</h4>
                        @include('helpers.message_handler')
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    
                        <form method="POST" action="{{ route('attendance.fetchStudents') }}">
                            @csrf
                    
                            <!-- Select Attendance -->
                            <div class="form-group">
                                <label for="attendanceSelect">Select Attendance</label>
                                <select name="attendance_id" class="form-control" id="attendanceSelect" required>
                                    <option value="">Select Attendance</option>
                                    @foreach ($attendances as $attendance)
                                        <option value="{{ $attendance->id }}" data-type="{{ $attendance->attendance_type }}">
                                            {{ $attendance->attendance_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Select Class -->
                            <div class="form-group">
                                <label for="classSelect">Select Class</label>
                                <select name="class_id" class="form-control" id="classSelect" required>
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Select Section -->
                            <div class="form-group">
                                <label for="sectionSelect">Select Section</label>
                                <select name="section_id" class="form-control" id="sectionSelect" required>
                                    <option value="">Select Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Conditional Form Group for Per Subject Attendance -->
                            <div class="form-group" id="subjectGroup" style="display: none;">
                                <label for="subjectSelect">Select Subject</label>
                                <select name="subject_id" class="form-control" id="subjectSelect">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Submit Button to Fetch Students -->
                            <button type="submit" class="btn btn-primary mt-4">Fetch Students</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table for Attendance Data -->
    @if($students->count() > 0)
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('attendance.storeData') }}">
                        @csrf

                        <input type="hidden" name="attendance_id" value="{{ request('attendance_id') }}">
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                        <input type="hidden" name="section_id" value="{{ request('section_id') }}">
                        <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                        <input type="hidden" name="date" id="dateInput" value="{{ now()->format('Y-m-d') }}">

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th id="subjectColumn" style="display: none;">Subject</th>
                                        <th>Status</th>
                                        <th>Total Appearance</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="#" class="date-picker-trigger">{{ now()->format('Y-m-d') }}</a>
                                            </td>
                                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td id="subjectDataColumn" style="display: none;">
                                                <input type="text" name="subject[{{ $student->id }}]" value="{{ request('subject_id') }}">
                                            </td>
                                            <td>
                                                <select name="status[{{ $student->id }}]" class="form-control">
                                                    <option value="Present">Present</option>
                                                    <option value="Absent">Absent</option>
                                                    <option value="Allowed">Allowed</option>
                                                    <option value="Sick">Sick</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="total_appearance[{{ $student->id }}]" class="form-control">
                                            </td>
                                            {{-- <td>
                                                <input type="number" name="percentage[{{ $student->id }}]" class="form-control" readonly>
                                            </td> --}}
                                               <td>
                                                <input type="number" name="percentage" class="form-control" value="1" readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.getElementById('attendanceSelect').addEventListener('change', function () {
        const attendanceType = this.options[this.selectedIndex].getAttribute('data-type');
        const subjectGroup = document.getElementById('subjectGroup');
        const subjectColumn = document.getElementById('subjectColumn');
        const subjectDataColumn = document.getElementById('subjectDataColumn');

        if (attendanceType === 'per_subject') {
            subjectGroup.style.display = 'block';
            subjectColumn.style.display = 'table-cell';
            subjectDataColumn.style.display = 'table-cell';
        } else {
            subjectGroup.style.display = 'none';
            subjectColumn.style.display = 'none';
            subjectDataColumn.style.display = 'none';
        }
    });

    // Date picker functionality
    document.querySelectorAll('.date-picker-trigger').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();

            // Show date picker
            const dateInput = document.getElementById('dateInput');
            const newDate = prompt('Enter new date (YYYY-MM-DD):', dateInput.value);
            
            if (newDate && /^(\d{4}-\d{2}-\d{2})$/.test(newDate)) {
                this.textContent = newDate;
                dateInput.value = newDate;
            } else {
                alert('Invalid date format. Please enter in YYYY-MM-DD format.');
            }
        });
    });
</script>