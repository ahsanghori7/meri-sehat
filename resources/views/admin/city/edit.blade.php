@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural('city') }}
@endsection
@section('content')
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Today Tab Start-->
            @if (session('success'))
                <div class="toast" data-title="Saved" data-message="Your Content Saved." data-type="success">
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
                            <label for="validationCustom01">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Title"
                                value="{{ $city->name }}" required>

                        </div>
                        <div class="mb-3">
                            <label class="btn btn-success r-10 text-white mr-2">
                                <input type="radio" @if ($city->status == 1) checked @endif name="status"
                                    id="option1" value="1"> Active
                            </label>
                            <label class="btn btn-danger r-10 text-white mr-2">
                                <input type="radio" @if ($city->status == 0) checked @endif name="status"
                                    id="option1" value="0"> Disable
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
