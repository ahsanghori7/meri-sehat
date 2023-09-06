@extends('layouts.admin.app')
@section('page_header')
    {{ $page_header }}
@endsection
@section('content')
{{--    @dd($result)--}}
    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">

            <div class="container-fluid animatedParent animateOnce my-3">
                <div class="animated fadeInUpShort">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="patient_name mb-0"></h5>
{{--                                <a href="" target="_blank" class="btn btn-primary doctor_anchor">View Doctor Profile</a>--}}
                            </div>
                            <div class="tab">
                                @can('patients-management-profile')
                                <button type="button" class="tablinks" id="Profile_tab"
                                        onclick="openTab(event, 'Profile')">Profile
                                </button>
                                @endcan
                                @can('patients-management-appointments')
                                <button type="button" class="tablinks" id="Appointments_tab" onclick="openTab(event, 'Appointments')">
                                    Appointments
                                </button>
                                @endcan
                                @can('patients-management-reports')
                                <button type="button" class="tablinks" id="Reports_tab" onclick="openTab(event, 'Reports')">
                                    Reports
                                </button>
                                @endcan
                                @can('patients-management-lab-history')
                                <button type="button" class="tablinks" id="LabHistory_tab" onclick="openTab(event, 'LabHistory')">
                                    Lab History
                                </button>
                                @endcan
                                @can('patients-management-vital-history')
                                <button type="button" class="tablinks" id="VitalHistory_tab" onclick="openTab(event, 'VitalHistory')">
                                    Vital History
                                </button>
                                @endcan
                                @can('patients-management-subscriptions')
                                <button type="button" class="tablinks" id="Subscriptions_tab" onclick="openTab(event, 'Subscriptions')">
                                    Subscriptions
                                </button>
                                @endcan
                                @can('patients-management-transactions')
                                <button type="button" class="tablinks" id="Wallet_tab" onclick="openTab(event, 'Wallet')">
                                    Transactions
                                </button>
                                @endcan
                            </div>

                            <div id="Profile" class="tabcontent"></div>
                            <div id="Appointments" class="tabcontent"></div>
                            <div id="Reports" class="tabcontent"></div>
                            <div id="LabHistory" class="tabcontent"></div>
                            <div id="VitalHistory" class="tabcontent"></div>
                            <div id="Subscriptions" class="tabcontent"></div>
                            <div id="Wallet" class="tabcontent"></div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="modal fade" id="edit_patient_details_modal" tabindex="-1" role="dialog"
                 aria-labelledby="edit_patient_details_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });
        const convertTime12to24 = (time12h) => {
            const [time, modifier] = time12h.split(' ');
            let [hours, minutes] = time.split(':');
            hours = pad(hours, 2);
            if (hours === '12') {
                hours = '00';
            }
            if (modifier === 'PM') {
                hours = parseInt(hours, 10) + 12;
            }
            return `${hours}:${minutes}`;
        };

        var patient_id = "{{$result->id}}"

        function openTab(evt, tabName) {
            var route_slug = 'view-'+tabName.toLowerCase()+'/{{$result->id}}';
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            $('#'+tabName+'_tab').addClass('active');
            $.ajax({
                url: '{{url('/admin/patients/')}}/'+route_slug,
                type: "GET",
                success: function(data){
                    $('#'+tabName).html(data);
                },
                error: function(data){
                    swalWithBootstrapButtons.fire(
                        'Something went wrong',
                        '',
                        'error'
                    )
                }
            });

        }

        document.getElementById("Profile_tab").click();

        $(document).on('click','.family_member_view', function (e) {
            e.preventDefault();
            $('#edit_family_member_modal').find('.modal-body').empty('');

            var patient_family_id = $(this).data('id');
            var patient_family_view_url = '{{ route("patient-viewFamily", ":id") }}';
            patient_family_view_url = patient_family_view_url.replace(':id', patient_id);
            var fd = new FormData();
            fd.append('family_id', patient_family_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                url: patient_family_view_url,
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (response) {
                    $('#edit_family_member_modal').find('.modal-body').html(response);
                    $('#edit_family_member_modal').find('#user_id').val(patient_family_id);
                    $('#edit_family_member_modal').modal('show');
                },
                error: function(data){
                    swalWithBootstrapButtons.fire(
                        'Something went wrong',
                        '',
                        'error'
                    );
                }
            });
        });

        // $('#disable_enter_submit_family').submit(function (e) {
        $(document).on('submit','#disable_enter_submit_family', function (e) {
            e.preventDefault();
            var patient_family_id = $(this).data('id');
            var patient_family_edit_url = '{{ route("patient-editFamily", ":id") }}';
            patient_family_edit_url = patient_family_edit_url.replace(':id', patient_id);
            var myform = document.getElementById("disable_enter_submit_family");
            var fd = new FormData(myform);
            fd.append('family_id', patient_family_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                url: patient_family_edit_url,
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (response) {
                    $('#edit_family_member_modal').modal('hide');
                    swalWithBootstrapButtons.fire(
                        'Patient family has been updated.',
                        '',
                        'success'
                    );
                    $('#Profile').find('.modal-body').empty();
                    $('#Profile_tab').click();
                },
                error: function(data){
                    swalWithBootstrapButtons.fire(
                        'Something went wrong',
                        '',
                        'error'
                    );
                }
            });
        });
    </script>
@endpush
