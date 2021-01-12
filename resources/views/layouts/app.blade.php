<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <?php $tribe = session('tribe'); ?>
    @if ($tribe)
        <title>{{ $tribe->name . ' | ' . config('app.name', 'Laravel') }}</title>
    @else
        <title>{{ config('app.name', 'Laravel') }}</title>
    @endif

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body>
<div id="app">
    <div class="uk-background-primary uk-light">
        <nav class="uk-navbar-container uk-navbar-transparent">
            <div class="uk-container">
                <div class="uk-navbar" data-uk-navbar>
                    <div class="uk-navbar-left">
                        <a class="uk-navbar-item uk-logo" href="/">{{ config('app.name', 'Laravel') }}</a>
                    </div>
                    <div class="uk-navbar-right">
                        <ul class="uk-navbar-nav">
                            <li>
                                <a href="https://github.com/mindyourteam/stimmungskurve/wiki">{{ __('About') }}</a>
                            </li>
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li>
                                        <a href="{{ route('login') }}">{{ __('Log In') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li>
                                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li>
                                    <a href="#">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="uk-navbar-dropdown">
                                        <ul class="uk-nav uk-navbar-dropdown-nav">
                                            <li>
                                                <a href="{{ route('tribe.index') }}">Gruppen</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    {{ __('Log Out') }}
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                      style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <main data-uk-height-viewport="expand: true">
        @yield('content')
    </main>

    <footer class="uk-section uk-section-xsmall uk-section-secondary">
        <div class="uk-container">
            <div class="uk-grid uk-text-center uk-text-left@s uk-flex-middle" data-uk-grid>
                <div class="uk-text-small uk-text-muted uk-width-1-3@s">
                    Gebaut von
                    <a target="_blank" href="https://schettler.net/olav/">Dr. Olav Schettler</a>,
                    inspiriert von <a rel="nofollow" target="_blank" href="https://www.zeit.de/gesellschaft/2017-03/stimmung-wie-geht-es-uns">Zeit Online</a>
                </div>
                <div class="uk-text-center uk-width-1-3@s">
                    <a target="_blank" rel="nofollow" href="https://twitter.com/stimmungskurve"
                       class="uk-icon-button uk-margin-small-right" data-uk-icon="twitter"></a>
                    <a target="_blank" rel="nofollow" href="https://github.com/mindyourteam/stimmungskurve"
                       class="uk-icon-button" data-uk-icon="github"></a>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
