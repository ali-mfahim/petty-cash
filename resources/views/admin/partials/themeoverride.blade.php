<style>
    /* Colors used overall */
    /* #0c243c */
    /* #17334e */
    /* #091928 */
    /* #b9b9b9 */
    /* #163551 */
    /* #434546 */
    /* #081d30 */
    /* #1f3e5e */
    /* #13223d */
    /* #f1f1f1 */
    /* Colors used overall */


    .navbar-header {
        background: #0c243c !important
    }

    .main-menu.menu-dark .navigation>li.active>a {
        background: linear-gradient(118deg, #0c243c, rgb(12 10 37 / 70%));
        box-shadow: 0 0 10px 1px #93a6b9;
        color: #fff;
        font-weight: 400;
        border-radius: 4px;
    }

    .main-menu.menu-dark .navigation>li ul .active {
        background: linear-gradient(118deg, #0c243c, rgb(12 10 37 / 70%));
        box-shadow: 0 0 10px 1px #7a8189;
        border-radius: 4px;
        z-index: 1;
    }
    .main-menu-content{
        background-color: #0c243c !important; 
    }
    #main-menu-navigation {
        background-color: #0c243c !important
    }
    .dark-layout .main-menu-content .navigation-main .nav-item .menu-content {
        background-color: #17334e;
    }

    .dark-layout .main-menu-content .navigation-main .sidebar-group-active .menu-content {
        background-color: #17334e;
    }

    .dark-layout .main-menu-content .navigation-main li a {
        color: #ffffff !important;
    }

    .dark-layout .header-navbar {
        background-color: #0c243c;
    }

    .header-navbar.navbar-shadow {
        /* box-shadow: 0 0px 13px 2px rgb(188 188 188 / 10%); */
        box-shadow: 0px;
    }


    .dark-layout body {
        color: #ffffff;
        background-color: #091928;
    }


    .dark-layout .card {
        background-color: #0c243c;
        box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.24);
    }

    .dark-layout .table:not(.table-dark):not(.table-light) thead:not(.table-dark) th,
    .dark-layout .table:not(.table-dark):not(.table-light) tfoot:not(.table-dark) th {
        background-color: #091928;
    }


    .dark-layout .border,
    .dark-layout .border-top,
    .dark-layout .border-end,
    .dark-layout .border-bottom,
    .dark-layout .border-start {
        border-color: #091928 !important;
    }


    .dark-layout input.form-control:not(:focus),
    .dark-layout select.form-select:not(:focus),
    .dark-layout textarea.form-control:not(:focus) {
        border-color: #091928;
    }


    .dark-layout input.form-control,
    .dark-layout select.form-select,
    .dark-layout textarea.form-control {
        background-color: #091928;
        color: #ffffff;
    }


    .form-select:focus {
        border-color: #091928;
        outline: 0;
        box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
    }

    .form-control:focus {
        color: #f3f2f4;
        background-color: #fff;
        border-color: #b9b9b9;
        outline: 0;
        box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
    }

    .btn-primary {
        border-color: #163551 !important;
        background-color: #163551 !important;
        color: #fff !important;
    }

    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary.active {
        color: #fff;
        background-color: #163551 !important;
    }



    .btn-primary:hover:not(.disabled):not(:disabled) {
        box-shadow: 0 0px 15px 0px #434546;
    }

    .dark-layout .nav-pills .nav-item .nav-link.active {
        color: #fff;
        background-color: #0c243c;
    }

    .nav-pills .nav-link.active {
        border-color: #0f2d4b;
        box-shadow: 0 4px 18px -4px rgb(98 98 99 / 65%);
    }

    .dark-layout .table.table-hover tbody tr:hover {
        --bs-table-accent-bg: #081d30;
    }

    .dark-layout .dropdown-menu {
        background-color: #163551;
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.24);
    }

    .dark-layout .dropdown-menu .dropdown-item:hover,
    .dark-layout .dropdown-menu .dropdown-item:focus {
        background: rgb(255 255 255 / 12%);
        color: white;
    }


    .bg-primary {
        --bs-bg-opacity: 1;
        background-color: rgb(22 53 81) !important;
    }


    .dark-layout .pagination:not([class*='pagination-']) .page-item.active .page-link {
        background-color: #163551;
    }

    .dark-layout .pagination:not([class*='pagination-']) .page-item .page-link:hover {
        color: #7b797f;
    }


    .dark-layout .modal .modal-content,
    .dark-layout .modal .modal-body,
    .dark-layout .modal .modal-footer {
        background-color: #0c243c;
        border-color: #0c243c;
    }

    .dark-layout .modal .modal-header .btn-close {
        text-shadow: none;
        background-color: #1f3e5e !important;
        color: white;
        box-shadow: 0 3px 8px 0 rgba(11, 10, 25, 0.49) !important;
        background-image: url(data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23b4b7bd'><path d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/></svg>);

    }

    select option {
        background-color: #13223d;
        /* Change this to your desired color */
        color: white;
        /* Optional: Change the text color */
    }

    .jconfirm.jconfirm-supervan .jconfirm-bg {
        background-color: rgb(14 24 39 / 95%);
    }


    html .pace .pace-progress {
        background: #ffffff;
    }


    * {
        scrollbar-width: thin;
        scrollbar-color: #0c243c #f1f1f1;
    }


    .dark-layout .header-navbar .navbar-container .nav .nav-item.nav-search .search-input.open {
        background-color: #0c243c;
    }

    .dark-layout .header-navbar .navbar-container .nav .nav-item .search-list {
        background-color: #1d2a37;
    }

    .form-check-input:checked {
        background-color: #53687c;
        border-color: #53687c;
    }


    /* emails left sidebar */
    .dark-layout .content-area-wrapper .sidebar .sidebar-content {
        background-color: #0c243c !important;
    }


    .email-application .content-area-wrapper .sidebar .list-group .list-group-item.active {
        border-color: white;
    }



    .email-application .content-area-wrapper .sidebar .list-group .list-group-item:hover,
    .email-application .content-area-wrapper .sidebar .list-group .list-group-item:focus,
    .email-application .content-area-wrapper .sidebar .list-group .list-group-item.active {
        background: transparent;
        color: #ffffff;
    }


    .dark-layout .list-group .list-group-item:not([class*='list-group-item-']),
    .dark-layout .list-group .list-group-item.list-group-item-action:not(.active):not(:active) {
        background-color: #0c243c;
        border-color: #0c243c;
    }

    /* emails left sidebar */

    /* emails right side bar  */
    .dark-layout .content-area-wrapper .app-fixed-search {
        background-color: #0c243c !important;
        border-color: #0c243c !important;
    }

    dark-layout .email-application .content-area-wrapper .email-app-list .app-action {
        border-color: #605a5a;
        background-color: #0c243c;
    }

    .dark-layout .email-application .content-area-wrapper .email-app-list .email-user-list .mail-read {
        background-color: #061728;
    }

    .dark-layout .email-application .content-area-wrapper .email-app-list .email-user-list .user-mail {
        border-color: #061728;
        /* background-color: #283046; */
    }


    .dark-layout .email-application .content-area-wrapper .email-app-list .app-action {
        border-color: #56585a;
        background-color: #0c243c;
    }

    .dark-layout .email-application .content-area-wrapper .email-app-list .email-user-list .user-mail {
        border-color: #3b4253;
        background-color: #0c243c;
    }

    .dark-layout .email-application .content-area-wrapper .email-app-details .email-detail-header {
        background-color: #0c243c;
        border-color: #3b4253;
    }

    .badge.badge-light-primary {
        background-color: rgba(115, 103, 240, 0.12);
        color: #ffffff !important;
    }




    .dark-layout .email-application .content-area-wrapper .email-app-details .email-scroll-area {
        background-color: #091928;
    }

    .dark-layout .ql-container {
        border-color: #0c243c !important;
        background-color: #0c243c;
    }


    .dark-layout .quill-toolbar,
    .dark-layout .ql-toolbar {
        background-color: #0c243c;
        border-color: #504a4a !important;
    }

    .dark-layout .quill-toolbar .ql-picker-options,
    .dark-layout .quill-toolbar .ql-picker-label,
    .dark-layout .ql-toolbar .ql-picker-options,
    .dark-layout .ql-toolbar .ql-picker-label {
        background-color: #293a4c;
    }

    .ql-toolbar .ql-formats .ql-picker-label:hover,
    .ql-toolbar .ql-formats .ql-picker-label:focus,
    .ql-toolbar .ql-formats button:hover,
    .ql-toolbar .ql-formats button:focus {
        color: #ffffff !important;
    }

    .select2-container--classic .select2-results__option[aria-selected='true'],
    .select2-container--default .select2-results__option[aria-selected='true'] {
        background-color: #0c243c !important;
        color: #fff !important;
    }

    .dark-layout .select2-container .select2-selection--multiple .select2-selection__choice {
        background: rgb(255 255 255 / 12%) !important;
        color: white !important;
        border: none;
    }

    .select2-primary .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #7367f0 !important;
        border-color: #ffffff !important;
    }


    .select2-primary .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #24476a !important;
        border-color: #ffffff !important;
    }




    /* emails right side bar  */



    /* timeline */
    .timeline .timeline-item .timeline-point.timeline-point-indicator {
        left: -0.412rem;
        top: 0.07rem;
        height: 12px;
        width: 12px;
        border: 0;
        background-color: #ffffff;
    }

    /* timeline */
</style>