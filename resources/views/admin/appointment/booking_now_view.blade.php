@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-between rounded border border-dark p-3 mb-4">
                        <h4 class="mb-0">Now Booking</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3">
                        <h5 class="">Filter</h5>
                        <span class="icon icon-filter"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="search_name">Name</label>
                            <input type="text" class="form-control searchable_field" name="search_name" id="search_name" value="" />
                            @error('search_name')
                            <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="search_toDate">Date</label>
                            <input type="date" name="search_toDate" id="search_toDate" class="form-control searchable_field" width="30%">
                            @error('search_toDate')
                            <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="search_category">Speciality</label>
                            <select class="form-control searchable_field" id="search_speciality" name="search_speciality">
                                <option value=""> Select Speciality </option>
                                @foreach ($specialities as $speciality)
                                    <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                                @endforeach
                            </select>
                            @error('search_category')
                                                    <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="search_type">Location</label>
                            <select class="form-control searchable_field" id="search_location" name="search_location">
                                <option value=""> Select Location </option>
                                @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('search_type')
                                                    <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="search_progress">Status</label>
                            <select class="form-control searchable_field" id="search_progress" name="search_progress">
                                <option value=""> Select Status </option>
                                <option value="pending"> Pending </option>
                                <option value="processing"> Processing </option>
                                <option value="completed"> Completed </option>
                                <option value="cancelled"> Cancelled </option>
                                <option value="cancelled by user"> Cancelled by User </option>
                                <option value="cancelled by doctor"> Cancelled by Doctor </option>
                            </select>
                            @error('search_progress')
                                                    <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <table class="table table-bordered table-hover data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th width=" 10px">Date & Time</th>
                                <th class="">Patient Name</th>
                                <th class="">Consultant</th>
                                <th class="">Speciality</th>
                                <th class="">Location</th>
                                <th class="">Summary</th>
                                <th class="">Status</th>
{{--                                <th class="">Action</th>--}}
                            </tr>
                        </thead>
                        <tbody class="">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function initDatatable() {
            $('.data-table').DataTable().clear().destroy();

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                searching: false,
                ajax: {
                    url: "{{ route('appointment-booking_now') }}",
                    data: function(d) {
                        d.search_name = $("#search_name").val();
                        d.search_toDate = $("#search_toDate").val();
                        d.search_speciality = $("#search_speciality").val();
                        d.search_location = $("#search_location").val();
                        d.search_progress = $("#search_progress").val();
                    }
                },
                columns: [
                    {data: 'time_slot', name: 'time_slot'},
                    {data: 'user', name: 'user'},
                    {data: 'doctor', name: 'doctor'},
                    {
                        render: function (data, type, row, index) {
                            var specialities = '';
                            var speciality_json = '';
                            if (row.doctor_speciality) {
                                var speciality_json = $.parseJSON(row.doctor_speciality)
                            }
                            if (speciality_json != '') {
                                speciality_json.forEach((element) => {
                                    specialities += '<div class="speciality_tags">' + element.name + '</div>'
                                });
                            }
                            return specialities;
                        }
                    },
                    {data: 'location', name: 'location'},
                    {data: 'reason', name: 'reason'},
                    {data: 'progress', name: 'progress'},
                    // {data: 'actionby', name: 'actionby'},
                ]
            });
        }

        $( document ).ready(function() {

            initDatatable();
            $(document).on('change', '.searchable_field', function(){
                initDatatable();
            });
        });

    </script>
    <script>
    $(document).on('click','#date-filter',function(){
        $('.data-table').DataTable().draw();
      });
    </script>
@endpush
