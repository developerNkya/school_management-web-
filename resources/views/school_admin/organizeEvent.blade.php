@include('school_admin.partial_headers')
<?php
$value = request()->session()->get('user_name', '');  
$role = request()->session()->get('role_id', '');  
?>
<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Events</h4>
                        @include('helpers.message_handler')
                        @if($role == 2)  
                        <button type="button" class="btn btn-dark" onclick="showModal({{$total_events}})">Add Event +</button>
                        <p class="statistics-title" style="margin-top:3%">Total Events:<b>{{ $total_events }} of 20 events</b>
                        @endif
                        <div class="table-responsive pt-3">
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered" style="width: 100%; table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No.</th>
                                            <th style="width: 150px;">Name</th>
                                            <th style="width: 100px;">Date</th>
                                            <th style="width: 100px;">Cost</th>
                                            <th style="width: 400px; word-wrap: break-word; white-space: normal;">Description</th>
                                            @if($role == 2)  
                                            <th style="width: 100px;">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $index => $event)
                                        <tr>
                                            <td style="width: 50px;">{{ $index + 1 }}</td>
                                            <td style="width: 150px;">{{ $event->name }}</td>
                                            <td style="width: 100px;">{{ $event->event_date }}</td>
                                            <td style="width: 100px;">{{ $event->cost }}</td>
                                            <td style="width: 400px; word-wrap: break-word; white-space: normal;">{{ $event->description }}</td>
                                            @if($role == 2)  
                                            <td>
                                                <form action="{{ route('deleteById') }}" method="post" name="deletable" onsubmit="return confirmDelete(this,'event')">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $event->id }}">
                                                    <input type="hidden" name="table" value="event">
                                                    <button class="btn btn-dark" type="button" onclick="confirmDelete(this,'event')">
                                                        <i class="fa fa-trash-o" style="font-size:20px;color:white"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                    $paginated = $events
                    ?>
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
                        <h4 class="card-title">Add Event</h4>
                        @include('helpers.message_handler')
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
                                <textarea name="description" class="form-control textArea-body" id="description" rows="3" placeholder="Event description" required></textarea>
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
    function showModal(size) {
        if (size>=20) {
            window.alert("Event Space is full, kindly delete some events to proceed");
        }else{
         document.getElementById('form-modal').style.display = 'block';
         document.getElementById('main-panel').style.display = 'none';
        }
    }

    function disableModal() {
        document.getElementById('form-modal').style.display = 'none';
        document.getElementById('main-panel').style.display = 'block';
    }
</script>
