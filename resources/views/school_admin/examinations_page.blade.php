@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Examinations</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Exam +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Name </th>
                                        <th> Start Date</th>
                                        <th> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $index => $exam)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $exam->name }}</td>
                                            <td>{{ $exam->start_date }}</td>
                                            <td>
                                                <div class="flexer">
                                                    <form action="{{ route('deleteById') }}" method="post"
                                                        name="deletable" onsubmit="return confirmDelete(this,'exam')">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $exam->id }}">
                                                        <input type="hidden" name="table" value="exam">
                                                        <button class="btn btn-dark" type="button"
                                                            onclick="confirmDelete(this,'exam')">
                                                            <i class="fa fa-trash-o"
                                                                style="font-size:20px;color:white"></i>
                                                        </button>
                                                    </form>

                                                    <button class="btn btn-info flexer-item" type="button"
                                                        onclick="showEditModal({{ $exam }})">
                                                        <i class="fa fa-pencil-square-o font-icon-sizer"
                                                            style="font-size:20px;color:white"></i>
                                                    </button>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    $paginated = $exams;
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>
        </div>
    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>



<div class="form-modal" id="form-modal" style="display: none;">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" id="card-title">Add Exam</h4>
                        <form class="forms-sample" id="exam-form" method="POST">
                            @csrf
                            <input type="hidden" name="exam_id" id="exam-id">
                            <div class="form-group">
                                <label for="name">Exam Name</label>
                                <input name="name" class="form-control" id="exam-name" placeholder="Exam Name" required>
                            </div>
                            <div class="form-group">
                                <label for="classes">Select Classes</label><br>
                                <select id="classes" name="classes[]" class="form-control multiple-selector" multiple="multiple" style="width: 100%;">
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subjects">Select Subjects</label><br>
                                <select id="subjects" name="subjects[]" class="form-control multiple-selector" multiple="multiple" style="width: 100%;">
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" class="form-control" id="start_date" required>
                            </div>
                            <div class="submit-container" style="margin-top: 10%">
                                <button type="submit" id="form-btn" class="btn btn-primary me-2">Submit</button>
                                <button type="button" class="btn btn-light" onclick="disableModal()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('helpers.copyright')
</div>

@include('school_admin.partial_footers')
<script>
function showEditModal(item) {
    fetch(`/school_admin/exam-details/${item.id}`)
        .then(response => response.json())
        .then(data => {
            if (data.exam) {
                document.getElementById('form-modal').style.display = 'block';
                document.getElementById('main-panel').style.display = 'none';
                document.getElementById("card-title").innerHTML = "Edit Exam";
                document.getElementById("exam-name").value = data.exam[0].name;
                document.getElementById("exam-id").value = data.exam[0].id;
                document.getElementById("form-btn").innerHTML = "Update";
                document.getElementById("exam-form").action = "/school_admin/edit-exam";

            } else {
                alert('Kindly create a class before editing an exam.');
            }
        })
        .catch(error => console.error('Error:', error));
}

function showModal() {
    fetch('/helper/class-existence-checker')
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                document.getElementById("exam-form").action = "/school_admin/add-exam";
                document.getElementById('form-modal').style.display = 'block';
                document.getElementById('main-panel').style.display = 'none';
                document.getElementById("card-title").innerHTML = "Add Exam";
                document.getElementById("exam-name").value = "";
                document.getElementById("exam-id").value = "";
                document.getElementById("form-btn").innerHTML = "Submit";
            } else {
                alert('Kindly create a class before adding an exam.');
            }
        })
        .catch(error => console.error('Error:', error));
}

function disableModal() {

    document.getElementById('form-modal').style.display = 'none';
    document.getElementById('main-panel').style.display = 'block';
}

function addSection() {
        const container = document.getElementById('sectionsContainer');

        // Create a new section
        const newSection = document.createElement('div');
        newSection.classList.add('section-container');
        newSection.style.display = 'flex';
        newSection.style.marginBottom = '10px';

        newSection.innerHTML = `
      <input name="sections[]" class="form-control" placeholder="Section name">
      <button type="button" class="btn btn-danger removeBtn" onclick="removeSection(this)" style="margin-left:10px">Remove</button>
    `;

        // Insert the new section before the "Add" button
        container.insertBefore(newSection, container.lastElementChild);
    }

    function removeSection(button) {
        button.parentElement.remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            $('#subjects').select2({
                placeholder: "Select subjects",
                allowClear: true
            });

            $('#classes').select2({
                placeholder: "Select classes",
                allowClear: true
            });
        });

        function updateStreams() {
            const classId = document.getElementById('classSelect').value;
            const streamSelect = document.getElementById('streamSelect');
            const subjectSelect = document.getElementById('subjectSelect');

            if (classId) {
                fetch(`/school_admin/get-streams/${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        streamSelect.innerHTML = '<option value="">Select a stream</option>';
                        data.streams.forEach(stream => {
                            streamSelect.innerHTML +=
                                `<option value="${stream}">${stream}</option>`;
                        });

                        // Clear subjects when class or stream changes
                        subjectSelect.innerHTML = '<option value="">Select a subject</option>';
                    });
            } else {
                streamSelect.innerHTML = '<option value="">Select a stream</option>';
                subjectSelect.innerHTML = '<option value="">Select a subject</option>';
            }
        }

        function updateSubjects() {
            const classId = document.getElementById('classSelect').value;
            const stream = document.getElementById('streamSelect').value;
            const subjectSelect = document.getElementById('subjectSelect');

            if (classId && stream) {
                fetch(`/school_admin/get-subjects/${classId}/${stream}`)
                    .then(response => response.json())
                    .then(data => {
                        subjectSelect.innerHTML = '<option value="">Select a subject</option>';
                        data.subjects.forEach(subject => {
                            subjectSelect.innerHTML +=
                                `<option value="${subject}">${subject}</option>`;
                        });
                    });
            } else {
                subjectSelect.innerHTML = '<option value="">Select a subject</option>';
            }
        }

        // Expose functions to global scope
        window.updateStreams = updateStreams;
        window.updateSubjects = updateSubjects;
    });
</script>
