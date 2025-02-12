@extends('admin.layouts.master')
@push('title', $title ?? '')
@section('content')
    <section>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title ?? '' }} - Admin Blade</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-datatable">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer ">
                                <table
                                    class="datatables-users   table-hover table border-top dataTable no-footer dtr-column data_table"
                                    id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Paid & Submit By</th>
                                            <th scope="col">Food Item</th>
                                            <th scope="col">Divided In</th>
                                            <th scope="col">Total </th>
                                            <th scope="col">Per head</th>
                                            <th scope="col">Entry Date</th>
                                            <th scope="col">Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" name="" id="page_url"
        value="{{ route('entries.json', ['month' => $month, 'year' => $year, 'user_id' => $user_id]) }}">
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            loadPageData()
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
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
                    url: $("#page_url").val(),
                },
                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,

                    },
                    {
                        data: 'paid_by',
                        name: 'paid_by',
                        orderable: false,

                    },
                    {
                        data: 'food_item',
                        name: 'food_item',
                        orderable: false,

                    },


                    {
                        data: 'divided_in',
                        name: 'divided_in',
                        orderable: false,

                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        orderable: false,

                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        orderable: false,

                    },
                    // {
                    //     data: 'transaction_type',
                    //     name: 'transaction_type',
                    //     orderable: false,

                    // },
                    {
                        data: 'entry_date',
                        name: 'entry_date',
                        orderable: false,

                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,

                    },



                ]
            });
        }
    </script>
@endpush
