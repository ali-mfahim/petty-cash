<!-- BEGIN: Header-->
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-dark navbar-shadow container-fluid">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon "
                            data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav bookmark-icons" style="display: none">
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Email"><i class="ficon"
                            data-feather="mail"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat.html" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Chat"><i class="ficon"
                            data-feather="message-square"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Calendar"><i class="ficon"
                            data-feather="calendar"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Todo"><i class="ficon" data-feather="check-square"></i></a>
                </li>
            </ul>
            <ul class="nav navbar-nav" style="display: none">
                <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon text-warning"
                            data-feather="star"></i></a>
                    <div class="bookmark-input search-input">
                        <div class="bookmark-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0"
                            data-search="search">
                        <ul class="search-list search-list-bookmark"></ul>
                    </div>
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon"
                        data-feather="search"></i></a>
                <div class="search-input">

                    <div class="search-input-icon"><i data-feather="search"></i></div>
                    <input class="form-control input" type="text" id="search_input"
                        placeholder="Explore {{ projectSettings()->title ?? '' }}..." tabindex="-1"
                        data-search="search">
                    <div class="search-input-close"><i data-feather="x"></i></div>
                    <ul class="search-list search-list-main"></ul>
                </div>
            </li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span
                            class="user-name fw-bolder">{{ getUserName(getUser()) }}</span><span
                            class="user-status">{{ getMyRole(getUser()->id) }}</span></div><span class="avatar">
                        <img class="round"
                            src="{{ asset('admin/assets/app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                            alt="avatar" height="40" width="40"><span
                            class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('profiles.index') }}"><i class="me-50"
                            data-feather="user"></i> Profile</a>


                    <a href="javascript:;" class="dropdown-item generate-form-link" data-type="1"
                        data-user-id="{{ getUser()->id }}">
                        <i class="me-50" data-feather="file-text"></i> Petty Link </a>



                    {{-- <a href="javascript:;"  data-type="2" class="dropdown-item generate-form-link"
                        data-user-id="{{ getUser()->id }}">
                        <i class="me-50" data-feather="file-text"></i> Expense Link</a> --}}
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="me-50" data-feather="power"></i> {{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

    </div>
</nav>
<!--
<ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
            <div class="d-flex justify-content-start"><span class="me-75" data-feather="alert-circle"></span><span>No results found.</span></div>
        </a>
    </li>
</ul> -->
<!-- END: Header-->


@push('scripts')
    <script>
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
        $(document).on("keyup keydown", "#search_input", debounce(function() {
            var input = $(this).val();
            $.ajax({
                url: "",
                method: "GET",
                data: {
                    input: input
                },
                beforeSend: function() {
                    $('ul.search-list').empty()
                },
                success: function(response) {

                    if (response.success == true) {
                        if (response.data) {
                            $.each(response.data, function(key, value) {
                                $('ul.search-list').append(value);
                            });
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }, 300));
    </script>
@endpush
