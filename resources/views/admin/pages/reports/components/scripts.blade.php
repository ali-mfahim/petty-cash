<script>
    $(document).ready(function() {
        loadPageData();
    });

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
                url: "{{route('reports.details' , $store->slug)}}",
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
                    data: 'order_id',
                    name: 'order_id',
                    orderable: false,
                },
                {
                    data: 'customer_id',
                    name: 'customer_id',
                    orderable: false,
                },
                {
                    data: 'tags',
                    name: 'tags',
                    orderable: false,
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
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
</script>