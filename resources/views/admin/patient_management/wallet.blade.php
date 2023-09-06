<div class="modal fade" id="view_wallet_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="view_wallet_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">View Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box_border">
                    <div class="row items_div">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Transactions</h3>
</div>

<h5>Transaction History</h5>
<table class="table table-striped table-bordered-none" id="wallet_listing">
    <thead>
    <tr>
        @can('patients-management-transactions-filter_id')
            <th scope="col">Id</th>
        @endcan
        @can('patients-management-transactions-filter_patient')
            <th scope="col">Patient</th>
        @endcan
        @can('patients-management-transactions-filter_credit')
            <th scope="col">Credit</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_type')
            <th scope="col">Transaction Type</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_by')
            <th scope="col">Transaction By</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_at')
            <th scope="col">Transaction At</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_against')
            <th scope="col">Transaction Against</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_method')
            <th scope="col">Method</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_status')
            <th scope="col">Status</th>
        @endcan
        <th scope="col">Actions</th>
    </tr>
    <tr>
        @can('patients-management-transactions-filter_id')
            <th scope="col">Id</th>
        @endcan
        @can('patients-management-transactions-filter_patient')
            <th scope="col">Patient</th>
        @endcan
        @can('patients-management-transactions-filter_credit')
            <th scope="col">Credit</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_type')
            <th scope="col">Transaction Type</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_by')
            <th scope="col">Transaction By</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_at')
            <th scope="col">Transaction At</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_against')
            <th scope="col">Transaction Against</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_method')
            <th scope="col">Method</th>
        @endcan
        @can('patients-management-transactions-filter_transaction_status')
            <th scope="col">Status</th>
        @endcan
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    @if (count($result->transactionDetails) > 0)
        @foreach ($result->transactionDetails as $transactionDetails)
{{--            @if ($transactionDetails->status == 1)--}}
                <tr>
                    <td>{{ $transactionDetails->id }}</td>
                    <td>{{ $transactionDetails->user->name }}</td>
                    <td>{{ $transactionDetails->amount ? 'Rs. '. $transactionDetails->amount : '-' }}</td>
                    <td>{{ $transactionDetails->type }}</td>
                    <td>{{ $transactionDetails->refferal_by ? $transactionDetails->refferal_by->name : 'System' }}</td>
                    <td>{{ date('d M Y', strtotime($transactionDetails->created_at)) }}</td>
                    <td>{{ $transactionDetails->reference_type }}</td>
                    <td>{{ $transactionDetails->payment_method }}</td>
                    <td>{{ $transactionDetails->status == 1 ? 'Paid' : 'Unpaid' }}</td>
                    <td>
                        <a href="javascript:void(0)" class="mr-2 text-primary view_wallet_details" data-id="{{ $transactionDetails->e_id }}">View</a>
                    </td>
                </tr>
{{--            @endif--}}
        @endforeach
    @else
        <tr>
            <td colspan="10">There is no any transactions made.</td>
        </tr>
    @endif
    </tbody>
</table>
<script>
    $('#wallet_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#wallet_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#wallet_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });

    $(document).on('click', '.view_wallet_details', function(e) {
        $('.items_div').empty();
        var data = new FormData();
        var transactionId = $(this).data('id');
        var transaction_view_url = '{{ route("patient-view-transaction", ":id") }}';
        transaction_view_url = transaction_view_url.replace(':id', patient_id);
        data.append( 'recordId',  transactionId);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $.ajax({
            url: transaction_view_url,
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                $('.items_div').html(response);
                $('#view_wallet_details_modal').modal('show');
                console.log(response);
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
            }
        });
    });
</script>
