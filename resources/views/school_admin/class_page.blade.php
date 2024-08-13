@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Classes</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Add Class +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Class Name </th>
                                        <th> Total Students </th>
                                        <th> Total Teachers </th>
                                        <th> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $class)
                                        <tr>
                                            <td>1</td>
                                            <td>{{ $class->name }}</td>
                                            <td>{{ $class->name }}</td>
                                            <td>{{ $class->name }}</td>
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

                            <h3 class="card-title">Sections</h3>
                            <div id="sectionsContainer" class="form-group">
                                <div class="section-container" style="display: flex; margin-bottom: 10px;">
                                    <input name="sections[]" class="form-control" placeholder="Section name">
                                </div>
                                <button type="button" class="btn btn-dark addBtn" onclick="addSection('sectionsContainer')">Add</button>
                            </div>

                            <h3 class="card-title">Subjects</h3>
                            <div id="subjectsContainer" class="form-group">
                                <div class="section-container" style="display: flex; margin-bottom: 10px;">
                                    <input name="subjects[]" class="form-control" placeholder="Subject name">
                                </div>
                                <button type="button" class="btn btn-dark addBtn" onclick="addSection('subjectsContainer')">Add</button>
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
    <!-- content-wrapper ends -->
    <!-- partial:../../partials/_footer.html -->
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

