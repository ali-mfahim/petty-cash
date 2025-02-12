<link rel="apple-touch-icon" href="{{ getLogos()->fav_icon ?? ''  }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ getLogos()->fav_icon ?? ''  }}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/vendors.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/extensions/toastr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Inconsolata&amp;family=Roboto+Slab&amp;family=Slabo+27px&amp;family=Sofia&amp;family=Ubuntu+Mono&amp;display=swap">
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
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/plugins/extensions/ext-component-toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/pages/app-email.css')}}">
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/style.css')}}">
<!-- END: Custom CSS-->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" integrity="sha512-nNlU0WK2QfKsuEmdcTwkeh+lhGs6uyOxuUs+n+0oXSYDok5qy0EI0lt01ZynHq6+p/tbgpZ7P+yUb+r71wqdXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">



<!-- datatables -->
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')}}">
<!-- datatables -->

<!-- jquer confirm -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
<!-- jquer confirm -->

<style>
    .lazy-loading-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
  
</style>
@include("admin.partials.themeoverride")

@stack("styles")