<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin</title>
        @include('layouts.head')
        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
	    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.2.2/css/fixedHeader.bootstrap.min.css">
	    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
        <style>
            #sites_list_table {
                width: 100% !important;
            }
            #sites_list_table_wrapper {
                margin: 20px 0;
            }
            #sites_list_table_wrapper #sites_list_table_filter label,
            #sites_list_table_wrapper .dataTables_length label,
            #sites_list_table_wrapper.form-inline {
                display: block !important;
            }
            .dataTables_length {
                margin-bottom: 10px;
            }
            #sites_list_table_wrapper #sites_list_table_filter {
                display: flex;
                justify-content: flex-end;
            }
        </style>
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
                                    Info Modal Contents
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
                                    <div class="card">
                                        <div class="card-body">
                                            @if (session('success'))
                                                <div class="toast"
                                                    data-title="Saved"
                                                    data-message="{{session('success')}}"
                                                    data-type="success">
                                                </div>
                                            @endif
                                            @if (session('warning'))
                                                <div class="toast"
                                                    data-title="Warning"
                                                    data-message="{{session('warning')}}"
                                                    data-type="warning">
                                                </div>
                                            @endif
                                            <table id="sites_list_table" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Key</th>
                                                    <th>Title</th>
                                                    <th>Languages</th>
                                                    <th >Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($info_modals as $info_modal)
                                                        <tr>
                                                            <td>{{$info_modal->id}}</td>
                                                            <td>{{$info_modal->key}}</td>
                                                            <td>{{$info_modal->title}}</td>
                                                            <td>
                                                                @if ($info_modal->language=='en')
                                                                    English
                                                                @endif
                                                                @if ($info_modal->language=='ur')
                                                                    Urdu
                                                                @endif
                                                                @if ($info_modal->language=='sd')
                                                                    Sindhi
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-outline-success btn-xs" href="/admin/info-modal/{{$info_modal->id}}/edit">Edit</a>
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
        @include('layouts.scripts')
        <script type="text/javascript" language="javascript" src="https://datatables.net/examples/resources/demo.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedheader/3.2.2/js/dataTables.fixedHeader.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>
       
        <script>
            $(document).ready(function() {
    var table = $('#sites_list_table').DataTable( {
        responsive: true,
        "pageLength": 25
    } );
 
    new $.fn.dataTable.FixedHeader( table );
} );
        </script>
    </body>
</html>
