@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">


    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Suggestions</h4>
                        @include('helpers.message_handler')
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Parent Name </th>
                                        <th> Student Name</th>
                                        <th> Class</th>
                                        <th> Suggestion</th>
                                        <th> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suggestions as $index=>$suggestion)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <?php
                                             $parent_full_name =  $suggestion->student->parent_first_name. ' ' . $suggestion->student->parent_last_name; 
                                             $student_full_name =  $suggestion->student->first_name. ' '.$suggestion->student->last_name 
                                            ?>
                                            <td>{{$parent_full_name }}</td>
                                            <td>{{  $student_full_name }}</td>
                                            <td>{{  $suggestion->student->schoolclass->name  }}</td>
                                            <td>{{ $suggestion->suggestion  }}</td>
                                            <td>  
                                                <form action="{{ route('deleteSuggestion') }}" method="post" name="delete_suggestion">
                                                    <input type="hidden" name="id" value="{{ $suggestion->id }}">                  
                                                <button class="btn btn-dark" type="submit">
                                                    <i class="fa fa-trash-o" style="font-size:20px;color:white"></i>
                                                </button>                                         
                                                </form>                                             
                                                </td>
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

@include('school_admin.partial_footers')
