@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Grades</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick="showModal()">Add Grade +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>From(%)</th>
                                        <th>To(%)</th>
                                        <th>Grade</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grades as $index => $grade)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $grade->from }}</td>
                                            <td>{{ $grade->to }}</td>
                                            <td>{{ $grade->grade }}</td>
                                            <td>
                                                <form action="{{ route('deleteById') }}" method="post" name="deletable" onsubmit="return confirmDelete(this,'grade')">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $grade->id }}">
                                                    <input type="hidden" name="table" value="grade">
                                                    <button class="btn btn-dark" type="button" onclick="confirmDelete(this,'grade')">
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
                    $paginated = $grades;
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
                        <h4 class="card-title">Add Grade</h4>
                        <form class="forms-sample" method="POST" action="/school_admin/add_grade">
                            <div class="form-group">
                                <label for="exampleInputUsername1">From</label>
                                <input type="number" name="from" class="form-control" id="exampleInputUsername1"
                                    placeholder="eg. 90">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputUsername1">To</label>
                                <input type="number" name="to" class="form-control" id="exampleInputUsername1"
                                    placeholder="eg. 100">
                            </div>
                            <div class="form-group">
                                <label for="gender">Grade</label>
                                <select name="grade" class="form-control" id="gender" required>
                                    <option value="">Select Grade</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="F">F</option>
                                </select>
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
</script>
