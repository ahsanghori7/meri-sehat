{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

<div class="card-body">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 offset-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                @endif
            </div>
        </div>
    </form>
</div>
</div>
</div>
</div>
</div>
@endsection --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Meri Sehat Login</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/app.css') }}">
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

        .height-full {
            background-image: url(' {{ asset('admin/img/login_bg.png') }}');
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
            background-size: cover;
        }

        @media screen and (min-width:320px) and (max-width:1365px) {
            .height-full {
                background-image: none;
            }
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
        <main>
            <div id="primary" class="p-t-b-100 height-full ">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 mx-md-auto">
                            <div class="text-center">
                                <img src="{{ asset('admin/img/logo.svg') }}" alt="">
                                <h3 class="mt-2"></h3>
                                <p class="p-t-b-20"></p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group has-icon"><i class="icon-envelope-o"></i>
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email Address">
                                    @error('email')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="form-group has-icon"><i class="icon-user-secret"></i>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password">
                                    @error('password')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="form-group has-icon">
                                    @error('invalid_credentials')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <input type="submit" class="btn btn-success btn-lg btn-block" value="Log In">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #primary -->
        </main>
        <!-- Right Sidebar -->

        <div class="control-sidebar-bg shadow white fixed"></div>
    </div>
    <!--/#app -->
    <script src="{{ asset('assets/js/app.js') }}"></script>


    <!--
--- Footer Part - Use Jquery anywhere at page.
--- http://writing.colin-gourlay.com/safely-using-ready-before-including-jquery/
-->
    <script>
        (function($, d) {
            $.each(readyQ, function(i, f) {
                $(f)
            });
            $.each(bindReadyQ, function(i, f) {
                $(d).bind("ready", f)
            })
        })(jQuery, document)

    </script>
</body>

</html>
