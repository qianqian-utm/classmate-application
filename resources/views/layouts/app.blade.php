<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        /* Toggle switch style */
/* Toggle switch style */
.switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #4CAF50; /* Green for active state */
}

input:checked + .slider:before {
    transform: translateX(18px);
}

/* Disabled state */
input:disabled + .slider {
    cursor: not-allowed; /* Prevent interaction */
}

button:disabled {
    cursor: not-allowed ;
}

.badge {
    background-color: #43498a;
    color: white;
    padding: 4px 8px;
    text-align: center;
    border-radius: 5px;
    font-size: 0.9em;
    margin: 0 2px;

    &.badge-lab {
        background-color: #2b914a;
    }

    &.badge-individual {
        background-color: #2b6091;
    }

    &.badge-group_project {
        background-color: #916d2b;
    }

    &.badge-quiz {
        background-color: #2b914a;
    }

    &.badge-midterm {
        background-color: #2b6091;
    }

    &.badge-final {
        background-color: #916d2b;
    }
}





    </style>
</head>
<body>
    <div id="app">
        <main class="">
            <div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:block; width:20%;" id="mySidebar">
                <div class=" d-flex justify-content-end">
                    <button class="btn btn-secondary" onclick="w3_close()">X</button>
                </div>
                <a href="{{ url('/') }}">
                    <img class="mb-3" src="{{ asset('images/classmatelogo.png') }}" alt="Logo" style="width: 80px; height: auto; display: block; margin:  auto;">
                </a>
                @if(auth()->check() && ( auth()->user()->role == '1' ))
                    <a href="{{ url('/') }}" class="w3-bar-item w3-button">Users</a>
                    <a href="{{ route('groups.index') }}" class="w3-bar-item w3-button">Groups</a>
                    <a href="{{ route('subjects.index') }}" class="w3-bar-item w3-button">Subjects</a>
                @endif

                <a href="{{ route('notification.index') }}" class="w3-bar-item w3-button">Notification</a>
                <a href="{{ route('tt.index') }}" class="w3-bar-item w3-button">Timetable</a>
                <a href="{{ route('chatbot.index') }}" class="w3-bar-item w3-button">Chatbot</a>
                <a href="#" class="w3-bar-item w3-button">Settings</a>
            </div>

            <div class="" id="main" style="margin-left: 20%;">
                <div class="">
                    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                        <div class="">
                            <button id="openNav" class="w3-button " onclick="w3_open()" style="display:none;">&#9776;</button>
                        </div>
                        <div class="container">
                            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                                <img src="{{ asset('images/classmatelogo.png') }}" alt="Logo" style="width: 50px; height: auto; display: block; margin:  auto;">

                                ClassMate
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <!-- Left Side Of Navbar -->
                                <ul class="navbar-nav me-auto">

                                </ul>

                                <!-- Right Side Of Navbar -->
                                <ul class="navbar-nav ms-auto">
                                    <!-- Authentication Links -->
                                    @guest
                                        @if (Route::has('login'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                            </li>
                                        @endif

                                        @if (Route::has('register'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                            </li>
                                        @endif
                                    @else
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                {{ Auth::user()->name }}
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </li>
                                    @endguest
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                @yield('content')
            </div>
        </main>
    </div>


    <script>
function w3_open() {
    document.getElementById("main").style.marginLeft = "20%";
    document.getElementById("mySidebar").style.width = "20%";
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("openNav").style.display = 'none';
}
function w3_close() {
    document.getElementById("main").style.marginLeft = "0";
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("openNav").style.display = "inline-block";
}
</script>
@stack('scripts')
</body>
</html>
