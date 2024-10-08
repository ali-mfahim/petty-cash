<script>
    $(document).ready(function() {
        loadPageData()

        if ($("#add-new-category-btn").hasClass("disabled")) {
            $("#add-new-category-btn").removeClass("disabled")
        }


        // create category
        $(document).on("click", "#add-new-category-btn", function() {
            $("#error_message").html("");
            $("#success_message").html("");
            $("#title_error").html("");
            $("#status_error").html("")
            $("#createCategoryForm")[0].reset();
            // Reset the file input and thumbnail
            $('#image').val('');
            $('#thumbnail').attr('src', "{{asset('upload-icon.png')}}");
            $("#createCategoryModal").modal("show");
        });


        // image upload
        // for create form
        $(document).on("click", "#thumbnail", function() {
            $('#image').click();
        });
        $(document).on("change", "#image", function(event) {
            var reader = new FileReader();


            // Show the spinner
            if ($("#image_spinner").hasClass("d-none")) {
                $("#image_spinner").removeClass("d-none")
            }

            reader.onload = function(e) {

                // Hide the spinner
                setTimeout(() => {
                    $('#thumbnail').attr('src', e.target.result);
                    if (!$("#image_spinner").hasClass("d-none")) {
                        $("#image_spinner").addClass("d-none")
                    }
                }, 1000);
            }

            // If an error occurs, hide the spinner
            reader.onerror = function() {
                setTimeout(() => {
                    if (!$("#image_spinner").hasClass("d-none")) {
                        $("#image_spinner").addClass("d-none")
                    }
                }, 1000);
            }

            reader.readAsDataURL(event.target.files[0]);
        });
        // for create form



        // for update form
        $(document).on("click", "#update_thumbnail", function() {
            $('#update_image').click();
        });
        $(document).on("change", "#update_image", function(event) {
            var reader = new FileReader();


            // Show the spinner
            if ($("#update_image_spinner").hasClass("d-none")) {
                $("#update_image_spinner").removeClass("d-none")
            }

            reader.onload = function(e) {

                // Hide the spinner
                setTimeout(() => {
                    $('#update_thumbnail').attr('src', e.target.result);
                    if (!$("#update_image_spinner").hasClass("d-none")) {
                        $("#update_image_spinner").addClass("d-none")
                    }
                }, 1000);
            }

            // If an error occurs, hide the spinner
            reader.onerror = function() {
                setTimeout(() => {
                    if (!$("#update_image_spinner").hasClass("d-none")) {
                        $("#update_image_spinner").addClass("d-none")
                    }
                }, 1000);
            }

            reader.readAsDataURL(event.target.files[0]);
        });
        // for update form
        // image upload

        $('#createCategoryForm').submit(function(event) {
            event.preventDefault();
            $("#error_message").html("");
            $("#success_message").html("");
            $("#title_error").html("");
            var title = $("#title").val();
            if (!title) {
                $("#title_error").html("Please insert title")
            }


            if (!title) {
                return false;
            }

            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('categories.store') }}",
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
                        $("#createCategoryModal").modal("hide");
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
                        if (errors.title) {
                            $('#title_error').text(errors.title[0]);
                        }
                    } else {
                        // Handle other types of errors (500, 404, etc.)
                        console.log('An error occurred:', xhr);
                    }
                }
            });
        });
        // create category

        // edit category
        $(document).on("click", ".edit-category-btn", function() {
            $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
            $("#editCategoryModalContent").html($modalSpinner);

            $("#error_message").html("");
            $("#success_message").html("");
            $("#category_name").html("")

            var category_id = $(this).attr("data-category-id");
            if (!category_id) {
                showToastr("error", "Error Occured", "Something went wrong with this category")
                return false
            }
            $("#editCategoryModal").modal("show");
            $.ajax({
                url: "{{route('categories.getEditCategoryModalContent')}}",
                data: {
                    category_id: category_id,
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.data.view) {
                        $("#editCategoryModalContent").html(res.data.view);
                    }
                    if (res.data.title) {
                        $("#title").html(res.data.title)
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });
        });
        $(document).on("submit", "#editCategoryForm", function(event) {
            event.preventDefault();

            // Clear previous error messages
            $("#update_title_error").html("");

            // Collect form data
            var title = $("#update_title").val();
            if (!title) {
                $("#update_title_error").html("Please insert first name");
            }
            if (!title) {
                return false;
            }
            var url = $("#update-category-route").val();
            var formData = new FormData(this);
            console.log(formData)
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
            });
            $.ajax({
                type: 'POST', // Use POST for updating
                url: url, // Ensure you pass the category ID to update
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
                        $("#editCategoryModal").modal("hide");
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
        // edit category


        // delete category
        $(document).on("click", ".delete-category-btn", function() {
            var userId = $(this).attr("data-category-id");
            var url = $(this).attr("data-route");
            $.confirm({
                title: 'Are you sure!',
                theme: 'supervan',
                content: 'Category will be completely deleted!',
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
                                        showToastr("success", "Category Deleted", res.message);
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
        // delete category





        // view category description
        $(document).on("click", ".view-description-btn", function() {
            $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
            var categoryId = $(this).attr("data-category-id");
            $.ajax({
                url: "{{route('categories.viewDescription')}}",
                type: 'GET',
                data: {
                    category_id: categoryId,
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.success == true) {
                        $("#viewCategoryModal").modal("show")
                        if (res.data.description) {
                            $("#viewCategoryModalContent").html(res.data.description)
                        }
                    }
                },
                error: function(res) {
                    showToastr("error", "Error", res.message);
                    console.log(res);
                }
            });
        });
        // view category description

        setTimeout(() => {
            
        }, timeout);
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
                    url: "{{route('categories.index')}}",
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
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        // width: "78px",

                    },
                    {
                        data: 'description',
                        name: 'description',
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