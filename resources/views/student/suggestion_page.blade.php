@include('student.partial_headers')

<div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Suggestion</h4>
                    @include('helpers.message_handler')
                    <p class="card-description">Feel free to provide us with suggestions! we would like to here from you! </p>
                    <form class="forms-sample" action="{{ url('/student/add-suggestion') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control">
                                <option value="issue">Issue</option>
                                <option value="suggestion">Suggestion</option>
                            </select>
                        </div>
        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea  class="form-control textArea-body" id="message" name="message" placeholder="Your message here"></textarea>
                        </div>
        
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        
      </div>
    </div>
    @include('helpers.copyright')
    <!-- partial -->
  </div>

@include('student.partial_footers')
