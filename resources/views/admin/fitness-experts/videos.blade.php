{{--<div id="Videos" class="tabcontent">--}}
<div class="modal fade" id="add_videos_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="add_videos_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="videos_add_submit" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Assign Videos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="box_border">
                        <div class="row items_div">
                            <div class="col-md-12 mb-3 add">
                                <select class="form-control badge" name="video" required>
                                    <option value="">Please select video</option>
                                    @foreach ($assign_videos as $assign_video)
                                        @if ($assign_video->referenceWidget)
                                            @if ($assign_video->referenceWidget->page)
                                                <option value="{{$assign_video->id}}" >{{$assign_video->referenceWidget->page->name}}</option>
                                            @elseif ($assign_video->referenceWidget->article)
                                                <option value="{{$assign_video->id}}" >{{$assign_video->referenceWidget->article->name}}</option>
                                            @endif
                                        @endif
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
        <div class="d-md-flex align-items-center">
            <i class="icon-videocam fs-25 mr-2"></i>
            <h3 class="mb-0">Video</h3>
        </div>
        @can('fitness-management-assign-videos-add-new')
        <div class="box-header with-border">
            <div class="d-flex justify-content-start">
                <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#add_videos_details_modal">
                    <i class=" icon-add"></i>
                    Assign new
                </a>
            </div>
        </div>
        @endcan

    </div>
    <div class="row mb-3">
        @foreach($videos as $video)
            <div class="col-md-3 text-center">
                <div class="border-2 border-dark width-height-same rounded mb-2">
                    <i class="icon-file-video-o fs-25"></i>
                </div>
                @can('fitness-management-assign-videos-unassign')
                <a href="javascript:void(0);" class="btn-danger btn-sm btn my-3 cursor-pointer delete-btn" data-id="{{ $video->id }}">
                @endcan
                {{--                    <i class="icon-trash fs-20">--}}Unassign
                </a>
                @if (isset($video->referenceWidget) && isset($video->referenceWidget->page))
                    <h5>{{$video->referenceWidget->page->name ?? '-'}}</h5>
                    <div>{{$video->referenceWidget->page->description ?? '-'}}</div>
                    <div>Language: {{ $video->language->name ?? '-'}}</div>
                @elseif (isset($video->referenceWidget) && isset($video->referenceWidget->article))
                    <h5>{{$video->referenceWidget->article->name ?? '-'}}</h5>
                    <div>{{$video->referenceWidget->article->description ?? '-'}}</div>
                    <div>Language: {{$video->language->name ?? '-'}}</div>
                @endif
            </div>
        @endforeach
    </div>
{{--</div>--}}

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
    $('#add_videos_details_modal').on('hidden.bs.modal', function (e) {
        $('#add_videos_details_modal').find('.add').not(':first').remove();
        $('#videos_add_submit').trigger("reset");
    });

    $('#videos_add_submit').submit(function (event) {
        event.preventDefault();
        var videos_add_url = '{{ route("fitness-experts-update-videos", ":id") }}';
        videos_add_url = videos_add_url.replace(':id', fitness_experts_id);
        var myform = document.getElementById("videos_add_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: videos_add_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Video has been assigned.',
                    '',
                    'success'
                );
                $('#Videos_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Videos_tab').click();
            }
        });
        $('#add_videos_details_modal').find('.add').not(':first').remove();
        $('#videos_add_submit').trigger("reset");
    });

    $(document).on('click', '.delete-btn', function(e) {
        var videoId = $(this).data('id');
        var video_delete_url = '{{ route("fitness-experts-delete-videos", ":id") }}';
        video_delete_url = video_delete_url.replace(':id', fitness_experts_id);
        var data = new FormData();
        data.append( 'recordId',  videoId);
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, unassign it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: video_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Video has been unassigned.',
                            '',
                            'success'
                        );
                        $('#Videos_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Videos_tab').click();
                    }
                });
            }
        })
    });
</script>
