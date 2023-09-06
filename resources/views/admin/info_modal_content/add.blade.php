@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural('info modal') }}
@endsection
@section('content')
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Today Tab Start-->
            @if (session('success'))
                <div class="toast" data-title="Saved" data-message="{{ session('success') }}" data-type="success">
                </div>
            @endif
            @if (session('warning'))
                <div class="toast" data-title="Warning" data-message="{{ session('warning') }}" data-type="warning">
                </div>
            @endif
            <form method="POST">
                @csrf
                <div class="row p-t-b-10 ">
                    <div class="col-md-8 mx-auto">

                        <div class="mb-3">
                            <label for="validationCustom01">Key</label>
                            <input type="text" name="key" class="form-control" placeholder="Title" value="" required>

                        </div>
                        <div class="mb-3">
                            <label for="validationCustom01">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="title" value="" required>

                        </div>

                        <div class="mb-3">
                            <label for="validationCustom01">Content</label>
                            <textarea name="content" class="summernote" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="validationCustom01">Language</label>
                            <select name="language" class="form-control">
                                <option value="en">English</option>
                                <option value="ur">Urdu</option>
                                <option value="sd">Sindhi</option>

                            </select>

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
@endsection
