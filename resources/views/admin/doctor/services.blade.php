<div class="modal fade" id="edit_services_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="edit_services_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="services_edit_submit" enctype="multipart/form-data" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Services</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" id="service_id" name="service_id" value="" />
                <div class="row items_div">
                    <div class="col-md-12 mb-3 add">
                        <select class="form-control badge" id="service" name="service">
                            @foreach ($services as $key => $service)
                                <option value="{{$service->id}}" >{{$service->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_services_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="add_services_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="services_add_submit" enctype="multipart/form-data" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Services</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="box_border">
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn-success btn-sm btn mb-2 cursor-pointer service_add_more">
                            <i class=" icon-add"></i>
                            Add New
                        </button>
                    </div>
                    <div class="row items_div">
                        <div class="col-md-12 mb-3 add">
                            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                <label>Select Service</label>
                                <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 services_delete_this">
                                    &times;
                                </button>
                            </div>
                            <select class="form-control badge" name="service[]">
                                @foreach ($services as $key => $service)
                                    <option value="{{$service->id}}" >{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Services</h3>
@can('doctor-management-service-add-services')
    <div class="box-header with-border">
        <div class="d-flex justify-content-start">
            <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#add_services_details_modal">
                <i class=" icon-add"></i>
                Add Services
            </a>
        </div>
    </div>
@endcan    

</div>
@can('doctor-management-service-list-view')
<table class="table table-striped table-bordered-none" id="services_listing">
    <thead>
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Action</th>
    </tr>
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @if($result->user->doctorServices && count($result->user->doctorServices) > 0)
        @foreach ($result->user->doctorServices as $service)
        <tr>
            <td>{{$service->service->name}}</td>
            <td>
                @can('doctor-management-service-edit-services')
                <a href="javascript:void(0)" class="mr-2 text-primary services_edit" data-toggle="modal" data-id="{{$service->id}}" data-target="#edit_services_details_modal">Edit</a>
                @endcan
                @can('doctor-management-service-delete')
                <a href="javascript:void(0)" data-id="{{$service->id}}" class="text-danger services_delete">Delete</a>
                @endcan
            </td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="2">No Services</td>
        </tr>
    @endif
    </tbody>
</table>
@endcan
<script>
    var doctor_link = '{{$doctor_link}}';
    if (doctor_link != '') {
        $(document).find('.warning').addClass('d-none');
        $(document).find('.warning').removeClass('d-flex');
        $(document).find('.warning').removeClass('justify-content-center');
        $('.doctor_anchor').removeClass('d-none').attr('href', doctor_link);
    } else {
        $(document).find('.warning').removeClass('d-none');
        $(document).find('.warning').addClass('d-flex');
        $(document).find('.warning').addClass('justify-content-center');
        $('.doctor_anchor').addClass('d-none').attr('href', 'javascript:void(0)');
    }
    var services_template =
        `<div class="col-md-12 mb-3 add">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Select Service</label>
                    <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 services_delete_this">
                        &times;
                    </button>
                </div>
                <select class="form-control badge" name="service[]" required>
                    <option value="">Select Service</option>
                    @foreach ($services as $key => $service)
                    <option value="{{$service->id}}">{{$service->name}}</option>
                    @endforeach
                </select>
        </div>`;
    $(document).on('click', '.service_add_more', function() {
        $('#add_services_details_modal').find('.items_div').append(services_template);
    })

    $(document).on('click', '.services_delete_this', function() {
        $(this).parent().parent().remove()
    })

    $(document).on('click', '.services_edit', function(event) {
        event.preventDefault();
        var service_id = $(this).data('id');
        $('#service_id').val(service_id);

        var services_edit_url = '{{ route("doctor-edit-services", ":id") }}';
        services_edit_url = services_edit_url.replace(':id', service_id);
        var myform = document.getElementById("services_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: services_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'GET',
            success: function (response) {
                $('#service').val(response.result[0].service_id).change();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
            }
        });
    })

    $('#services_edit_submit').submit(function (event) {
        event.preventDefault();
        var service_id = $('#service_id').val();
        $('#service_id').val(service_id);

        var services_edit_url = '{{ route("doctor-edit-services", ":id") }}';
        services_edit_url = services_edit_url.replace(':id', service_id);
        var myform = document.getElementById("services_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: services_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Service has been updated.',
                    '',
                    'success'
                );
                $('#Services_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Services_tab').click();
            }
        });
    });

    // $('#edit_services_details_modal').on('shown.bs.modal', function (e) {
        // do something...
    // })



    $('#add_services_details_modal').on('hidden.bs.modal', function (e) {
        $('#add_services_details_modal').find('.add').not(':first').remove();
        $('#services_add_submit').trigger("reset");
    });

    $('#services_add_submit').submit(function (event) {
        event.preventDefault();

        var services_add_url = '{{ route("doctor-update-services", ":id") }}';
        services_add_url = services_add_url.replace(':id', doctor_id);
        var myform = document.getElementById("services_add_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: services_add_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Service has been added.',
                    '',
                    'success'
                );
                $('#Services_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Services_tab').click();
            }
        });
        $('#add_services_details_modal').find('.add').not(':first').remove();
        $('#services_add_submit').trigger("reset");
    });

    $(document).on('click', '.services_delete', function(e) {
        var data = new FormData();
        var serviceId = $(this).data('id');
        var services_delete_url = '{{ route("doctor-delete-services", ":id") }}';
        data.append( 'recordId',  serviceId);
        services_delete_url = services_delete_url.replace(':id', doctor_id);
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: services_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Service has been deleted.',
                            '',
                            'success'
                        );
                        $('#Services_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Services_tab').click();
                    }
                });
            }
        })
    });

    $('#services_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#services_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#services_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });

</script>
