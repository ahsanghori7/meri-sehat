@extends('layouts.admin.app')
@section('page_header')
All {{ Str::plural($module_name) }}
@endsection
@section('content')
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <fieldset>
                <legend>User Information</legend>
                    <div class="row ">
                        <div class="col-md-3">
                            <label for="user_name">User Id</label>
                            <input type="text" name="user_id" id="user_id" class="form-control" placeholder="User Id" readonly
                                   value="{{ $result->user_id }}">
                        </div>
                        <div class="col-md-3">
                            <label for="user_name">User Name</label>
                            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="User Name" readonly
                                   value="{{ $result->user->name }}">
                        </div>
                        <div class="col-md-3">
                            <label for="user_email">User Email</label>
                            <input type="text" name="user_email" id="user_email" class="form-control" placeholder="User Email" readonly
                                   value="{{ $result->user->email }}">
                        </div>
                        <div class="col-md-3">
                            <label for="user_role">User Role</label>
                            <input type="text" name="user_role" id="user_role" class="form-control" placeholder="User Role" readonly
                                   value="{{ $result->user->role->name }}">
                        </div>
                    </div>
                </legend>
                <legend class="mt-4">Activity Log</legend>
                <div class="row ">
                    <div class="col-md-4">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" readonly
                               value="{{ $result->subject }}">
                    </div>
                    <div class="col-md-8">
                        <label for="url">URL</label>
                        <input type="text" name="url" id="url" class="form-control" placeholder="URL" readonly
                               value="{{ $result->url }}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="method">Method</label>
                        <input type="text" name="method" id="method" class="form-control" placeholder="Method" readonly
                               value="{{ $result->method }}">
                    </div>
                    <div class="col-md-9">
                        <label for="agent">User Agent</label>
                        <input type="text" name="agent" id="agent" class="form-control" placeholder="User Agent" readonly
                               value="{{ $result->agent }}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="event">Event</label>
                        <input type="text" name="event" id="event" class="form-control" placeholder="Event" readonly
                               value="{{ $result->event }}">
                    </div>
                    <div class="col-md-3">
                        <label for="table">Table</label>
                        <input type="text" name="table" id="table" class="form-control" placeholder="Table" readonly
                               value="{{ $result->table }}">
                    </div>
                    <div class="col-md-3">
                        <label for="module">Module</label>
                        <input type="text" name="module" id="module" class="form-control" placeholder="module" readonly
                               value="{{ $result->module }}">
                    </div>
                    <div class="col-md-3">
                        <label for="ip">User IP</label>
                        <input type="text" name="ip" id="ip" class="form-control" placeholder="IP Address" readonly
                               value="{{ $result->ip }}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="old_value">Old Value</label>
                        <textarea type="text" rows="12" name="old_value" id="old_value" class="form-control" placeholder="Old Value" readonly>
                            @foreach(json_decode($result->old_value, true) as $key => $value)
{{ $key }}: {{ $value }}
                            @endforeach
                        </textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="new_value">New Value</label>
                        <textarea type="text" rows="12" name="new_value" id="new_value" class="form-control" placeholder="New Value" readonly>
                            @foreach(json_decode($result->new_value, true) as $key => $value)
{{ $key }}: {{ $value }}
                            @endforeach
                        </textarea>
                    </div>
                </div>
                </legend>
            </fieldset>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var myStr = $('#old_value').val();
        var trimStr = myStr.replace(/  /g, "");
        $('#old_value').val(trimStr);

        var myStr2 = $('#new_value').val();
        var trimStr2 = myStr2.replace(/  /g, "");
        $('#new_value').val(trimStr2);
    </script>
@endpush
