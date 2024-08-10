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
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($classes as $class)
                                    <tr>
                                        <td>1</td>
                                        <td>{{$class->name}}</td>
                                        <td>{{$class->name}}</td>
                                        <td>{{$class->name}}</td>
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
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a
                    href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
                BootstrapDash.</span>
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
                <input name="class_name" class="form-control" id="exampleInputUsername1" placeholder="eg. grade 1">
              </div>
              <button type="submit" class="btn btn-primary me-2">Submit</button>
              <button class="btn btn-light" onclick="disableModal()">Cancel</button>
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
      <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
      <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
    </div>
  </footer>
  <!-- partial -->
</div>


<script>
    function showModal() {
        // alert("Welcome to the javaTpoint.com");
        document.getElementById( 'form-modal' ).style.display = 'block';
        document.getElementById( 'main-panel' ).style.display = 'none';
    }

    function disableModal() {
        // alert("Welcome to the javaTpoint.com");
        document.getElementById( 'form-modal' ).style.display = 'none';
        document.getElementById( 'main-panel' ).style.display = 'block';
        event.preventDefault();
    }

   
</script>
@include('school_admin.partial_footers')
