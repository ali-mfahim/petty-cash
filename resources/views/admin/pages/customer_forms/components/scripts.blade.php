<script>
    loadPageData()

    $(document).on("click", ".view-customer-btn", function() {
        var formId = $(this).attr("data-form-id");
        var route = $(this).attr("data-route");
        $.ajax({
            url: route,
            method: "GET",
            beforeSend: function() {
                // console.log("fetcing customer form details");
            },
            success: function(res) {
                $("#viewCustomerFormModal").modal("show");
                $("#viewCustomerFormModalBody").html(res.data);
                // setTimeout(() => {
                //     getFormFiles(formId, "files_row", "col-md-3");
                // }, 500);


            },
            error: function(xhr, status, error) {
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        });
    })



    $(document).on("click", ".add-followup", function() {
        $("#remarks_error").html("");
        var id = $(this).attr("data-form-id");
        var route = $(this).attr("data-route");


        if (id && route) {
            $.ajax({
                url: "{{route('coorporate-forms.addFollowupModalContent')}}",
                data: {
                    form_id: id,
                    route: route,
                },
                method: "GET",
                beforeSend: function() {
                    showFancyBox();
                },
                success: function(res) {
                    hideFancyBox()
                    if (res.success == true) {
                        $("#addFollowupModal").modal("show");
                        $("#addFollowupModalBody").html(res.data)
                    }
                    if (res.success == false) {}

                },
                error: function(xhr, status, error) {
                    hideFancyBox()
                    console.log(xhr)
                    console.log(status)
                    console.log(error)

                }
            });



        }
    });

    $(document).on("submit", "#addFollowupModalForm", function(event) {
        $("#remarks_error").html("");
        event.preventDefault();
        var remarks = $("#remarks").val();
        var route = $("#form_route").val();
        if (!remarks) {
            $("#remarks_error").html("Please insert remarks");
        }
        var formData = $(this).serialize();
        $.ajax({
            url: route,
            method: "POST",
            data: formData,
            beforeSend: function() {
                showFancyBox();
            },
            success: function(res) {
                hideFancyBox()
                if (res.success == true) {
                    $("#addFollowupModalForm")[0].reset();
                    $("#addFollowupModal").modal("hide");
                    var page_reload = $("#page_reload").val();
                    if (page_reload) {
                        location.reload()
                    }
                    loadPageData()

                    showToastr("success", "Success", res.message)

                }
                if (res.success == false) {
                    showToastr("error", "Error", res.message)
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        });
    });
    $(document).on("click", ".view-followup", function() {
        $("#remarks_error").html("");
        var form_id = $(this).attr("data-form-id");
        var route = $(this).attr("data-route");
        $.ajax({
            url: route,
            method: "GET",
            data: {
                form_id: form_id,
            },
            beforeSend: function() {
                showFancyBox();
                $("#viewFollowups").modal("show")
                $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
                $("#viewFollowupsBody").html($modalSpinner)
            },
            success: function(res) {
                hideFancyBox()
                if (res.success == true) {
                    if (res.data) {
                        $("#viewFollowupsBody").html(res.data)
                    }
                }
                if (res.success == false) {
                    showToastr("error", "Error", res.message)
                }
            },
            error: function(xhr, status, error) {
                hideFancyBox()
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        });
    });



    $(document).on("click", ".send-email-to-customer", function() {
        var route = $(this).attr("data-route");
        var id = $(this).attr("data-form-id");
        var email = $(this).attr("data-customer-email");
        $("#sendEmailModalForm")[0].reset();
        $("#subject_error").html("");
        $("#message_error").html("");
        $("#sendEmailModal").modal("show");
        $("#email_form_id").val(id)
        $("#to_email").val(email)
        $("#email_form_route").val(route)
    });



    $(document).on("submit", "#sendEmailModalForm", function(event) {
        event.preventDefault();
        var to_email = $("#to_email").val();
        if (!to_email) {
            $("#to_email_error").html("To email can not be empty")
        }
        var subject = $("#subject").val()
        if (!subject) {
            $("#subject_error").html("Please insert subject before sending email");
        }
        var message = $("#message").val()
        if (!message) {
            $("#message_error").html("Please insert message before sending email");
        }

        if (!subject || !message) {
            return false;
        }
        var formData = $(this).serialize();
        var route = $("#email_form_route").val();
        $.ajax({
            url: route,
            method: "POST",
            data: formData,
            beforeSend: function() {
                showFancyBox()
            },
            success: function(res) {
                hideFancyBox()
                if (res.success == true) {
                    $("#sendEmailModal").modal("hide");
                    $("#sendEmailModalForm")[0].reset();

                    var page_reload = $("#page_reload").val();
                    if (page_reload) {
                        location.reload()
                    }


                    loadPageData();
                    showToastr("success", "Success", res.message)
                }
                if (res.success == false) {
                    showToastr("error", "Error", res.message)
                }


            },
            error: function(xhr, status, error) {
                hideFancyBox()
                console.log(xhr)
                console.log(status)
                console.log(error)

            }
        });

    });


    $(document).on('click', '.status_item', function(event) {
        event.preventDefault();
        var status = $(this).data('status-name');
        var id = $(this).data('status-id');
        var currentStatusID = $(this).attr("data-old-status-id");
        var formId = $(this).attr("data-form-id");
        var currentStatusName = $(this).attr("data-old-status-name");
        $.ajax({
            url: "{{route('coorporate-forms.getChangeStatusModalContent')}}",
            data: {
                status_id: id,
                form_id: formId,
            },
            beforeSend: function() {
                showFancyBox();
            },
            success: function(res) {
                hideFancyBox()
                if (res.success == true) {
                    $("#changeStatusModal").modal("show")
                    $("#changeStatusModalBody").html(res.data);
                }
                // $('#status-button_' + formId).text(status);
            },
            error: function(xhr, status, error) {
                hideFancyBox()
                console.log(xhr)
                console.log(status)
                console.log(error)

            }
        });
    });

    $(document).on("submit", "#changeStatusModalForm", function(event) {
        event.preventDefault();
        $("#status_remarks_error").html("");
        var remarks = $("#status_remarks").val();
        var new_status_id = $("#new_status_id").val();
        if (!remarks) {
            $("#status_remarks_error").html("Please type in the reason of changing the status");
        }
        if (!new_status_id) {
            showToastr("error", "Error", "Failed to load the data, Please reload the page");
        }
        if (!remarks || !new_status_id) {
            return false;
        }
        var formData = $(this).serialize();
        $.ajax({
            url: "{{route('coorporate-forms.updateFormStatus')}}",
            data: formData,
            method: "POST",
            beforeSend: function() {
                showFancyBox();
            },
            success: function(res) {
                hideFancyBox()
                if (res.success == true) {
                    $("#changeStatusModal").modal("hide")
                    $("#changeStatusModalBody").html("");
                    loadPageData()
                    showToastr("success", "Success", res.message)
                }
                if (res.success == false) {
                    showToastr("error", "Error", res.message)
                }

            },
            error: function(xhr, status, error) {
                hideFancyBox()
                console.log(xhr)
                console.log(status)
                console.log(error)

            }
        });
    });

    function loadPageData() {


        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }

        var table = $('.data_table').DataTable({
            "paging": true,
            "processing": true,
            "serverSide": false, // Disable server-side processing
            "searching": true,
            "smart": true,
            "pageLength": 20, // Set the default page length
            "lengthMenu": [
                [10, 20, 50, 100],
                [10, 20, 50, 100]
            ],
            // "pagingType": "full_numbers",
            language: {
                "processing": '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            },
            searchPanes: {
                filterChanged: function(count) {
                    $('.SPDetails').text(this.i18n('searchPanes.collapse', {
                        0: 'AdvancedFilter',
                        _: 'Advancedfilter (%d)'
                    }, count));
                }
            },
            ajax: {
                url: "{{route('coorporate-forms.index')}}",
                // data: function(d) {
                //     d.daterange = $('.daterange').val()
                // }
            },
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,

                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: false,
                    // width: "78px",

                },


                {
                    data: 'color',
                    name: 'color',
                    orderable: false,
                    // width: "78px",

                },
                {
                    data: 'country',
                    name: 'country',
                    orderable: false,
                    // width: "78px",

                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: false,
                    // width: "78px",

                },
                {
                    data: 'eye',
                    name: 'eye',
                    orderable: false,
                    // width: "78px",

                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    // width: "78px",

                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    // width: "78px",

                },
            ],

            createdRow: function(row, data, dataIndex) {
                if (data.status_id == 1) {
                    $(row).css('background-color', '#587e878a');
                }
            }
        });


        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }

    }

    function getFormFiles(formId, filesDivId, col) {
        $.ajax({
            url: "{{route('coorporate-forms.getFormFiles')}}",
            method: "GET",
            data: {
                form_id: formId,
                col: col,
            },
            beforeSend: function() {
                // console.log("getting files");
            },
            success: function(res) {
                if (res.success == true && res.data.length > 0) {
                    $.each(res.data, function(key, val) {
                        $("#" + filesDivId).append(val);
                    });
                }
                $(".lazy-loading-img").lazyload();
            },
            error: function(xhr, status, error) {
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        });

    }
</script>