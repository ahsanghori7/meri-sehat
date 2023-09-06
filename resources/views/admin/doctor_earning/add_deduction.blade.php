@extends('layouts.admin.app')
@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        input[name=transaction]{
            width: 1.3rem;
            height: 1.3rem;
        }
    </style>
@endpush
@section('page_header')
    All {{Str::plural($module_name)}}
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <h2 class="mt-2"><a href="{{ url()->previous() }}" class="text-dark"><i class="fa fa-chevron-left"></i></a> New Transaction</h2>
            <div class="form-group">
                <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="transaction" name="transaction" id="transaction" {{ isset($transaction) ? 'checked' : '' }}>
                        <label class="form-check-label font-weight-bold" for="transaction">
                            Transaction
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="deduction" name="transaction" id="deduction" {{ isset($deduction) ? 'checked' : '' }}>
                        <label class="form-check-label font-weight-bold" for="deduction">
                            Deduction
                        </label>
                    </div>
                </div>
            @isset($deduction)
            <form action="{{ route('doctor-payout-appointments-add_deduction') }}" method="POST" id="transaction-from" class="my-4 p-3">
                @csrf
                <input type="hidden" name="checkAppointments" data-value="" id="checkAppointments">
                <input type="hidden" name="doctor_earning_id" id="doctor_earning_id">
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="doctor_name">Doctor Name: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <select class="select2 doctor-name" id="doctor_name" name="doctor_name">
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="doctor_name">Total Receiveables: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="total_receivables" id="total_receivables" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="doctor_name">Amount: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="select appointments first" name="amount" id="amount" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="doctor_name">Date Of Transaction: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control" name="date_of_transaction" id="date_of_transaction">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <a href="{{ url()->previous() }}" class="btn bg-danger text-white">Cancel</a>
                        <button type="submit" class="btn bg-success text-white">Save</button>
                    </div>
                </div>
            </form>
            @endisset
        </div>
        <div class="col-md-6 col-sm-12 border-left vh-100">
            <div class="form-group row mt-4">
                <div class="col-md-4">
                    <label class="font-weight-bold" for="searchDate">Date Range</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="searchDate" id="searchDate" class="form-control searchable_field" value="">
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover data-table-transaction-form" style="width:100%;">
                <thead>
                <tr>
                    <th></th>
                    <th><input type="checkbox" name="check_appointments" id="check_appointments" class="check_appointments"></th>
                    <th width=" 10px">Date</th>
                    <th class="">Appt ID</th>
                    <th class="">Consultation Fees</th>
                </tr>
                </thead>
                <tbody class="">
        
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).on('click', '.check_appointments', function() {
            if ($(this).is(':checked')) {
                $('.appointment_chk').each(function( index ) {
                    $(this).click();
                });
            } else {
                $('.appointment_chk').each(function( index ) {
                    $(this).click();
                });
            }
        });
        $appointment_price_array = [];
        $appointment_id_array = [];
        $(document).on('click', '.appointment_chk', function() {
            var id = $(this).val(); // this gives me null
            var price = $(this).attr('data-value');
            var index = $appointment_price_array.indexOf(price);
            if($(this).is(':checked')){
                $appointment_price_array.push(price);
                $appointment_id_array.push(id);
                $('#checkAppointments').attr('data-value',$appointment_price_array);
                $('#checkAppointments').val($appointment_id_array);
                let earning_id = $("#earning_id").val();
                $("#doctor_earning_id").val(earning_id);
            }
            else{
                if (index > -1) {
                    $appointment_price_array.splice(index, 1);
                    $appointment_id_array.splice(index, 1);
                    $('#checkAppointments').attr('data-value',$appointment_price_array);
                $('#checkAppointments').val($appointment_id_array);
                }
            }
            calculateReceivable();
        });

        calculateReceivable();
        function calculateReceivable() {
            var checkAmount = $('#checkAppointments').val();
            var array = $('#checkAppointments').attr('data-value').split(",");
            // var array = $("#checkAppointments").attr('data-value').split(",");
            var totalAmount = 0;
            if (checkAmount) {
                $.each(array,function(i){
                    totalAmount = parseInt(totalAmount)+parseInt(array[i]);
                });
            }
            $('#total_receivables').val(totalAmount);
            $('#amount').val(totalAmount);
        }

        $(document).ready(function(){
            $('#searchDate').daterangepicker();
            $('#searchDate').val('');
            $("input[name='transaction']").change(function(){
                if($(this).val() == 'transaction'){
                    window.location.href = '{{ route("doctor-payout-add", ["transaction"]) }}';
                }
                else if($(this).val() == 'deduction'){
                    window.location.href = '{{ route("doctor-payout-add", ["deduction"]) }}';
                }
            });
            $("#doctor_name").select2({
            placeholder: "Search for doctor here",
            minimumInputLength: 1,
            ajax: {
                url: "{{ route('doctor-payout-doctors') }}",
                type: 'GET',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        page_limit: 10,
                        search: term.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
            $("#doctor_name").change(function(){
                var table = $('.data-table-transaction-form');
                if($.fn.dataTable.isDataTable(table)){
                    table.DataTable().clear();
                    table.DataTable().destroy();
                }
                var id = $(this).val();
                var url = '{{ route("doctor-payout-deduction_appointments", ":id") }}';
                url = url.replace(":id", id);
                console.log(url);
                $('.data-table-transaction-form').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                lengthChange: false,
                searching: false,
                ajax: {
                    url: url,
                    data: function(d) {
                        d.tab = 'deduction';
                        d.searchDate = $("#searchDate").val();
                    }
                },
                columns: [
                    {
                        render: function (data, type, row, index) {
                            return '<input type="hidden" name="earning_id" id="earning_id" class="earning_id" value="'+row.doctor_earning_id+'" />';
                        }
                    },
                    {
                        render: function (data, type, row, index) {
                            return '<input type="checkbox" name="appointment_chk" id="appointment_chk" class="appointment_chk" value="'+row.appointment.id+'" data-value="' + row.appointment.consultation_fee + '" />';
                        }
                    },
                    {data: 'date', name: 'date'},
                    {data: 'appt_id', name: 'appt_id'},
                    {data: 'consultation_fee', name: 'consultation_fee'},
                ],
                order: [[0, 'desc']],
            });
        });
            $(document).on('change', '.searchable_field', function(){
                var table = $('.data-table-transaction-form');
                if($.fn.dataTable.isDataTable(table)){
                    table.DataTable().ajax.reload(null, false);
                }
            });
        });
    </script>
@endpush