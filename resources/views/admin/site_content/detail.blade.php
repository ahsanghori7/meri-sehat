<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin</title>
        @include('layouts.head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css"/>
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> --}}

        
        </head>
    <body class="light">
        {{-- pre loader --}}
        @include('layouts.pre_loader')
        <div id="app">

            @include('layouts.aside_left')
            @include('layouts.header')

            {{-- page body start here --}}
            <div class="page has-sidebar-left height-full">
                <header class="blue accent-3 relative nav-sticky">
                    <div class="container-fluid text-white">
                        <div class="row p-t-b-10 ">
                            <div class="col">
                                <h4>
                                    <i class="icon-newspaper"></i>
                                    Site Content Management
                                </h4>
                            </div>
                        </div>
                        
                    </div>
                </header>
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="tab-content pb-3" id="v-pills-tabContent">
                        <!--Today Tab Start-->
                        @if (session('success'))
                            <div class="toast"
                                data-title="Saved"
                                data-message="Your Content Saved."
                                data-type="success">
                            </div>
                        @endif
                        <form method="POST">
                            @csrf
                            <div class="row p-t-b-10 ">
                                <div class="col-md-8 mx-auto">
                                    
                                    <div class="mb-3">
                                        <label for="validationCustom01"> Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Title" value="{{$detail->title}}" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01"> Slug</label>
                                        <input type="text" readonly name="Slug" class="form-control" placeholder="Slug" value="{{$detail->slug}}" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01"> heading</label>
                                        <input type="text" name="heading" class="form-control" placeholder="Heading" value="{{$detail->heading}}" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01"> Keyword</label>
                                        <input type="text" name="keyword" class="form-control" placeholder="Keyword" value="{{$detail->keyword}}" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01">Language</label>
                                        <select name="language" class="form-control">
                                            @if ($detail->language=='en')
                                                        <option value="en">English</option>
                                                    @endif
                                                    @if ($detail->language=='ur')
                                                        <option value="ur">Urdu</option>
                                                    @endif
                                                    @if ($detail->language=='sd')
                                                        <option value="sd">Sindhi</option>
                                                    @endif
                                        </select>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01">Meta Description</label>
                                        <input type="text" name="meta_description" class="form-control" placeholder="Meta Description" value="{{$detail->meta_description}}" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01">Content</label>
                                        <textarea name="content" class="summernote" name="description">{{$detail->content}}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        @if ($detail->status)
                                            <label class="btn btn-success r-10 text-white mr-2">
                                                <input type="radio" name="status" id="option1" value="1" checked> Active
                                            </label>
                                            <label class="btn btn-danger r-10 text-white mr-2">
                                                <input type="radio" name="status" id="option1" value="0"> Disable
                                            </label>
                                        @else
                                            <label class="btn btn-success r-10 text-white mr-2">
                                                <input type="radio" name="status" id="option1" value="1"> Active
                                            </label>
                                            <label class="btn btn-danger r-10 text-white mr-2">
                                                <input type="radio" name="status" id="option1" value="0" checked> Disable
                                            </label>
                                        @endif
                                        
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                        <!--Yesterday Tab Start-->
                    </div>
                </div>
            </div>
            {{-- page body end here --}}
        </div>
        @include('layouts.scripts')


        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-ko-KR.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.summernote').summernote();
            });
        </script>
            
    </body>
</html>
