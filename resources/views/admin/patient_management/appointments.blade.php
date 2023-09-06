<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Appointments</h3>
</div>
<table class="table table-striped table-bordered-none" id="appointment_listing">
    <thead>
    <tr>
        @can('patients-management-appointments-filter_date_time')
            <th scope="col">Date & Time</th>
        @endcan
        @can('patients-management-appointments-filter_patient')
            <th scope="col">Patient</th>
        @endcan
        @can('patients-management-appointments-filter_doctor')
            <th scope="col">Doctor</th>
        @endcan
        @can('patients-management-appointments-filter_category')
            <th scope="col">Category</th>
        @endcan
        @can('patients-management-appointments-filter_appointment_type')
            <th scope="col">Appointment Type</th>
        @endcan
        @can('patients-management-appointments-filter_charges')
            <th scope="col">Charges</th>
        @endcan
        @can('patients-management-appointments-filter_status')
            <th scope="col">Status</th>
        @endcan
        <th scope="col">Action</th>
    </tr>
    <tr>
        @can('patients-management-appointments-filter_date_time')
            <th scope="col">Date & Time</th>
        @endcan
        @can('patients-management-appointments-filter_patient')
            <th scope="col">Patient</th>
        @endcan
        @can('patients-management-appointments-filter_doctor')
            <th scope="col">Doctor</th>
        @endcan
        @can('patients-management-appointments-filter_category')
            <th scope="col">Category</th>
        @endcan
        @can('patients-management-appointments-filter_appointment_type')
            <th scope="col">Appointment Type</th>
        @endcan
        @can('patients-management-appointments-filter_charges')
            <th scope="col">Charges</th>
        @endcan
        @can('patients-management-appointments-filter_status')
            <th scope="col">Status</th>
        @endcan
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @if (count($result) > 0)
        @foreach ($result as $appointment)
        <tr>
            <td>{{ date('d M', strtotime($appointment->date)).' '.date('h:i', strtotime($appointment->time))  }}</td>
            <td>{{ $appointment->user->name ?? '-' }}</td>
            <td>{{ $appointment->doctor->name ?? '-' }}</td>
            <td>{{ $appointment->priority ?? '-' }}</td>
            <td>{{ $appointment->type ?? '-' }}</td>
            <td>{{ $appointment->consultation_fee ? 'Rs. '.$appointment->consultation_fee : '-' }}</td>
            <td>{{ $appointment->progress ?? '-' }}</td>
            <td>
                <a href="{{ route('appointment-detail', ['id' => $appointment->e_id]) }}" target="_blank" class="mr-2 text-primary reports_view">View</a>
            </td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="8">There is no any appointment(s)</td>
        </tr>
    @endif
    </tbody>
</table>
<script>

    $('#appointment_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#appointment_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#appointment_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });

</script>
