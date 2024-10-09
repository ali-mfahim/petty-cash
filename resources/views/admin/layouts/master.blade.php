<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>

    <!-- Meta Tags -->
    @include("admin.partials.meta")
    <!-- Meta Tags -->

    <title> @stack("title") - {{projectSettings()->title ?? ''}}</title>

    <!-- styles -->
    @include("admin.partials.styles")
    <!-- styles -->
    <style>
        /* Full Page Loader Styles */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: black;
            /* Semi-transparent background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* Google-Like Spinner */
        .spinner {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .spinner div {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 64px;
            height: 64px;
            margin: 8px;
            border: 8px solid;
            border-radius: 50%;
            animation: spinner 1.4s cubic-bezier(0.4, 0.0, 0.2, 1) infinite;
            border-color: #4285f4 transparent transparent transparent;
        }

        .spinner div:nth-child(1) {
            animation-delay: -0.3s;
        }

        .spinner div:nth-child(2) {
            animation-delay: -0.15s;
            border-color: #ea4335 transparent transparent transparent;
        }

        .spinner div:nth-child(3) {
            animation-delay: 0s;
            border-color: #fbbc05 transparent transparent transparent;
        }

        .spinner div:nth-child(4) {
            animation-delay: 0.15s;
            border-color: #34a853 transparent transparent transparent;
        }

        .spinner div:nth-child(5) {
            animation-delay: 0.3s;
            border-color: #ffffff transparent transparent transparent;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <div class="loader" id="loader">
        <div class="spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    @if (Session::has('success'))
    <input type="hidden" name="" value="{{ Session::get('success') }}" id="success_msg_global">
    @endif
    @if (Session::has('error'))
    <input type="hidden" name="" value="{{ Session::get('error') }}" id="error_msg_global">
    @endif

    <!-- BEGIN: Header-->
    @include("admin.partials.header")
    <!-- END: Header-->

    <!-- BEGIN: Side Bar -->
    @include("admin.partials.sidebar")
    <!-- END: Side Bar -->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-fluid p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                @yield("content")
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    @include("admin.partials.footer")
    <!-- END: Footer-->


    <!-- scripts -->
    @include("admin.partials.scripts")
    <!-- scripts -->



    <script>
        // Hide loader after page loads
        setTimeout(() => {
            $(document).ready(function() {
                $("#loader").fadeOut("slow");
            });
        }, 500);
    </script>
</body>
<!-- END: Body-->

</html>