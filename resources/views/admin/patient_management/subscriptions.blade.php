<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Subscriptions</h3>
</div>

<h5 class="mb-3">Active Subscription</h5>
@if (isset($result->subscription))
    <div class="border border-dark rounded p-4 w-25">
        <div class="mb-2">{{ $result->subscription->package->duration_text.' '.$result->subscription->package->name.' Premium' }}</div>
        <div class="fs-20 mb-2"><strong>Rs. {{ $result->subscription->package->price.' '.$result->subscription->package->duration_text }}</strong></div>
        <div class="fs-14 mb-2">Expires on {{ date('d M Y', strtotime($result->subscription->end_date)) }}</div>
        <button type="button" data-id="{{ $result->subscription->id }}" class="btn btn-danger subscription_delete">Cancel Subscription</button>
    </div>
@else
    <div class="border border-dark rounded p-4 w-25">
        <div>No any active subscription</div>
    </div>
@endif
<script>
    $(document).on('click', '.subscription_delete', function(e) {
        var data = new FormData();
        var subscriptionId = $(this).data('id');
        var subscriptions_delete_url = '{{ route("patient-delete-subscription", ":id") }}';
        data.append( 'recordId',  subscriptionId);
        subscriptions_delete_url = subscriptions_delete_url.replace(':id', patient_id);
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: subscriptions_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Subscription has been cancelled.',
                            '',
                            'success'
                        );
                        $('#Subscriptions_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Subscriptions_tab').click();
                    }
                });
            }
        })
    });
</script>
