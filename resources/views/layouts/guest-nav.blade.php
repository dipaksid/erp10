<div id="app">
    <nav class="navbar sticky-top navbar-expand-sm navbar-light bg-white mb-4">
        <div class="container">
            <!-- Branding Image -->
            <header class="sticky-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <strong>{{ config('app.name', 'ERP') }}</strong>
                </a>
            </header>
            <!-- Collapsed Hamburger -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                </ul>

            </div>
        </div>
    </nav>
</div>
@yield('content')
