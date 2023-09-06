<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
{{--    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">--}}
    <title>{{ env('APP_NAME') }} - @yield('page_header')</title>
    <!-- CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <link href="{{ asset('admin/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('admin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dropify/dropify.min.css') }}" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('admin/css/qrcode-reader.min.css') }}">
    <!-- include summernote css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" crossorigin="anonymous"/>
{{--    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">--}}

    <!-- Moyasar Styles -->
    {{-- <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.5.0/moyasar.css"> --}}

    <link rel="stylesheet" href="{{ asset('admin/switch/tailwind-switch.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.min.css" crossorigin="anonymous"/>
    @stack('css')
    <style>
        .loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #F5F8FA;
            z-index: 9998;
            text-align: center;
        }

        .plane-container {
            position: absolute;
            top: 50%;
            left: 50%;
        }

        .validation-error {
            width: 100%;
            margin-top: .25rem;
            font-size: 80%;
            color: #ed5564;
        }

        /* For summernote override unordered and order list */
        .note-editable ul li{
            list-style: disc !important;
            list-style-position: inside !important;
        }

    </style>
    <!-- Js -->
    <!--
    --- Head Part - Use Jquery anywhere at page.
    --- http://writing.colin-gourlay.com/safely-using-ready-before-including-jquery/
    -->
    <script>
        (function(w, d, u) {
            w.readyQ = [];
            w.bindReadyQ = [];

            function p(x, y) {
                if (x == "ready") {
                    w.bindReadyQ.push(y);
                } else {
                    w.readyQ.push(x);
                }
            };
            var a = {
                ready: p
                , bind: p
            };
            w.$ = w.jQuery = function(f) {
                if (f === d || f === u) {
                    return a
                } else {
                    p(f)
                }
            }
        })(window, document)

    </script>
</head>

<body class="light">
    <!-- Pre loader -->
    @include('layouts.admin.loader')
    <div id="app">
        <div class="page has-sidebar-left">
            <div class="navbar navbar-expand d-flex navbar-dark justify-content-between bd-navbar blue accent-3 shadow d-print-none">

                <div class="relative">
                    <div class="d-flex justify-content-between">
                        <div class="d-none d-md-block">
                            <h1 class="nav-title text-white ">@yield('page_header')</h1>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="d-flex justify-content-between align-items-center">

                        <div class="d-none d-md-block cursor-pointer">
                            <h1 class="nav-title text-white mb-2 pb-1 close_iframe">&times;</h1>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT START --}}
            @yield('content')
            {{-- CONTENT END --}}


            <!-- Right Sidebar -->
            {{-- <aside class="control-sidebar fixed white ">
            <div class="slimScroll">
                <div class="sidebar-header">
                    <h4>Activity List</h4>
                    <a href="#" data-toggle="control-sidebar" class="paper-nav-toggle  active"><i></i></a>
                </div>
                <div class="p-3">
                    <div>
                        <div class="my-3">
                            <small>25% Complete</small>
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%;"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="my-3">
                            <small>45% Complete</small>
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 45%;"
                                    aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="my-3">
                            <small>60% Complete</small>
                            `
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;"
                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="my-3">
                            <small>75% Complete</small>
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 75%;"
                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="my-3">
                            <small>100% Complete</small>
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 bg-primary text-white">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-normal s-14">Sodium</h5>
                            <span class="font-weight-lighter text-primary">Spark Bar</span>
                            <div> Oxygen
                                <span class="text-primary">
                                    <i class="icon icon-arrow_downward"></i> 67%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <canvas width="100" height="70" data-chart="spark" data-chart-type="bar"
                                data-dataset="[[28,68,41,43,96,45,100,28,68,41,43,96,45,100,28,68,41,43,96,45,100,28,68,41,43,96,45,100]]"
                                data-labels="['a','b','c','d','e','f','g','h','i','j','k','l','m','n','a','b','c','d','e','f','g','h','i','j','k','l','m','n']">
                            </canvas>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="#">INV-281281</a>
                                </td>
                                <td>
                                    <span class="badge badge-success">Paid</span>
                                </td>
                                <td>$ 1228.28</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">INV-01112</a>
                                </td>
                                <td>
                                    <span class="badge badge-warning">Overdue</span>
                                </td>
                                <td>$ 5685.28</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">INV-281012</a>
                                </td>
                                <td>
                                    <span class="badge badge-success">Paid</span>
                                </td>
                                <td>$ 152.28</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">INV-01112</a>
                                </td>
                                <td>
                                    <span class="badge badge-warning">Overdue</span>
                                </td>
                                <td>$ 5685.28</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#">INV-281012</a>
                                </td>
                                <td>
                                    <span class="badge badge-success">Paid</span>
                                </td>
                                <td>$ 152.28</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="sidebar-header">
                    <h4>Activity</h4>
                    <a href="#" data-toggle="control-sidebar" class="paper-nav-toggle  active"><i></i></a>
                </div>
                <div class="p-4">
                    <div class="activity-item activity-primary">
                        <div class="activity-content">
                            <small class="text-muted">
                                <i class="icon icon-user position-left"></i> 5 mins ago
                            </small>
                            <p>Lorem ipsum dolor sit amet conse ctetur which ascing elit users.</p>
                        </div>
                    </div>
                    <div class="activity-item activity-danger">
                        <div class="activity-content">
                            <small class="text-muted">
                                <i class="icon icon-user position-left"></i> 8 mins ago
                            </small>
                            <p>Lorem ipsum dolor sit ametcon the sectetur that ascing elit users.</p>
                        </div>
                    </div>
                    <div class="activity-item activity-success">
                        <div class="activity-content">
                            <small class="text-muted">
                                <i class="icon icon-user position-left"></i> 10 mins ago
                            </small>
                            <p>Lorem ipsum dolor sit amet cons the ecte tur and adip ascing elit users.</p>
                        </div>
                    </div>
                    <div class="activity-item activity-warning">
                        <div class="activity-content">
                            <small class="text-muted">
                                <i class="icon icon-user position-left"></i> 12 mins ago
                            </small>
                            <p>Lorem ipsum dolor sit amet consec tetur adip ascing elit users.</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside> --}}
            <!-- /.right-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
            <div class="control-sidebar-bg shadow white fixed"></div>
        </div>
        <!--/#app -->
        <script src="{{ asset('admin/js/app.js') }}"></script>
        <script src="{{ asset('admin/js/qrcode-reader.min.js') }}"></script>

        {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}
        {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9" crossorigin="anonymous"></script>
        <script src="{{ asset('admin/js/sweetalert.min.js') }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

        <script src="{{ asset('admin/toastr/toastr.min.js') }}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&amp;sensor=false&amp;libraries=places">
        </script>
        <script src="{{ asset('admin/js/locationpicker.js') }}"></script>
        <script src="{{ asset('admin/dropify/dropify.min.js') }}" crossorigin="anonymous"></script>
{{--        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>--}}
        {{-- <button class="btn btn-primary btn-lg toast-action" data-title="Hey, Bro!"
            data-message="Paper Panel has toast as well." data-type="error" data-position-class="toast-bottom-left">
            Error Toast</button> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js" crossorigin="anonymous"></script>
        <!-- include summernote js -->
{{--        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>--}}

{{--        <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js" crossorigin="anonymous"></script>--}}

        <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
        {{-- <script src="https://cdn.moyasar.com/mpf/1.5.0/moyasar.js"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" crossorigin="anonymous"></script>

        <script src="{{ asset('admin/ckeditor_new/ckeditor.js') }}"></script>
        <script src="{{ asset('admin/switch/jquery-tailwind-toggle.js') }}"></script>
        @include('layouts.admin.widgets_modals')
        @stack('widget_modals')
        <script>
            @if(Session::has('message'))
            toastr["{{ Session::get('type') }}"]("{{ Session::get('message') }}", "{{ Session::get('title') }}")
            window.parent.refreshIframe();
            // window.top.location.reload();

            @endif
            $('#disable_enter_submit').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                                console.log("false")
                            } else {
                                console.log("true")
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();

            $(document).ready(function() {
                $('#loader').fadeOut()
                $('.dropify').dropify();
                // $('.summernote').summernote({
                //     dialogsInBody: true,
                //     onPaste: function(e) {
                //         var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                //         e.preventDefault();
                //         document.execCommand('insertText', false, bufferText);
                //     }
                //     , toolbar: [
                //         ['styleTags', []]
                //         , ['cleaner', ['cleaner']], // The Button
                //         ['style', ['style']]
                //         , ['font', ['bold', 'italic', 'underline', 'clear', 'superscript' ]]
                //         , ['para', ['ul', 'ol', 'paragraph']]
                //         , ['table', ['table']]
                //         , ['insert', ['hr' , 'link']]
                //         , ['view', ['fullscreen', 'codeview']]
                //     ]
                // });

                // We need to turn off the automatic editor creation first.
                CKEDITOR.disableAutoInline = true;
                CKEDITOR.replaceAll( 'summernote' );
                // config.copyFormatting_allowedContexts = true;
                // CKEDITOR.config.removeButtons = 'font';
                // CKEDITOR.config.forcePasteAsPlainText = true;
                // CKEDITOR.config.removeButtons = 'Anchor';
                CKEDITOR.on('instanceReady', function(ev) {
                    var editor = ev.editor;
                    editor.on('paste', function(even) {
                        even.data.dataValue = even.data.dataValue.replace(/lang="EN"/g, '');
                    });
                });

                $('[data-toggle="tooltip"]').tooltip()

                $.qrCodeReader.jsQRpath = "{{ asset('admin/dist/js/jsQR/jsQR.min.js') }}";
                $.qrCodeReader.beepPath = "{{ asset('admin/dist/audio/beep.mp3') }}";
                var element = "#openreader-btn-0";
                var target = "#target-input-0";
                $(element).qrCodeReader({
                    target: target
                    , audioFeedback: true
                    , multiple: false
                    , skipDuplicates: true
                , });

                $(".token_tags").select2({
                    placeholder: "Tags", //placeholder
                    tags: true
                    , tokenSeparators: ['/', ',', ';', " "]
                , });

                $(".token_article").select2({
                    placeholder: "Articles", //placeholder
                    tags: true
                    , tokenSeparators: ['/', ',', ';', " "]
                , });

                $('[data-toggle="tooltip"]').tooltip()
                $(".sortable").sortable({
                    revert: true
                });

                $(document).on("click", ".close_iframe", function() {
                    window.parent.$('.modal').modal('hide');
                });

            })

        </script>
        @stack('scripts')
        @stack('widget_scripts')




        <!--
--- Footer Part - Use Jquery anywhere at page.
--- http://writing.colin-gourlay.com/safely-using-ready-before-including-jquery/
-->
</body>

</html>
