@extends('layouts.admin.app')
@section('page_header')
All {{ Str::plural('setting') }}
@endsection
@section('content')
<div class="container-fluid relative animatedParent animateOnce">
    <!--Today Tab Start-->
    <form method="POST">
        @csrf
        <div class="row p-t-b-10 card mx-2 my-3">
            <div class="col-md-12 mx-auto">

                <table class="table table-stripped table-hover">
                    <thead>
                            <tr>
                                <th width="25%">Title</th>
                                <th width="25%">Value</th>
                                <th width="25%">Description</th>
                                <th width="25%">Group</th>
                            </tr>
                        </thead>
                    <tbody>

                        @foreach ($settings as $group_name => $grouped_items)
                        @php
                            $index = 0;
                        @endphp
                            @foreach ($grouped_items as $key => $item)
                                @if($index == 0)
                                    @php
                                        $group_name == "" ? "Default Settings" : $group_name;
                                    @endphp
                                    @can('general-settings-'.\App\Http\Common\Helper::settings_permission_replace($group_name))
                                        <tr data-toggle="collapse" data-target="#accordion_{{Str::slug($group_name)}}" class="clickable">
                                            <th colspan="4">
                                                <h6>{{$group_name}}</h6>
                                            </th>
                                        </tr>
                                    @endcan
                                @endif
                                @can('general-settings-'.\App\Http\Common\Helper::settings_permission_replace($group_name != "" ? $group_name : 'Default Settings').'-'.$item->key)
                                    <tr id="accordion_{{Str::slug($group_name)}}" class="{{$group_name == "" ? "" :  "collapse"}}">
                                        <td>
                                            {{ $item->title ? $item->title : ucfirst(str_replace('_', ' ', $item->key)) }}
                                        </td>
                                        <td>
                                            @if ($item->json_params && json_decode($item->json_params)->type == 'dropdown')
                                                <select class="form-control" name="{{ $item->key }}">
                                                    @foreach (json_decode($item->json_params)->data as $option)
                                                    <option value="{{ $option->value }}" @if ($item->value == $option->value) selected @endif>
                                                        {{ $option->label }} </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" class="form-control" name="{{ $item->key }}" value="{{ $item->value }}" required>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->description ? $item->description : '-' }}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="{{ $item->key }}_group" value="{{ $item->group }}">
                                        </td>
                                    </tr>
                                @endcan
                                @php
                                    $index++;
                                @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                @can('general-settings-save')
                    <button type="submit" class="btn btn-primary" style="background-color: #1FA7A8; "><i class="icon-save"></i> Save</button>
                @endcan
            </div>
        </div>
    </form>
    <!--Yesterday Tab Start-->
</div>
</div>
{{-- page body end here --}}
</div>
@endsection
