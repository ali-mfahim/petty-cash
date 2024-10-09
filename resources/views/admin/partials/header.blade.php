<!-- BEGIN: Header-->
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-dark navbar-shadow container-fluid">
    <div class="navbar-container d-flex content">

        <ul class="nav navbar-nav align-items-center ms-auto">

            <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                <div class="search-input">

                    <div class="search-input-icon"><i data-feather="search"></i></div>
                    <input class="form-control input" type="text" id="search_input" placeholder="Explore {{projectSettings()->title ?? ''}}..." tabindex="-1" data-search="search">
                    <div class="search-input-close"><i data-feather="x"></i></div>
                    <ul class="search-list search-list-main"></ul>
                </div>
            </li>
            <!-- <li>
                <a class="nav-link" href="{{route('emails.index')}}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Email" aria-label="Email">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail ficon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </a>
            </li> -->

            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{getUserName(getUser())}}</span><span class="user-status">{{getMyRole(getUser()->id) }}</span></div><span class="avatar">
                        <img class="round" src="{{asset('admin/assets/app-assets/images/portrait/small/avatar-s-11.jpg')}}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{route('profiles.index')}}"><i class="me-50" data-feather="user"></i> Profile</a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="me-50" data-feather="power"></i> {{ __('Logout') }}</a>
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


@push("scripts")
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
            url: "{{route('search.global')}}",
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