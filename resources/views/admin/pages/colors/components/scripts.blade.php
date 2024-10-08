<script>
    $(document).ready(function() {
        loadPageData()
        if ($("#add-new-color-btn").hasClass("disabled")) {
            $("#add-new-color-btn").removeClass("disabled")
        }
        // create color
        $(document).on("click", "#add-new-color-btn", function() {
            $("#error_message").html("");
            $("#success_message").html("");
            $("#name_error").html("");
            $("#code_error").html("");
            $("#createColorForm")[0].reset();
            $("#createColorModal").modal("show");
        });
        $('#createColorForm').submit(function(event) {
            event.preventDefault();
            $("#error_message").html("");
            $("#success_message").html("");
            $("#name_error").html("");
            $("#code_error").html("");
            var name = $("#name").val();
            if (!name) {
                $("#name_error").html("Please type color name")
            }
            var code = $("#code").val();
            if (!code) {
                $("#code_error").html("Please pickup a color code")
            }

            if (!name || !code) {
                return false;
            }

            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('colors.store') }}",
                data: formData,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    if (response.success == true) {

                        $("#success_message").html(response.message);
                        loadPageData();
                        $("#createColorModal").modal("hide");
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
                        if (errors.name) {
                            $('#name_error').text(errors.name[0]);
                        }
                        if (errors.code) {
                            $('#code_error').text(errors.code[0]);
                        }

                    } else {
                        // Handle other types of errors (500, 404, etc.)
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        // create color

        // edit color
        $(document).on("click", ".edit-color-btn", function() {
            $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
            $("#editColorModalContent").html($modalSpinner);

            $("#error_message").html("");
            $("#success_message").html("");

            var id = $(this).attr("data-color-id");
            if (!id) {
                showToastr("error", "Error Occured", "Something went wrong with this color")
                return false
            }
            $("#editColorModal").modal("show");
            $.ajax({
                url: "{{route('colors.getEditColorModalContent')}}",
                data: {
                    id: id,
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.data.view) {
                        $("#editColorModalContent").html(res.data.view);
                    }

                },
                error: function(res) {
                    console.log(res);
                }
            });
        });
        $(document).on("submit", "#editColorForm", function(event) {
            event.preventDefault();

            // Clear previous error messages
            $("#update_name_error").html("");

            // Collect form data
            var name = $("#update_name").val();
            if (!name) {
                $("#update_name_error").html("Please type in color name");
            }
            if (!name) {
                return false;
            }
            var url = $("#update-color-route").val();
            var formData = $(this).serialize();
            $.ajax({
                type: 'PUT',
                url: url,
                data: formData,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    if (response.success == true) {
                        $("#success_message").html(response.message);
                        loadPageData();
                        $("#editColorModal").modal("hide");
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

                    } else {
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        $(document).on("click", ".delete-color-btn", function() {
            var colorId = $(this).attr("data-color-id");
            var url = $(this).attr("data-route");
            $.confirm({
                title: 'Are you sure!',
                theme: 'supervan',
                content: 'Color will be completely deleted!',
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
                                showToastr("warning", "Please Wait", "while we are deleting the color");
                            },
                            success: function(res) {
                                loadPageData();
                                setTimeout(() => {
                                    showToastr("success", "Success", res.message);
                                }, 1000);
                                // Optionally remove the user row from the table or refresh the page
                                if (res.success == false) {
                                    showToastr("error", "Error Occured!", res.message);
                                }
                            },
                            error: function(res) {
                                showToastr("error", "Error", "There was an error deleting the color.");
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
        // edit color


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
                    url: "{{route('colors.index')}}",
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
                        data: 'code',
                        name: 'code',
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
                ]
            });
        }
    });
</script>