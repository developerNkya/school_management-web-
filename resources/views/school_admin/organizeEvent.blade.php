@include('school_admin.partial_headers')

<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Events</h4>
                        @include('helpers.message_handler')
                        <button type="button" class="btn btn-dark" onclick="showModal()">Add Event +</button>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Cost</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $index => $event)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->event_date }}</td>
                                        <td>{{ $event->cost }}</td>
                                        <td>{{ $event->description }}</td>
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
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
        </div>
    </footer>
</div>

<div class="form-modal" id="form-modal" style="display: none">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Event</h4>
                        <form class="forms-sample" method="POST" action="{{ route('events.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="event_name">Event Name</label>
                                <input name="event_name" class="form-control" id="event_name" placeholder="eg. grade 1" required>
                            </div>
                            <div class="form-group">
                                <label for="event_date">Date</label>
                                <input type="date" name="event_date" class="form-control" id="event_date" required>
                            </div>
                            <div class="form-group">
                                <label for="cost">Cost</label>
                                <input type="number" name="cost" class="form-control" id="cost" placeholder="Event cost" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="3" placeholder="Event description" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                            <button type="button" class="btn btn-light" onclick="disableModal()">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    }
</script>
