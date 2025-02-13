<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3 flex justify-between">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
            </ol>
            <h6 class="font-weight-bolder mb-0" id="breadcrumb"></h6>
        </nav>
        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <a href="javascript:;"" class="text-secondary text-sm"
                    onclick="this.closest('form').submit()">Logout</a>
            </form>
        </div>
    </div>
</nav>
