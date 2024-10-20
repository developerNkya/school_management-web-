@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Drivers</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Driver +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Names </th>
                                        <th> Gender</th>
                                        <th> Nationality</th>
                                        <th>Phone No</th>
                                        <th>Email</th>
                                        <th> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($drivers as $index=> $driver)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{ $driver->name }}</td>
                                            {{-- <td>{{ $driver->first_name.' '.$driver->second_name.' '.$driver->last_name }}</td> --}}
                                            <td>{{ $driver->gender }}</td>
                                            <td>{{ $driver->location }}</td>
                                            <td>{{$driver->phone }}</td>
                                            <td>{{ $driver->email }}</td>
                                            <td>
                                                <form action="{{ route('deleteById') }}" method="post" name="deletable" onsubmit="return confirmDelete(this)">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $driver->id }}">
                                                    <input type="hidden" name="table" value="driver">
                                                    <button class="btn btn-dark" type="button" onclick="confirmDelete(this,'driver')">
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
                    $paginated = $drivers;
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
                        <h4 class="card-title">Add Driver</h4>
                        <form class="forms-sample" method="POST" action="/bus-management/add-driver">
                            @csrf
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input name="first_name" class="form-control" id="firstName" placeholder="First name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="secondName">Second Name</label>
                                <input name="second_name" class="form-control" id="secondName" placeholder="Second name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input name="last_name" class="form-control" id="lastName" placeholder="Last name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <input name="phone_number" class="form-control" id="phoneNumber"
                                    placeholder="Phone number" required>
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
                                <label for="nationality">Nationality</label>
                                <input name="nationality" class="form-control" id="nationality"
                                    placeholder="e.g. American" required>
                            </div>
                            <div class="form-group">
                                <label for="city">Region</label>
                                <input name="city" class="form-control" id="city" placeholder="e.g. Springfield"
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
