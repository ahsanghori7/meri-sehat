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

            @include('layouts.admin.sidenav')
            @include('layouts.header')

            {{-- page body start here --}}
            <div class="page has-sidebar-left">
                <div>
                    <header class="blue accent-3 relative">
                        <div class="container-fluid text-white">
                            <div class="row p-t-b-10 ">
                                <div class="col">
                                    <div class="pb-3">
                                        <div class="image mr-3  float-left">
                                            <img class="user_avatar no-b no-p" src="/assets/img/dummy/u6.png" alt="User Image">
                                        </div>
                                        <div>
                                            <h6 class="p-t-10">{{$user->name}}</h6>
                                            {{$user->email}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($user->role=='0')
                                <div class="row">
                                    <ul class="nav nav-material nav-material-white responsive-tab" id="v-pills-tab" role="tablist">
                                        <li>
                                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home"><i class="icon icon-home2"></i>Home</a>
                                        </li>
                                        <li>
                                            <a class="nav-link" id="v-pills-payments-tab" data-toggle="pill" href="#v-pills-payments" role="tab" aria-controls="v-pills-payments" aria-selected="false"><i class="icon icon-money-1"></i>Scan Reports</a>
                                        </li>
                                        {{-- <li>
                                            <a class="nav-link" id="v-pills-timeline-tab" data-toggle="pill" href="#v-pills-timeline" role="tab" aria-controls="v-pills-timeline" aria-selected="false"><i class="icon icon-cog"></i>Timeline</a>
                                        </li>
                                        <li>
                                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="icon icon-cog"></i>Edit Profile</a>
                                        </li> --}}
                                    </ul>
                                </div>
                            @endif
                                
                        </div>
                    </header>
                    
                    <div class="container-fluid animatedParent animateOnce my-3">
                        <div class="animated fadeInUpShort">
                            @if ($user->role=='1')
                                <div class="row">
                                    <div class="col-md-8 mx-auto">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card ">
            
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><strong class="s-12">Phone</strong> <span class="float-right s-12">+91 333470 456 99</span></li>
                                                        <li class="list-group-item"><strong class="s-12">Email</strong> <span class="float-right s-12">{{$user->email}}</span></li>
                                                        <li class="list-group-item"> <strong class="s-12">City</strong> <span class="float-right s-12">{{$user->city}}</span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Gender</strong> <span class="float-right s-12">{{$user->gender}}</span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Date Of Birth</strong> <span class="float-right s-12">{{$user->date_of_birth}}</span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Is Subscribed</strong> <span class="float-right s-12">
                                                            @if ($user->is_subscribed=='1')
                                                                Yes
                                                            @else
                                                                No
                                                            @endif    
                                                        </span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Status</strong> <span class="float-right s-12">
                                                            @if ($user->status=='1')
                                                                Active
                                                            @else
                                                                Disable
                                                            @endif
                                                        </span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Email Verified</strong> <span class="float-right s-12">
                                                            @if ($user->email_verified=='1')
                                                                Verified
                                                            @else
                                                                Not Verified
                                                            @endif    
                                                        </span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Source</strong> <span class="float-right s-12">-</span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Role</strong> <span class="float-right s-12">Doctor</span></li>
                                                        <li class="list-group-item"> <strong class="s-12">Created At</strong> <span class="float-right s-12">{{$user->created_at}}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                @if ($user['doctorDetail'])
                                                <div class="card mb-3">
                                                    <div class="card-header bg-white">
                                                        <strong class="card-title">Detail</strong>
                                                    </div>
                                                    <ul class="no-b">
                                                        <p></p>
                                                        <li class="list-group-item">
                                                            speciality
                                                            {{$user['doctorDetail']->speciality}}
                                                        </li>
                                                        <li class="list-group-item">
                                                            Experience 
                                                            {{$user['doctorDetail']->experience_year}}
                                                        </li>
                                                        <li class="list-group-item">
                                                            is_physical_consultancy: 
                                                            @if ($user['doctorDetail']->is_physical_consultancy=='1')
                                                                Yes
                                                            @else
                                                                No
                                                            @endif
                                                        </li>
                                                        <li class="list-group-item">
                                                            is_voice_consultancy: 
                                                            @if ($user['doctorDetail']->is_voice_consultancy=='1')
                                                                Yes
                                                            @else
                                                                No
                                                            @endif
                                                        </li>
                                                        <li class="list-group-item">
                                                            is_video_consultancy: 
                                                            @if ($user['doctorDetail']->is_video_consultancy=='1')
                                                                Yes
                                                            @else
                                                                No
                                                            @endif
                                                        </li>
                                                    </ul>
                    
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        
            
                                    </div>
                                
                                </div>
                            @else
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <div class="row">
                                            
                                            <div class="col-md-10 mx-auto">
                                            
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="card ">
                            
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item"><strong class="s-12">Phone</strong> <span class="float-right s-12">+91 333470 456 99</span></li>
                                                                <li class="list-group-item"><strong class="s-12">Email</strong> <span class="float-right s-12">{{$user->email}}</span></li>
                                                                <li class="list-group-item"> <strong class="s-12">City</strong> <span class="float-right s-12">{{$user->city}}</span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Gender</strong> <span class="float-right s-12">{{$user->gender}}</span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Date Of Birth</strong> <span class="float-right s-12">{{$user->date_of_birth}}</span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Is Subscribed</strong> <span class="float-right s-12">
                                                                    @if ($user->is_subscribed=='1')
                                                                        Yes
                                                                    @else
                                                                        No
                                                                    @endif    
                                                                </span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Status</strong> <span class="float-right s-12">
                                                                    @if ($user->status=='1')
                                                                        Active
                                                                    @else
                                                                        Disable
                                                                    @endif
                                                                </span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Email Verified</strong> <span class="float-right s-12">
                                                                    @if ($user->email_verified=='1')
                                                                        Verified
                                                                    @else
                                                                        Not Verified
                                                                    @endif    
                                                                </span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Source</strong> <span class="float-right s-12">-</span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Role</strong> <span class="float-right s-12">Doctor</span></li>
                                                                <li class="list-group-item"> <strong class="s-12">Created At</strong> <span class="float-right s-12">{{$user->created_at}}</span></li>
                                                            </ul>
                                                        </div>
                            
                                                    </div>
                                                </div>
                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-payments" role="tabpanel" aria-labelledby="v-pills-payments-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card no-b">
                                                    <div class="card-header white b-0 p-3">
                                                        <h4 class="card-title">Invoices</h4>
                                                        <small class="card-subtitle mb-2 text-muted">Items purchase by users.</small>
                                                    </div>
                                                    <div class="collapse show" id="invoiceCard">
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table id="recent-orders"
                                                                    class="table table-hover mb-0 ps-container ps-theme-default">
                                                                    <thead class="bg-light">
                                                                    <tr>
                                                                        <th>SKU</th>
                                                                        <th>Invoice#</th>
                                                                        <th>Customer Name</th>
                                                                        <th>Status</th>
                                                                        <th>Amount</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>PAP-10521</td>
                                                                        <td><a href="#">INV-281281</a></td>
                                                                        <td>Baja Khan</td>
                                                                        <td><span class="badge badge-success">Paid</span></td>
                                                                        <td>$ 1228.28</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAP-532521</td>
                                                                        <td><a href="#">INV-01112</a></td>
                                                                        <td>Khan Sab</td>
                                                                        <td><span class="badge badge-warning">Overdue</span>
                                                                        </td>
                                                                        <td>$ 5685.28</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAP-05521</td>
                                                                        <td><a href="#">INV-281012</a></td>
                                                                        <td>Bin Ladin</td>
                                                                        <td><span class="badge badge-success">Paid</span></td>
                                                                        <td>$ 152.28</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAP-15521</td>
                                                                        <td><a href="#">INV-281401</a></td>
                                                                        <td>Zoor Shoor</td>
                                                                        <td><span class="badge badge-success">Paid</span></td>
                                                                        <td>$ 1450.28</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAP-532521</td>
                                                                        <td><a href="#">INV-01112</a></td>
                                                                        <td>Khan Sab</td>
                                                                        <td><span class="badge badge-warning">Overdue</span>
                                                                        </td>
                                                                        <td>$ 5685.28</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAP-05521</td>
                                                                        <td><a href="#">INV-281012</a></td>
                                                                        <td>Bin Ladin</td>
                                                                        <td><span class="badge badge-success">Paid</span></td>
                                                                        <td>$ 152.28</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAP-15521</td>
                                                                        <td><a href="#">INV-281401</a></td>
                                                                        <td>Zoor Shoor</td>
                                                                        <td><span class="badge badge-success">Paid</span></td>
                                                                        <td>$ 1450.28</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAP-32521</td>
                                                                        <td><a href="#">INV-288101</a></td>
                                                                        <td>Walter R.</td>
                                                                        <td><span class="badge badge-warning">Overdue</span>
                                                                        </td>
                                                                        <td>$ 685.28</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                    
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-timeline" role="tabpanel" aria-labelledby="v-pills-timeline-tab">
                    
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label">Name</label>
                    
                                                <div class="col-sm-10">
                                                    <input class="form-control" id="inputName" placeholder="Name" type="email">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                    
                                                <div class="col-sm-10">
                                                    <input class="form-control" id="inputEmail" placeholder="Email" type="email">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 control-label">Name</label>
                    
                                                <div class="col-sm-10">
                                                    <input class="form-control" id="inputName" placeholder="Name" type="text">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputExperience" class="col-sm-2 control-label">Experience</label>
                    
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputSkills" class="col-sm-2 control-label">Skills</label>
                    
                                                <div class="col-sm-10">
                                                    <input class="form-control" id="inputSkills" placeholder="Skills" type="text">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                       
                   </div>
                    </div>
            
                </div>
            </div>

            {{-- page body end here --}}
        </div>
        @include('layouts.scripts')
        
    </body>
</html>
