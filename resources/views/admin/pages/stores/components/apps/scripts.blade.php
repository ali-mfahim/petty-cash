<script>
    $(document).ready(function() {
        loadPageData()

        if ($("#add-new-app-btn").hasClass("disabled")) {
            $("#add-new-app-btn").removeClass("disabled")
        }


        // create app
        $(document).on("click", "#add-new-app-btn", function() {
            $("#error_message").html("");
            $("#success_message").html("");
            $("#name_error").html("");
            $("#app_key_error").html("");
            $("#app_secret_error").html("");
            $("#access_token_error").html("");
            $("#api_version_error").html("")
            $("#status_error").html("")
            $("#createAppForm")[0].reset();
            $('#image').val('');
            $('#thumbnail').attr('src', "{{asset('upload-icon.png')}}");
            $("#createAppModal").modal("show");
        });
        $('#createAppForm').submit(function(event) {
            event.preventDefault();
            $("#error_message").html("");
            $("#success_message").html("");
            $("#name_error").html("");
            $("#app_key_error").html("");
            $("#app_secret_error").html("");
            $("#access_token_error").html("");
            $("#api_version_error").html("")
            $("#status_error").html("")
            var error = 0;
            var name = $("#name").val();

            if (!name) {
                $("#name_error").html("Please insert app name")
                var error = 1;
            }

            var app_key = $("#app_key").val();
            if (!app_key) {
                $("#app_key_error").html("Please insert app key")
                var error = 1;
            }

            var app_secret = $("#app_secret").val();
            if (!app_secret) {
                $("#app_secret_error").html("Please insert app secret")
                var error = 1;
            }
            var access_token = $("#access_token").val();
            if (!access_token) {
                $("#access_token_error").html("Please insert access token")
                var error = 1;
            }

            var api_version = $("#api_version").val();
            if (!api_version) {
                $("#api_version_error").html("Please insert api_version")
                var error = 1;
            }

            if (error != 0) {
                return false;
            }

            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('stores.saveApp') }}",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    if (response.success == true) {
                        $("#success_message").html(response.message);
                        loadPageData();
                        $("#createAppModal").modal("hide");
                        showToastr("success", "Success", response.message)
                    }
                    if (response.success == false) {
                        showToastr("error", "Error", response.message)
                    }
                },
                error: function(xhr, status, error) {
                    hideFancyBox()
                    if (xhr.status === 422) {
                        // Handle validation errors here
                        var errors = xhr.responseJSON.errors;

                        // Optionally, you could display the errors in your HTML
                        if (errors.name) {
                            $('#name_error').text(errors.name[0]);
                        }
                        if (errors.app_key) {
                            $('#app_key_error').text(errors.app_key[0]);
                        }
                        if (errors.app_secret) {
                            $('#app_secret_error').text(errors.app_secret[0]);
                        }
                        if (errors.api_version) {
                            $('#api_version_error').text(errors.api_version[0]);
                        }
                        if (errors.access_token) {
                            $('#access_token_error').text(errors.access_token[0]);
                        }
                    } else {
                        // Handle other types of errors (500, 404, etc.)
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });



        // create store

        // edit store
        $(document).on("click", ".edit-app-btn", function() {
            $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
            $("#editAppModalContent").html($modalSpinner);

            $("#error_message").html("");
            $("#success_message").html("");

            var app_id = $(this).attr("data-app-id");
            if (!app_id) {
                showToastr("error", "Error Occured", "Something went wrong with this store")
                return false
            }
            $("#editAppModal").modal("show");
            $.ajax({
                url: "{{route('stores.getEditAppContent')}}",
                method: "GET",
                data: {
                    app_id: app_id,
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.data.view) {
                        $("#editAppModalContent").html(res.data.view);
                    }
                    if (res.data.name) {
                        $("#name").html(res.data.name)
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });
        });

        $(document).on("submit", "#editAppForm", function(event) {
            event.preventDefault();

            // Clear previous error messages
            $("#update_name_error").html("");
            $("#update_app_key_error").html("");
            $("#update_app_secret_error").html("");
            $("#update_access_token_error").html("");
            $("#update_api_version_error").html("");

            var error = 0;
            // Collect form data
            var name = $("#update_name").val();
            if (!name) {
                $("#update_name_error").html("Please insert name");
                var error = 1;
            }

            var app_key = $("#update_app_key").val();
            if (!app_key) {
                $("#update_app_key_error").html("Please insert app_key");
                var error = 1;
            }


            var app_secret = $("#update_app_secret").val();
            if (!app_secret) {
                $("#update_app_secret_error").html("Please insert app secret");
                var error = 1;
            }


            var access_token = $("#update_access_token").val();
            if (!access_token) {
                $("#update_access_token_error").html("Please insert access token");
                var error = 1;
            }


            var api_version = $("#update_api_version").val();
            if (!api_version) {
                $("#update_api_version_error").html("Please insert api version");
                var error = 1;
            }


            if (error != 0) {
                return false;
            }
            var url = $("#update-app-route").val();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
            });
            $.ajax({
                type: 'POST', // Use POST for updating
                url: url, // Ensure you pass the store ID to update
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    if (response.success == true) {
                        $("#success_message").html(response.message);
                        loadPageData();
                        $("#editAppModal").modal("hide");
                        showToastr("success", "Success", response.message)
                    }
                    if (response.success == false) {
                        showToastr("error", "Error Occured", response.message);
                    }
                },
                error: function(xhr, status, error) {
                    hideFancyBox()
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            $('#update_name_error').text(errors.name[0]);
                        }
                        if (errors.app_key) {
                            $('#update_app_key_error').text(errors.app_key[0]);
                        }
                        if (errors.app_secret) {
                            $('#update_app_secret_error').text(errors.app_secret[0]);
                        }

                        if (errors.acess_token) {
                            $('#update_acess_token_error').text(errors.acess_token[0]);
                        }
                        if (errors.api_version) {
                            $('#update_api_version_error').text(errors.api_version[0]);
                        }
                    } else {
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        // edit store


        // delete store
        $(document).on("click", ".delete-app-btn", function() {
            var app_id = $(this).attr("data-app-id");
            var url = $(this).attr("data-route");
            $.confirm({
                title: 'Are you sure!',
                theme: 'supervan',
                content: 'App will be completely deleted!',
                buttons: {
                    confirm: function() {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': "{{csrf_token()}}" // Add CSRF token header
                            },
                            beforeSend: function() {
                                showToastr("warning", "Please Wait", "while we are deleting the user");
                            },
                            success: function(res) {

                                if (res.success == true) {
                                    loadPageData();
                                    setTimeout(() => {
                                        showToastr("success", "Store Deleted", res.message);
                                    }, 1000);
                                }
                                if (res.success == false) {
                                    showToastr("error", "Error Occured!", res.message);
                                }
                            },
                            error: function(res) {
                                showToastr("error", "Error", res.message);
                                console.log(res);
                            }
                        });
                    },
                    cancel: function() {
                        // $.alert('Canceled!');
                    },
                    // somethingElse: {
                    //     text: 'Something else',
                    //     btnClass: 'btn-blue',
                    //     keys: ['enter', 'shift'],
                    //     action: function() {
                    //         $.alert('Something else?');
                    //     }
                    // }
                }
            });


        });
        // delete store


        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        function loadPageData() {
            // Check if DataTable is already initialized and destroy if it is
            if ($.fn.DataTable.isDataTable('.data_table')) {
                $('.data_table').DataTable().destroy();
            }

            // Initialize the DataTable
            $('.data_table').DataTable({
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
                    url: $("#page_url").val(),
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
                        data: 'created_by',
                        name: 'created_by',
                        orderable: false,
                    },
                    {
                        data: 'app_name',
                        name: 'app_name',
                        orderable: false,
                    },
                    {
                        data: 'app_key',
                        name: 'app_key',
                        orderable: false,
                    },
                    {
                        data: 'app_secret',
                        name: 'app_secret',
                        orderable: false,
                    },
                    {
                        data: 'access_token',
                        name: 'access_token',
                        orderable: false,
                    },
                    {
                        data: 'api_version',
                        name: 'api_version',
                        orderable: false,
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                    },
                ]
            });
        }




        $(document).on("click", ".reveal", function() {
            var value = $(this).attr("data-value");
            var currentHtml = $(this).text()
            if (currentHtml == "Reveal") {
                $(this).html(value)
            } else {
                $(this).html("Reveal")
            }
        });

        $(document).on("change", ".status_switch_btn", function() {
            var clickedButton = $(this);
            var app_id = clickedButton.attr("data-app-id");
            var status = clickedButton.val();

            // Set all other buttons' values to 2 and switch them off
            $(".status_switch_btn").not(clickedButton).each(function() {
                $(this).val(2);
                $(this).prop("checked", false); // Assuming you use checkboxes or switches
            });

            // Toggle the clicked button's value and status
            if (status == 1) {
                clickedButton.val(2);
                clickedButton.prop("checked", false); // Switch off the clicked button
                var newStatus = 2;
            } else {
                clickedButton.val(1);
                clickedButton.prop("checked", true); // Switch on the clicked button
                var newStatus = 1;
            }

            $.ajax({
                url: "{{route('stores.updateAppStatus')}}",
                method: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    "app_id": app_id,
                    "status": newStatus,
                },
                beforeSend: function() {
                    $(".status_switch_btn").attr("disabled", true);
                },
                success: function(res) {
                    $(".status_switch_btn").attr("disabled", false);
                    if (res.success == true) {
                        showToastr("success", "Success", res.message);
                        setTimeout(() => {
                            // loadPageData()
                        }, 500);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        });

    });
</script>