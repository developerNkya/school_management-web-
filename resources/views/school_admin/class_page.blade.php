@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Classes</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick="showModal()">Add Class +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Class Name</th>
                                        <th>Total Students</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $index => $class)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $class->info->name }}</td>
                                            <td>{{ $class->total_students }}</td>
                                            <td>
                                                <form action="{{ route('deleteById') }}" method="post" name="deletable" onsubmit="return confirmDelete(this,'class')">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $class->info->id }}">
                                                    <input type="hidden" name="table" value="schoolClass">
                                                    <button class="btn btn-dark" type="button" onclick="confirmDelete(this,'class')">
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
                        <h4 class="card-title">Add Class</h4>
                        <form class="forms-sample" method="POST" action="/school_admin/add-class">
                            <div class="form-group">
                                <label for="exampleInputUsername1">Class Name</label>
                                <input name="class_name" class="form-control" id="exampleInputUsername1"
                                    placeholder="eg. grade 1">
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
