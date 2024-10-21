@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Track Attendance</h4>
                        @include('helpers.message_handler')
                        <div class="form-group">
                            <input type="text" id="searchField" class="form-control"
                                placeholder="Search Driver by name or email...">
                        </div>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered" id="usersTable">
                                <thead>
                                    <tr>
                                        <th> No.</th>
                                        <th> Names </th>
                                        <th> Gender</th>
                                        <th> Nationality</th>
                                        <th>Phone No</th>
                                        <th>Email</th>
                                        <th>View Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($drivers as $index => $driver)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $driver->name }}</td>
                                            <td>{{ $driver->gender }}</td>
                                            <td>{{ $driver->location }}</td>
                                            <td>{{ $driver->phone }}</td>
                                            <td>{{ $driver->email }}</td>
                                            <td>
                                                <button class="btn btn-primary" type="button"
                                                    onclick="viewAttendance('{{ $driver->id }}')">
                                                    <i class="fa fa-eye" style="font-size:20px;color:white"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    $paginated = $drivers;
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>

        </div>
    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>


<div class="main-panel" id="form-modal" style="display: none">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-m-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Driver Attendance</h4>
                        @include('helpers.message_handler')

                        <div class="table-responsive pt-1">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Driver Name</th>
                                        <td id="attendance-driver"></td>
                                    </tr>
                                    <tr>
                                        <th>Current Activity</th>
                                        <td id="current-activity"></td>
                                    </tr>

                                    <tr>
                                        <th>Taken Attendance</th>
                                        <td id="taken-attendance"></td>
                                    </tr>

                                    <tr id="total-attendance-row" style="display: none;">
                                        <th>Total Students</th>
                                        <td id="total-attendance"></td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td id="attendance-date"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div>
                                <button type="button" class="btn btn-light" onclick="disableModal()"
                                    style="margin-top:3%">Back</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@include('school_admin.partial_footers')
<script>
    function viewAttendance(driverId) {

        $.ajax({
            url: '/bus-management/driver-attendance',
            method: 'GET',
            data: {
                driver_id: driverId,
                toJson: true
            },
            success: function(response) {
                console.log(response);


                $('#attendance-driver').text(response.driver_name);
                $('#current-activity').text(response.activity);
                $('#taken-attendance').text(response.driver_attendance.data.length);
                $('#total-attendance').text(response.total_students);
                $('#attendance-date').text(response.date);


                if (response.activity !== 'Onboard Students from Home' && response.activity !=='Not started Trip') {
                    $('#total-attendance-row').show();
                } else {
                    $('#total-attendance-row').hide();
                }


                document.getElementById('form-modal').style.display = 'block';
                document.getElementById('main-panel').style.display = 'none';
                $('#form-modal').show();
            },
            error: function() {
                alert('Failed to fetch driver attendance data.');
            }
        });
    }


    function showModal() {
        document.getElementById('form-modal').style.display = 'block';
        document.getElementById('main-panel').style.display = 'none';
    }

    function disableModal() {
        document.getElementById('form-modal').style.display = 'none';
        document.getElementById('main-panel').style.display = 'block';
        event.preventDefault();
    }

    document.getElementById('searchField').addEventListener('keyup', function() {
        const searchValue = this.value.trim().toLowerCase();
        if (searchValue.length > 0) {
            fetch(`/bus-management/filter-drivers/${encodeURIComponent(searchValue)}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#usersTable tbody');
                    tableBody.innerHTML = '';

                    const drivers = data[0].data;

                    console.log(drivers);

                    drivers.forEach((driver, index) => {
                        const row = `
                        <tr>
                            <td>${index + 1}</td>
                         <td> ${driver.driver_name}</td>
                            <td>${ driver.gender }</td>
                           <td>${ driver.location }</td>
                           <td>${ driver.phone }</td>
                           <td>${ driver.email }</td>
                            <td>
                               <button class="btn btn-primary" type="button" onclick="viewAttendance('${ driver.driver_id }')">
                                                    <i class="fa fa-eye" style="font-size:20px;color:white"></i>
                                 </button>
                             </td>
                        </tr>
                    `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                });
        }
    });
</script>
