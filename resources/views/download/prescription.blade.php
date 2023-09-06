@if ($settings['html'] == 0)
<html>
<head>
    <title>Prescription</title>
{{--    <link href="https://fonts.cdnfonts.com/css/circular-std" rel="stylesheet">--}}
<style>
    @page {
        header: page-header;
        footer: page-footer;
    }
</style>
</head>
<body>
<htmlpageheader name="page-header">
    <div style="width: 100%">
        <div style="width:70%; float:left">
            <h2 style="font-family: 'circularstd_book', sans-serif; margin-bottom: 5px; font-weight: 500;margin-top: 0">{{$appointment->doctor->doctorDetail->prefix}}. {{$appointment->doctor->name}}</h2>
            @if($appointment->doctor && $appointment->doctor->doctorSpecialities)
                <p style="margin-top: 10px; margin-bottom: 5px; font-family: circularstd_light; font-size: 16px; line-height: 20px ">
                    @foreach ($appointment->doctor->doctorSpecialities as $key => $speciality)
                        {{$speciality->speciality->name}}{{count($appointment->doctor->doctorSpecialities) -1 != $key? "," : ''}}
                    @endforeach
                </p>
            @endif

            {{-- static--}}

            @if($appointment->doctor && $appointment->doctor->doctorEducation)
                <p style="margin-top: 8px; text-transform: uppercase; font-family: circularstd_light; font-size: 16px; line-height: 20px">
                    @foreach ($appointment->doctor->doctorEducation as $key => $degree)
                        {{$degree->degree}}{{count($appointment->doctor->doctorEducation) -1 != $key? "," : ''}}
                    @endforeach
                </p>
            @endif
        </div>
        <div style="width:30%; float:left">
            <img width="200px" src="{{asset('admin/img/logo.png')}}" />
        </div>
    </div>

    <div style="width: 100%; margin-top: 0; line-height: 10px">
        <div style="width:70%; float:left">
            <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Patient Name:</span> {{ $appointment->patientname }}</p>
            {{-- static--}}
            <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Patient Number:</span> {{ str_pad($appointment->user->id,8,"0",STR_PAD_LEFT) }}</p>
        </div>
        <div style="width:30%; float:left">
            <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Prescribed On:</span> {{ \Illuminate\Support\Carbon::parse($appointment->created_at)->format('d/m/Y') }}</p>
        </div>
    </div>

{{--    @if($appointment->doctor_clinic_id)--}}
{{--    <div style="width: 100%">--}}
{{--        <div style="width:70%; float:left">--}}
{{--            {{$appointment->doctorClinic->clinic->name}} | {{$appointment->doctorClinic->clinic->address}}--}}
{{--            <br>--}}
{{--            {{$appointment->doctorClinic->clinic->phone}} {{$appointment->doctorClinic->clinic->email}}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    @endcan--}}

    <div style="border-top: 1px solid #C8C8C8;"></div>
</htmlpageheader>
<style>
    table {
        border: 1px solid #C8C8C8;
    }
    table, th, td {
        border-bottom: 1px solid #C8C8C8;
        border-right: 1px solid #C8C8C8;
        padding: 8px 12px;
        font-size: 12px;
    }
</style>

<h2 style="text-align: center; font-family: circularstd_book; font-size: 14pt; margin-top: 0px">Prescription</h2>
@if($appointment->getPrescription && $appointment->getPrescription->prescribedMedicine && count($appointment->getPrescription->prescribedMedicine) > 0)
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px">
        <thead>
            <tr>
                <td width="30%" align="left" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Drug Name</td>
                <td width="44%" colspan="4" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Medicine/Day</td>
                <td width="12%" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Duration</td>
                <td width="14%" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Instructions</td>
            </tr>
            <tr>
                <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;">&nbsp;</td>
                <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Morning</td>
                <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Afternoon</td>
                <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Evening</td>
                <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Night</td>
                <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;">&nbsp;</td>
                <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;">&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointment->getPrescription->prescribedMedicine as $key => $medicine)
            <tr>
                <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;">{{$medicine->prescriptionElement->name}}</td>
                <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->morning ? $medicine->morning.' '.$medicine->unit : '-'}}</td>
                <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->afternoon ? $medicine->afternoon.' '.$medicine->unit : '-'}}</td>
                <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->evening ? $medicine->evening.' '.$medicine->unit : '-'}}</td>
                <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->night ? $medicine->night.' '.$medicine->unit : '-'}}</td>
                <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->number_of_days}}</td>
                <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->is_after_meal ?'After':'Before' }} Meal</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
        @if($appointment->getAppointmentPrescription && $appointment->getAppointmentPrescription->count() > 0)
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <thead>
                </thead>
                <tbody style="">
                @foreach ($appointment->getAppointmentPrescription as $prescription)
                    <tr>
                        <td style="border: 0;font-family: circularstd_light; font-weight: 300;">
                            {{ $loop->iteration }}. {{$prescription->prescription}}
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
    @endif

    @if($appointment->getPrescription && $appointment->getPrescription->prescribedLab && count($appointment->getPrescription->prescribedLab) > 0)
        <table style="width: 100%; margin-bottom: 30px; border-collapse: collapse">
            <thead>
            <tr>
                <td width="30%" align="left" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Lab Test</td>
                <td width="70%" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;"></td>
            </tr>
            </thead>
            <tbody>
            @foreach ($appointment->getPrescription->prescribedLab as $key => $labtest)
                <tr>
                    <td style="font-family: circularstd_light; line-height: 25px; color: #313131;">{{$labtest->description}}</td>
                    <td style="font-family: circularstd_light; justify-content: space-between; text-align:left; line-height: 25px; color: #313131;" align="center">{{$labtest->prescriptionElement->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($appointment->getPrescription) && isset($appointment->getPrescription->cosultation_note) && $appointment->getPrescription->cosultation_note)
        <table style="width: 100%; border-collapse: collapse">
            <thead>
            <tr>
                <td align="left" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Notes</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($appointment->getPrescription->prescribedLab as $key => $labtest)
                <tr>
                    <td style="font-family: circularstd_light; line-height: 25px; color: #313131;">{{(isset($appointment->getPrescription) && isset($appointment->getPrescription->cosultation_note)) ? $appointment->getPrescription->cosultation_note : ''}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if (count($appointment->getPrescription->prescribedMedicine) == 0 && $appointment->prescription_here == '' && count($appointment->getPrescription->prescribedLab) == 0 && $appointment->getPrescription->cosultation_note == '')
        <div style="border: 1px solid #C8C8C8; padding: 8px 12px; font-family: circularstd_light; font-size: 12px; margin-bottom: 30px">No prescription found.</div>
    @endif
        <htmlpagefooter  name="page-footer">
            <div style="width:100%;background-color: #CBEFF0; border-radius: 10px">
                <div style="width:27%; padding: 8px 12px; float:left; color: #078A8E; font-size:12pt; font-family: circularstd_book;">Have you checked your<br>health today?</div>
                <div style="width:10%; float:left; color: #078A8E; font-size:12pt; font-family: circularstd_light; margin-top: 8px;padding-top: 8px; line-height: 18px;">Scan this<br>code:</div>
                <div style="width:23%; float:left; color: #078A8E; font-size:10pt; font-family: circularstd_light; margin-top: -8px;padding-top: 6px; line-height: 18px;"><img width="54px" src="{{asset('admin/img/scan-logo.png')}}" /></div>
                <div style="width:30%; float:right; text-align: right; color: #078A8E; margin-top: -35px;"><img width="170px" src="{{asset('admin/img/right-fixed.png')}}"  /></div>
                <div style="clear:both"></div>
            </div>

            <div style="border-top: 1px solid #C8C8C8; margin-top: 20px">&nbsp;</div>

            <table width="100%" cellpadding="0" style="font-size:12pt; font-family: circularstd_light;border: 0; margin-top: -15px; padding: 0">
                <tr>
                    <td width="25%" valign="middle" style="border:0; margin: 0; padding: 0;font-size:10pt; font-family: circularstd_light">
                        <a href="tel:{{$settings['uan_number']}}" style="color: #404040; text-decoration: none; ">
                            <img src="{{asset('admin/img/icon_phone.png')}}" alt="" style="vertical-align:middle; margin-right: 6px;" width="18px">UAN: {{$settings['uan_number']}}
                        </a>
                    </td>
                    <td width="25%" valign="middle" style="border:0; margin: 0; padding: 0;font-size:10pt; font-family: circularstd_light">
                        <a href="mailto:{{$settings['email']}}" style="color: #404040; text-decoration: none; ">
                            <img src="{{asset('admin/img/icon_envelope.png')}}" alt="" style="vertical-align:middle; margin-right: 6px;" width="18px">{{$settings['email']}}
                        </a>
                    </td>
                    <td width="25%" valign="middle" style="border:0; margin: 0; padding: 0;font-size:10pt; font-family: circularstd_light">
                        <a href="https://merisehat.pk" style="color: #404040; text-decoration: none; ">
                            <img src="{{asset('admin/img/icon_web.png')}}" alt="" style="vertical-align:middle; margin-right: 6px;" width="18px">www.merisehat.pk
                        </a>
                    </td>
                    <td width="25%" align="right" style="margin: 0; border:0; padding: 0; text-align: right" valign="middle">
                        <table cellspacing="0" cellpadding="0" style="border: 0; border-collapse: collapse;">
                            <tr>
                                @if ($settings['facebook'] != '')
                                <td align="right" style="border: 0; margin-right: 0px;padding-right: 0px;">
                                    <a href="{{ $settings['facebook'] }}" style="margin-right:5px;">
                                        <img src="{{asset('admin/img/Icon_FB.svg')}}" alt="" width="35px">
                                    </a>
                                </td>
                                @endif
                                @if ($settings['instagram'] != '')
                                <td align="right" style="border: 0; margin-right: 0px;padding-right: 0px;">
                                    <a href="{{ $settings['instagram'] }}" style="margin-right:5px;">
                                        <img src="{{asset('admin/img/Icon_Insta.svg')}}" alt="" width="35px">
                                    </a>
                                </td>
                                @endif
                                @if ($settings['youtube'] != '')
                                <td align="right" style="border: 0; margin-right: 0px;padding-right: 0px;">
                                    <a href="{{ $settings['youtube'] }}" style="margin-right:5px;">
                                        <img src="{{asset('admin/img/Icon_YT.svg')}}" alt="" width="35px">
                                    </a>
                                </td>
                                @endif
                                @if ($settings['twitter'] != '')
                                <td align="right" style="border: 0; margin-right: 0px;padding-right: 0px;">
                                    <a href="{{ $settings['twitter'] }}" style="margin-right:5px;">
                                        <img src="{{asset('admin/img/Icon_Twitter.svg')}}" alt="" width="35px">
                                    </a>
                                </td>
                                @endif
                                @if ($settings['linkedin'] != '')
                                <td align="right" style="border: 0; padding-right: 0;margin-right: 0">
                                    <a href="{{ $settings['linkedin'] }}">
                                        <img src="{{asset('admin/img/Icon_LinkedIn_Latest.svg')}}" alt="" width="35px">
                                    </a>
                                </td>
                                @endif
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </htmlpagefooter>

    </body>
    </html>
    @else
    <html>

    <head>
        <title>Prescription</title>
        {{--    <link href="https://fonts.cdnfonts.com/css/circular-std" rel="stylesheet">--}}
    </head>

    <body>
    <table style="max-width: 750px; width: 750px; margin: auto; padding: 50px 0">
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td>
                            <h2 style="font-family: 'Nunito', sans-serif; margin-bottom: 5px; font-weight: 500;margin-top: 0">{{$appointment->doctor->doctorDetail->prefix}}. {{$appointment->doctor->name}}</h2>
                            @if($appointment->doctor && $appointment->doctor->doctorSpecialities)
                                <p style="margin-top:10px; margin-bottom: 5px; font-family: 'Circular Std', sans-serif;font-weight:300; font-size: 18px; line-height: 20px ">
                                    @foreach ($appointment->doctor->doctorSpecialities as $key => $speciality)
                                        {{$speciality->speciality->name}}{{count($appointment->doctor->doctorSpecialities) -1 != $key? "," : ''}}
                                    @endforeach
                                </p>
                            @endif
                            {{-- static--}}

                            @if($appointment->doctor && $appointment->doctor->doctorEducation)
                                <p style="text-transform: uppercase; margin-top: 0; font-family: 'Circular Std', sans-serif;font-weight:300; font-size: 18px; line-height: 20px">
                                    @foreach ($appointment->doctor->doctorEducation as $key => $degree)
                                        {{$degree->degree}}{{count($appointment->doctor->doctorEducation) -1 != $key? "," : ''}}
                                    @endforeach
                                </p>
                            @endif
                        </td>
                        <td valign="top" style="text-align: right;">
                            <img width="150px" src="{{asset('admin/img/logo.png')}}" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="padding-top: 20px;font-family: 'Circular Std', sans-serif;">
                            <p style="margin-bottom: 4px"><strong>Patient Name:</strong> {{ $appointment->user->name }}</p>
                            {{-- static--}}
                            <p style="margin-top: 0"><strong>Patient Number:</strong> {{ str_pad($appointment->user->id,8,"0",STR_PAD_LEFT) }}</p>
                        </td>
                        <td style="padding-top: 20px;font-family: 'Circular Std', sans-serif; text-align: right"><strong>Prescribed On:</strong> {{ \Illuminate\Support\Carbon::parse($appointment->created_at)->format('d/m/Y') }}</td>
                    </tr>

                    {{--                    <tr>--}}
                    {{--                        <td>--}}
                    {{--                            @if($appointment->doctor_clinic_id)--}}
                    {{--                            {{$appointment->doctorClinic->clinic->name}} | {{$appointment->doctorClinic->clinic->address}}--}}
                    {{--                            <br>--}}
                    {{--                            {{$appointment->doctorClinic->clinic->phone}} {{$appointment->doctorClinic->clinic->email}}--}}
                    {{--                            @endif--}}
                    {{--                        </td>--}}
                    {{--                    </tr>--}}
                </table>
            </td>

        </tr>
        <tr>
            <td style="padding: 10px 0"  colspan="1">
                <p style="border-top: 0.1px solid #C8C8C8";></p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding-top: 40px">
                <table width="80%" cellspacing="5" cellpadding="5">
                    <tbody>
                    @if($appointment->getPrescription && $appointment->getPrescription->prescribedMedicine && count($appointment->getPrescription->prescribedMedicine) > 0)
                        <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Morning</th>
                            <th>Afternoon</th>
                            <th>Evening</th>
                            <th>Night</th>
                            <th>Duration</th>
                            <th>Instruction</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($appointment->getPrescription->prescribedMedicine as $key => $medicine)
                            <tr>
                                <td>{{$medicine->prescriptionElement->name}}</td>
                                <td>{{$medicine->morning ? $medicine->morning.' '.$medicine->unit : ''}}</td>
                                <td>{{$medicine->afternoon ? $medicine->afternoon.' '.$medicine->unit : ''}}</td>
                                <td>{{$medicine->evening ? $medicine->evening.' '.$medicine->unit : ''}}</td>
                                <td>{{$medicine->night ? $medicine->night.' '.$medicine->unit : ''}}</td>
                                <td>{{$medicine->number_of_days}}</td>
                                <td>{{$medicine->is_after_meal ?'After':'Before' }} Meal</td>
                            </tr>
                        @endforeach
                    @else
                    @if($appointment->getAppointmentPrescription && $appointment->getAppointmentPrescription->count() > 0)
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                            <thead>
                            </thead>
                            <tbody style="">
                                @foreach ($appointment->getAppointmentPrescription as $prescription)
                                    <tr>
                                        <td style="border: 0;font-family: circularstd_light; font-weight: 300;">
                                            {{$prescription->prescription}}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    @endif
                        </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding-top: 25px">
                <table width="100%" cellpadding="5" style="font-family: 'Circular Std', sans-serif; border: 1px solid #C8C8C8;border-spacing: 0" >

                    <thead>
                    <tr>
                        <th style="text-align:left;background-color: #D0F4F5; color: #078A8E;padding: 10px 15px;">Lab Test</th>
                        <th style="text-align:left;background-color: #D0F4F5; color: #078A8E;padding: 10px;">&nbsp;</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach ($appointment->getPrescription->prescribedLab as $key => $labtest)
                        <tr>
                            <td style="text-align: left;border-right: 1px solid #C8C8C8;border-bottom: 1px solid #C8C8C8;padding: 20px 15px;">{{$labtest->description}}</td>
                            <td style="text-align: left;padding: 20px 15px;border-bottom: 1px solid #C8C8C8;">{{$labtest->prescriptionElement->name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        {{-----------------------notes table start----------------------------}}
        @if(isset($appointment->getPrescription) && isset($appointment->getPrescription->cosultation_note) && $appointment->getPrescription->cosultation_note)
            <tr>
                <td>
                    <p><strong>Consultation Notes : </strong>{{isset($appointment->getPrescription) && isset($appointment->getPrescription->cosultation_note) ? $appointment->getPrescription->cosultation_note : ''}}</p>
                </td>
            </tr>
        @endif

        {{-----------------------notes table end----------------------------}}

        {{--        @if($appointment->getPrescription->cosultation_note)--}}
        {{--        <tr>--}}
        {{--            <td>--}}
        {{--              <p><strong>Consultation Notes : </strong>{{$appointment->getPrescription->cosultation_note}}</p>--}}
        {{--            </td>--}}
        {{--        </tr>--}}
        {{--        @endif--}}
        <tr>
            <td>
                <table width="100%" cellpadding="5" style="font-family: 'Circular Std', sans-serif;border-spacing: 0; background-color: #CBEFF0; border-radius: 6px; margin-top: 60px; position: relative">

                    <thead>


                    </thead>
                    <tbody>
                    <tr>
                        <td width="33%" style="padding: 7px 15px"><h4 style="color: #078A8E; margin: 0; letter-spacing: 0.3px">Have you checked your <br>health today?</h4></td>
                        <td width="12%" style="padding: 0;"><p style="color: #404040">Scan this<br>
                                code:
                            </p></td>
                        <td width="10%" style="padding: 0;position: relative"><img width="43px" src="{{asset('admin/img/scan-logo.png')}}" /></td>
                        <td style="text-align: right; padding: 0; position: relative"><img width="150px" src="{{asset('admin/img/right-fixed.png')}}"  style="position: relative; right: 0; bottom: 0"/></td>
                    </tr>

                    </tbody>
                </table>
            </td>


        </tr>
        <tr>
            <td style="padding: 20px 0"  colspan="1">
                <p style="border-top: 0.1px solid #C8C8C8";></p>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="5" style="font-family: 'Circular Std', sans-serif;border-spacing: 0;">
                    <thead>


                    </thead>
                    <tbody>
                    <tr>
                        <td valign="middle"><a href="tel:{{$settings['uan_number']}}" style="color: #404040; font-size: 15px; text-decoration: none"><img src="{{asset('admin/img/phone.svg')}}" alt="" style="margin-right: 6px;position:relative;top: 3px;" width="18px">UAN: {{$settings['uan_number']}}</a></td>
                        <td valign="middle"><a href="mailto:{{$settings['email']}}" style="color: #404040; font-size: 15px; text-decoration: none"><img src="{{asset('admin/img/envelope.svg')}}" alt="" style="margin-right: 6px;position:relative;top: 2px;" width="22px">{{$settings['email']}}</a></td>
                        <td valign="middle"><a href="https://merisehat.pk" style="color: #404040; font-size: 15px; text-decoration: none"><img src="{{asset('admin/img/website.png')}}" alt="" style="margin-right: 6px;position:relative;top: 5px;" width="20px">www.merisehat.pk</a></td>
                        <td style="padding: 10px"></td>
                        <td style="padding: 0; text-align: right" valign="middle">
                            @if ($settings['facebook'] != '')
                            <a href="{{$settings['facebook']}}" style="margin-right:5px;background: #EEEEEE;width: 30px;height: 30px;text-align: center;vertical-align: middle;display: inline-flex;align-items: center;justify-content: center;border-radius: 8px;">
                                <img src="{{asset('admin/img/fb.png')}}" alt="" width="9px" style="padding-top: 5px">
                            </a>
                            @endif
                            @if ($settings['instagram'] != '')
                            <a href="{{$settings['instagram']}}" style="margin-right:5px;background: #EEEEEE;width: 30px;height: 30px;text-align: center;vertical-align: middle;display: inline-flex;align-items: center;justify-content: center;border-radius: 8px;"><img src="{{asset('admin/img/insta.png')}}" alt="" width="15px" style="padding-top: 6px"></a>
                            @endif
                            @if ($settings['youtube'] != '')
                            <a href="{{$settings['youtube']}}" style="margin-right:5px;background: #EEEEEE;width: 30px;height: 30px;text-align: center;vertical-align: middle;display: inline-flex;align-items: center;justify-content: center;border-radius: 8px;"><img src="{{asset('admin/img/youtube.png')}}" alt="" width="18px" style="padding-top: 7px"></a>
                            @endif
                            @if ($settings['twitter'] != '')
                            <a href="{{$settings['twitter']}}" style="margin-right:5px;background: #EEEEEE;width: 30px;height: 30px;text-align: center;vertical-align: middle;display: inline-flex;align-items: center;justify-content: center;border-radius: 8px;"><img src="{{asset('admin/img/twitter.png')}}" alt="" width="18px" style="padding-top: 7px"></a>
                            @endif
                            @if ($settings['linkedin'] != '')
                            <a href="{{$settings['linkedin']}}" style="background: #EEEEEE;width: 30px;height: 30px;text-align: center;vertical-align: middle;display: inline-flex;align-items: center;justify-content: center;border-radius: 8px;"><img src="{{asset('admin/img/linkedin.png')}}" alt="" width="13px" style="padding-top: 7px"></a>
                            @endif
                        </td>
                    </tr>

                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    </body>
    </html>

    @endif
