<!-- BEGIN: Vendor JS-->
<script src="{{ asset('admin/assets/app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('admin/assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('admin/assets/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/js/core/app.js') }}"></script>
<!-- END: Theme JS-->

<script src="{{ asset('admin/assets/app-assets/js/scripts/pages/app-email.js') }}"></script>



<!-- BEGIN: Page JS-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"
    integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<!-- END: Page JS-->

<!-- datatables -->
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
<script src="{{ asset('admin/assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<!-- datatables -->

<!-- jquery confirm -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<!-- jquery confirm -->


<!-- Lazy loading images -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"
    integrity="sha512-jNDtFf7qgU0eH/+Z42FG4fw3w7DM/9zbgNPe3wfJlCylVDTT3IgKW5r92Vy9IHa6U50vyMz5gRByIu4YIXFtaQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Lazy loading images -->
<script>
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }

    $(document).ready(function() {
        $(".lazy-loading-img").lazyload();
    });

    function showToastr(type, title, message) {
        var isRtl = $('html').attr('data-textdirection') === 'rtl';
        toastr[type](
            message,
            title, {
                closeButton: true,
                tapToDismiss: true,
                rtl: isRtl,
                progressBar: true,
                timeout: 50000000000

            }
        );
    }

    function showSweetAlert(type, message) {
        Swal.fire({
            text: message,
            icon: type,
            buttonsStyling: false,
            confirmButtonText: "Ok",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
    }

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

    $(document).on("change", "#selectAll", function() {
        if ($(this).is(':checked')) {
            $(".child_checkbox").prop("checked", true); // Check all child checkboxes
        } else {
            $(".child_checkbox").prop("checked", false); // Uncheck all child checkboxes
        }
    });
</script>


@if (Session::has('error'))
    <script>
        var message = $("#error_msg_global").val();
        // showSweetAlert("error", message)
        showToastr("error", "⚠ Error Occured!", message)
    </script>
@endif
@if (Session::has('success'))
    <script>
        var message = $("#success_msg_global").val();
        // showSweetAlert("success", message)
        showToastr("success", "✔ Success", message)
    </script>
@endif
<script>
    $(document).on("click", ".generate-form-link", function() {
        var userId = $(this).attr("data-user-id");
        var type = $(this).attr("data-type");
        $("#generateFormLinkModal").modal("show");
        $.ajax({
            url: "{{ route('front.paymentForms.modalContent') }}",
            data: {
                user_id: userId,
                type: type,
            },
            beforeSend: function() {
                console.log("WORKING");
            },
            success: function(res) {
                console.log(res)
                $("#generateFormLinkModalBody").html(res.data);
            },
            error: function(xhr, status, error) {
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        });
        //
        // if (type == 1) {
        //     $("#form-link-modal-heading").html("Petty Cash Form Link")
        //     $pettyLink = $("#lastest-petty-cash-form-link").val();
        //     $("#form_link").val($pettyLink)
        // }
        // if (type == 2) {
        //     $("#form-link-modal-heading").html("Personal Expense Form Link")
        //     $expenseLink = $("#lastest-expense-form-link").val();
        //     $("#form_link").val($expenseLink)
        // }
        // $(".generate-link-button").attr("data-type", type);
        // $("#generateFormLinkModal").attr("data-type", type);

    });
    $(document).on("click", ".generate-link-button", function() {
        var user_id = $("#user-id").val();
        var type = $(this).attr("data-type");
        $.ajax({
            url: "{{ route('profiles.generateLink') }}",
            method: "GET",
            data: {
                type: type,
                user_id: user_id,
            },
            beforeSend: function() {

            },
            success: function(res) {
                if (res.data.link && res.success == true) {
                    showToastr("success", "Success!", res.message)
                    $("#form_link").val(res.data.link)
                } else {
                    showToastr("error", "Error!", res.message)
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        })
    });
    $(document).on("click", ".copy_button", function() {
        var copyText = $('#form_link');
        copyText.select();
        copyText[0].setSelectionRange(0, 99999); // For mobile devices
        document.execCommand('copy');
        showToastr("success", "Success!", "Link has been copied");
    });
</script>
@stack('scripts')
