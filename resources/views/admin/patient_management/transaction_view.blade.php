<div class="row">
    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Transaction ID</label>
        </div>
        {{ $result->id }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Patient Name</label>
        </div>
        {{ $result->user->name }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Transaction Against</label>
        </div>
        {{ $result->reference_type }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Payment Method</label>
        </div>
        {{ $result->payment_method }}
    </div>
</div>

@if ($result->reference_type == 'appointment' && isset($result->appointment))
<div class="row is_appointment">
    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Appointment ID</label>
        </div>
        {{ $result->appointment->id }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Appointment Date/Time</label>
        </div>
        {{ date('d M Y', strtotime($result->appointment->date)). ' '. date('h:i a', strtotime($result->appointment->time)) }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Doctor Name</label>
        </div>
        {{ $result->appointment->doctor->name }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Type</label>
        </div>
        {{ $result->appointment->type }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Reason</label>
        </div>
        {{ $result->appointment->reason }}
    </div>

    <div class="col-md-12 mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <label>Status</label>
        </div>
        {{ $result->appointment->progress }}
    </div>
</div>
@endif
@if ($result->reference_type == 'subscription' && isset($result->subscription))
    <div class="row is_subscription">
        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Subscription ID</label>
            </div>
            {{ $result->subscription->id }}
        </div>

        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Appointment Date/Time</label>
            </div>
            {{ date('d M Y @ h:i a', strtotime($result->subscription->created_at)) }}
        </div>

        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Subscription Name</label>
            </div>
            {{ $result->subscription->package->name }}
        </div>

        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Subscription Start</label>
            </div>
            {{ date('d M Y @ h:i a', strtotime($result->subscription->start_date)) }}
        </div>

        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Subscription End</label>
            </div>
            {{ date('d M Y @ h:i a', strtotime($result->subscription->end_date)) }}
        </div>
    </div>
@endif
