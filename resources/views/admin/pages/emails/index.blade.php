<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <!-- Meta Tags -->
    @include("admin.partials.meta")
    <!-- Meta Tags -->

    <title> {{$title ?? '' }} - {{projectSettings()->title ?? ''}}</title>

    <!-- styles -->
    @include("admin.partials.styles")
    <!-- styles -->


</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">
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
    <div class="app-content content email-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-area-wrapper container-fluid p-0">

            @include("admin.pages.emails.components.leftsidebar") 
            @include("admin.pages.emails.components.rightsidebar" ,['records' => $emails ?? ''])
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
</body>
<!-- END: Body-->

</html>