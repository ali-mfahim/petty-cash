<script>
    $(document).ready(function() {
        loadPageData()

        $(document).on("click", ".download-excel", function() {
            var url = $("#full-page-url").val();
            var download = url + "?download=true";
            var downloadLink = document.createElement('a');
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.href = download;
            downloadLink.click();
            document.body.removeChild(downloadLink);
        });
        $(document).on("click", ".update-user-report-status", function() {
            var loader =
                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';

            var user_id = $(this).attr("data-user-id");
            var month = $(this).attr("data-month");
            var year = $(this).attr("data-year");
            $.ajax({
                url: "{{ route('entries.getUserReportStatus') }}",
                method: "GET",
                data: {
                    user_id: user_id,
                    month: month,
                    year: year,
                },
                beforeSend: function() {
                    $("#updateUserReportStatusModalContent").html(loader);
                    $("#updateUserReportStatusModal").modal("show")

                },
                success: function(res) {
                    $("#updateUserReportStatusModalContent").html(res.data.view);
                },
                error: function(xhr, status, error) {
                    console.log(xhr)
                    console.log(status)
                    console.log(error)
                }
            });
        });
        $(document).on("submit", "#updateUserReportStatusForm", function(event) {
            event.preventDefault();


            var errors = 0;
            $("#status_error").html("")
            $("#transaction_status_error").html("")
            $("#transaction_user_id_error").html("")
            $("#amount_error").html("");
            var user_id = $("#update-user-id").val()
            var status = $("#status").val()
            if (!status) {
                $("#status_error").html("Select status")
                var error = 1;
            } else {
                console.log(status)
            }
            var transaction_status = $("#transaction_status").val()
            if (!transaction_status) {
                $("#transaction_status_error").html("Select transaction status ")
                var error = 1;
            } else {
                console.log(transaction_status)
            }
            var transaction_user_id = $("#transaction_user_id").val()
            if (!transaction_user_id) {
                $("#transaction_user_id_error").html("Select transaction person")
                var error = 1;
            } else {
                console.log(transaction_user_id)
            }
            var amount = $("#amount").val()
            if (!amount || amount == 0) {
                $("#amount_error").html("Amount must be greater than 0");
                var error = 1;
            } else {
                console.log(amount)
            }

            if (error > 0) {
                return false;
            }
            var loader =
                ' <div class="spinner-border" role="status"  ><span class="visually-hidden">Loading...</span></div> ';

            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('entries.updateUserReportStatus') }}",
                data: formData,
                beforeSend: function() {
                    showFancyBox()
                },
                success: function(response) {
                    hideFancyBox()
                    if (response.success == true) {

                        $("#updateUserReportStatusModal").modal("hide");
                        showToastr("success", "Success", response.message)
                        $(".update-user-report-status-" + user_id).hide()
                        $(".update-user-report-status-btn-" + user_id).fadeIn()


                        // setTimeout(() => {
                        //     $(".update-user-report-status-" + user_id).html(
                        //         '<button class="btn btn-primary btn-sm">Sattled</button>'
                        //     )
                        // }, 1000);
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
                        if (errors.status) {
                            $('#status_error').text(errors.status[0]);
                        }
                        if (errors.transaction_status) {
                            $('#transaction_status_error').text(errors.transaction_status[
                                0]);
                        }
                        if (errors.transaction_user_id) {
                            $('#transaction_user_id_error').text(errors.transaction_user_id[
                                0]);
                        }
                        if (errors.amount) {
                            $('#amount_error').text(errors.amount[0]);
                        }
                    } else {
                        // Handle other types of errors (500, 404, etc.)
                        console.log('An error occurred:', xhr);
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
                    url: "{{ route('users.index') }}",
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
