<script>
    $(document).ready(function() {
        loadPageData()
        if ($("#add-new-user-btn").hasClass("disabled")) {
            $("#add-new-user-btn").removeClass("disabled")
        }
        // create User
        $(document).on("click", "#add-new-user-btn", function() {
            $("#error_message").html("");
            $("#success_message").html("");
            $("#first_name_error").html("");
            $("#last_name_error").html("");
            $("#email_error").html("");
            $("#phone_error").html("");
            $("#password_error").html("");
            $("#role_error").html("")
            $("#createUserForm")[0].reset();
            $("#createUserModal").modal("show");
        });
        $('#createUserForm').submit(function(event) {
            event.preventDefault();
            $("#error_message").html("");
            $("#success_message").html("");
            $("#first_name_error").html("");
            $("#last_name_error").html("");
            $("#email_error").html("");
            $("#phone_error").html("");
            $("#password_error").html("");
            $("#role_error").html("")
            var first_name = $("#first_name").val();
            if (!first_name) {
                $("#first_name_error").html("Please insert first name")
            }
            var email = $("#email").val();
            if (!email) {
                $("#email_error").html("Please insert email")
            }

            var password = $("#password").val();
            if (!password) {
                $("#password_error").html("Please insert password")
            }


            var role = $("#role").val();
            if (!role) {
                $("#role_error").html("Please insert role")
            }


            if (!first_name || !email || !password || !role) {
                return false;
            }

            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('users.store') }}",
                data: formData,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    if (response.success == true) {

                        $("#success_message").html(response.message);
                        loadPageData();
                        $("#createUserModal").modal("hide");
                        showToastr("success", "Success", response.message)



                    }
                    if (response.success == false) {

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
                        if (errors.first_name) {
                            $('#first_name_error').text(errors.first_name[0]);
                        }
                        if (errors.email) {
                            $('#email_error').text(errors.email[0]);
                        }
                        if (errors.password) {
                            $('#password_error').text(errors.password[0]);
                        }
                        if (errors.phone) {
                            $('#phone_error').text(errors.phone[0]);
                        }
                    } else {
                        // Handle other types of errors (500, 404, etc.)
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        // create User

        // edit User
        $(document).on("click", ".edit-user-btn", function() {
            $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
            $("#editUserModalContent").html($modalSpinner);

            $("#error_message").html("");
            $("#success_message").html("");
            $("#user_name").html("")

            var user_id = $(this).attr("data-user-id");
            if (!user_id) {
                showToastr("error", "Error Occured", "Something went wrong with this user")
                return false
            }
            $("#editUserModal").modal("show");
            $.ajax({
                url: "{{route('users.getEditUserModalContent')}}",
                data: {
                    user_id: user_id,
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.data.view) {
                        $("#editUserModalContent").html(res.data.view);
                    }
                    if (res.data.user_name) {
                        $("#user_name").html(res.data.user_name)
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });
        });
        $(document).on("submit", "#editUserForm", function(event) {
            event.preventDefault();

            // Clear previous error messages
            $("#update_first_name_error").html("");
            $("#update_email_error").html("");
            $("#update_password_error").html("");
            $("#update_phone_error").html("");

            // Collect form data
            var first_name = $("#update_first_name").val();
            if (!first_name) {
                $("#update_first_name_error").html("Please insert first name");
            }
            var email = $("#update_email").val();
            if (!email) {
                $("#update_email_error").html("Please insert email");
            }

            if (!first_name || !email) {
                return false;
            }
            var url = $("#update-user-route").val();
            var formData = $(this).serialize();
            $.ajax({
                type: 'PUT', // Use PUT for updating
                url: url, // Ensure you pass the user's ID to update
                data: formData,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    if (response.success == true) {
                        $("#success_message").html(response.message);
                        loadPageData();
                        $("#editUserModal").modal("hide");
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
                        if (errors.first_name) {
                            $('#update_first_name_error').text(errors.first_name[0]);
                        }
                        if (errors.email) {
                            $('#update_email_error').text(errors.email[0]);
                        }
                        if (errors.password) {
                            $('#update_password_error').text(errors.password[0]);
                        }
                        if (errors.phone) {
                            $('#update_phone_error').text(errors.phone[0]);
                        }
                    } else {
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        $(document).on("click", ".delete-user-btn", function() {
            var userId = $(this).attr("data-user-id");
            var url = $(this).attr("data-route");
            $.confirm({
                title: 'Are you sure!',
                theme: 'dark',
                content: 'User will be completely deleted!',
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
                                loadPageData();
                                setTimeout(() => {
                                    showToastr("success", "User Deleted", res.message);
                                }, 1000);
                                // Optionally remove the user row from the table or refresh the page
                                if (res.success == false) {
                                    showToastr("error", "Error Occured!", res.message);
                                }
                            },
                            error: function(res) {
                                showToastr("error", "Error", "There was an error deleting the user.");
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
        // edit User


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
                    url: "{{route('users.index')}}",
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
                    // {
                    //     data: 'checkbox',
                    //     name: 'checkbox',
                    //     orderable: false,
                    //     // width: "78px",

                    // },
                    {
                        data: 'name',
                        name: 'name',
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
                        data: 'role',
                        name: 'role',
                        orderable: false,
                        // width: "78px",

                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        // width: "78px",

                    },
                ]
            });
        }
    });
</script>