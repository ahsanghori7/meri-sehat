@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural('Site content') }}
@endsection
@section('content')
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Today Tab Start-->
            <form method="POST">
                @csrf
                <div class="row p-t-b-10 ">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <table id="sites_list_table" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Slug</th>
                                            <th>Languages</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topics as $topic)
                                            <tr>
                                                <td class="text-capitalize">{{ $topic->id }}</td>
                                                <td>{{ $topic->title }}</td>
                                                <td>{{ $topic->slug }}</td>
                                                <td>
                                                    @if ($topic->language == 'en')
                                                        English
                                                    @endif
                                                    @if ($topic->language == 'ur')
                                                        Urdu
                                                    @endif
                                                    @if ($topic->language == 'sd')
                                                        Sindhi
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($topic->status == 1)
                                                        <span class="badge badge-pill badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-pill badge-danger">Disable</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-outline-success btn-xs"
                                                        href="/admin/site-content/{{ $topic->slug }}/{{ $topic->language }}">Edit</a>
                                                    @if ($topic->language == 'en')
                                                        <a class="btn btn-outline-primary btn-xs"
                                                            href="/admin/site-content/{{ $topic->slug }}/new/translate">Add
                                                            New Translatation</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
