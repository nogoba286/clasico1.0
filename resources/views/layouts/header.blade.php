<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Brand Logo -->
            <a class="navbar-brand" href="#">
                <img class="custom-logo" src="{{ asset('assets/image/logo.png') }}" alt="Logo">
            </a>
            <!-- Navbar Toggler (for mobile view) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <i class="fa fa-navicon"></i>
            </button>
            <!-- Collapsible Navbar -->
            <div class="collapse navbar-collapse" id="mynavbar">
                <div class="col-md-8 navbar-content">
                    <!-- Navigation Links (Centered) -->
                    <ul class="navbar-nav me-auto flex-wrap justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link" href="#">SPORTS BETTING</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">LIVE BETTING</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">CASINO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">LIVE CASINO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">LIVE GAMES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">VIRTUALS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">SPACEMAN</a>
                        </li>                        
                    </ul>
                    <button class="btn btn-outline-light auth-button">Login/Register</button>
                </div>
                <!-- Login & Register Section -->
                <div class="col-md-4 auth-form">
                @if(Auth::check())
                    <!-- Show when logged in -->
                    <div class="logged-in-info">
                        <span class="text-white">Welcome, {{ Auth::user()->username }}</span>
                        <span class="text-white">Balance: {{ Auth::user()->balance }}</span>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">Logout</button>
                        </form>
                    </div>
                @else
                    <!-- Show login form when not logged in -->
                    <form action="{{ route('login') }}" method="POST" class="row auth-form">
                        @csrf
                        <span class="span-input col-md-4">
                            <i class="fa fa-user input-icon"></i>
                            <input class="input-field" name="username" type="text" placeholder="Username" required />
                        </span>
                        <span class="span-input col-md-4">
                            <i class="fa fa-lock input-icon"></i>
                            <input class="input-field" name="password" type="password" placeholder="Password" id="passwordField" required />
                        </span>
                        <div class="col-md-4 d-flex">
                            <button class="btn btn-outline-warning" type="submit">Login</button>
                            <a href="{{ route('register') }}" class="btn btn-danger ms-2">Register</a>
                        </div>

                        <!-- Language Selector -->
                        <div class="dropdown col-md-1">
                            <button class="dropdown-toggle" type="button" id="languageMenuButton" data-bs-toggle="dropdown">
                                <img src="https://flagcdn.com/w40/gb.png" width="30" alt="UK Flag">
                            </button>
                            <ul class="dropdown-menu language-menu">
                                <li><a class="dropdown-item" href="#"><img src="https://flagcdn.com/w40/fr.png" width="30" alt="FR Flag"></a></li>
                                <li><a class="dropdown-item" href="#"><img src="https://flagcdn.com/w40/de.png" width="30" alt="DE Flag"></a></li>
                                <li><a class="dropdown-item" href="#"><img src="https://flagcdn.com/w40/us.png" width="30" alt="US Flag"></a></li>
                            </ul>
                        </div>
                    </form>
                @endif
                </div>
            </div>
        </div>
    </nav>
</header>