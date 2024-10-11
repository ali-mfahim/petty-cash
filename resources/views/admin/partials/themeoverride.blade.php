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


    /* 
    Temp 1
    #181818
    #303335
    #707AFF
    #3A439B
    #141923

    Temp 2
    #0E0A19
    #191135
    #32D4D7
    #0F8484
    #351E24

    Temp 3
    #0B0B0B
    #131313
    #FF1916
    #960000
    #141312 
    */

    :root {
        /* Main color */
        --color-primary: #131313;
        /* Secondary color */
        --color-secondary: #1a1b1d;
        /* Background color */
        --color-background: #0B0B0B;
        /* Gray color */
        --color-gray: #b9b9b9;
        /* Darker shade */
        --color-dark: #1e2124;
        /* Border color */
        --color-border: #434546;
        /* Deep color */
        --color-deep: #081d30;
        /* Blue color */
        --color-blue: #1f3e5e;
        /* Title color */
        --color-title: #13223d;
        /* Light color */
        --color-light: #f1f1f1;
    }

    html .pace .pace-progress {
        background: white;
    }


    * {
        scrollbar-width: thin;
        scrollbar-color: var(--color-primary) #f1f1f1;
    }

    .navbar-header {
        background: var(--color-primary) !important;
    }

    .main-menu.menu-dark .navigation>li.active>a {
        background: linear-gradient(118deg, var(--color-primary), rgb(30 29 34 / 70%));
        box-shadow: 0 0 10px 1px #2f2b2b;
        color: #fff;
        font-weight: 400;
        border-radius: 4px;
    }

    .main-menu.menu-dark .navigation>li ul .active {
        background: linear-gradient(118deg, var(--color-primary), rgb(17 17 22 / 70%));
        box-shadow: 0 0 10px 1px #2b2d2f;
        border-radius: 4px;
        z-index: 1;
    }

    .main-menu-content {
        background-color: var(--color-primary) !important;
    }

    #main-menu-navigation {
        background-color: var(--color-primary) !important
    }

    .dark-layout .main-menu-content .navigation-main .nav-item .menu-content {
        background-color: var(--color-secondary);
    }

    .dark-layout .main-menu-content .navigation-main .sidebar-group-active .menu-content {
        background-color: var(--color-secondary);
    }

    .dark-layout .main-menu-content .navigation-main li a {
        color: #ffffff !important;
    }



    .dark-layout .main-menu-content .navigation-main .nav-item.open>a {
        background-color: #252121 !important;
    }

    .dark-layout .header-navbar {
        background-color: var(--color-primary);
    }

    .dark-layout .header-navbar-shadow {
        background: linear-gradient(180deg, rgb(17 17 20 / 90%) 44%, rgb(26 26 26 / 43%) 73%, rgba(22, 29, 49, 0))
    }

    .header-navbar.navbar-shadow {
        box-shadow: 0 0px 13px 2px rgb(188 188 188 / 10%);
        box-shadow: 0px;
    }


    .dark-layout body {
        color: #ffffff;
        background-color: var(--color-background);
    }


    .dark-layout .card {
        background-color: var(--color-primary);
        box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.24);
    }

    .dark-layout .table:not(.table-dark):not(.table-light) thead:not(.table-dark) th,
    .dark-layout .table:not(.table-dark):not(.table-light) tfoot:not(.table-dark) th {
        background-color: var(--color-background);
    }


    .dark-layout .border,
    .dark-layout .border-top,
    .dark-layout .border-end,
    .dark-layout .border-bottom,
    .dark-layout .border-start {
        border-color: var(--color-background) !important;
    }


    .dark-layout input.form-control:not(:focus),
    .dark-layout select.form-select:not(:focus),
    .dark-layout textarea.form-control:not(:focus) {
        border-color: var(--color-background);
    }


    .dark-layout input.form-control,
    .dark-layout select.form-select,
    .dark-layout textarea.form-control {
        background-color: var(--color-background);
        color: #ffffff;
    }


    .form-select:focus {
        border-color: var(--color-background);
        outline: 0;
        box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
    }

    .form-control:focus {
        color: #f3f2f4;
        background-color: #fff;
        border-color: var(--color-gray);
        outline: 0;
        box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
    }

    .btn-primary {
        border-color: #920004 !important;
        background-color: #920004 !important;
        color: #fff !important;
    }


    .bg-primary {
        background-color: rgb(65 9 9) !important;
    }

    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary.active {
        color: #fff;
        background-color: #920004 !important;
    }



    .btn-primary:hover:not(.disabled):not(:disabled) {
        box-shadow: 0 0px 5px 0px #4b4b50;
    }

    .dark-layout .nav-pills .nav-item .nav-link.active {
        color: #fff;
        background-color: var(--color-primary);
    }

    .nav-pills .nav-link.active {
        border-color: #131313;
        box-shadow: 0 5px 32px -5px rgb(60 3 3 / 65%);
    }

    .dark-layout .table.table-hover tbody tr:hover {
        --bs-table-accent-bg: #181919;
    }

    .dark-layout .dropdown-menu {
        background-color: var(--color-dark);
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
        background-color: var(--color-dark);
    }

    .dark-layout .pagination:not([class*='pagination-']) .page-item .page-link:hover {
        color: #7b797f;
    }


    .dark-layout .modal .modal-content,
    .dark-layout .modal .modal-body,
    .dark-layout .modal .modal-footer {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }

    .dark-layout .modal .modal-header .btn-close {
        text-shadow: none;
        background-color: #29262a !important;
        color: white;
        box-shadow: 0 3px 8px 0 rgba(11, 10, 25, 0.49) !important;
        background-image: url(data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23b4b7bd'><path d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/></svg>);

    }


    /* Style the select box */
    select {
        /* background-color: #2f2f2f !important; */
        color: white;
        padding: 10px;
        font-size: 16px;
        /* border: 1px solid #555555 !important; */
        border-radius: 4px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        outline: none;
    }

    /* Style the options */
    select option {
        background-color: #2f2f2f !important;
        color: white;
    }

    /* Style the hover effect for options */
    select option:hover {
        background-color: #920004 !important;
    }

    /* Style the selected option */
    select option:checked {
        background-color: #555555 !important;
        color: white;
    }

    .jconfirm.jconfirm-supervan .jconfirm-bg {
        background-color: rgb(14 24 39 / 95%);
    }


    .form-check-primary .form-check-input:checked {
        background-color: #920004;
    }


    .dark-layout .header-navbar .navbar-container .nav .nav-item.nav-search .search-input.open {
        background-color: var(--color-primary);
    }

    .dark-layout .header-navbar .navbar-container .nav .nav-item .search-list {
        background-color: #1d2a37;
    }

    .dark-layout a {
        color: #f00000
    }

    .dark-layout a:hover {
        color: #ff2323
    }

    .form-check-input:not(:disabled):checked {
        box-shadow: 0 2px 4px 0 rgb(66 66 66 / 40%);
    }

    .form-check-input:checked {
        background-color: #4a4b4b;
        border-color: #4a4b4b;
    }


    /* emails left sidebar */
    .dark-layout .content-area-wrapper .sidebar .sidebar-content {
        background-color: var(--color-primary) !important;
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
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }

    .dark-layout input:-webkit-autofill,
    .dark-layout textarea:-webkit-autofill,
    .dark-layout select:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #242425 inset !important;
        -webkit-text-fill-color: #ffffff !important;
    }


    /* emails left sidebar */

    /* emails right side bar  */
    .dark-layout .content-area-wrapper .app-fixed-search {
        background-color: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
    }


    dark-layout .email-application .content-area-wrapper .email-app-list .app-action {
        border-color: #605a5a;
        background-color: var(--color-primary);
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
        background-color: var(--color-primary);
    }

    .dark-layout .email-application .content-area-wrapper .email-app-list .email-user-list .user-mail {
        border-color: #3b4253;
        background-color: var(--color-primary);
    }

    .dark-layout .email-application .content-area-wrapper .email-app-details .email-detail-header {
        background-color: var(--color-primary);
        border-color: #3b4253;
    }

    .badge.badge-light-primary {
        background-color: rgba(115, 103, 240, 0.12);
        color: #ffffff !important;
    }




    .dark-layout .email-application .content-area-wrapper .email-app-details .email-scroll-area {
        background-color: var(--color-background);
    }

    .dark-layout .ql-container {
        border-color: var(--color-primary) !important;
        background-color: var(--color-primary);
    }


    .dark-layout .quill-toolbar,
    .dark-layout .ql-toolbar {
        background-color: var(--color-primary);
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
        background-color: var(--color-primary) !important;
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



    .dark-layout #toast-container .toast {
        background-color: #4a0a0a;
        color: white !important;
    }

    .toast-success .toast-progress {
        background-color: white;
    }



    .dark-layout .header-navbar .navbar-container .nav .nav-item.nav-search .search-input.open .input {
        border-color: #161a1d;

    }

    .dark-layout .header-navbar .navbar-container .nav .nav-item .search-list {
        background-color: #4c4c4c;
    }
</style>