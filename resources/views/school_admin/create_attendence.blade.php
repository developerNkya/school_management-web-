@include('school_admin.partial_headers')

<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Attendances</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Atteandance +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Name </th>
                                        <th> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendences as $index => $attendence)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $attendence->attendance_name }}</td>
                                            <td>
                                                <div class="flexer">
                                                    <form action="{{ route('deleteById') }}" method="post"
                                                        name="deletable" onsubmit="return confirmDelete(this,'attendance')">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $attendence->id }}">
                                                        <input type="hidden" name="table" value="attendance">
                                                        <button class="btn btn-dark" type="button"
                                                            onclick="confirmDelete(this,'attendance')">
                                                            <i class="fa fa-trash-o"
                                                                style="font-size:20px;color:white"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    $paginated = $attendences;
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>
        </div>
    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>

<div class="main-panel" id="form-modal" style="display: none;">

    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Attendence</h4>
                        @include('helpers.message_handler')
                        <form class="forms-sample" method="POST" action="{{ route('attendance.saveNewAttendence') }}">
                            @csrf
                            <div class="form-group">
                                <label for="attendanceName">Attendance Name</label>
                                <input name="attendance_name" class="form-control" id="attendanceName" placeholder="e.g., Daily Attendance" required>
                            </div>
                        
                            <div class="form-group">
                                <label for="academicYear">Academic Year</label>
                                <input name="academic_year" class="form-control" id="academicYear" placeholder="e.g., 2023/2024" required>
                            </div>
                        
                            <div class="form-group">
                                <label for="attendanceType">Attendance Type</label>
                                <select name="attendance_type" class="form-control" id="attendanceType" required>
                                    <option value="">Select Option</option>
                                    <option value="per_day">Per Day</option>
                                    <option value="per_subject">Per Subject</option>
                                </select>
                            </div>
                        
                            <input type="hidden" name="school_id" value="{{ Auth::user()->school_id }}">
                        
                            <div class="submit-container" style="margin-top: 10%">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <button type="button" class="btn btn-light" onclick="disableModal()">Cancel</button>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>


@include('school_admin.partial_footers')
<script>
    function showModal() {
        document.getElementById('form-modal').style.display = 'block';
        document.getElementById('main-panel').style.display = 'none';
    }

    function disableModal() {
        document.getElementById('form-modal').style.display = 'none';
        document.getElementById('main-panel').style.display = 'block';
        event.preventDefault();
    }

    function addSection(containerId) {
    const container = document.getElementById(containerId);

    // Create a new section or subject
    const newSection = document.createElement('div');
    newSection.classList.add('section-container');
    newSection.style.display = 'flex';
    newSection.style.marginBottom = '10px';

    newSection.innerHTML = `
        <input name="${containerId === 'sectionsContainer' ? 'sections[]' : 'subjects[]'}" class="form-control" placeholder="${containerId === 'sectionsContainer' ? 'Section name' : 'Subject name'}">
        <button type="button" class="btn btn-danger removeBtn" onclick="removeSection(this)" style="margin-left:10px">Remove</button>
    `;

    // Insert the new section before the "Add" button
    container.insertBefore(newSection, container.lastElementChild);
}

function removeSection(button) {
    button.parentElement.remove();
}

</script>

