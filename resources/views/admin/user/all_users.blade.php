@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural('User') }}
@endsection
@section('content')
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Today Tab Start-->
            <table id="yajra" class="table table-striped table-bordered nowrap">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Phone Number</th>
                        <th>City</th>
                        <th>Is Subscriber</th>
                        <th>Role</th>
                        <th>Verified</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><a href="/admin/user/{{ $user->id }}/profile">{{ $user->name }}</a></td>
                            <td>{{ $user->email }}</td>
                            <td class="text-lowercase">{{ $user->gender }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->city }}</td>
                            <td>
                                @if ($user->is_subscribed == '1')
                                    Subscribed
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($user->role == '1')
                                    Doctor
                                @else
                                    Patient
                                @endif

                            </td>
                            <td>Verified</td>
                            <td>
                                @if ($user->status == '1')
                                    Active
                                @else
                                    Blocked
                                @endif
                            </td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <!--Yesterday Tab Start-->
        </div>
    </div>
    </div>
    {{-- page body end here --}}
    </div>
@endsection
