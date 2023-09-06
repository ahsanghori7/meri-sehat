@extends('layouts.widget.app')
@section('page_header')
Edit Wellness Experts Experiences
@endsection
@php
@endphp
@section('content')
<form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="box_border">
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn-success btn-sm btn mb-2 cursor-pointer add_more">
                                <i class=" icon-add"></i>
                                Add New
                            </button>
                        </div>
                        <div class="row items_div">

                            @if($result && count($result) > 0)
                            @foreach ($result as $key => $record)

                            <div class="row item">
                                <div class="col-md-12 d-flex justify-content-end align-items-center mb-3">
                                    <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 delete_this">
                                        &times;
                                    </button>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Select Designation</label>
                                    </div>
                                    <select class="form-control badge" name="position[]">
                                        @foreach ($positions as $key => $position)
                                        <option value="{{$position}}" @if($record->position == $position) selected @endif>{{$position}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Institute</label>
                                    </div>
                                    <input type="text" class="form-control" value="{{$record->institute}}" required name="institute[]">
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Start Year</label>
                                    </div>
                                    <input type="number" class="form-control" value="{{$record->start_year ?? null}}" required name="start_year[]">
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Year of completion (leave empty if not completed)</label>
                                    </div>
                                    <input type="number" class="form-control" value="{{$record->year_of_completion ?? null}}" name="year_of_completion[]">
                                </div>

                            </div>
                            @endforeach
                            @endif

                        </div>
                    </div>
                    <div class="bg-transparent">
                        <button type="submit" class="cst_btn px-5 btn-sm">Update</button>
                    </div>
                </div>
            </div>
        </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var template =
            `<div class="row item">
                                <div class="col-md-12 d-flex justify-content-end align-items-center mb-3">
                                    <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 delete_this">
                                        &times;
                                    </button>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Select Designation</label>
                                    </div>
                                    <select class="form-control badge" name="position[]">
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
        $(document).on('click', '.add_more', function() {
            $('.items_div').append(template);
        })

        $(document).on('click', '.delete_this', function() {
            $(this).parent().parent().remove()
        })
    })

</script>
@endpush
