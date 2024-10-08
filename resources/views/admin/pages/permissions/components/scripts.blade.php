<script>
    $(document).ready(function() {
        loadPageData()
        if ($("#add-new-permission-btn").hasClass("disabled")) {
            $("#add-new-permission-btn").removeClass("disabled")
        }

        // create Permission
        $(document).on("click", "#add-new-permission-btn", function() {
            $("#modalPermissionName_error").html("");
            $("#display_names_error").html("");
            $("#addNewPermissionForm")[0].reset();
            $("#addNewPermissionModal").modal("show");
        });
        $('#addNewPermissionForm').submit(function(event) {
            event.preventDefault();
            $("#modalPermissionName_error").html("");
            $("#display_names_error").html("");



            var permissionName = $("#modalPermissionName").val();
            if (!permissionName) {
                $("#modalPermissionName_error").html("Please insert a permission group name");
                return false;

            }
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('permissions.store') }}",
                data: formData,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    console.log(response)
                    if (response.success == true) {
                        loadPageData();
                        $("#addNewPermissionModal").modal("hide");
                        showToastr("success", "Success", response.message)



                    }
                    if (response.success == false) {
                        showToastr("error", "Error Occured!", response.message)
                    }
                },
                error: function(xhr, status, error) {

                    hideFancyBox()
                    if (xhr.status === 422) {

                        var errors = xhr.responseJSON.errors;
                        if (errors.modalPermissionName) {
                            $('#modalPermissionName_error').text(errors.modalPermissionName[0]);
                        }
                        if (errors.display_names) {
                            $('#display_names_error').text(errors.display_names[0]);
                        }

                    } else {
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        // create Permission


        // add permission to existing group
        $(document).on("click", ".add-permission-in-group-btn", function() {
            var permissionId = $(this).attr("data-permission-id");
            var groupName = $(this).attr("data-group-name");
            $("#permissionGroupId").val(permissionId)
            $("#permissionGroupNameAppend").html(groupName)
            $("#addPermissionToGroupModal").modal("show");
            $("#addPermissionToGroupnForm")[0].reset();
        });
        // add permission to existing group
        $(document).on("submit", "#addPermissionToGroupnForm", function(event) {

            event.preventDefault()
            $('#modalPermissionName_error2').html("");
            $('#permissionGroupId_error').html("");
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('permissions.addPermissionInTheGroup') }}",
                data: formData,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    console.log(response)

                    if (response.success == true) {

                        loadPageData();
                        $("#addPermissionToGroupModal").modal("hide");
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
                    if (xhr.status == 422) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.modalPermissionName) {
                            $('#modalPermissionName_error2').html(errors.modalPermissionName[0]);
                        }
                        if (errors.permissionGroupId) {
                            $('#permissionGroupId_error').html(errors.permissionGroupId[0]);
                        }
                    }


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
                    url: "{{route('permissions.index')}}",
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
                        data: 'permissions',
                        name: 'permissions',
                        orderable: false,
                        // width: "78px",

                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
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