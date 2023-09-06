@extends('layouts.admin.app')
@section('page_header')
    {{ $page_header }}
@endsection
@section('content')

    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">

                <div class="container-fluid animatedParent animateOnce my-3">
                    <div class="animated fadeInUpShort">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="fitness_name mb-0"></h5>
{{--                                    <div class="warning d-none">--}}
{{--                                        <div class="btn btn-danger">Please add specialities, services, educations, experience</div>--}}
{{--                                    </div>--}}
{{--                                    <a href="" target="_blank" class="btn btn-primary fitness_anchor d-none">View Wellness Expert Profile</a>--}}
                                </div>
                                <div class="tab">
                                    <button type="button" class="tablinks" id="Profile_tab"
                                            onclick="openTab(event, 'Profile')">Profile
                                    </button>
                                    <button type="button" class="tablinks" id="Services_tab" onclick="openTab(event, 'Services')">
                                        Services
                                    </button>
                                    <button type="button" class="tablinks" id="Specialities_tab" onclick="openTab(event, 'Specialities')">
                                        Specialities
                                    </button>
                                    <button type="button" class="tablinks" id="Educations_tab" onclick="openTab(event, 'Educations')">
                                        Educations
                                    </button>
                                    <button type="button" class="tablinks" id="Experiences_tab" onclick="openTab(event, 'Experiences')">
                                        Experiences
                                    </button>
                                    <button type="button" class="tablinks" id="Articles_tab" onclick="openTab(event, 'Articles')">Assign
                                        Articles
                                    </button>
                                    <button type="button" class="tablinks" id="Videos_tab" onclick="openTab(event, 'Videos')">Assign
                                        Videos
                                    </button>
                                </div>

                                <div id="Profile" class="tabcontent"></div>
                                <div id="Services" class="tabcontent"></div>
                                <div id="Specialities" class="tabcontent"></div>
                                <div id="Educations" class="tabcontent"></div>
                                <div id="Experiences" class="tabcontent"></div>
                                <div id="Articles" class="tabcontent"></div>
                                <div id="Videos" class="tabcontent"></div>

                            </div>
                        </div>
                    </div>

        </div>

        <div class="modal fade" id="edit_fitness_details_modal" tabindex="-1" role="dialog"
             aria-labelledby="edit_fitness_details_modal" aria-hidden="true">
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

        var fitness_experts_id = "{{$result->fitness_experts_id}}"

        function openTab(evt, cityName) {
            var route_slug = 'view-'+cityName.toLowerCase()+'/{{$result->fitness_experts_id}}';
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            $('#'+cityName+'_tab').addClass('active');
            $.ajax({
                url: '{{url('/admin/fitness-experts/')}}/'+route_slug,
                type: "GET",
                success: function(data){
                    $('#'+cityName).html(data);
                    $('.dropify').dropify();
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
        // openTab(event, 'Profile');
    </script>
@endpush
