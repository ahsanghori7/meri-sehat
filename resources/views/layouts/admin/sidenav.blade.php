 <aside class="main-sidebar fixed offcanvas shadow" data-toggle='offcanvas'>
     <section class="sidebar">
         <div class="my-3 mx-4 text-center ">
             <a href="{{ route('admin.home') }}">
                 <img src="{{ asset('admin/img/logo.svg') }}" class="hide-on-collapse" alt="SkyFreight">
                 <img src="{{ asset('admin/img/closed-menu.png') }}" class="show-on-collapse" alt="SkyFreight">
             </a>
         </div>
         {{-- <div class="relative">
             <div class="user-panel p-3 light mb-2">
                 <div>
                     <div class="float-left image">
                         <img class="user_avatar" src="{{ Auth::user()->image ? env('ASSETS_STORAGE'). Auth::user()->image : env('ASSETS_STORAGE').'assets/img/default.png' }}" alt="User Image">
         </div>
         <div class="float-left info">
             <h6 class="font-weight-light mt-2 mb-1">{{ Auth::user()->name }}</h6>
         </div>
         </div>
         </div>
         </div> --}}
         <ul class="sidebar-menu">
             <li class="header"><strong>MAIN NAVIGATION</strong></li>
             <li>
                 <a href="{{ route('admin.home') }}">
                     <span class="d-inline-block">
                         <i class="icon icon-dashboard2"></i>
                     </span>
                     Dashboard
                 </a>
             </li>
             @can('booking-management')
             <li class="treeview">
                 @can('booking-management-view')
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-note-list2 s-18"></i>
                     </span>
                     Booking Management
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 @endcan
                 <ul class="treeview-menu" style="display: none;">
                     @can('booking-management-view')
                     <li>
                         <a href="{{ route('appointment-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             All Booking
                         </a>
                     </li>
                     @endcan
                     @can('booking-management-view')
                     <li>
                         <a href="{{ route('appointment-booking_now') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             Now Booking
                         </a>
                     </li>
                     @endcan
                 </ul>
             </li>
             @endcan
             @can('scan-management')
             <li class="treeview">
                 @can('scan-management-view')
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-crosshairs s-18"></i>
                     </span>
                     Scan Management
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 @endcan
                 @can('scan-management-view')
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('health-scan-management-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             Sehat Scan Listing
                         </a>
                     </li>
                 </ul>
                 @endcan
             </li>
             @endcan
             @can('article-management')
                 <li class="treeview">
                     <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                         Articles
                         <i class="icon icon-angle-left s-18 pull-right"></i>
                     </a>
                     <ul class="treeview-menu" style="display: none;">
                         @can('article-management-view')
                             @can('article-management-wellness')
                             <li>
                                 <a href="{{ route('article-wellness-view') }}">
                                         <span class="d-inline-block">
                                             <i class="icon icon-list4"></i>
                                         </span>
                                     All Wellness Articles
                                 </a>
                             </li>
                             @endcan

                             @can('article-management-disease')
                             <li>
                                 <a href="{{ route('article-disease-view') }}">
                                         <span class="d-inline-block">
                                             <i class="icon icon-list4"></i>
                                         </span>
                                     All Disease Articles
                                 </a>
                             </li>
                             @endcan
                         @endcan

                         @can('article-management-draft')
{{--                             <li>--}}
{{--                                 <a href="{{ route('article-draft') }}">--}}
{{--                                     <span class="d-inline-block">--}}
{{--                                         <i class="icon icon-list4"></i>--}}
{{--                                     </span>--}}
{{--                                     All Draft Articles--}}
{{--                                 </a>--}}
{{--                             </li>--}}
                             @can('article-management-wellness')
                             <li>
                                 <a href="{{ route('article-wellness-draft') }}">
                                 <span class="d-inline-block">
                                     <i class="icon icon-list4"></i>
                                 </span>
                                     All Wellness Draft Articles
                                 </a>
                             </li>
                             @endcan

                             @can('article-management-disease')
                             <li>
                                 <a href="{{ route('article-disease-draft') }}">
                                 <span class="d-inline-block">
                                     <i class="icon icon-list4"></i>
                                 </span>
                                     All Disease Draft Articles
                                 </a>
                             </li>
                             @endcan
                         @endcan

                         @can('article-management-add')
                             @can('article-management-wellness')
                             <li>
                                 <a href="{{ route('article-wellness-draft-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Wellness Article
                                 </a>
                             </li>
                             @endcan

                             @can('article-management-disease')
                             <li>
                                 <a href="{{ route('article-disease-draft-add') }}">
                                    <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Disease Article
                                 </a>
                             </li>
                             @endcan
                         @endcan
                     </ul>
                 </li>
             @endcan
             @can('disease-page')
                 <li class="treeview">
                     <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                         Disease Page
                         <i class="icon icon-angle-left s-18 pull-right"></i>
                     </a>
                     <ul class="treeview-menu" style="display: none;">
                         @can('disease-page-view')
                         <li>
                             <a href="{{ route('disease-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                 All Diseases
                             </a>
                         </li>
                         @endcan
                         @can('disease-page-draft')
                         <li>
                             <a href="{{ route('disease-draft') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                                 All Draft Diseases
                             </a>
                         </li>
                         @endcan
                         @can('disease-page-add')
                             <li>
                                 <a href="{{ route('disease-draft-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Disease
                                 </a>
                             </li>
                         @endcan
                     </ul>
                 </li>
             @endcan

             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-th-list s-18"></i>
                     </span>
                     Content Management
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     @can('menu-management')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             Menu Management
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             @foreach($languages as $language)
                             <li>
                                 <a href="{{ route('menu-view',['lang_id' => $language->e_id])}}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-circle-o"></i>
                                     </span>
                                     Header Menu ({{$language->name}})
                                 </a>
                             </li>
                             @endforeach
                             <hr>
                             @foreach($languages as $language)
                             <li>
                                 <a href="{{ route('footer-view',['lang_id' => $language->e_id])}}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-circle-o"></i>
                                     </span>
                                     Footer Menu ({{$language->name}})
                                 </a>
                             </li>
                             @endforeach
                         </ul>
                     </li>
                     @endcan
                     @can('drug-page')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             Drug Page
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             @can('drug-page-view')
                             <li>
                                 <a href="{{ route('drug-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                     All Drugs
                                 </a>
                             </li>
                             @endcan
                             @can('drug-page-draft')
                             <li>
                                 <a href="{{ route('drug-draft') }}">
                                 <span class="d-inline-block">
                                     <i class="icon icon-list4"></i>
                                 </span>
                                     All Draft Drugs
                                 </a>
                             </li>
                             @endcan
                             @can('drug-page-add')
                             <li>
                                 <a href="{{ route('drug-draft-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Drug
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
                     @endcan
                     @can('category-management')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             Category Listing
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             @can('category-management-view')
                             <li>
                                 <a href="{{ route('topics-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                     All Categories
                                 </a>
                             </li>
                             @endcan
                             @can('category-management-draft')
                                 <li>
                                     <a href="{{ route('topics-draft') }}">
                                 <span class="d-inline-block">
                                     <i class="icon icon-list4"></i>
                                 </span>
                                         All Draft Categories
                                     </a>
                                 </li>
                             @endcan
                             @can('category-management-add')
                             <li>
                                 <a href="{{ route('topics-draft-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Category
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
                     @endcan

                     @if(auth()->user()->role_id != (new App\Http\Common\Constant)->UPLOADERS_ROLE_ID)
                         @can('default-page')
                         <li class="treeview">
                             <a href="#">
                                 <span class="d-inline-block">
                                     <i class="icon icon-change_history s-18"></i>
                                 </span>
                                 Default Page
                                 <i class="icon icon-angle-left s-18 pull-right"></i>
                             </a>
                             <ul class="treeview-menu" style="display: none;">
                                 @can('default-page-view')
                                 <li>
                                     <a href="{{ route('page-view') }}">
                                         <span class="d-inline-block">
                                             <i class="icon icon-list4"></i>
                                         </span>
                                         All Pages
                                     </a>
                                 </li>
                                 @endcan
                                 @can('default-page-draft')
                                     <li>
                                         <a href="{{ route('page-draft') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                             All Draft Pages
                                         </a>
                                     </li>
                                 @endcan
                                 @can('default-page-add')
                                 <li>
                                     <a href="{{ route('page-draft-add') }}">
                                         <span class="d-inline-block">
                                             <i class="icon icon-add"></i>
                                         </span>
                                         Add Page
                                     </a>
                                 </li>
                                 @endcan
                             </ul>
                         </li>
                         @endcan
                     @endif


                     @can('faq-category')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             FAQ Categories
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             <li>
                                 <a href="{{ route('faq-category-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                     All FAQ Categories
                                 </a>
                             </li>
                             @can('faq-category-add')
                             <li>
                                 <a href="{{ route('faq-category-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add FAQ Category
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
                     @endcan
                     @can('faq')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             FAQ
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             @can('faq-view')
                             <li>
                                 <a href="{{ route('faq-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                     All FAQs
                                 </a>
                             </li>
                             @endcan
                             @can('faq-draft')
                             <li>
                                 <a href="{{ route('faq-draft') }}">
                                 <span class="d-inline-block">
                                     <i class="icon icon-list4"></i>
                                 </span>
                                     All Draft FAQs
                                 </a>
                             </li>
                             @endcan
                             @can('faq-add')
                             <li>
                                 <a href="{{ route('faq-draft-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add FAQ
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
                     @endcan
                     @can('tags')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             Tags
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             @can('tags-view')
                             <li>
                                 <a href="{{ route('tag-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                     All Tags
                                 </a>
                             </li>
                             @endcan
                             @can('tags-draft')
                                 <li>
                                     <a href="{{ route('tag-draft') }}">
                                 <span class="d-inline-block">
                                     <i class="icon icon-list4"></i>
                                 </span>
                                         All Draft Tags
                                     </a>
                                 </li>
                             @endcan
                             @can('tags-add')
                             <li>
                                 <a href="{{ route('tag-draft-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Tag
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
                     @endcan

                 </ul>
             </li>
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-people s-18"></i>
                     </span>
                     User Management
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     @can('admin-roles')
                     <li>
                         <a href="{{ route('admin_users-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-circle-o"></i>
                             </span>
                             Manage Admin Users
                         </a>
                     </li>
                     @endcan
                     @can('rights-management-view')
                     <li>
                         <a href="{{ route('roles.show') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-circle-o"></i>
                             </span>
                             Rights Management
                         </a>
                     </li>
                     @endcan
                     @can('user-management')
                     <li>
                         <a href="{{ route('user_management-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             List all Users
                         </a>
                     </li>
                     @endcan
                 </ul>
             </li>
             @can('patients-management')
             <li>
                 <a href="{{ route('patient-view') }}">
                 <span class="d-inline-block">
                     <i class="icon icon-dashboard2"></i>
                 </span>
                     Patients
                 </a>
             </li>
             @endcan
             @can('doctor-management')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-user-md s-18"></i>
                     </span>
                     Doctors Management
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('doctor-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             List all Doctors
                         </a>
                     </li>
                 </ul>
             </li>
             @endcan
             @can('fitness-management')
                 <li class="treeview">
                     <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-user-md s-18"></i>
                     </span>
                         Wellness Experts
                         <i class="icon icon-angle-left s-18 pull-right"></i>
                     </a>
                     <ul class="treeview-menu" style="display: none;">
                         <li>
                             <a href="{{ route('fitness-experts-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                                 List all Wellness Experts
                             </a>
                         </li>
                     </ul>
                 </li>
             @endcan
             @can('ads-management')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-mms s-18"></i>
                     </span>
                     Ads Management
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('ads-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             All Ads
                         </a>
                     </li>
                     @can('ads-management-add')
                     <li>
                         <a href="{{ route('ads-add') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-add"></i>
                             </span>
                             Add Ads
                         </a>
                     </li>
                     @endcan
                 </ul>
             </li>
             @endcan
             @can('finance')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-poll s-18"></i>
                     </span>
                     Finance
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                    <li>
                        {{-- <a href="{{ route('site-content-view') }}"> --}}
                        <a href="{{ route('doctor-earning-view') }}">
                            <span class="d-inline-block">
                                <i class="icon icon-list4"></i>
                            </span>
                            Default Pricing
                        </a>
                    </li>
                     <li>
                         {{-- <a href="{{ route('site-content-view') }}"> --}}
                         <a href="{{ route('doctor-earning-details') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             Doctor Earnings
                         </a>
                     </li>
                 </ul>
             </li>
             @endcan
             @can('reporting')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-printer2 s-18"></i>
                     </span>
                     Reports
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">

                     @can('reporting-sehat-scan-report')
                     <li>
                         <a href="{{ route('report-sehat_scan_report') }}">
                             {{-- <a href="#"> --}}
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             Sehat Scan Report
                         </a>
                     </li>
                     @endcan
                 </ul>
             </li>
             @endcan

             @can('newsletter-subscribers')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-email2 s-18"></i>
                     </span>
                     Newsletter Subscribers
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('newsletter-view') }}">
                             {{-- <a href="#"> --}}
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             All Subscribers
                         </a>
                     </li>
                 </ul>
             </li>
             @endcan
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-gears s-18"></i>
                     </span>
                     Settings
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     @can('activity-logs')
                     <li>
                         <a href="{{ route('activity-logs-index') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list"></i>
                             </span>
                             Activity Logs
                         </a>
                     </li>
                     @endcan
                     @can('subscription')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             Subscription
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             <li>
                                 <a href="{{ route('subscription-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                     All Subscription
                                 </a>
                             </li>
                             @can('subscription-add')
                             <li>
                                 <a href="{{ route('subscription-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Subscription
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
                     @endcan
                     @can('language-management')
                     <li>
                         <a href="{{ route('language-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history"></i>
                             </span>
                             Language Management
                         </a>
                     </li>
                     @endcan
                     @can('media-language-management')
                         <li>
                             <a href="{{ route('media-language-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-change_history"></i>
                         </span>
                                 Media Language Management
                             </a>
                         </li>
                     @endcan
                     @can('widgets-management')
                     <li class="treeview">
                         <a href="#">
                             <span class="d-inline-block">
                                 <i class="icon icon-change_history s-18"></i>
                             </span>
                             Widgets
                             <i class="icon icon-angle-left s-18 pull-right"></i>
                         </a>
                         <ul class="treeview-menu" style="display: none;">
                             <li>
                                 <a href="{{ route('widget-view') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-list4"></i>
                                     </span>
                                     All Widgets
                                 </a>
                             </li>
                             @can('widgets-management-add')
                             <li>
                                 <a href="{{ route('widget-add') }}">
                                     <span class="d-inline-block">
                                         <i class="icon icon-add"></i>
                                     </span>
                                     Add Widget
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
             </li>
             @endcan
             @can('speciality-management')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-change_history s-18"></i>
                     </span>
                     Specialities
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('speciality-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             All Specialities
                         </a>
                     </li>
                     @can('speciality-management-add')
                     <li>
                         <a href="{{ route('speciality-add') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-add"></i>
                             </span>
                             Add Speciality
                         </a>
                     </li>
                     @endcan
                 </ul>
             </li>
             @endcan
             @can('services-management')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-change_history s-18"></i>
                     </span>
                     Services
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('service-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             All Services
                         </a>
                     </li>
                     @can('services-management-add')
                     <li>
                         <a href="{{ route('service-add') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-add"></i>
                             </span>
                             Add Service
                         </a>
                     </li>
                     @endcan
                 </ul>
             </li>
             @endcan
             @can('cities-management')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-change_history s-18"></i>
                     </span>
                     Cities Management
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('city-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             All Cities
                         </a>
                     </li>
                     @can('cities-management-add')
                     <li>
                         <a href="{{ route('city-add') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-add"></i>
                             </span>
                             Add City
                         </a>
                     </li>
                     @endcan
                 </ul>
             </li>
             @endcan
             @can('article-fact')
             <li class="treeview">
                <a href="#">
                    <span class="d-inline-block">
                        <i class="icon icon-change_history s-18"></i>
                    </span>
                    Article Facts / Labels
                    <i class="icon icon-angle-left s-18 pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    @can('article-fact-view')
                    <li>
                        <a href="{{ route('article-fact-view') }}">
                            <span class="d-inline-block">
                                <i class="icon icon-list4"></i>
                            </span>
                            All Article Facts / Labels
                        </a>
                    </li>
                    @endcan
                    @can('article-fact-add')
                    <li>
                        <a href="{{ route('article-fact-add') }}">
                            <span class="d-inline-block">
                                <i class="icon icon-add"></i>
                            </span>
                            Add Article Fact / Label
                        </a>
                    </li>
                    @endcan
                </ul>
             </li>
             @endcan
             @can('general-settings')
             <li>
                 <a href="{{ route('settings') }}">
                     <span class="d-inline-block">
                         <i class="icon icon-settings2"></i>
                     </span>
                     General Settings
                 </a>
             </li>
             @endcan
             @can('questionaire-form')
             <li>
                 <a href="{{ route('questionaire_form-view') }}">
                     <span class="d-inline-block">
                         <i class="icon icon-change_history"></i>
                     </span>
                     Questionaire Form
                 </a>
             </li>
             @endcan
             @can('info-modal-management')
             <li class="treeview">
                 <a href="#">
                     <span class="d-inline-block">
                         <i class="icon icon-change_history s-18"></i>
                     </span>
                     Info Modal Contents
                     <i class="icon icon-angle-left s-18 pull-right"></i>
                 </a>
                 <ul class="treeview-menu" style="display: none;">
                     <li>
                         <a href="{{ route('info-model-view') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-list4"></i>
                             </span>
                             All
                         </a>
                     </li>
                     <li>
                         <a href="{{ route('info-model-add') }}">
                             <span class="d-inline-block">
                                 <i class="icon icon-add"></i>
                             </span>
                             Add
                         </a>
                     </li>
                 </ul>
             </li>
             @endcan


         </ul>
         </li>
         </ul>
         </li>
         </ul>




         {{-- <ul class="sidebar-menu">
            <li class="header"><strong>MAIN NAVIGATION</strong></li>
            <li>
                <a href="{{ route('admin.home') }}">
         <span class="d-inline-block">
             <i class="icon icon-dashboard2"></i>
         </span>
         Dashboard
         </a>
         </li>

         <li class="treeview">
             <a href="#">
                 <span class="d-inline-block">
                     <i class="icon icon-ship s-18"></i>
                 </span>
                 Shipment
                 <i class="icon icon-angle-left s-18 pull-right"></i>
             </a>
             <ul class="treeview-menu" style="display: none;">
                 <li>
                     <a href="{{ route('shipment-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-circle-o"></i>
                         </span>
                         All Shipments
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('shipment-add') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Add Shipment
                     </a>
                 </li> --}}
                 {{-- <li>
                        <a href="{{ route('shipment-add', 'schedule') }}">
                 <span class="d-inline-block">
                     <i class="icon icon-add"></i>
                 </span>
                 Schedule a shipment
                 </a>
         </li> --}}
         {{-- <li>
                        <a href="{{ route('shipment-add', 'tv_cargo') }}">
         <span class="d-inline-block">
             <i class="icon icon-add"></i>
         </span>
         Add TV Cargo
         </a>
         </li>
         </ul>
         </li>

         @can('buy-a-box')
         <li><a href="{{ route('box_order-view') }}">
                 <span class="d-inline-block">
                     <i class="icon icon-box6"></i>
                 </span>
                 Buy Box
             </a> </li>
         </li>
         @endcan

         @can('outlet-management')
         <li class="treeview">
             <a href="#">
                 <span class="d-inline-block">
                     <i class="icon icon-shop s-18"></i>
                 </span>
                 Outlet
                 <i class="icon icon-angle-left s-18 pull-right"></i>
             </a>
             <ul class="treeview-menu" style="display: none;">
                 <li>
                     <a href="{{ route('outlets-view') }}">
                         <span class="d-inline-block">

                             <i class="icon icon-circle-o"></i>
                         </span>
                         All Outlets
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('outlets-inventory-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Add Inventory 
                     </a>
                 </li>
             </ul>
         </li>
         @endcan

         @can('estimate-shipment')
         <li>
             <a href="{{ route('simulator') }}">
                 <span class="d-inline-block">
                     <i class="icon icon-money-1"></i>
                 </span>
                 Estimate Shipment 
             </a>
         </li>
         @endcan

         @can('promo-code-management')
         <li>
             <a href="{{ route('promo_code-view') }}">
                 <span class="d-inline-block">

                     <i class="icon icon-local_offer"></i>
                 </span>
                 Promo Codes Management
             </a>
         </li>
         @endcan

         @can('qr-code-management')
         <li>
             <a href="{{ route('qr_code-view') }}">
                 <span class="d-inline-block">

                     <i class="icon icon-qrcode"></i>
                 </span>
                 QR Codes Management
             </a>
         </li>
         @endcan

         @can('employee-management')
         <li class="treeview">
             <a href="#">
                 <span class="d-inline-block">
                     <i class="icon icon-vcard-o s-18"></i>
                 </span>
                 Employee Management 
                 <i class="icon icon-angle-left s-18 pull-right"></i>
             </a>
             <ul class="treeview-menu" style="display: none;">
                 <li>
                     <a href="{{ route('employee-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-circle-o"></i>
                         </span>
                         All Employees
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('employee-add') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Add Employee
                     </a>
                 </li>
             </ul>
         </li>
         @endcan

         @can('customer-management')
         <li><a href="{{ route('customer-view') }}">
                 <span class="d-inline-block">
                     <i class="icon icon-box6"></i>
                 </span>
                 Customers
             </a> </li>
         </li>
         @endcan

         @can('location-management')
         <li class="treeview">
             <a href="#">
                 <span class="d-inline-block">
                     <i class="icon icon-edit_location s-18"></i>
                 </span>
                 Location
                 <i class="icon icon-angle-left s-18 pull-right"></i>
             </a>
             <ul class="treeview-menu" style="display: none;">
                 <li>
                     <a href="{{ route('region-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-circle-o"></i>
                         </span>
                         Regions 
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('city-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Cities 
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('saudi_city-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Saudi Cities 
                     </a>
                 </li>
             </ul>
         </li>
         @endcan
         @can('settings')
         <li class="treeview">
             <a href="#">
                 <span class="d-inline-block">
                     <i class="icon icon-cog s-18"></i>
                 </span>
                 Settings
                 <i class="icon icon-angle-left s-18 pull-right"></i>
             </a>
             <ul class="treeview-menu" style="display: none;">
                 <li>
                     <a href="{{ route('box_company-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-circle-o"></i>
                         </span>
                         Box Companies
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('box-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-circle-o"></i>
                         </span>
                         Boxes
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('time_slots-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Service Timeslots  
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('tv_type-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Tv Types   
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('content-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Content Management
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('roles.show') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Rights Management   
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('icon-view') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Icon Management  
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('admin.settings') }}">
                         <span class="d-inline-block">
                             <i class="icon icon-add"></i>
                         </span>
                         Preferences  
                     </a>
                 </li>

             </ul>
         </li>
         @endcan --}}














         {{-- @if (Auth::user()->role_id != 99)
                <li>
                    <a href="{{ route('roles.show') }}">
         <i class="icon icon-folder5"></i>
         Rights Management
         </a>
         </li>
         @endif --}}
         {{-- @if (Auth::user()->role_id != 99)
                <li>
                    <a href="{{ route('employee-view') }}">
         <i class="icon icon-folder5"></i>
         Employee Management
         </a>
         </li>
         @endif

         @if (Auth::user()->role_id != 99)
         <li>
             <a href="{{ route('outlets-view') }}">
                 <i class="icon icon-folder5"></i>
                 Outlets
             </a>
         </li>
         @endif

         @if (Auth::user()->role_id != 99)
         <li>
             <a href="{{ route('outlets-inventory-view') }}">
                 <i class="icon icon-folder5"></i>
                 Outlets Inventory
             </a>
         </li>
         @endif

         <li>
             <a href="{{ route('box-view') }}">
                 <i class="icon icon-folder5"></i>
                 Boxes
             </a>
         </li>

         <li>
             <a href="{{ route('tv_type-view') }}">
                 <i class="icon icon-folder5"></i>
                 Tv Types
             </a>
         </li>

         <li>
             <a href="{{ route('region-view') }}">
                 <i class="icon icon-folder5"></i>
                 Regions
             </a>
         </li>

         <li>
             <a href="{{ route('city-view') }}">
                 <i class="icon icon-folder5"></i>
                 Cities
             </a>
         </li>





         @if (Auth::user()->role_id != 99)
         <li><a href="{{ route('time_slots-view') }}">
                 <i class="icon icon-folder5"></i>
                 Time Slots Management
             </a> </li>
         </li>
         @endif

         @if (Auth::user()->role_id != 99)
         <li><a href="{{ route('promo_code-view') }}">
                 <i class="icon icon-folder5"></i>
                 Promo Codes Management
             </a> </li>
         </li>
         @endif


         @if (Auth::user()->role_id != 99)
         <li><a href="{{ route('shipment-view') }}">
                 <i class="icon icon-folder5"></i>
                 All Shipments
             </a> </li>
         </li>
         @endif

         @if (Auth::user()->role_id != 99)
         <li><a href="{{ route('shipment-add') }}">
                 <i class="icon icon-folder5"></i>
                 Add Shipment
             </a> </li>
         </li>
         @endif





         @if (Auth::user()->role_id != 99)
         <li><a href="{{ route('admin.settings') }}">
                 <i class="icon icon-folder5"></i>
                 Preferences
             </a> </li>
         </li>
         @endif --}}



         </ul>
     </section>
 </aside>
