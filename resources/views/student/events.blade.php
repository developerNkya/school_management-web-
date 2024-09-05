@include('student.partial_headers')
<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Events</h4>
                        @include('helpers.message_handler')
                        <div class="table-responsive pt-3">
                            <!-- Wrapper for horizontal scroll -->
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered" style="width: 100%; table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No.</th>
                                            <th style="width: 150px;">Name</th>
                                            <th style="width: 100px;">Date</th>
                                            <th style="width: 100px;">Cost</th>
                                            <th style="width: 400px; word-wrap: break-word; white-space: normal;">Description</th>
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
@include('helpers.partials_footers')

