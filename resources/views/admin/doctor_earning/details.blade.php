@extends('layouts.admin.app')
@section('page_header')
     {{Str::singular($module_name)}}
@endsection
@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')

<div class="my-4 mx-4">
    <button type="button" class="tablinks px-4 py-1 doctorEarning" onclick="openTab(event, 'doctor-earning')">Doctor Earning</button>
    <button type="button" class="tablinks px-4 py-1 payout" id="defaultOpen" onclick="openTab(event, 'payout')">Payout</button>
</div>
<div id="doctor-earning" class="tabcontent">
    <div class="row">
        <div class="col-md-6">
            <label for="search_doctor_id">Search</label>
            <input type="text" name="search_doctor_id" id="search_doctor_id" class="form-control searchable_field" width="30%">
            @error('search_doctor_id')
                <div class="validation-error"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label for="searchEarningDate">Date</label>
            <input type="text" name="searchEarningDate" id="searchEarningDate" class="form-control searchable_field" width="30%">
            @error('searchEarningDate')
                <div class="validation-error"> {{ $message }}</div>
            @enderror
        </div>
    </div>
    <table class="table table-striped table-bordered table-hover data-table-doctor-earning" style="width:100%;">
        <thead>
        <tr>
            <th class="">Doctor ID</th>
            <th class="">Doctor Name</th>
            <th class="">Total Payables</th>
            <th class="">Amount Paid By MS</th>
            <th class="">Last Payout</th>
            <th class="">Action</th>
        </tr>
        </thead>
        <tbody class="">

        </tbody>
    </table>
</div>
<div id="payout" class="tabcontent">
    <div class="row">
        <div class="col-md-6">
            <label for="search_doctor">Search</label>
            <input type="text" name="search_doctor" id="search_doctor" class="form-control searchable_field" width="30%">
            @error('search_doctor')
                <div class="validation-error"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label for="searchDate">Date</label>
            <input type="text" name="searchDate" id="searchDate" class="form-control searchable_field" value="">
            @error('searchDate')
                <div class="validation-error"> {{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <div class="float-right">
                <form action="{{ route('doctor-payout-add') }}" method="GET">
                    <input type="hidden" name="transaction">
                    <button type="submit" class="btn bg-green text-white px-4">Add New</button>
                </form>
                <p class="font-weight-bolder mt-2">Total Payouts: {{ $total_payouts }}</p>
                <a href="{{ route('doctors-deduction-list') }}" id="deduction_list" class="mb-2">View deduction list</a>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered table-hover data-table-payout" style="width:100%;">
        <thead>
        <tr>
            <th width=" 10px">Date</th>
            <th class="">Doctor Name</th>
            <th class="">Total Receiveable</th>
            <th class="">Transaction ID</th>
            <th class="">Date Of Transaction</th>
            <th class="">Amount</th>
            <th class="">Action</th>
        </tr>
        </thead>
        <tbody class="">

        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#searchDate').daterangepicker();
            $('#searchDate').val('');
            $('#searchEarningDate').daterangepicker();
            $('#searchEarningDate').val('');
        });
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active btn btn-primary", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active btn btn-primary";

            if(tabName == 'payout'){
                sessionStorage.removeItem('tab');
                sessionStorage.setItem("tab", "subscription");
                $('.data-table-'+tabName).DataTable().clear().destroy();

            var table = $('.data-table-'+tabName).DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                lengthChange: false,
                searching: false,
                ajax: {
                    url: "{{ route('doctors-payout-table') }}",
                    data: function(d) {
                        d.tab = tabName;
                        d.search_doctor = $("#search_doctor").val();
                        d.searchDate = $("#searchDate").val();
                    }
                },
                columns: [
                    // {
                    //     render: function (data, type, row, index) {
                    //         return '<input type="checkbox" name="subscriptions_chk" id="subscriptions_chk" class="subscriptions_chk" value="' + row.id + '" />';
                    //     }
                    // },
                    {data: 'date', name: 'date'},
                    {data: 'doctor_name', name: 'doctor_name'},
                    {data: 'receiveable', name: 'receiveable'},
                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'transaction_date', name: 'transaction_date'},
                    {data: 'amount', name: 'amount'},
                    {data: 'actionBy', name: 'actionBy'},
                ],
                order: [[0, 'desc']],
            });
            }
            else if(tabName=='doctor-earning'){
            sessionStorage.removeItem('tab');
                sessionStorage.setItem("tab", "subscription");
                $('.data-table-'+tabName).DataTable().clear().destroy();
            var table = $('.data-table-'+tabName).DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                lengthChange: false,
                searching: false,
                ajax: {
                    url: "{{ route('doctor-earning-doctor-earning-list') }}",
                    data: function(d) {
                        d.tab = tabName;
                        d.search_doctor_id = $("#search_doctor_id").val();
                        d.searchEarningDate = $("#searchEarningDate").val();
                    }
                },
                columns: [
                    // {
                    //     render: function (data, type, row, index) {
                    //         return '<input type="checkbox" name="subscriptions_chk" id="subscriptions_chk" class="subscriptions_chk" value="' + row.id + '" />';
                    //     }
                    // },
                    {data: 'doctor_id', name: 'doctor_id'},
                    {data: 'doctor.name', name: 'doctor_name'},
                    {data: 'total_payable', name: 'total_payable'},
                    {data: 'paid_amount', name: 'paid_amount'},
                    {data: 'last_payout', name: 'last_payout'},
                    {data: 'actionBy', name: 'actionBy'}


                ],
                order: [[0, 'desc']],
            });

        }
        }
        $( document ).ready(function() {
            if(sessionStorage.getItem('tab')){
                var element = document.getElementById('defaultOpen');
                if(element != null || element != undefined){
                    for(var i = 0; i < element.length; i++){
                        element[i].removeAttribute('id');
                    }
                }
                var tab = document.getElementsByClassName(sessionStorage.getItem('tab')+'Tab');
                for (var i = 0; i < tab.length;  i++ ){
                    tab[i].setAttribute("id", 'defaultOpen');
                }
            }
            else if(! sessionStorage.getItem('tab')){
                var elem = document.getElementsByClassName("subscriptionTab");
                for (var i = 0; i < elem.length;  i++ ){
                    elem[i].setAttribute("id", 'defaultOpen');
                }
            }
            document.getElementById("defaultOpen").click();
        });
        $(document).on('change', '.searchable_field', function(){
            document.getElementById("defaultOpen").click();
        });
    </script>
@endpush
