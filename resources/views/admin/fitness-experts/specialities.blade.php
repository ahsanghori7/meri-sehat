<div class="modal fade" id="edit_specialities_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="edit_specialities_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="specialities_edit_submit" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Specialities</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="speciality_id" name="speciality_id" value="" />
                    <div class="row items_div">
                        <div class="col-md-12 mb-3 add">
                            <select class="form-control badge" id="speciality" name="speciality">
                                @foreach ($specialities as $key => $speciality)
                                    <option value="{{$speciality->id}}" >{{$speciality->name}}</option>
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

<div class="modal fade" id="add_specialities_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="add_specialities_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="specialities_add_submit" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Specialities</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="box_border">
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn-success btn-sm btn mb-2 cursor-pointer speciality_add_more">
                                <i class=" icon-add"></i>
                                Add New
                            </button>
                        </div>
                        <div class="row items_div">
                            <div class="col-md-12 mb-3 add">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select Speciality</label>
                                    <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 specialities_delete_this">
                                        &times;
                                    </button>
                                </div>
                                <select class="form-control badge" name="specialities[]">
                                    @foreach ($specialities as $key => $speciality)
                                        <option value="{{$speciality->id}}" >{{$speciality->name}}</option>
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
    <h3 class="mb-0">Specialities</h3>
@can('fitness-management-specialties-add')
    <div class="box-header with-border">
        <div class="d-flex justify-content-start">
            <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#add_specialities_details_modal">
                <i class=" icon-add"></i>
                Add Speciality
            </a>
        </div>
    </div>
@endcan    

</div>
@can('fitness-management-specialties-list-view')
<table class="table table-striped table-bordered-none" id="specialities_listing">
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
    @if($result->user->fitnessSpecialities && count($result->user->fitnessSpecialities) > 0)
        @foreach ($result->user->fitnessSpecialities as $speciality)
            <tr>
                <td>{{$speciality->speciality->name}}</td>
                <td>
                    @can('fitness-management-specialties-edit')
                    <a href="javascript:void(0)" class="mr-2 text-primary specialities_edit" data-toggle="modal" data-id="{{$speciality->id}}" data-target="#edit_specialities_details_modal">Edit</a>
                    @endcan
                    @can('fitness-management-specialties-delete')
                    <a href="javascript:void(0)" data-id="{{$speciality->id}}" class="text-danger specialities_delete">Delete</a>
                    @endcan
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="2">No Specialities</td>
        </tr>
    @endif
    </tbody>
</table>
@endcan
<script>
    var fitness_link = '{{$fitness_link}}';
    if (fitness_link != '') {
        $(document).find('.warning').addClass('d-none');
        $(document).find('.warning').removeClass('d-flex');
        $(document).find('.warning').removeClass('justify-content-center');
        $('.fitness_anchor').removeClass('d-none').attr('href', fitness_link);
    } else {
        $(document).find('.warning').removeClass('d-none');
        $(document).find('.warning').addClass('d-flex');
        $(document).find('.warning').addClass('justify-content-center');
        $('.fitness_anchor').addClass('d-none').attr('href', 'javascript:void(0)');
    }
    var specialities_template =
        `<div class="col-md-12 mb-3 add">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Select Speciality</label>
                    <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 specialities_delete_this">
                        &times;
                    </button>
                </div>
                <select class="form-control badge" name="specialities[]" required>
                    <option value="">Select Speciality</option>
                    @foreach ($specialities as $key => $speciality)
        <option value="{{$speciality->id}}">{{$speciality->name}}</option>
                    @endforeach
        </select>
</div>`;
    $(document).on('click', '.speciality_add_more', function() {
        $('#add_specialities_details_modal').find('.items_div').append(specialities_template);
    });

    $(document).on('click', '.specialities_delete_this', function() {
        $(this).parent().parent().remove()
    });

    $(document).on('click', '.specialities_edit', function(event) {
        event.preventDefault();
        var speciality_id = $(this).data('id');
        $('#speciality_id').val(speciality_id);

        var specialities_edit_url = '{{ route("fitness-experts-edit-specialities", ":id") }}';
        specialities_edit_url = specialities_edit_url.replace(':id', speciality_id);
        var myform = document.getElementById("specialities_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: specialities_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'GET',
            success: function (response) {
                $('#speciality').val(response.result[0].speciality_id).change();
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

    $('#specialities_edit_submit').submit(function (event) {
        event.preventDefault();
        var speciality_id = $('#speciality_id').val();
        $('#speciality_id').val(speciality_id);

        var specialities_edit_url = '{{ route("fitness-experts-edit-specialities", ":id") }}';
        specialities_edit_url = specialities_edit_url.replace(':id', speciality_id);
        var myform = document.getElementById("specialities_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: specialities_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Speciality has been updated.',
                    '',
                    'success'
                );
                $('#Specialities_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Specialities_tab').click();
            }
        });
    });

    // $('#add_specialities_details_modal').on('shown.bs.modal', function (e) {
    // do something...
    // })

    $('#add_specialities_details_modal').on('hidden.bs.modal', function (e) {
        $('#add_specialities_details_modal').find('.add').not(':first').remove();
        $('#specialities_add_submit').trigger("reset");
    });

    $('#specialities_add_submit').submit(function (event) {
        event.preventDefault();
        var specialities_add_url = '{{ route("fitness-experts-update-specialities", ":id") }}';
        specialities_add_url = specialities_add_url.replace(':id', fitness_experts_id);
        var myform = document.getElementById("specialities_add_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: specialities_add_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Speciality has been added.',
                    '',
                    'success'
                );
                $('#Specialities_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Specialities_tab').click();
            }
        });
        $('#add_specialities_details_modal').find('.add').not(':first').remove();
        $('#specialities_add_submit').trigger("reset");

    });

    $(document).on('click', '.specialities_delete', function(e) {
        var data = new FormData();
        var specialityId = $(this).data('id');
        var specialities_delete_url = '{{ route("fitness-experts-delete-specialities", ":id") }}';
        data.append( 'recordId',  specialityId);
        specialities_delete_url = specialities_delete_url.replace(':id', fitness_experts_id);
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
                    url: specialities_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Speciality has been deleted.',
                            '',
                            'success'
                        );
                        $('#Specialities_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Specialities_tab').click();
                    }
                });
            }
        })
    });

    $('#specialities_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#specialities_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#specialities_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });

</script>
