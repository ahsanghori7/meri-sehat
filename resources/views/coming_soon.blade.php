<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!--CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('coming_soon/assets/css/style.css') }}" />
    <title>Coming Soon - Meri Sehat</title>
</head>

<body>
    <section class="wrapper">
        <div class="container">
            <div class="comming-soon">
                <div class="social-container">
                    <a href="https://www.facebook.com/MeriSehat.pk" target="_blank" class="social-icon">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/merisehat.pk" target="_blank" class="social-icon">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com/merisehat" target="_blank" class="social-icon">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="content">
                            <div class="logo">
                                <img src="{{ asset('coming_soon/assets/images/logo.svg') }}" alt="logo" />
                            </div>
                            <div>
                                <p>
                                    Pakistan’s largest health platform featuring leading
                                    doctors, hospitals and much more. Launching soon!
                                </p>
                                <h3 class="heading-3">
                                    Sign up for our newsletter and keep yourself updated.
                                </h3>
                                <div class="subscription_box">
                                    <form method="post" action="{{route('newsletter')}}">
                                        <div class="input-group">
                                            <input type="email" name="email" class="form-control" placeholder="Email Address"
                                                aria-label="Email Address" aria-describedby="basic-addon2" />
                                            <div class="input-group-append">
                                                <button>
                                                    <button type="submit" class="input-group-text"
                                                        id="basic-addon2"><img
                                                            src="{{ asset('coming_soon/assets/images/arrow-right.png') }}" /></button>
                                                </button>
                                            </div>
                                     
                                        </div>
                                    </form>
                                    @error('email')
                                     <div class="validation-error text-danger">{{ $message }}</div> 
                                    @enderror
                                    @error('already_subscribed')
                                    <div class="validation-error text-success">{{ $message }}</div> 
                                   @enderror
                            </div>
                            <div class="btn-filled">
                                <a href="{{ route('register_doctor_index') }}">ARE YOU A DOCTOR? REGISTER HERE
                                    <img src="{{ asset('coming_soon/assets/images/arrow-right.png') }}" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- <img src="{{ asset('coming_soon/assets/images/women.png') }}" alt="women" class="women" />
      <img src="{{ asset('coming_soon/assets/images/mark.png') }}" alt="women" class="mark" /> -->
    </section>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
    </script>
</body>

</html>
