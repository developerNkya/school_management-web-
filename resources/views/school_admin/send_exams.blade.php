@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Send Results</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick = "showModal()">Send Exams</button>
                        <div class="table-responsive pt-3">
                            <p class="card-subtitle card-subtitle-dash">Below are some of the exams that are already
                                sent to parents via sms</p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Exam Name </th>
                                        <th> Class Name </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $index => $exam)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $exam->name }}</td>
                                            <td>
                                                @foreach ($exam->classes as $class)
                                                    {{ $class->name }}<br>
                                                @endforeach
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
                        <h4 class="card-title" id="card-title">Send Results</h4>
                        <p class="card-subtitle card-subtitle-dash">Kindly select an exam to send to parents</p>

                        <form class="forms-sample" id="exam-form" method="POST" action="/results/send-exams">
                            @csrf
                            <input type="hidden" name="exam_id" id="exam-id">
                            <div class="form-group">
                                <label for="examSelect">Select Exam</label>
                                <select name="exam_id" class="form-control" id="unsentExams" required>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="classes">Select Class</label><br>
                                <select id="classes" name="class_id" class="form-control"
                                     style="width: 100%;" required>
                                </select>
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
    function showModal() {
        const unsentExams = document.getElementById('unsentExams');
        const classes = document.getElementById('classes');
        fetch('/results/unsent-exams')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                
                unsentExams.innerHTML = '<option value="">Select an Exam</option>';
                data.exams.forEach(exam => {
                    unsentExams.innerHTML +=
                        `<option value="${exam.id}">${exam.name}</option>`;
                });

                classes.innerHTML = '<option value="">Select an Class</option>';
                data.classes.forEach(classVal => {
                    classes.innerHTML +=
                        `<option value="${classVal.id}">${classVal.name}</option>`;
                });

            });
        document.getElementById('form-modal').style.display = 'block';
        document.getElementById('main-panel').style.display = 'none';
    }

    function disableModal() {

        document.getElementById('form-modal').style.display = 'none';
        document.getElementById('main-panel').style.display = 'block';
    }

</script>
