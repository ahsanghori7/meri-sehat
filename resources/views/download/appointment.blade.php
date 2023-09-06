<html>
<head>
    <title>Appointments</title>
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
        <div style="width:30%; float:left">
            <img width="200px" src="{{asset('admin/img/logo.png')}}" />
        </div>
    </div>
    @foreach($appointments as $appointment)
        <div style="width: 100%; margin-top: 0; line-height: 10px">
            <div style="width:70%; float:left">
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Appointment Id:</span> {{ $appointment->id }}</p>
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Patient Name:</span> {{ $appointment->patient_name }}</p>
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Date:</span> {{ $appointment->appointment_date }}</p>
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Time Slot:</span> {{ $appointment->appointment_time }}</p>
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Appointment Type:</span> {{ $appointment->type }}</p>
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Amount:</span> {{ $appointment->amount }}</p>
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Cancellation Fee:</span> {{ $appointment->cancellation_fee }}</p>
                <p style="font-family: circularstd_light"><span style="font-family: circularstd_book">Payment Type:</span> {{ $appointment->payment_type }}</p>
            </div>
        </div>
    @endforeach
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

