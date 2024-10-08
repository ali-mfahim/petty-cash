<script> 
    // add roles
    $(document).on("click", "#add-new-role-btn", function() {
        $("#error_message").html("");
        $("#success_message").html("");
        $("#roleName_error").html("");
        $("#addRoleForm")[0].reset();
        $("#addRoleModal").modal("show");
    });
    $(document).on("submit", "#addRoleForm", function(event) {
        event.preventDefault();

        $("#roleName_error").html("");

        $("#success_message").html("");

        var role_name = $("#roleName").val();
        if (!role_name) {
            $("#roleName_error").html("Please insert role name");
            return false;
        }

        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('roles.store') }}",
            data: formData,
            beforeSend: function() {
                showFancyBox()
            },
            success: function(response) {
                hideFancyBox()
                if (response.success == true) {
                    $("#success_message").html(response.message);
                    $("#addRoleModal").modal("hide");
                    showToastr("success", "Success", response.message)
                }
                if (response.success == false) {
                    $("#error_message").html(response.message);
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
                    if (errors.roleName) {
                        $('#roleName_error').text(errors.roleName[0]);
                    }

                } else {
                    // Handle other types of errors (500, 404, etc.)
                    console.log('An error occurred:', xhr);
                }
            }
        });

    });
    // add roles

    // edit role
    $(document).on("click", ".edit-role-modal-btn", function() {
        $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
        $("#editRoleModalContent").html($modalSpinner);
        $("#editRoleModal").modal("show");
        var roleId = $(this).attr("data-role-id");
        var url = $(this).attr("data-update-url");

        $.ajax({
            url: url,
            data: {
                roleId: roleId,
            },
            beforeSend: function() {},
            success: function(res) {
                if (res.data.view) {
                    $("#editRoleModalContent").html(res.data.view);
                }
            },
            error: function(res) {
                console.log(res)
            }

        });




    });
    $(document).on("submit", "#editRoleModalForm", function(event) {
        console.log("form submit");
        event.preventDefault();
        var url = $("#update-role-route").val();
        if (url) {

            // Clear previous error messages
            $("#update_roleName_error").html("");

            $("#success_message").html("");

            var role_name = $("#update_roleName").val();
            if (!role_name) {
                $("#update_roleName_error").html("Please insert role name");
                return false;
            }

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
                        $("#editRoleModal").modal("hide");
                        showToastr("success", "Success", response.message)
                        setTimeout(() => {
                            location.reload()
                        }, 500);
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
        }

    });
    // edit role

</script>