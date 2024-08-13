@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Subjects</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Subject +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Name </th>
                                        <th> Short Code </th>
                                        <th> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjects as $subject)
                                        <tr>
                                            <td>1</td>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->short_name }}</td>
                                            <td> <button type="button" class="btn btn-light">View More</button></td>
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
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">

            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
                reserved.</span>
        </div>
    </footer>
    <!-- partial -->
</div>

<div class="form-modal" id="form-modal" style="display: none;">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Subject</h4>
                        <form class="forms-sample" method="POST" action="/school_admin/add-subject">
                            @csrf
                            <div class="form-group">
                                <label for="firstName">Subject Name</label>
                                <input name="subject_name" class="form-control" id="firstName" placeholder="eg.. English"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="firstName">Short Name</label>
                                <input name="short_name" class="form-control" id="firstName" placeholder="eg.. Eng"
                                    required>
                            </div>

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


    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a
                    href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
                BootstrapDash.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
                reserved.</span>
        </div>
    </footer>
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
        // Fetch streams and subjects based on selected class
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
