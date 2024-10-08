<script>
    $(document).ready(function() {
        loadPageData()
        $(document).on("click", ".view-customer-btn", function() {
            var customerId = $(this).attr("data-customer-id");
            var route = $(this).attr("data-route");
            $.ajax({
                url: route,
                method: "GET",
                beforeSend: function() {
                    $modalSpinner = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;"><span class="visually-hidden">Loading...</span></div></div>';
                    $("#viewCustomerModal").modal("show");
                    $("#viewCustomerModalBody").html($modalSpinner)
                    showFancyBox();
                },
                success: function(res) {
                    hideFancyBox()
                    if (res.success == true && res.data) {
                        $("#viewCustomerModalBody").html(res.data)
                    }
                    if (res.success == false) {

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
                    url: "{{route('customers.index')}}",
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
                        data: 'forms',
                        name: 'forms',
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