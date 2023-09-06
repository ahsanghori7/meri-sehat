@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural('Site content') }}
@endsection
@section('content')
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="tab-content pb-3" id="v-pills-tabContent">
                        <!--Today Tab Start-->
                        @if (session('error'))
                            <div class="toast"
                                data-title="Already Exist"
                                data-message="This slug already exist try new one..."
                                data-type="error">
                            </div>
                        @endif
                        
                        <form method="POST">
                            @csrf
                            <div class="row p-t-b-10 ">
                                <div class="col-md-8 mx-auto">
                                    
                                    <div class="mb-3">
                                        <label for="validationCustom01"> Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Title" value="" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01">Slug</label>
                                        <input type="text" name="slug" class="form-control" placeholder="Slug" value="" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01"> heading</label>
                                        <input type="text" name="heading" class="form-control" placeholder="Heading" value="" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01"> Keyword</label>
                                        <input type="text" name="keyword" class="form-control" placeholder="Keyword" value="" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01">Language</label>
                                        <select name="language" class="form-control">
                                            <option value="en">English</option>
                                            <option value="ur">Urdu</option>
                                            <option value="sd">Sindhi</option>
                                        </select>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01">Meta Description</label>
                                        <input type="text" name="meta_description" class="form-control" placeholder="Meta Description" value="" required>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom01">Content</label>
                                        <textarea name="content" class="summernote" name="description"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="btn btn-success r-10 text-white mr-2">
                                            <input type="radio" name="status" id="option1" value="1"> Active
                                        </label>
                                        <label class="btn btn-danger r-10 text-white mr-2">
                                            <input type="radio" name="status" id="option1" value="0"> Disable
                                        </label>
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