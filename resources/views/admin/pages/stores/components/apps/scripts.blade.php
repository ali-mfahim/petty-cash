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
            $("#status_error").html("")
            $("#api_version_error").html("")
            $("#createAppForm")[0].reset();
            // Reset the file input and thumbnail
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
            $("#status_error").html("")
            $("#api_version_error").html("")
            var error = 0;
            var name = $("#name").val();
            if (!name) {
                $("#name_error").html("Please insert name")
                var error = 1;
            }

            var app_key = $("#app_key").val();
            if (!app_key) {
                $("#app_key_error").html("Please insert app_key")
                var error = 1;
            }

            var app_secret = $("#app_secret").val();
            if (!app_secret) {
                $("#app_secret_error").html("Please insert app_secret")
                var error = 1;
            }
            var access_token = $("#access_token").val();
            if (!access_token) {
                $("#access_token_error").html("Please insert app_secret")
                var error = 1;
            }

            var access_token = $("#access_token").val();
            if (!access_token) {
                $("#access_token_error").html("Please insert app_secret")
                var error = 1;
            }

            if (error != 0) {
                return false;
            }

            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('stores.store') }}",
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
                        $("#createStoreModal").modal("hide");
                        $('#image').val('');
                        $('#thumbnail').attr('src', "{{asset('upload-icon.png')}}");
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

                        // Example: Displaying the errors in the console
                        // console.log(errors);

                        // Optionally, you could display the errors in your HTML
                        if (errors.name) {
                            $('#name_error').text(errors.name[0]);
                        }
                        if (errors.base_url) {
                            $('#base_url_error').text(errors.base_url[0]);
                        }
                        if (errors.domain) {
                            $('#domain_error').text(errors.domain[0]);
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
        $(document).on("click", ".edit-store-btn", function() {
            $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
            $("#editStoreModalContent").html($modalSpinner);

            $("#error_message").html("");
            $("#success_message").html("");

            var store_id = $(this).attr("data-store-id");
            if (!store_id) {
                showToastr("error", "Error Occured", "Something went wrong with this store")
                return false
            }
            $("#editStoreModal").modal("show");
            $.ajax({
                url: "{{route('stores.getEditStoreModalContent')}}",
                data: {
                    store_id: store_id,
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.data.view) {
                        $("#editStoreModalContent").html(res.data.view);
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

        $(document).on("submit", "#editStoreForm", function(event) {
            event.preventDefault();

            // Clear previous error messages
            $("#update_name_error").html("");
            $("#update_domain_error").html("");
            $("#update_base_url_error").html("");
            $("#update_api_url_error").html("");

            var error = 0;
            // Collect form data
            var name = $("#update_name").val();
            if (!name) {
                $("#update_name_error").html("Please insert name");
                var error = 1;
            }

            var domain = $("#update_domain").val();
            if (!domain) {
                $("#update_domain_error").html("Please insert domain");
                var error = 1;
            }



            var base_url = $("#update_base_url").val();
            if (!base_url) {
                $("#update_base_url_error").html("Please insert base url");
                var error = 1;
            }


            var api_url = $("#update_api_url").val();
            if (!api_url) {
                $("#update_api_url_error").html("Please insert api url");
                var error = 1;
            }
            if (error != 0) {
                return false;
            }
            var url = $("#update-store-route").val();
            var formData = new FormData(this);
            console.log(formData)
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
                        $("#editStoreModal").modal("hide");
                        showToastr("success", "Success", response.message)
                    }
                    if (response.success == false) {
                        // Handle failure case
                        // $("#error_message").html(response.message);
                        showToastr("error", "Error Occured", response.message);
                    }
                },
                error: function(xhr, status, error) {
                    hideFancyBox()
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.title) {
                            $('#update_title_error').text(errors.title[0]);
                        }
                    } else {
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        // edit store


        // delete store
        $(document).on("click", ".delete-store-btn", function() {
            var userId = $(this).attr("data-store-id");
            var url = $(this).attr("data-route");
            $.confirm({
                title: 'Are you sure!',
                theme: 'supervan',
                content: 'Store will be completely deleted!',
                buttons: {
                    confirm: function() {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
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
            console.log("loadPageData");

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
                    url: "{{route('stores.index')}}",
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
                        data: 'logo',
                        name: 'logo',
                        orderable: false,
                    },
                    {
                        data: 'domain',
                        name: 'domain',
                        orderable: false,
                    },
                    {
                        data: 'base_url',
                        name: 'base_url',
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

    });
</script>