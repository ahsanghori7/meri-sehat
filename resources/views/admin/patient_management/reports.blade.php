<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Reports</h3>
</div>
{{--@dd($result)--}}
<table class="table table-striped table-bordered-none" id="reports_listing">
    <thead>
    <tr>
        @can('patients-management-reports-filter_name')
            <th scope="col">Name</th>
        @endcan
        @can('patients-management-reports-filter_uploaded_date')
            <th scope="col">Uploaded Date</th>
        @endcan
        <th scope="col">Action</th>
    </tr>
    <tr>
        @can('patients-management-reports-filter_name')
            <th scope="col">Name</th>
        @endcan
        @can('patients-management-reports-filter_uploaded_date')
            <th scope="col">Uploaded Date</th>
        @endcan
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @if (count($result) > 0)
        @foreach ($result as $result_list)
            @foreach ($result_list->medicalRecordFiles as $report)
                <tr>
                    <td>{{ $report->file }}</td>
                    <td>{{ date('d M Y', strtotime($report->created_at)) }}</td>
                    <td>
                        <a href="{{ env('ASSETS_STORAGE').'user'.$result_list->user->id.'/'.$report->file }}" target="_blank" class="mr-2 text-primary">View</a>
                    </td>
                </tr>
            @endforeach
        @endforeach
    @else
        <tr>
            <td colspan="3">There is no any report(s)</td>
        </tr>
    @endif
    </tbody>
</table>
<script>
    $('#reports_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#reports_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#reports_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });
</script>
