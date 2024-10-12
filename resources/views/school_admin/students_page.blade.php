@include('school_admin.partial_headers')
<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Students</h4>
                        @include('helpers.message_handler')
                        <div class="form-group">
                            <input type="text" id="searchField" class="form-control"
                                placeholder="Search Student by name,email or registration no...">
                        </div>
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Student +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="usersTable">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Student Name </th>
                                        <th> Email </th>
                                        <th> Registration No </th>
                                        <th> Class </th>
                                        <th>Status</th>
                                        <th>Password</th>
                                        <th> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->full_name }}</td>
                                            <td>{{ $student->parent_email }}</td>
                                            <td>{{ $student->registration_no }}</td>
                                            <td>{{ $student->SchoolClass->name }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-{{ $student->user->isActive ? 'success' : 'danger' }}"
                                                    data-user-id="{{ $student->user->id }}" onclick="#">
                                                    {{ $student->user->isActive ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success"
                                                    onclick="showPasswordManager('{{ $student->user->id }}', '{{ $student->full_name }}', 'add_student_page')">
                                                    Change
                                                </button>
                                            </td>
                                            <td>
                                                <form action="{{ route('deleteById') }}" method="post" name="deletable"
                                                    onsubmit="return confirmDelete(this,'student')">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $student->id }}">
                                                    <input type="hidden" name="table" value="student">
                                                    <button class="btn btn-dark" type="button"
                                                        onclick="confirmDelete(this,'student')">
                                                        <i class="fa fa-trash-o" style="font-size:20px;color:white"></i>
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
                    $paginated = $students;
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>

        </div>
    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>

<div class="main-panel" id="form-modal" style="display: none">
    @include('helpers.message_handler')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6">
                <form id="studentForm" class="forms-sample" method="POST" action="{{ route('add_student') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="step active">
                            <p class="text-center mb-4">Step 1: Student Information</p>
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input name="first_name" class="form-control" id="firstName" placeholder="e.g. John"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="middleName">Middle Name</label>
                                <input name="middle_name" class="form-control" id="middleName" placeholder="e.g. Lee"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input name="last_name" class="form-control" id="lastName" placeholder="e.g. Doe"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="registrationNo">Registration No</label>
                                <input name="registration_no" class="form-control" id="registrationNo"
                                    placeholder="e.g. 123456" required>
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" id="dob" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-control" id="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bloodGroup">Blood Group</label>
                                <select name="blood_group" class="form-control" id="bloodGroup" required>
                                    <option value="">Select Blood Group</option>
                                    <option value="O-">O-</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="O+">O+</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nationality">Nationality</label>
                                <input name="nationality" class="form-control" id="nationality"
                                    placeholder="e.g. American" required>
                            </div>
                            <div class="form-group">
                                <label for="city">Region</label>
                                <input name="city" class="form-control" id="city"
                                    placeholder="e.g. Springfield" required>
                            </div>
                        </div>

                        <div class="step">
                            <p class="text-center mb-4">Step 2: Student Data</p>
                            <div class="form-group">
                                <label for="class">Class</label>
                                <select name="class_id" class="form-control" id="classSelect" required
                                    onchange="updateStreams()">
                                    <option value="">Select a class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="parentFirstName">Parent First Name</label>
                                <input name="parent_first_name" class="form-control" id="parentFirstName"
                                    placeholder="e.g. Jane" required>
                            </div>

                            <div class="form-group">
                                <label for="parentLastName">Parent Last Name</label>
                                <input name="parent_last_name" class="form-control" id="parentLastName"
                                    placeholder="e.g. Doe" required>
                            </div>

                            <div class="form-group">
                                <label for="parentPhone">Parent Phone No</label>
                                <input name="parent_phone" class="form-control" id="parentPhone"
                                    placeholder="e.g. 1234567890" required>
                            </div>
                        </div>

                        <div class="form-footer d-flex">
                            <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                            <button type="button" id="nextBtn" onclick="nextPrev(1)"
                                style="margin-left:5%">Next</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>

@include('helpers.password_manager')

<script>
    function showModal() {
        fetch('/helper/class-existence-checker')
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('form-modal').style.display = 'block';
                    document.getElementById('main-panel').style.display = 'none';
                } else {
                    alert('Kindly create a class before registering student');

                }
            })
            .catch(error => console.error('Error:', error));
    }

    function disableModal() {
        document.getElementById('form-modal').style.display = 'none';
        document.getElementById('main-panel').style.display = 'block';
        document.getElementById('password-manager').style.display = 'none';
        event.preventDefault();
    }

    document.getElementById('searchField').addEventListener('keyup', function() {
        const searchValue = this.value.trim().toLowerCase();

        if (searchValue.length > 0) {
            fetch(`/school_admin/filter-students/${encodeURIComponent(searchValue)}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#usersTable tbody');
                    tableBody.innerHTML = ''; // Clear the table

                    const students = data[0].data;
                    console.log(students);

                    students.forEach((student, index) => {
                        const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${student.full_name}</td>
                            <td>${student.parent_email}</td>
                            <td>${student.registration_no}</td>
                            <td>${student.school_class.name}</td>
                            <td>
                                <button type="button" class="btn btn-${student.user.isActive ? 'success' : 'danger'}" data-user-id="${student.user.id}">
                                    ${student.user.isActive ? 'Active' : 'Inactive'}
                                </button>
                            </td>
                            <td>
                                <form action="/deleteById" method="post" name="deletable" onsubmit="return confirmDelete(this,'student')">
                                    <input type="hidden" name="id" value="${student.id}">
                                    <input type="hidden" name="table" value="student">
                                    <button class="btn btn-dark" type="button" onclick="confirmDelete(this,'student')">
                                        <i class="fa fa-trash-o" style="font-size:20px;color:white"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                });
        }
    });


    // multi form::
    let currentTab = 0;
    showTab(currentTab);

    function showTab(n) {
        let x = document.getElementsByClassName("step");
        x[n].style.display = "block";
        let progress = (n / (x.length - 1)) * 100;
        document.querySelector(".progress-bar").style.width = progress + "%";
        document.querySelector(".progress-bar").setAttribute("aria-valuenow", progress);
        document.getElementById("prevBtn").style.display = n == 0 ? "none" : "inline";
        document.getElementById("nextBtn").innerHTML = n == x.length - 1 ? "Submit" : "Next";
    }

    function nextPrev(n) {
        let x = document.getElementsByClassName("step");
        if (n == 1 && !validateForm()) return false;
        x[currentTab].style.display = "none";
        currentTab += n;
        if (currentTab >= x.length) {
            document.getElementById("studentForm").submit()
        }
        showTab(currentTab);
    }

    function validateForm() {
        let valid = true;
        let x = document.getElementsByClassName("step");
        let y = x[currentTab].getElementsByTagName("input");
        for (var i = 0; i < y.length; i++) {
            if (y[i].value == "") {
                y[i].className += " invalid";
                valid = false;
            }
        }
        return valid;
    }

    function resetForm() {
        let x = document.getElementsByClassName("step");
        for (var i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        let inputs = document.querySelectorAll("input");
        inputs.forEach(input => {
            input.value = "";
            input.className = "";
        });
        currentTab = 0;
        showTab(currentTab);
        document.querySelector(".progress-bar").style.width = "0%";
        document.querySelector(".progress-bar").setAttribute("aria-valuenow", 0);
        document.getElementById("prevBtn").style.display = "none";
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Fetch streams and subjects based on selected class
        function updateStreams() {
            const classId = document.getElementById('classSelect').value;
            const streamSelect = document.getElementById('streamSelect');
            const subjectSelect = document.getElementById('subjectSelect');

            if (classId) {
                console.log('class id', classId);

                fetch(`/school_admin/get-streams/${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('the response', data);

                        streamSelect.innerHTML = '<option value="">Select a stream</option>';
                        data.streams.forEach(stream => {
                            streamSelect.innerHTML +=
                                `<option value="${stream.id}" name="section_id">${stream.name}</option>`;
                        });
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
        }

        window.updateStreams = updateStreams;
        window.updateSubjects = updateSubjects;
    });

    function showPasswordManager(userId, fullName, page) {
        document.getElementById('password-manager').style.display = 'block';
        document.getElementById('main-panel').style.display = 'none';
        document.getElementById('fullName').value = fullName;
        document.getElementById('page').value = page;
        document.getElementById('user_id').value = userId;

        event.preventDefault();
    }
</script>
@include('school_admin.partial_footers')
