<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <title>Doctor Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">

    <script src="https://colorlib.com/etc/regform/colorlib-regform-4/vendor/jquery/jquery.min.js" crossorigin="anonymous"></script>
    <link
        href="https://colorlib.com/etc/regform/colorlib-regform-4/vendor/mdi-font/css/material-design-iconic-font.min.css"
        crossorigin="anonymous" rel="stylesheet" media="all">
    <link href="https://colorlib.com/etc/regform/colorlib-regform-4/vendor/font-awesome-4.7/css/font-awesome.min.css"
        crossorigin="anonymous" media="all">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css" crossorigin="anonymous">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>

    <link href="https://colorlib.com/etc/regform/colorlib-regform-4/vendor/select2/select2.min.css" rel="stylesheet"
        crossorigin="anonymous" media="all">
    <link href="https://colorlib.com/etc/regform/colorlib-regform-4/vendor/datepicker/daterangepicker.css" crossorigin="anonymous"
        rel="stylesheet" media="all">

    <link href="https://colorlib.com/etc/regform/colorlib-regform-4/css/main.css" rel="stylesheet" media="all" crossorigin="anonymous">
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" crossorigin="anonymous"/>
    <style id="" media="all">
        /*custom font*/
        @import url(https://fonts.googleapis.com/css?family=Montserrat);

        /*basic reset*/
        * {
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
            background: #D5FFFC;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #D5FFFC, #2a0845);
            /* Chrome 10-25, Safari 5.1-6 */
        }

        body {
            display: flex;
            justify-content: center;
            font-family: montserrat, arial, verdana;
            background: transparent;
            height: 100%;
        }

        /*form styles*/
        #msform {
            /* text-align: center; */
            position: relative;
            margin: auto;
            width: 1000px;
            height: 100%;
        }

        .row {
            width: 100%;
            margin: 0px !important;
        }

        .logo {
            width: 100%;
            margin: 30px 0;
            max-width: 250px;
            padding-top: 25px;
        }


        #progressbar li,
        #msform .fs-subtitle,
        #msform .fs-title {
            text-align: center;
        }

        label {
            margin-bottom: 0px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .row .col-6 {
            padding: 0;
        }

        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 0px;
            padding: 20px 30px;
            box-sizing: border-box;
            width: 100%;

            /*stacking fieldsets above each other*/
        }

        .thankyou-form {
            position: absolute;
            top: 40%;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        /*inputs*/
        #msform input,
        #msform textarea {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 0px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #2C3E50;
            font-size: 13px;
        }

        .select2-container {
            width: 100% !important;
        }

        #msform input:focus,
        #msform textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 1px solid #F16085;
            outline-width: 0;
            transition: All 0.5s ease-in;
            -webkit-transition: All 0.5s ease-in;
            -moz-transition: All 0.5s ease-in;
            -o-transition: All 0.5s ease-in;
        }

        .select2-search .select2-search--inline,
        .select2-search__field {
            width: 100% !important;
        }

        .select2-search__field {
            border: none !important;
        }

        /*buttons*/
        #msform .action-button {
            width: 150px;
            background: #FDECF1;
            font-weight: bold;
            color: #EF6286;
            border: 0 none;
            text-decoration: none;
            border-radius: 25px;
            cursor: pointer;
            padding: 8px 20px;
            margin: auto;
            margin-top: 10px;
            height: 40px;
            transition: 0.2s ease-in-out;
        }


        #msform .btn-sm {
            font-weight: bold;
            color: white;
            border-radius: 25px;
            cursor: pointer;
            padding: 10px 20px;
            margin-top: 10px;
            min-width: 150px;
            transition: 0.2s ease-in-out;
        }

        #msform .btn-sm.delete_qualification,
        #msform .btn-sm.delete_experience,
        #msform .btn-sm.delete_collaboration,
        #msform .btn-sm.delete_time,
        #msform .btn-sm.delete_clinic {
            border-radius: 50%;
            height: 50px;
            min-width: 0;
        }

        #msform .action-button-green {
            width: 150px;
            background: #72D54A;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 25px;
            cursor: pointer;
            padding: 0 20px;
            margin: auto;
            margin-top: 10px;
            height: 40px;
            transition: 0.2s ease-in-out;
        }

        #msform .inline-button {
            display: flex;
            align-items: center;
        }

        #msform .action-button:hover,
        #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #F16085;
        }

        #msform .action-button-green:hover {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #72D54A;
        }

        .social-container .social-icon:hover {
            transform: none !important;
        }

        .social-container .social-icon:hover i {
            transform: none !important;
        }

        #msform .action-button-previous:hover,
        #msform .action-button-previous:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #C5C5F1;
        }

        /*headings*/
        .fs-title {
            font-size: 18px;
            text-transform: uppercase;
            color: #2C3E50;
            margin-bottom: 10px;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .fs-subtitle {
            font-weight: normal;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            /*CSS counters to number the steps*/
            counter-reset: step;
        }

        #progressbar li {
            list-style-type: none;
            color: #2C3E50;
            font-size: 14px;
            width: 33.33%;
            float: left;
            position: relative;
        }


        #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 24px;
            height: 24px;
            line-height: 26px;
            display: block;
            font-size: 12px;
            color: #333;
            background: white;
            border-radius: 25px;
            margin: 0 auto 10px auto;
        }

        /*progressbar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: white;
            position: absolute;
            left: -50%;
            top: 9px;
            z-index: -1;
            /*put it behind the numbers*/
        }

        #progressbar li:first-child:after {
            /*connector not needed before the first step*/
            content: none;
        }

        /*marking active/completed steps green*/
        /*The number of the step and the connector before it = green*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #F16085;
            color: white;
        }


        /* Not relevant to this form */
        .dme_link {
            margin-top: 30px;
            text-align: center;
        }

        .dme_link a {
            background: #FFF;
            font-weight: bold;
            color: #F16085;
            border: 0 none;
            border-radius: 25px;
            cursor: pointer;
            padding: 5px 25px;
            font-size: 12px;
        }

        .dme_link a:hover,
        .dme_link a:focus {
            background: #C5C5F1;
            text-decoration: none;
        }

        .collaborations_div {
            padding-top: 20px
        }

        @media screen and (min-width: 320px) and (max-width:1000px) {
            #msform {
                width: auto;
            }

            .dropify-wrapper .dropify-message p {
                font-size: 13px
            }
        }
    </style>

</head>

<body>
    @include('layouts.admin.loader')

    @yield('form')


    <script src="https://colorlib.com/etc/regform/colorlib-regform-4/vendor/select2/select2.min.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js" crossorigin="anonymous"></script>


    <script src="https://colorlib.com/etc/regform/colorlib-regform-4/js/global.js" crossorigin="anonymous"></script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13" crossorigin="anonymous"></script>


    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194"
        data-cf-beacon='{"rayId":"70cccdecebaf29c2","token":"cd0b4b3a733644fc843ef0b185f98241","version":"2021.12.0","si":100}'>
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" crossorigin="anonymous"></script>



    <script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js" crossorigin="anonymous"></script>















    <script nonce="4344d06a-3bf7-44fe-b613-1f6ed03656a3">
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()


        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        var maxBirthdayDate = new Date();
        maxBirthdayDate.setFullYear(maxBirthdayDate.getFullYear() - 20);

        $(document).ready(function() {
            $('.multi_select').select2();
            $('.select_2_sigle_select').select2();
            $('.dropify').dropify({
                messages: {
                    'default': '',
                }
            });


            $('#phone').inputmask({
                mask: "03#########",
                definitions: {
                    '#': {
                        validator: "[0-9]",
                        // cardinality: 1
                    }
                }
            });


        });

        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0",
            maxDate: maxBirthdayDate
        });
        $('.yearpicker').datepicker({
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy',
            onClose: function(dateText, inst) {
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, 1));
            }
        });

        $(".yearpicker").focus(function() {
            $(".ui-datepicker-month").hide();
            $(".ui-datepicker-calendar").hide();
        });

        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>

    @stack('scripts')
</body>

</html>
