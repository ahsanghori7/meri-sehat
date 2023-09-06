@extends('layouts.admin.app')
@section('page_header')
Dashboard
@endsection
@section('content')

<div class="container-fluid">
    <div class="row my-3">
        @can('dashboard-doctor_online_count')
        <div class="col-md-3 my-2">
            <div class="counter-box white r-5 p-3">
                <div class="p-4">
                    <div class="float-right">
                        <span class="icon icon-user-circle text-success s-48"></span>
                    </div>
                    <div class="counter-title">Doctor Online</div>
                    <h5 class="mt-3">{{number_format($doctor_available_count)}}</h5>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <div class="row m-0">
        @can('dashboard-filter_allow')
        <div class="card p-3 col-md-12">
            <div class="box-header with-border">
                <h5 class="ml-3">Filter <span class="icon icon-filter"></span></h5>
                <form>
                    <div class="d-flex justify-content-start">

                        <div class="col-md-4 mb-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{$start_date}}" class="form-control">
                            {{-- @error('start_date') --}}
                            <div class="text-danger start_date_error" style="display: none">Start Date Cannot be greater than End Date</div>
                            {{-- @enderror --}}
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{$end_date}}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="search_language">Actions:</label>
                            <br>
                            {{-- <input type="submit" name="action" class="btn-success btn-sm btn mb-2 cursor-pointer" value="fetch" > --}}
                            <button value="fetch" name="action" class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class="icon-search4"></i>
                                Fetch Results
                            </button>
                            {{-- <button value="download" name="action" class="btn-info btn-sm btn mb-2 cursor-pointer">
                                <i class="icon-get_app"></i>
                                Download Report
                            </button> --}}
                            <a href="{{route('admin.home')}}" class="btn-warning btn-sm btn mb-2 cursor-pointer">
                                &times;
                                Clear
                            </a>
                            {{-- <a href="#" class="btn-info btn-sm btn mb-2 cursor-pointer">
                                <i class="icon-get_app"></i>
                                Download Report
                            </a> --}}
                        </div>



                    </div>
                </form>
            </div>
        </div>
        @endcan
    </div>
    <div class="row my-3">
        @can('dashboard-doctor_average_time')
        <div class="col-md-3 my-2">
            <div class="counter-box white r-5 p-3">
                <div class="p-4">
                    <div class="float-right">
                        <span class="icon icon-user-circle text-success s-48"></span>
                    </div>
                    <div class="counter-title">Doctor Average Time</div>
                    <h5 class="mt-3">{{ $doctor_average_time }}</h5>
                </div>
            </div>
        </div>
        @endcan

        @can('dashboard-total_earning_count')
        <div class="col-md-3 my-2">
            <div class="counter-box white r-5 p-3">
                <div class="p-4">
                    <div class="float-right">
                        <span class="icon icon-money text-success s-48"></span>
                    </div>
                    <div class="counter-title">Total Earnings</div>
                    <h5 class="mt-3">{{number_format($total_earnings)}}</h5>
                </div>
            </div>
        </div>
        @endcan
        @can('dashboard-articles_count')
        <div class="col-md-3 my-2">
            <a href="{{route('article-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-note-list text-light-blue s-48"></span>
                        </div>
                        <div class="counter-title">Articles Count</div>
                        <h5 class="mt-3">{{$articles_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-disease_count')
        <div class="col-md-3 my-2">
            <a href="{{route('disease-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-bug_report s-48 text-green"></span>
                        </div>
                        <div class="counter-title ">Disease Count</div>
                        <h5 class="mt-3">{{$diseases_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-categories_count')
        <div class="col-md-3 my-2">
            <a href="{{route('topics-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-chrome_reader_mode s-48 text-red"></span>
                        </div>
                        <div class="counter-title">Categories Count</div>
                        <h5 class="mt-3">{{$topics_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-total_signup_count')
        <div class="col-md-3 my-2">
            <a href="{{route('user_management-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-user s-48 text-purple"></span>
                        </div>
                        <div class="counter-title">Total Signups</div>
                        <h5 class="mt-3">{{$users_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-paid_subscribers_count')
        <div class="col-md-3 my-2">
            <a href="{{route('user_management-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-payment s-48 text-yellow"></span>
                        </div>
                        <div class="counter-title">Paid Subscribers</div>
                        <h5 class="mt-3">{{$registered_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('dashboard-drugs_count')
        <div class="col-md-3 my-2">
            <a href="{{route('drug-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <img src="{{asset('admin/img/icon/drug.png')}}" style="width:80px; height:80px">
                        </div>
                        <div class="counter-title">Drugs Count</div>
                        <h5 class="mt-3">{{$drugs_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('dashboard-newsletter_subscribers_count')
        <div class="col-md-3 my-2">
            <a href="{{route('user_management-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-newspaper-o s-48 text-danger"></span>
                        </div>
                        <div class="counter-title">Newsletter Subscribers</div>
                        <h5 class="mt-3">{{$newsletter_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('dashboard-total_consultations_count')
            <div class="col-md-3 my-2">
                <a href="{{route('appointment-view')}}" class="text-black">
                    <div class="counter-box white r-5 p-3">
                        <div class="p-4">
                            <div class="float-right">
                                <span class="icon icon-user s-48 text-danger"></span>
                            </div>
                            <div class="counter-title">Total Consultations</div>
                            <h5 class="mt-3">{{$total_consultations_count}}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endcan
        @can('dashboard-consultation_completed_count')
            <div class="col-md-3 my-2">
                <a href="{{route('appointment-view')}}" class="text-black">
                    <div class="counter-box white r-5 p-3">
                        <div class="p-4">
                            <div class="float-right">
                                <span class="icon icon-verified_user s-48 text-danger"></span>
                            </div>
                            <div class="counter-title">Consultation Completed</div>
                            <h5 class="mt-3">{{$consultation_completed_count}}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endcan
        @can('dashboard-consultation_cancelled_count')
            <div class="col-md-3 my-2">
                <a href="{{route('appointment-view')}}" class="text-black">
                    <div class="counter-box white r-5 p-3">
                        <div class="p-4">
                            <div class="float-right">
                                <span class="icon icon-user-times s-48 text-danger"></span>
                            </div>
                            <div class="counter-title">Consultation Cancelled</div>
                            <h5 class="mt-3">{{$consultation_cancelled_count}}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endcan
        @can('dashboard-consultation_pending_count')
            <div class="col-md-3 my-2">
                <a href="{{route('appointment-view')}}" class="text-black">
                    <div class="counter-box white r-5 p-3">
                        <div class="p-4">
                            <div class="float-right">
                                <span class="icon icon-user-secret s-48 text-danger"></span>
                            </div>
                            <div class="counter-title">Consultation Pending/Waiting</div>
                            <h5 class="mt-3">{{$consultation_pending_count}}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endcan
        @can('dashboard-new_sehat_scan_count')
        <div class="col-md-3 my-2">
            <a href="{{route('report-sehat_scan_report')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-user-secret s-48 text-danger"></span>
                        </div>
                        <div class="counter-title">Number of new sehat scans</div>
                        <h5 class="mt-3">{{$new_scans_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-sehat_scan_count')
        <div class="col-md-3 my-2">
            <a href="{{route('report-sehat_scan_report')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-user-secret s-48 text-danger"></span>
                        </div>
                        <div class="counter-title">Number of total sehat scans</div>
                        <h5 class="mt-3">{{$total_scans_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-subscribers_count')
        <div class="col-md-3 my-2">
            <a href="{{route('patient-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-user-secret s-48 text-danger"></span>
                        </div>
                        <div class="counter-title">Number of subscribers</div>
                        <h5 class="mt-3">{{$new_subscribers_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-total_subscribers_count')
        <div class="col-md-3 my-2">
            <a href="{{route('patient-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-user-secret s-48 text-danger"></span>
                        </div>
                        <div class="counter-title">Number of total subscribers</div>
                        <h5 class="mt-3">{{$total_subscribers_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-recurring_subcribers_count')
        <div class="col-md-3 my-2">
            <a href="{{route('patient-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-user-secret s-48 text-danger"></span>
                        </div>
                        <div class="counter-title">Number of recurring subscribers</div>
                        <h5 class="mt-3">{{$recurring_subscribers_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
        @can('dashboard-subscription_type_count')
        <div class="col-md-3 my-2">
            <a href="{{route('subscription-view')}}" class="text-black">
                <div class="counter-box white r-5 p-3">
                    <div class="p-4">
                        <div class="float-right">
                            <span class="icon icon-user-secret s-48 text-danger"></span>
                        </div>
                        <div class="counter-title">Subscription Type</div>
                        <h5 class="mt-3">{{$subscription_count}}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        $('#start_date').change(function() {
            validateDate();
        })
        $('#end_date').change(function() {
            validateDate();
        })
        window.validateDate = function() {
            var start_date = new Date($('#start_date').val());
            var end_date = new Date($('#end_date').val());

            if (start_date > end_date) {
                $('.start_date_error').show()
            } else {
                $('.start_date_error').hide()
            }
        }

    })

</script>

@endpush
