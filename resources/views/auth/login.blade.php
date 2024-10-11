<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>

    <!-- Meta Tags -->
    @include("admin.partials.meta")
    <!-- Meta Tags -->

    <title> Login - {{projectSettings()->title ?? ''}}</title>

    <!-- styles -->
    <link rel="apple-touch-icon" href="{{asset('logos/fav-white.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('logos/fav-white.png')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/pages/authentication.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/style.css')}}">
    <!-- END: Custom CSS-->
    <!-- styles -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" integrity="sha512-nNlU0WK2QfKsuEmdcTwkeh+lhGs6uyOxuUs+n+0oXSYDok5qy0EI0lt01ZynHq6+p/tbgpZ7P+yUb+r71wqdXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html .pace .pace-progress {
            background: #ffffff;
        }

        .dark-layout body {
            color: white;
            background-color: #0B0B0B;
        }

        .dark-layout .auth-wrapper .auth-bg {
            background-color: #121010 !important;
        }

        .btn-primary {
            border-color: #920004 !important;
            background-color: #920004 !important;
            color: #fff !important;
        }

        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary.active {
            color: #fff;
            background-color: #163551 !important;
        }

        .dark-layout .input-group .input-group-text {
            background-color: #474747;
            border-color: #474747;
        }

        .dark-layout input:-webkit-autofill,
        .dark-layout textarea:-webkit-autofill,
        .dark-layout select:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #242425 inset !important;
            -webkit-text-fill-color: #ffffff !important;
        }


        .btn-primary:hover:not(.disabled):not(:disabled) {
            box-shadow: 0 0px 5px 0px #4b4b50;
        }

        .form-control:focus {
            color: #6e6b7b;
            background-color: #fff;
            border-color: #ffffff;
            outline: 0;
            box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
        }

        .dark-layout input.form-control,
        .dark-layout select.form-select,
        .dark-layout textarea.form-control {
            background-color: #091928;
            color: white;
        }

        .dark-layout .input-group:focus-within .form-control,
        .dark-layout .input-group:focus-within .input-group-text {
            border-color: #ffffff;
            box-shadow: none;
        }

        .form-check-input:not(:disabled):checked {
            box-shadow: 0 2px 4px 0 rgb(66 66 66 / 40%);
        }

        .form-check-input:checked {
            background-color: #4a4b4b;
            border-color: #4a4b4b;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-cover">
                    <div class="auth-inner row m-0">
                        <!-- Brand logo-->
                        <a class="brand-logo" href="javascript:;">
                            <img src="{{asset('logos/white.png')}}" alt="" style="max-width :170px;">
                            <h2 class="brand-text text-primary ms-1"></h2>
                        </a>
                        <!-- /Brand logo-->
                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                            <!-- <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="{{asset('admin/assets/app-assets/images/pages/login-v2-dark.svg')}}" alt="Login V2" /></div> -->
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                                <img class="img-fluid" src="{{asset('login-v2-dark.svg')}}" style="max-width: 70% !important;" alt="Login V2" />
                            </div>
                        </div>
                        <!-- /Left Text-->
                        <!-- Login-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <h2 class="card-title fw-bold mb-1">Welcome to {{projectSettings()->title ?? ''}}! 👋</h2>
                                <p class="card-text mb-2">Please sign-in to your account.</p>
                                <div id="errorMessage" style="font-size: 16px;font-weight: bold;margin-bottom: 15px;" class="text-danger"></div>
                                <form id="loginForm" class="mb-3" action="{{ route('adminLogin') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                                        <span class="text-danger" id="email_error">{{ $errors->first('email') }}</span>
                                    </div>
                                    <div class="mb-3 form-password-toggle">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="password">Password</label>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control form-control-merge" name="password" placeholder="" aria-describedby="password" tabindex="2" /> <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            <!-- <input class="form-control form-control-merge" id="login-password" type="password" name="login-password" placeholder="············" aria-describedby="login-password" tabindex="2" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span> -->
                                            <!-- <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span> -->
                                        </div>
                                        <div class="mb-1 mt-1">
                                            <div class="form-check">
                                                <input class="form-check-input" id="remember-me" name="remember_me" type="checkbox" tabindex="3" />
                                                <label class="form-check-label" for="remember-me"> Remember Me</label>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="password_error">{{ $errors->first('password') }}</span>
                                    </div>
                                    <button class="btn btn-primary d-grid w-100 mt-5">Sign in</button>
                                </form>
                                <!-- <p class="text-center mt-2"><span>New on our platform?</span>
                                    <a href="auth-register-cover.html"><span>&nbsp;Create an account</span></a>
                                </p>
                                <div class="divider my-2">
                                    <div class="divider-text">or</div>
                                </div>
                                <div class="auth-footer-btn d-flex justify-content-center"><a class="btn btn-facebook" href="#"><i data-feather="facebook"></i></a><a class="btn btn-twitter white" href="#"><i data-feather="twitter"></i></a><a class="btn btn-google" href="#"><i data-feather="mail"></i></a><a class="btn btn-github" href="#"><i data-feather="github"></i></a></div> -->
                            </div>
                        </div>
                        <!-- /Login-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- scripts -->


    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('admin/assets/app-assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('admin/assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('admin/assets/app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{asset('admin/assets/app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset('admin/assets/app-assets/js/scripts/pages/auth-login.js')}}"></script>
    <!-- END: Page JS-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

    <script>
        function showFancyBox() {
            $.fancybox.open('<div class="fancybox-loading"></div>', {
                closeExisting: true,
                toolbar: false,
                smallBtn: false,
                modal: false,
                keyboard: false,
                clickSlide: false,
                touch: false,
                caption: 'Please wait while your request is being processed.'
            });
        }

        function hideFancyBox() {
            $.fancybox.close();
        }
        $(document).ready(function() {
            $('#loginForm').submit(function(event) {
                event.preventDefault();


                $('#email_error').text("");
                $('#password_error').text();



                var email = $("#email").val()
                var password = $("#password").val()
                if (!email) {

                }


                if (!email) {
                    $('#email_error').text("Please insert an email");
                }
                if (!password) {
                    $('#password_error').text("Please insert a password");
                }

                if (!email || !password) {
                    return false;

                }
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('adminLogin') }}",
                    data: formData,
                    beforeSend: function() {
                        $("#errorMessage").html("");
                        showFancyBox()
                    },
                    success: function(response) {

                        if (response.success == true) {
                            if (response.data.url) {
                                window.location.href = response.data.url;
                            }
                        }
                        if (response.success == false) {
                            hideFancyBox()
                            $("#errorMessage").html(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        hideFancyBox()
                        if (xhr.status === 422) {
                            // Handle validation errors here
                            var errors = xhr.responseJSON.errors;

                            // Example: Displaying the errors in the console
                            console.log(errors);

                            // Optionally, you could display the errors in your HTML
                            if (errors.email) {
                                $('#email_error').text(errors.email[0]);
                            }
                            if (errors.password) {
                                $('#password_error').text(errors.password[0]);
                            }
                        } else {
                            // Handle other types of errors (500, 404, etc.)
                            console.log('An error occurred:', xhr);
                        }
                    }
                });
            });
        });
    </script>
    <!-- scripts -->

</body>
<!-- END: Body-->

</html>