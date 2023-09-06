<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin</title>
        @include('layouts.head')
    </head>
    <body class="light">
        {{-- pre loader --}}
        @include('layouts.pre_loader')
        <div id="app">

            @include('layouts.aside_left')
            @include('layouts.header')

            {{-- page body start here --}}
            <div class="page has-sidebar-left height-full">
                <header class="blue accent-3 relative nav-sticky">
                    <div class="container-fluid text-white">
                        <div class="row p-t-b-10 ">
                            <div class="col">
                                <h4>
                                    <i class="icon-newspaper"></i>
                                    Topics Categories
                                </h4>
                            </div>
                        </div>
                        
                    </div>
                </header>
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="tab-content pb-3" id="v-pills-tabContent">
                        <!--Today Tab Start-->
                        <form method="POST">
                            @csrf
                            <div class="row p-t-b-10 ">
                                <div class="col-md-8 mx-auto">
                                    <table class="table table-stripped table-hover">
                                        <thead>
                                        <tr>
                                            <th width="30%">Id</th>
                                            <th width="30%">Title</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topics as $topic)
                                                <tr>
                                                    <td>{{$topic->id}}</td>
                                                    <td><a href="/admin/content/topic/{{$topic->id}}">{{$topic->title}}</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
        @include('layouts.scripts')
        
    </body>
</html>
