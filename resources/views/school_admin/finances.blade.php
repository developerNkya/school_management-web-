@include('school_admin.partial_headers')


<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">My Finances</h4>
                        <p class="card-subtitle card-subtitle-dash">Note: <b>next payment amount</b> varies with the total number of students registered in the system.</p>
                        @include('helpers.message_handler')
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Last Payment</th>
                                        <th>Last Payment Date</th>
                                        <th>Pending Balance</th>
                                        <th>Next Payment Date</th>
                                        <th>Next Payment Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($finances as $index => $finance)
                                        <tr>
                                            <td>{{ $finance->last_payment }}</td>
                                            <td>{{ $finance->last_payment_date }}</td>
                                            <td>{{ $finance->pending_balance }}</td>      
                                            <td>{{ $finance->next_payment_date }}</td>   
                                            <td>{{ $finance->next_payment_amount }}</td>                                       
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    $paginated = $finances;
                    ?>
                    @include('helpers.paginator')
                </div>
            </div>

        </div>

    </div>
    @include('helpers.copyright')
    <!-- partial -->
</div>


@include('school_admin.partial_footers')

