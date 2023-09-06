    @extends('layouts.widget.app')
    @section('page_header')
    Edit Doctor Specialities
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

                                <div class="col-md-12 mb-3">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <label>Select Speciality</label>
                                        <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 delete_this">
                                            &times;

                                        </button>
                                    </div>
                                    <select class="form-control badge" name="specialities[]">
                                        @foreach ($specialities as $key => $speciality)
                                        <option value="{{$speciality->id}}" @if($record->speciality_id == $speciality->id) selected @endif>{{$speciality->name}}</option>
                                        @endforeach
                                    </select>
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
            `<div class="col-md-12 mb-3">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Select Service</label>
                    <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 delete_this">
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
            $(document).on('click', '.add_more', function() {
                $('.items_div').append(template);
            })
            
            $(document).on('click', '.delete_this', function() {
                $(this).parent().parent().remove()
            })
        })

    </script>
    @endpush
