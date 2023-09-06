@extends('layouts.admin.app')

@push('css')

@endpush
@section('page_header') @if ($role) Update Role
@else Add Role @endif @endsection
@section('content')


<div class="container-fluid animatedParent animateOnce">
    <div class="animated fadeInUpShort">
        <div class="row my-3">
            <div class="col-md-12">
                <form method="POST" class="needs-validation" enctype="multipart/form-data" novalidate @if ($role) action="{{ route('role.update', ['role' => $role]) }}" @else action="{{ route('role.create') }}" @endif>
                    @csrf
                    <div class="box_border">
                        <div class="">
                            <div class="">
                                <div class="form-row">
                                    <div class="form-group col-md-12 m-0">
                                        <h5 class="box_heading">Role Information</h5>
                                        <label for="name" class="col-form-label s-12">
                                            Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" required class="form-control r-0 light s-12 " id="name" placeholder="Enter Role name" @if ($role) value="{{ $role->name }}" @endif>
                                        @error('name')<div class="validation-error"> {{ $message }}</div> @enderror
                                    </div>

                                    <div class="form-group col-md-12 m-0">
                                        <label for="name" class="col-form-label mt-3 s-12">
                                            Select Permissions
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="form-group">
                                            <div class="form-check pl-0">


                                                {{-- @if ($role)
                                                @foreach ($role->permissions as $key => $item)

                                                <label class="form-check-label" for="permission{{ $key }}">
                                                <input class="form-check-input" name="permissions[]" id="permission{{ $key }}" type="checkbox" value="{{ $item->id }}" checked> {{ $item->name }}
                                                </label><br>
                                                @endforeach
                                                @endif
                                                @foreach ($permissions as $key => $permission)

                                                <label class="form-check-label" for="permission{{ $key }}">
                                                    <input class="form-check-input" name="permissions[]" id="permission{{ $key }}" type="checkbox" value="{{ $permission->id }}"> {{ $permission->name }}
                                                </label><br>

                                                @endforeach --}}
                                                @foreach ($permissions as $key => $permission)
                                                <div class="permission_title">
                                                    {{$permission->name}} <span class="arrow"></span>
                                                </div>
                                                <div class="permission_details">
                                                    <div class="parent">
                                                        <input type="checkbox" name="permissions[]" @if(in_array($permission->id,$selected_permission)) checked @endif value="{{$permission->id}}" id="{{$permission->id}}" /><label for="{{$permission->id}}" class="text-strong">{{$permission->name}} ({{$permission->slug}})</label>
                                                    </div>
    {{--                                                <div>--}}
                                                        <ul class="child">
                                                            @foreach ($permission->child as $key => $child)
                                                                <li class="ml-2">
                                                                    <input type="checkbox" @if(in_array($child->id,$selected_permission)) checked @endif name="permissions[]" value="{{$child->id}}" id="{{$child->id}}" />
                                                                    <label for="{{$child->id}}">{{$child->name}} ({{$child->slug}})</label>
                                                                </li>
                                                                @if (count($child->child) > 0)
                                                                    <ul class="child2">
                                                                        @foreach ($child->child as $key => $child2)
                                                                            <li class="ml-4">
                                                                                <input type="checkbox" @if(in_array($child2->id,$selected_permission)) checked @endif name="permissions[]" value="{{$child2->id}}" id="{{$child2->id}}" />
                                                                                <label for="{{$child2->id}}">{{$child2->name}} ({{$child2->slug}})</label>
                                                                            </li>
                                                                            @if (count($child2->child) > 0)
                                                                                <ul class="child3">
                                                                                    @foreach ($child2->child as $key => $child3)
                                                                                        <li class="ml-5">
                                                                                            <input type="checkbox" @if(in_array($child3->id,$selected_permission)) checked @endif name="permissions[]" value="{{$child3->id}}" id="{{$child3->id}}" />
                                                                                            <label for="{{$child3->id}}">{{$child3->name}} ({{$child3->slug}})</label>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            @endforeach
                                                        </ul>
    {{--                                                </div>--}}
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('name')<div class="validation-error"> {{ $message }}</div> @enderror
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="px-0">
                        <button type="submit" class="cst_btn px-5 btn-sm">
                            {{-- <i class="icon-save mr-2"></i> --}}
                            Add Role</button>
                    </div>


                </form>
            </div>
        </div>
    </div>


    @endsection


    @push('scripts')

    <script>
        $(".parent input").on('click', function() {
            var _parent = $(this);
            var nextli = $(this).parent().next().children();
            var next_next_li = $(this).parent().next().children().children();
            var next_next_next_li = $(this).parent().next().children().children().children();
            // var nextli = $(this).parent().next().children().children();

            if (_parent.prop('checked')) {
                console.log('parent checked');
                nextli.each(function() {
                    $(this).children().prop('checked', true);
                });
                next_next_li.each(function() {
                    $(this).children().prop('checked', true);
                });
                next_next_next_li.each(function() {
                    $(this).children().prop('checked', true);
                });
            } else {
                console.log('parent un checked');
                nextli.each(function() {
                    $(this).children().prop('checked', false);
                });
                next_next_li.each(function() {
                    $(this).children().prop('checked', false);
                });
                next_next_next_li.each(function() {
                    $(this).children().prop('checked', false);
                });

            }
        });

        $(".child input").on('click', function() {
            var ths = $(this);
            var _parent = $(this);
            var nextli = $(this).parent().next().children();
            var parentinput = ths.closest('div').find('input');
            var sibblingsli = ths.closest('ul').find('li');

            // if (ths.prop('checked')) {
            //     console.log('child checked');
            //     parentinput.prop('checked', true);
            // } else {
            //     console.log('child unchecked');
            //     var status = true;
            //     sibblingsli.each(function() {
            //         console.log('sibb');
            //         if ($(this).children().prop('checked')) status = false;
            //     });
            //     // if (status) parentinput.prop('checked', false);
            // }

            if (_parent.prop('checked')) {
                console.log('parent checked');
                nextli.each(function() {
                    $(this).children().prop('checked', true);
                });

            } else {
                console.log('parent un checked');
                nextli.each(function() {
                    $(this).children().prop('checked', false);
                });

            }
        });

        $(".child2 input").on('click', function() {
            var ths = $(this);
            var parentinput = ths.closest('div').prev().children();
            var sibblingsli = ths.closest('ul').find('li');

            if (ths.prop('checked')) {
                console.log('child checked');
                parentinput.prop('checked', true);
            } else {
                console.log('child unchecked');
                var status = true;
                sibblingsli.each(function() {
                    console.log('sibb');
                    if ($(this).children().prop('checked')) status = false;
                });
                // if (status) parentinput.prop('checked', false);
            }
        });

        let permission = $(".permission_details");
        $(".permission_title").click(function () {
            if ($(this).hasClass('active')) {
                permission.slideUp();
                $(this).removeClass('active');
                return false;
            }
            permission.slideUp();
            permission.prev().removeClass("active");
            $(this).next().slideDown();
            $(this).addClass("active");
            return false;
        });


    </script>

    @endpush
