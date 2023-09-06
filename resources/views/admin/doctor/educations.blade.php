<div class="modal fade" id="edit_educations_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="edit_educations_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="educations_edit_submit" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" id="education_id" name="education_id" value="" />
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Educations</h5>
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
                                    <label>Select Degree</label>
                                </div>
                                <select class="form-control badge" id="degree" name="degree">
                                    <option value="">Please select degree</option>
                                    @foreach ($degrees as $key => $degree)
                                        <option value="{{$degree->id}}">{{$degree->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select University</label>
                                </div>
                                <select class="form-control badge" id="university" name="university">
                                    <option value="">Please select university</option>
                                    @foreach ($universities as $key => $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                    @endforeach
                                </select>
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

<div class="modal fade" id="add_educations_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="add_educations_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="educations_add_submit" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Educations</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="box_border">
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn-success btn-sm btn mb-2 cursor-pointer education_add_more">
                                <i class=" icon-add"></i>
                                Add New
                            </button>
                        </div>
                        <div class="row items_div">
                            <div class="row item add">
                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Select Degree</label>
                                    </div>
                                    <select class="form-control badge" name="degree[]">
                                        <option value="">Please select degree</option>
                                        @foreach ($degrees as $key => $degree)
                                            <option value="{{$degree->id}}">{{$degree->name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Select University</label>
                                    </div>
                                    <select class="form-control badge" name="university[]">
                                        <option value="">Please select university</option>
                                        @foreach ($universities as $key => $university)
                                            <option value="{{$university->id}}">{{$university->name}}</option>
                                        @endforeach
                                    </select>
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
    <h3 class="mb-0">Educations</h3>
    @can('doctor-management-education-Add')
    <div class="box-header with-border">
        <div class="d-flex justify-content-start">
            <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#add_educations_details_modal">
                <i class=" icon-add"></i>
                Add Education
            </a>
        </div>
    </div>
    @endcan

</div>
@can('doctor-management-education-list-view')
<table class="table table-striped table-bordered-none" id="educations_listing">
    <thead>
    <tr>
        <th scope="col">Degree</th>
        <th scope="col">Institute</th>
        <th scope="col">Year Completion</th>
        <th scope="col">Action</th>
    </tr>
    <tr>
        <th scope="col">Degree</th>
        <th scope="col">Institute</th>
        <th scope="col">Year Completion</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @if($result->user->doctorEducation && count($result->user->doctorEducation) > 0)
        @foreach ($result->user->doctorEducation as $education)
            <tr>
                <td>{{$education->degree}}</td>
                <td>{{$education->institute}}</td>
                <td>{{$education->year_of_completion}}</td>
                <td>
                    @can('doctor-management-education-edit')
                    <a href="javascript:void(0)" class="mr-2 text-primary educations_edit" data-toggle="modal" data-id="{{$education->id}}" data-target="#edit_educations_details_modal">Edit</a>
                    @endcan
                    @can('doctor-management-education-delete')
                    <a href="javascript:void(0)" data-id="{{$education->id}}" class="text-danger educations_delete">Delete</a>
                    @endcan
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4">No Educations</td>
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
    var educations_template =
    `<div class="row item add">
        <div class="col-md-12 d-flex justify-content-end align-items-center mb-3">
            <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 educations_delete_this">
                &times;
            </button>
        </div>

        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Select Degree</label>
            </div>
            <select class="form-control badge" name="degree[]">
                <option value="">Please select degree</option>
                @foreach ($degrees as $key => $degree)
                    <option value="{{$degree->id}}">{{$degree->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Select University</label>
            </div>
            <select class="form-control badge" name="university[]">
                <option value="">Please select university</option>
                @foreach ($universities as $key => $university)
                    <option value="{{$university->id}}">{{$university->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12 mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                <label>Year of completion (leave empty if not completed)</label>
            </div>
            <input type="number" class="form-control" name="year_of_completion[]">
        </div>
    </div>`;

    $(document).on('click', '.education_add_more', function() {
        $('#add_educations_details_modal').find('.items_div').append(educations_template);
    });

    $(document).on('click', '.educations_delete_this', function() {
        $(this).parent().parent().remove()
    });

    $(document).on('click', '.educations_edit', function(event) {
        event.preventDefault();
        var education_id = $(this).data('id');
        $('#education_id').val(education_id);

        var educations_edit_url = '{{ route("doctor-edit-educations", ":id") }}';
        educations_edit_url = educations_edit_url.replace(':id', education_id);
        var myform = document.getElementById("educations_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: educations_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'GET',
            success: function (response) {
                $("#degree option").filter(function() {
                    return $(this).text() == response.result[0].degree;
                }).prop('selected', true);
                $("#university option").filter(function() {
                    return $(this).text() == response.result[0].institute;
                }).prop('selected', true);
                $('#year_of_completion').val(response.result[0].year_of_completion);
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

    $('#educations_edit_submit').submit(function (event) {
        event.preventDefault();
        var education_id = $('#education_id').val();
        $('#education_id').val(education_id);

        var educations_edit_url = '{{ route("doctor-edit-educations", ":id") }}';
        educations_edit_url = educations_edit_url.replace(':id', education_id);
        var myform = document.getElementById("educations_edit_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: educations_edit_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Educations has been updated.',
                    '',
                    'success'
                );
                $('#Educations_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Educations_tab').click();
            }
        });
    });

    // $('#add_educations_details_modal').on('shown.bs.modal', function (e) {
    // do something...
    // })

    $('#add_educations_details_modal').on('hidden.bs.modal', function (e) {
        $('#add_educations_details_modal').find('.add').not(':first').remove();
        $('#educations_add_submit').trigger("reset");
    });

    $('#educations_add_submit').submit(function (event) {
        event.preventDefault();
        var educations_add_url = '{{ route("doctor-update-educations", ":id") }}';
        educations_add_url = educations_add_url.replace(':id', doctor_id);
        var myform = document.getElementById("educations_add_submit");
        var fd = new FormData(myform);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $.ajax({
            url: educations_add_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            // enctype: 'multipart/form-data',
            method: 'post',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Education has been added.',
                    '',
                    'success'
                );
                $('#Educations_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Educations_tab').click();
            }
        });
        $('#add_educations_details_modal').find('.add').not(':first').remove();
        $('#educations_add_submit').trigger("reset");
    });

    $(document).on('click', '.educations_delete', function(e) {
        var data = new FormData();
        var educationId = $(this).data('id');
        var educations_delete_url = '{{ route("doctor-delete-educations", ":id") }}';
        data.append( 'recordId',  educationId);
        educations_delete_url = educations_delete_url.replace(':id', doctor_id);
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
                    url: educations_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Education has been deleted.',
                            '',
                            'success'
                        );
                        $('#Educations_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Educations_tab').click();
                    }
                });
            }
        })
    });

    $('#educations_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#educations_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#educations_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });

</script>
