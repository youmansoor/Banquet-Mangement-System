<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="description"
        content="VenueFlow - Banquet Management System"
    >

    <meta
        name="author"
        content="Coderzlab"
    >

    <!-- Favicon -->
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('assets/images/favicon.png') }}"
    >

    <title>Login | VenueFlow</title>


    <!-- Page CSS -->
    <link
        href="{{ asset('assets/dist/css/pages/login-register-lock.css') }}"
        rel="stylesheet"
    >

    <!-- Custom CSS -->
    <link
        href="{{ asset('assets/dist/css/style.min.css') }}"
        rel="stylesheet"
    >

    <style>
        #wrapper.login-register.login-sidebar { background-image: url('{{ asset('assets/images/background/login-register.jpg') }}');}
    </style>
</head>


<body>


    <!-- ==========================================
         PRELOADER
    =========================================== -->

    <div class="preloader">

        <div class="loader">

            <div class="loader__figure"></div>

            <p class="loader__label">
                VenueFlow
            </p>

        </div>

    </div>


    <!-- ==========================================
         MAIN WRAPPER
    =========================================== -->

    <section
        id="wrapper"
        class="login-register login-sidebar"
    >

        <div class="login-box card">

            <div class="card-body">


                <!-- ==================================
                     LOGIN FORM
                =================================== -->

                <form
                    class="form-horizontal form-material text-center"
                    id="loginform"
                    action="{{ route('login') }}"
                    method="POST"
                >

                    @csrf


                    <!-- ==================================
                         LOGO / BRAND
                    =================================== -->

                    <h1 class="venueflow-title" style="margin-top: 20%; font-weight: 600; font-size: 38px!important;">

                        VenueFlow

                    </h1>


                    <!-- ==================================
                         DESCRIPTION
                    =================================== -->

                    <p class="venueflow-description">

                        Manage your venue bookings, events,
                        customers, and payments in one place.

                        Keep your halls, schedules, and daily
                        operations organized with ease.

                        VenueFlow helps you run your banquet
                        business smarter and faster.

                    </p>


                    <!-- ==================================
                         VALIDATION ERRORS
                    =================================== -->

                    @if($errors->any())

                        <div class="alert alert-danger text-start">

                            @foreach($errors->all() as $error)

                                <div>
                                    {{ $error }}
                                </div>

                            @endforeach

                        </div>

                    @endif


                    <!-- ==================================
                         SESSION ERROR
                    =================================== -->

                    @if(session('error'))

                        <div class="alert alert-danger text-start">

                            {{ session('error') }}

                        </div>

                    @endif


                    <!-- ==================================
                         USERNAME / EMAIL
                    =================================== -->

                    <div class="form-group m-t-40">

                        <div class="col-xs-12">

                            <input
                                class="form-control"
                                type="text"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                placeholder="Username"
                            >

                        </div>

                    </div>


                    <!-- ==================================
                         PASSWORD
                    =================================== -->

                    <div class="form-group">

                        <div class="col-xs-12">

                            <input
                                class="form-control"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Password"
                            >

                        </div>

                    </div>


                    <!-- ==================================
                         LOGIN BUTTON
                    =================================== -->

                    <div class="form-group text-center m-t-20">

                        <div class="col-xs-12">

                            <button
                                class="btn btn-info btn-lg w-100 text-uppercase btn-rounded text-white"
                                type="submit"
                            >

                                Log In

                            </button>

                        </div>

                    </div>


                    <!-- ==================================
                         FOOTER
                    =================================== -->

                    <p class="login-footer" style="font-size: 13px;font-weight: 600; color:#d12828;border-top: 1px solid #d1cfcf;">

                        Design and Develop By Coderzlab © VenueFlow

                        <br>

                        Last Update By 15-Sep-2026

                    </p>


                </form>


            </div>

        </div>

    </section>


    <!-- ==========================================
         JQUERY
    =========================================== -->

    <script
        src="{{ asset('assets/node_modules/jquery/dist/jquery.min.js') }}"
    ></script>


    <!-- ==========================================
         BOOTSTRAP
    =========================================== -->

    <script
        src="{{ asset('assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"
    ></script>


    <!-- ==========================================
         CUSTOM JAVASCRIPT
    =========================================== -->

    <script>

        $(function () {

            $(".preloader").fadeOut();

        });


        $(function () {

            $('[data-bs-toggle="tooltip"]').tooltip();

        });


        // Login / Recover password support

        $('#to-recover').on('click', function () {

            $("#loginform").slideUp();

            $("#recoverform").fadeIn();

        });

    </script>


</body>

</html>