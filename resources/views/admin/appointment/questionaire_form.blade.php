@extends('layouts.widget.app')
@section('page_header')
{{ Str::singular($page_header) }}
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
                        <div class="row">
                            @if(isset($result))
                            @foreach ($result as $key => $field)
                            @if($field->input_type == 'text_box')
                            <div class="col-md-12 mb-3 px-0">
                                <strong for="type">{{$field->title}}</strong>
                                <br>
                                <i class="text-info">{{$field->description}}</i>
                                <input type="text" name="{{$field->title}}" value="{{$values[$field->title] ?? ''}}" class="form-control" placeholder="Enter {{$field->title}}">
                            </div>
                            @endif
                            @if($field->input_type == 'text_area')
                            <div class="col-md-12 mb-3 px-0">
                                <strong for="type">{{$field->title}}</strong>
                                <br>
                                <i class="text-info">{{$field->description}}</i>
                                <textarea rows="5" name="{{$field->title}}" placeholder="Enter {{$field->title}}" class="form-control">{{$values[$field->title] ?? ''}}</textarea>
                            </div>
                            @endif

                            @if($field->input_type == 'dropdown')
                            <div class="col-md-12 mb-3 px-0">
                                <strong for="type">{{$field->title}}</strong>
                                <br>
                                <i class="text-info">{{$field->description}}</i>
                                <select class="form-control parent_type" name="{{$field->title}}" >
                                    @foreach (json_decode($field->json_params, true) as $option)
                                    <option value="{{ $option }}"  @if($values && $values[$field->title] == $option) selected @endif>
                                        {{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="bg-transparent">
                        <button type="submit" class="cst_btn px-5 btn-sm">
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        let ref = $("#type").val();
        console.log(ref);
        if (ref == 'image') {
            $(".image").removeAttr('style');
            $(".link").attr('style', 'display:none');
        } else {
            $(".link").removeAttr('style');
            $(".image").attr('style', 'display:none');
        }
        $("#type").on('change', function() {
            let type = $("#type").val();
            console.log(type);
            if (type == 'image') {
                $(".image").removeAttr('style');
                $(".link").attr('style', 'display:none');
            } else {
                $(".link").removeAttr('style');
                $(".image").attr('style', 'display:none');
            }
        });
    })

</script>
@endpush
