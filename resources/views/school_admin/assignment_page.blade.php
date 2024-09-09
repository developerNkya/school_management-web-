@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Assignment</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick="showModal()">Add Assignment +</button>
                        <p class="statistics-title" style="margin-top:3%">Total Space used:<b>{{$totalSize}} Mb</b></p>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Assignment Name</th>
                                        <th>Assigned By</th>
                                        <th>class</th>
                                        <th>Subject</th>
                                        <th>Download</th>
                                        <th>File Size</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assignments as $index => $assignment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $assignment->name }}</td>
                                            <td>{{ $assignment->sender->name }}</td>
                                            <td>{{ $assignment->class->name }}</td>
                                            <td>{{ $assignment->subject->name }}</td>
                                            <td>
                                                <form action="{{ route('downloadFile') }}" method="post" name="downloadForm">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $assignment->id }}">
                                                    <button class="btn btn-dark" type="button" onclick="confirmDownload(this)">
                                                        <i class="fa fa-download" style="font-size:20px;color:white"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            
                                            <td>{{ $assignment->file_size }}Mb</td>
                                            <td>
                                                <form action="{{ route('deleteById') }}" method="post" name="deletable" onsubmit="return confirmDelete(this,'Assignment')">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{  $assignment->id }}">
                                                    <input type="hidden" name="table" value="assignment">
                                                    <button class="btn btn-danger" type="button" onclick="confirmDelete(this,'Assignment')">
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
                    $paginated = $assignments;
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>

        </div>

    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>

<div class="form-modal" id="form-modal" style="display: none">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Assignment</h4>
                        <form class="forms-sample" method="POST" action="/assignment/save-assignment" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputUsername1">Assignment Name</label>
                                <input name="assignment_name" class="form-control" id="exampleInputUsername1"
                                    placeholder="eg. grade 1">
                            </div>
                            <div class="form-group" id="subjectGroup">
                                <label for="subjectSelect">Select Subject</label>
                                <select name="subject_id" class="form-control" id="subjectSelect">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="subjectGroup">
                                <label for="ClassSelect">Select Class</label>
                                <select name="class_id" class="form-control" id="subjectSelect">
                                    <option value="">Select class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assignment_file">Select File(Pdf only,max 1mb)</label>
                                <input type="file" name="assignment_file" class="form-control" id="assignment_file" required>
                            </div>
                            <div class="form-group">
                                <label for="dob">Submission Date</label>
                                <input type="date" name="submission_date" class="form-control" id="dob" required>
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

    function addSection(containerId) {
        const container = document.getElementById(containerId);

        const newSection = document.createElement('div');
        newSection.classList.add('section-container');
        newSection.style.display = 'flex';
        newSection.style.marginBottom = '10px';

        newSection.innerHTML = `
        <input name="${containerId === 'sectionsContainer' ? 'sections[]' : 'subjects[]'}" class="form-control" placeholder="${containerId === 'sectionsContainer' ? 'Section name' : 'Subject name'}">
        <button type="button" class="btn btn-danger removeBtn" onclick="removeSection(this)" style="margin-left:10px">Remove</button>
    `;
        container.insertBefore(newSection, container.lastElementChild);
    }

    function removeSection(button) {
        button.parentElement.remove();
    }



</script>
