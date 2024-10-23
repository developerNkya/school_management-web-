@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Classes</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick="showModal('add')">Add Class +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Class Name</th>
                                        <th>Total Students</th>
                                        <th>Supervisor Name</th>
                                        <th>Supervisor Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $index => $class)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $class->info->name }}</td>
                                            <td>{{ $class->total_students }}</td>
                                            <td>{{ $class->info->supervisor_name }}</td>
                                            <td>{{ $class->info->supervisor_phone }}</td>
                                            <td style="display: flex; gap: 5px;">
                            
                                                <a href="#" class="btn btn-info" role="button" style="border-radius: 0;" 
                                                onclick="showModal('edit', {{ json_encode($class) }})">
                                                 <i class="fa fa-pencil" style="font-size:20px;color:white"></i>
                                             </a>
                                             <form action="{{ route('deleteById') }}" method="post" name="deletable" onsubmit="return confirmDelete(this)">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $class->info->id }}">
                                                <input type="hidden" name="table" value="schoolClass">
                                                <button class="btn btn-dark" type="button" onclick="confirmDelete(this,'class')" style="border-radius: 0;">
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
    
</div>

<div class="form-modal" id="form-modal" style="display: none">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" id="form-title">Add Class</h4>

                        
                        <div id="edit-card" class="card mb-4" style="border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; display:none;">
                            <h5>Current Class Details</h5>
                            <p><strong>Class Name:</strong> <span id="current-class-name"></span></p>
                            <p><strong>Supervisor's Name:</strong> <span id="current-supervisor-name"></span></p>
                            <p><strong>Supervisor's Phone:</strong> <span id="current-supervisor-phone"></span></p>
                        </div>

                        
                        <form class="forms-sample" method="POST" id="class-form">
                            @csrf
                            <div class="form-group">
                                <label for="class_name">Class Name</label>
                                <input name="class_name" class="form-control" id="class_name"
                                    placeholder="eg. grade 1">

                                    <input name="type" class="form-control" id="type" style="display:none">
                                    <input name="id" class="form-control" id="id" style="display:none">
                            </div>

                            <div class="form-group">
                                <label for="supervisor_name">Supervisor's Name</label>
                                <input name="supervisor_name" class="form-control" id="supervisor_name"
                                    placeholder="eg. calton">
                            </div>

                            <div class="form-group">
                                <label for="supervisor_phone">Supervisor's Phone</label>
                                <input name="supervisor_phone" class="form-control" id="supervisor_phone"
                                    placeholder="e.g. 0234567890" required>
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
    
</div>


@include('school_admin.partial_footers')
<script>
    function showModal(type, classData) {
    event.preventDefault();
    if (type == 'edit') {
        
        document.getElementById("form-title").innerHTML = "Edit Class";
        document.getElementById("class-form").action = "/school_admin/save-class";
        
        
        document.getElementById("edit-card").style.display = 'block';
        document.getElementById("current-class-name").innerText = classData.info.name;
        document.getElementById("current-supervisor-name").innerText = classData.info.supervisor_name;
        document.getElementById("current-supervisor-phone").innerText = classData.info.supervisor_phone;

        
        document.getElementById("class_name").value = classData.info.name;
        document.getElementById("supervisor_name").value = classData.info.supervisor_name;
        document.getElementById("supervisor_phone").value = classData.info.supervisor_phone;
        document.getElementById("type").value = 'edit';
        document.getElementById("id").value = classData.info.id;
    } else {
        
        document.getElementById("form-title").innerHTML = "Add Class";
        document.getElementById("class-form").action = "/school_admin/save-class";
        document.getElementById("type").value = 'add';
        
        
        document.getElementById("edit-card").style.display = 'none';     
        document.getElementById("class_name").value = '';
        document.getElementById("supervisor_name").value = '';
        document.getElementById("supervisor_phone").value = '';
    }

    
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
