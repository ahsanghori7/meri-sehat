{{--<div id="Articles" class="tabcontent">--}}
<div class="modal fade" id="add_articles_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="add_articles_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="articles_add_submit" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Assign Articles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="box_border">
                        <div class="row items_div">
                            <div class="col-md-12 mb-3 add">
                                <select class="form-control badge" name="article" required>
                                    <option value="">Please select article</option>
                                    @foreach ($assign_articles as $key => $assign_article)
                                        <option value="{{$assign_article->id}}" >{{$assign_article->name}}</option>
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
            <i class="icon-pencil fs-20 mr-2"></i>
            <h3 class="mb-0">Articles</h3>
        </div>
        @can('fitness-management-assign-articles-assign-new')
        <div class="box-header with-border">
            <div class="d-flex justify-content-start">
                <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#add_articles_details_modal">
                    <i class=" icon-add"></i>
                    Assign new
                </a>
            </div>
        </div>
        @endcan

    </div>
    @foreach($articles as $article)
    <div class="card py-2 px-3 mb-3 rounded-xl">
        <div class="d-md-flex align-items-center justify-content-between mb-2">
            @can('fitness-management-assign-articles-delete')
            <div class="d-md-flex align-items-center">
                <h5 class="mr-2 mb-0">{{ $article->name ?? '' }}</h5>
                <a class="delete-btn" href="javascript:void(0)" data-id="{{ $article->id }}"><i class="icon-trash fs-20"></i></a>
            </div>
            @endcan
            <div>
                <h5>
                    {{ $article->status == 1 ? 'Published on '.date('d M Y', strtotime($article->updated_at)) : 'Pending for Approval' }}
                </h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <span>{{ $article->descripton ?? '' }}</span>
            </div>
        </div>
    </div>
    @endforeach
{{--</div>--}}

<script>
    $('#add_articles_details_modal').on('hidden.bs.modal', function (e) {
        $('#add_articles_details_modal').find('.add').not(':first').remove();
        $('#articles_add_submit').trigger("reset");
    });

    $('#articles_add_submit').submit(function (event) {
        event.preventDefault();
        var articles_add_url = '{{ route("fitness-experts-update-articles", ":id") }}';
        articles_add_url = articles_add_url.replace(':id', fitness_experts_id);
        var myform = document.getElementById("articles_add_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: articles_add_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'Article has been assigned.',
                    '',
                    'success'
                );
                $('#Articles_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Articles_tab').click();
            }
        });
        $('#add_articles_details_modal').find('.add').not(':first').remove();
        $('#articles_add_submit').trigger("reset");
    });

    $(document).on('click', '.delete-btn', function(e) {
        var data = new FormData();
        var articleId = $(this).data('id');
        var article_delete_url = '{{ route("fitness-experts-delete-articles", ":id") }}';
        data.append( 'recordId',  articleId);
        article_delete_url = article_delete_url.replace(':id', fitness_experts_id);
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
                    url: article_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Article has been unassigned.',
                            '',
                            'success'
                        );
                        $('#Articles_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Articles_tab').click();
                    }
                });
            }
        })
    });
</script>
