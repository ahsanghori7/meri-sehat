@extends('layouts.admin.app')
@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('page_header')
    {{Str::singular($module_name)}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row my-4 px-4 align-items-center">
                <div class="col-md-5" >
                    <h5>{{$doctorName->doctor->name}}</h5>

                </div>
                <div class="col-md-7 row align-items-center justify-content-end">
                    <p class="mb-0 fw-500 mx-2">Earnings for this month : Rs.{{$doctorThisMonthEarning}}</p> |
                    <p class="mb-0 fw-500 mx-2">Total Earning : Rs.{{$doctorTotalEarning}}</p> |
                    <p class="mb-0 fw-500 mx-2">Total Payables : Rs.{{$doctorTotalPayables}}</p> |
                    <p class="mb-0 fw-500 mx-2">Amount Paid : Rs.{{$doctorAmountPaid}}</p>
                </div>
            </div>

        <div class="d-flex align-items-end justify-content-between">
            <div class="col-md-6 row">
                <div class="col-md-6">
                    <label for="search_appt_id">Search</label>
                    <input type="text" name="search_appt_id" id="search_appt_id" class="form-control searchable_field" width="30%">
                    @error('search_appt_id')
                        <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="searchApptDate">Date</label>
                    <input type="text" name="searchApptDate" id="searchApptDate" class="form-control searchable_field" width="30%" >
                    @error('searchApptDate')
                        <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="paymentmethod">Payment Method</label>
                    <select class="paymentmethod form-control">
                        <option value="">Select payment method</option>
                        <option value="one_time_payment">One time</option>
                        <option value="subscription">Subscription</option>
                    </select>
                    @error('paymentmethod')
                        <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="consulttype">Consult type</label>
                    <select class="consulttype form-control">
                        <option value="">Select consult type</option>
                        <option value="instant-consultation">Doctor Now</option>
                    </select>
                    @error('consulttype')
                        <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>



            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-transparent text-color-setts" data-toggle="modal" data-target="#backDetails">View Bank Details</button>
            </div>
        </div>






    </div>
    </div>

    <table class="table table-striped table-bordered table-hover data-table-doctor-earning-datails" style="width:100%;">
        <thead>
        <tr>
            <th class="">Appt ID</th>
            <th class="">Name</th>
            <th class="">Date and Time</th>
            <th class="">Consult Type</th>
            <th class="">Amount</th>
            <th class="">Cancellation fee</th>
            <th class="">Payment Type</th>
            <th class="">Action</th>
        </tr>
        </thead>
        <tbody class="">

        </tbody>
    </table>
    <!-- Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10 mx-auto">
                    <h5 class="modal-title text-center" id="invoiceLabel">Invoice</h5>
                </div>
                <div class="col-md-2 mx-auto">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 float-left">
                        <p>Appt ID</p>
                        <p>Patient Name</p>
                        <p>Consult Date & Time</p>
                        <p>Consult Type</p>
                        <p>Payment Type</p>
                        <p>Amount paid by patient</p>
                        <p>Meri sehat platform fee</p>
                        <p>Total amount</p>

                    </div>
                    <div class="col-md-6 text-right">
                        <p id="appt_id"></p>
                        <p id="patient_name"></p>
                        <p id="consult_time"></p>
                        <p id="consult_type"></p>
                        <p id="payment_type"></p>
                        <p id="patient_fee"></p>
                        <p id="merisehat_fee"></p>
                        <p id="grand_total"></p>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="backDetails" tabindex="-1" aria-labelledby="backDetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10 mx-auto">
                    <h5 class="modal-title text-center">Bank Details</h5>
                </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div class="text-left">
                            <div class="col-md-12 row">
                                <div class="col-md-5">
                                <label for="inputField">Account Title</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="inputField" name="inputField" value="{{$doctorName->doctor->doctorBankDetails->account_name}}">
                            </div>
                            </div>
                            <div class="col-md-12 row">
                                <div class="col-md-5">
                                <label for="inputField">Bank Name</label>
                                </div>
                                <div class="col-md-7">
                                <input type="text" id="inputField" name="inputField" value={{$doctorName->doctor->doctorBankDetails->bank_name}}>
                                </div>
                            </div>
                            <div class="col-md-12 row">
                                <div class="col-md-5">
                                <label for="inputField">Account Number</label>
                                </div>
                                <div class="col-md-7">
                                <input type="text" id="inputField" name="inputField" value={{$doctorName->doctor->doctorBankDetails->account_number}}>
                                </div>
                            </div>
                            <div class="col-md-12 row">
                                <div class="col-md-5">
                                <label for="inputField">Iban Number</label>
                                </div>
                                <div class="col-md-7">
                                <input type="text" id="inputField" name="inputField" value={{$doctorName->doctor->doctorBankDetails->iban_number}}>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 mx-auto text-center">
                <button type="button" class="btn btn-primary text-center" disabled>Save</button>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function(){

            $('#searchApptDate').daterangepicker();
            $('#searchApptDate').val('');
        });

    function doctorEarningInvoice(id) {

        var url = '{{ route("doctor-earning-invoice", ":id") }}';
        url = url.replace(":id", id);
        $.ajax({
            url: url,  // Replace with the actual URL

            dataType: 'json',  // Specify the expected data type (json, xml, html, etc.)
            success: function(response) {
                // Handle the success response
                $("#appt_id").text(response.id)
                $("#patient_name").text(response.patient_detail.name != null ? response.patient_detail.name : "null")
                $("#consult_time").text(response.call_started != null ? response.call_started : "null")
                $("#consult_type").text(response.type != null ? response.type : "null")
                $("#payment_type").text(response.booked_via_subscription !=null ? response.booked_via_subscription : "null")
                $("#merisehat_fee").text(response.ms_commission !=null ? response.ms_commission : "null")
                $("#patient_fee").text(response.amount !=null ? response.amount : "null")
                $("#grand_total").text(response.grand_total!=null ? response.grand_total : "null")
                $("#invoiceModal").modal('show');
                console.log(response);

            },
            error: function(xhr, status, error) {
                // Handle the error response
                console.log(xhr.responseText);
            }
        });

    }

    var currentUrl = window.location.href;
    var lastSlashIndex = currentUrl.lastIndexOf("/");
    var appt_id = currentUrl.substring(lastSlashIndex + 1);
    var url = '{{ route("doctor-earning-doctor-earning-view", ":id") }}';
    let id = appt_id;
    url = url.replace(":id", id);
    var table = $('.data-table-doctor-earning-datails').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        lengthChange: false,
        searching: false,
        ajax: {
            url: url,
            data: function(d) {
                d.search_appt_id = $("#search_appt_id").val();
                d.searchApptDate = $("#searchApptDate").val();
                d.paymentmethod = $(".paymentmethod").val();
                d.consulttype= $('.consulttype').val();
            }

        },
        columns: [
            // {
            //     render: function (data, type, row, index) {
            //         return '<input type="checkbox" name="subscriptions_chk" id="subscriptions_chk" class="subscriptions_chk" value="' + row.id + '" />';
            //     }
            // },
            {data: 'appointment.id', name: 'appt_id'},
            {data: 'appointment.patient_detail.name', name: 'name'},
            {data: 'appointment_created_at', name: 'created'},
            {data: 'appointment.type', name: 'type'},
            {data: 'appointment.amount', name: 'amount'},
            {data: 'cancellation_fee', name: 'cancellation fee'},
            {data: 'appointment.booked_via_subscription', name: 'booked_via_subscription'},
            {data: 'actionBy', name: 'actionBy'}

        ],
        order: [[0, 'desc']],

    });
    $(document).on('change', '.searchable_field,.paymentmethod, .consulttype', function(){
            table.ajax.reload(null, false);
        });
</script>
<style>
    .fw-500{
        font-weight: 500;
    }
    .text-color-setts,
    .text-color-setts:hover,
    .text-color-setts:focus{
        color: #007bff;
    border-bottom: 1px solid #007bff;
    padding: 0;
    border-radius: 0;
    font-weight: 500;
    box-shadow: none
    }
</style>
@endpush
