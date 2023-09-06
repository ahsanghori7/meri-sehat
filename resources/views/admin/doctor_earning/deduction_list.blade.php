@extends('layouts.admin.app')
@section('page_header')
    All {{Str::plural($module_name)}}
@endsection
@section('content')
@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
<div class="container">
    <h2 class="mt-2"><a href="{{ url()->previous() }}" class="text-dark"><i class="fa fa-chevron-left"></i></a> Deduction List</h2>
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
                    <input type="hidden" name="deduction">
                    <button type="submit" class="btn bg-green text-white px-4">Add New</button>
                </form>
                <p class="font-weight-bolder mt-2">Total Deductions: {{ $total_payouts }}</p>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered table-hover data-table-deduction" style="width:100%;">
        <thead>
        <tr>
            <th width=" 10px">Date</th>
            <th class="">Doctor Name</th>
            <th class="">Deduction Amount</th>
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
        var table = $('.data-table-deduction').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                lengthChange: false,
                searching: false,
                ajax: {
                    url: "{{ route('doctors-deduction-list') }}",
                    data: function(d) {
                        d.tab = 'deduction';
                        d.search_doctor = $("#search_doctor").val();
                        d.searchDate = $("#searchDate").val();
                    }
                },
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'doctor_name', name: 'doctor_name'},
                    {data: 'receiveable', name: 'receiveable'},
                    {data: 'actionBy', name: 'actionBy'},
                ],
                order: [[0, 'desc']],
            });
        $(document).ready(function(){
            $('#searchDate').daterangepicker();
            $('#searchDate').val('');
            $('.data-table-deduction').DataTable().ajax.reload(null, false);
        });
        $(document).on('change', '.searchable_field', function(){
            $('.data-table-deduction').DataTable().ajax.reload(null, false);
        });
    </script>
@endpush
