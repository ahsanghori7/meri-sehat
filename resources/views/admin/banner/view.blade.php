<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Laravel</title>
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
                                    <i class="icon-box"></i>
                                    Banner
                                </h4>
                            </div>
                        </div>
                        
                    </div>
                </header>
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="tab-content pb-3" id="v-pills-tabContent">
                        <!--Today Tab Start-->
                        <table class="table table-bordered mt-3">
                            <tbody>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Title</th>
                                <th>link</th>
                                <th>Target Self</th>
                                <th>Parent Id</th>
                                <th>Lang</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>
                                    <a href="/admin/banner/100/edit" class="btn btn-success">Update</a>
                                    <a class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>
                                    <a class="btn btn-success">Update</a>
                                    <a class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>Update software</td>
                                <td>
                                    <a class="btn btn-success">Update</a>
                                    <a class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            
                            </tbody>
                        </table>
                        <!--Yesterday Tab Start-->
                    </div>
                </div>
            </div>
            {{-- page body end here --}}
        </div>
        @include('layouts.scripts')
        
    </body>
</html>
