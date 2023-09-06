<div class="modal fade" id="edit_experiences_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="edit_experiences_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="experiences_edit_submit" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" id="experience_id" name="experience_id" value="" />
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Experiences</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box_border">
                    <div class="row items_div">
                        <div class="row item add">
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select Designation</label>
                                </div>
                                <select class="form-control badge" id="position" name="position">
                                    <option value="">Please select position</option>
                                    @foreach ($positions as $key => $position)
                                        <option value="{{$position}}">{{$position}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Institute</label>
                                </div>
                                <input type="text" class="form-control" required id="institute" name="institute">
                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Start Year</label>
                                </div>
                                <input type="number" class="form-control" required id="start_year" name="start_year">
                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Year of completion (leave empty if not completed)</label>
                                </div>
                                <input type="number" class="form-control" id="year_of_completion" name="year_of_completion">
                            </div>

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

<div class="modal fade" id="add_experiences_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="add_experiences_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="experiences_add_submit" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Experiences</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="box_border">
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn-success btn-sm btn mb-2 cursor-pointer experience_add_more">
                                <i class=" icon-add"></i>
                                Add New
                            </button>
                        </div>
                        <div class="row items_div">
                            <div class="row item add">
                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Select Designation</label>
                                    </div>
                                    <select class="form-control badge" name="position[]">
                                        <option value="">Please select position</option>
                                        @foreach ($positions as $key => $position)
                                            <option value="{{$position}}">{{$position}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Institute</label>
                                    </div>
                                    <input type="text" class="form-control" required name="institute[]">
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Start Year</label>
                                    </div>
                                    <input type="number" class="form-control" required name="start_year[]">
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Year of completion (leave empty if not completed)</label>
                                    </div>
                                    <input type="number" class="form-control" name="year_of_completion[]">
                                </div>

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
    <h3 class="mb-0">Experiences</h3>
    @can('doctor-management-experience-add')
    <div class="box-header with-border">
        <div class="d-flex justify-content-start">
            <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#add_experiences_details_modal">
                <i class=" icon-add"></i>
                Add Experiences
            </a>
        </div>
    </div>
    @endcan

</div>
@can('doctor-management-experience-list-view')
<table class="table table-striped table-bordered-none" id="experiences_listing">
    <thead>
    <tr>
        <th scope="col">Position</th>
        <th scope="col">Institute</th>
        <th scope="col">Start Year</th>
        <th scope="col">End Year</th>
        <th scope="col">Action</th>
    </tr>
    <tr>
        <th scope="col">Position</th>
        <th scope="col">Institute</th>
        <th scope="col">Start Year</th>
        <th scope="col">End Year</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @if($result->user->doctorExperiences && count($result->user->doctorExperiences) > 0)
        @foreach ($result->user->doctorExperiences as $experience)
            <tr>
                <td>{{$experience->position ?? '-' }}</td>
                <td>{{$experience->institute ?? '-' }}</td>
                <td>{{$experience->start_year ?? '-' }}</td>
                <td>{{$experience->end_year ?? '-' }}</td>
                <td>
                    @can('doctor-management-experience-edit')
                    <a href="javascript:void(0)" class="mr-2 text-primary experiences_edit" data-toggle="modal" data-id="{{$experience->id}}" data-target="#edit_experiences_details_modal">Edit</a>
                    @endcan
                    @can('doctor-management-experience-delete')
                        <a href="javascript:void(0)" data-id="{{$experience->id}}" class="text-danger experiences_delete">Delete</a>
                    @endcan
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">No Experiences</td>
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
    var experiences_template =
        `<div class="row item add">
            <div class="col-md-12 d-flex justify-content-end align-items-center mb-3">
                <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 experiences_delete_this">
                    &times;
                </button>
            </div>

            <div class="col-md-12 mb-3">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Select Designation</label>
                </div>
                <select class="form-control badge" name="position[]">
                    <option value="">Please select position</option>
                    @foreach ($positions as $key => $position)
                        <option value="{{$position}}">{{$position}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Institute</label>
                </div>
                <input type="text" class="form-control" required name="institute[]">
            </div>

            <div class="col-md-12 mb-3">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Start Year</label>
                </div>
                <input type="number" class="form-control" required name="start_year[]">
            </div>

            <div class="col-md-12 mb-3">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Year of completion (leave empty if not completed)</label>
                </div>
                <input type="number" class="form-control" name="year_of_completion[]">
            </div>
        </div>`;
    $(document).on('click', '.experience_add_more', function() {
        $('#add_experiences_details_modal').find('.items_div').append(experiences_template);
    });

    $(document).on('click', '.experiences_delete_this', function() {
        $(this).parent().parent().remove()
    });

    $(document).on('click', '.experiences_edit', function(event) {
        event.preventDefault();
        var experience_id = $(this).data('id');
        $('#experience_id').val(experience_id);

        var experiences_edit_url = '{{ route("doctor-edit-experiences", ":id") }}';
        experiences_edit_url = experiences_edit_url.replace(':id', experience_id);
        var myform = document.getElementById("experiences_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: experiences_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'GET',
            success: function (response) {
                $('#position').val(response.result[0].position).change();
                $('#institute').val(response.result[0].institute);
                $('#start_year').val(response.result[0].start_year);
                $('#year_of_completion').val(response.result[0].end_year);
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

    $('#experiences_edit_submit').submit(function (event) {
        event.preventDefault();
        var experience_id = $('#experience_id').val();
        $('#experience_id').val(experience_id);

        var experiences_edit_url = '{{ route("doctor-edit-experiences", ":id") }}';
        experiences_edit_url = experiences_edit_url.replace(':id', experience_id);
        var myform = document.getElementById("experiences_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: experiences_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Experiences has been updated.',
                    '',
                    'success'
                );
                $('#Experiences_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Experiences_tab').click();
            }
        });
    });

    // $('#add_experiences_details_modal').on('shown.bs.modal', function (e) {
    // do something...
    // })

    $('#add_experiences_details_modal').on('hidden.bs.modal', function (e) {
        $('#add_experiences_details_modal').find('.add').not(':first').remove();
        $('#experiences_add_submit').trigger("reset");
    });

    $('#experiences_add_submit').submit(function (event) {
        event.preventDefault();

        var experiences_add_url = '{{ route("doctor-update-experiences", ":id") }}';
        experiences_add_url = experiences_add_url.replace(':id', doctor_id);
        var myform = document.getElementById("experiences_add_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: experiences_add_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Experience has been added.',
                    '',
                    'success'
                );
                $('#Experiences_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Experiences_tab').click();
            }
        });
        $('#add_experiences_details_modal').find('.add').not(':first').remove();
        $('#experiences_add_submit').trigger("reset");

    });

    $(document).on('click', '.experiences_delete', function(e) {
        var data = new FormData();
        var experienceId = $(this).data('id');
        var experiences_delete_url = '{{ route("doctor-delete-experiences", ":id") }}';
        data.append( 'recordId',  experienceId);
        experiences_delete_url = experiences_delete_url.replace(':id', doctor_id);
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
                    url: experiences_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Experience has been deleted.',
                            '',
                            'success'
                        );
                        $('#Experiences_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Experiences_tab').click();
                    }
                });
            }
        })
    });

    $('#experiences_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#experiences_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#experiences_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });

</script>
