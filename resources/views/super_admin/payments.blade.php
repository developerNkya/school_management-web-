@include('helpers.partials_headers')
<div class="main-panel" id="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="/#overview"
                                    role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel"
                            aria-labelledby="overview">
                            <div class="row">
                                <div class="col-md-6 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Add New School</h4>
                                            @include('helpers.message_handler')
                                            <form class="needs-validation" novalidate method="POST" action="{{ url('/super_admin/update_payment') }}">
                                                @csrf
                                            
                                                <div class="mb-3">
                                                    <label for="schoolSelect" class="form-label">School</label>
                                                    <select name="school_id" class="form-select" id="schoolSelect" required>
                                                        <option value="">Select School</option>
                                                        @foreach ($schools as $school)
                                                            <option value="{{ $school->id }}">
                                                                {{ $school->school_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select a school.
                                                    </div>
                                                </div>
                                            
                                                <div class="mb-3">
                                                    <label for="last_payment" class="form-label">Last Payment</label>
                                                    <input type="number" required name="last_payment" id="last_payment" class="form-control" step="0.01" min="0" />
                                                    <div class="invalid-feedback">
                                                        Please provide a valid last payment.
                                                    </div>
                                                </div>
                                            
                                                <div class="mb-3">
                                                    <label for="last_payment_date" class="form-label">Last Payment Date</label>
                                                    <input type="date" required name="last_payment_date" id="last_payment_date" class="form-control" />
                                                    <div class="invalid-feedback">
                                                        Please select a last payment date.
                                                    </div>
                                                </div>
                                            
                                                <div class="mb-3">
                                                    <label for="pending_balance" class="form-label">Pending Balance</label>
                                                    <input type="number" required name="pending_balance" id="pending_balance" class="form-control" step="0.01" min="0" />
                                                    <div class="invalid-feedback">
                                                        Please provide a valid pending balance.
                                                    </div>
                                                </div>
                                            
                                                <div class="mb-3">
                                                    <label for="next_payment_date" class="form-label">Next Payment Date</label>
                                                    <input type="date" required name="next_payment_date" id="next_payment_date" class="form-control" />
                                                    <div class="invalid-feedback">
                                                        Please select a next payment date.
                                                    </div>
                                                </div>
                                            
                                                <div class="mb-3">
                                                    <label for="next_payment_amount" class="form-label">Next Payment Amount</label>
                                                    <input type="number" required name="next_payment_amount" id="next_payment_amount" class="form-control" step="0.01" min="0" />
                                                    <div class="invalid-feedback">
                                                        Please provide a valid next payment amount.
                                                    </div>
                                                </div>
                                            
                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                    <button type="button" class="btn btn-light" onclick="disableModal()">Cancel</button>
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="/https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
        </div>
    </footer>
</div>

@include('helpers.partials_footers')
