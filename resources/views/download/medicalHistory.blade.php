<html>

<head>
    <title>Prescription</title>
</head>

<body>
    <table>
        <tr>
            <td>
                <table width="70%">
                    <tr>
                        <td>
                            <h1>{{$appointment->doctor->doctorDetail->prefix}}. {{$appointment->doctor->name}}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if($appointment->doctor && $appointment->doctor->doctorSpecialities)
                            <h4>
                                @foreach ($appointment->doctor->doctorSpecialities as $key => $speciality)
                                {{$speciality->speciality->name}}{{count($appointment->doctor->doctorSpecialities) -1 != $key? "," : ''}}
                                @endforeach
                            </h4>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Patient Name</td>
                        <td>Prescribed On</td>
                    </tr>
                    <tr>
                        <td>{{ $appointment->user->name }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($appointment->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>
                            @if($appointment->doctor_clinic_id)
                            {{$appointment->doctorClinic->clinic->name}} | {{$appointment->doctorClinic->clinic->address}}
                            <br>
                            {{$appointment->doctorClinic->clinic->phone}} {{$appointment->doctorClinic->clinic->email}}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td width="100px">
                <img width="100px" src="{{asset('admin/img/closed-menu.png')}}" />
            </td>
        </tr>
        <tr>
            <td height="0.5px" colspan="2" style="background-color: blue">

            </td>
        </tr>
        <tr>
            <td align="center" style="padding-top: 40px">
                <table width="80%" cellspacing="5" cellpadding="5">
                    <tbody>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Fee</th>
                                <th>date</th>
                            </tr>
                        </thead>

                        <tr>
                            <td>{{$appointment->doctor->name? $appointment->doctor->name : ''}}</td>
                            <td>{{$appointment->type ? $appointment->type : ''}}</td>
                            <td>{{$appointment->consultation_fee ? $appointment->consultation_fee : 'free'}}</td>
                            <td>{{$appointment->date ? $appointment->date : ''}}</td>

                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td height="0.5px" colspan="2" style="background-color: blue">
            </td>
        </tr>
        @if(isset($appointment->getPrescription) && isset($appointment->getPrescription->patient_consultation_note))
        <tr>
            <td>
              <p><strong>Consultation Notes : </strong>{{$appointment->getPrescription->patient_consultation_note}}</p>
            </td>
        </tr>
        @endif
    </table>

</body>

</html>
