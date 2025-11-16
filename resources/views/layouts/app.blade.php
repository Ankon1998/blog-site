<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Lightweight Blog') | Blog Site</title>
    <meta name="description" content="@yield('description', 'A lightweight blog built with Laravel and Bootstrap for fast, easy reading.')">

    <!-- CRITICAL: ONLY PURE BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('posts.index') }}">My Blog</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                
                {{-- LEFT BLOCK: Home Link (Now Hidden) --}}
                <ul class="navbar-nav">
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">Home</a>
                    </li> --}}
                </ul>

                {{-- ALL RIGHT-ALIGNED CONTENT GROUP (Categories, Search, Auth) --}}
                {{-- ms-auto pushes this entire block to the right of the navbar-brand --}}
                <ul class="navbar-nav ms-auto"> 

                    {{-- 1. CATEGORIES DROPDOWN --}}
                    @if (isset($categories) && is_iterable($categories) && $categories->count() > 0)
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link dropdown-toggle fw-bold text-white" href="#" id="navbarDropdownCategories" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                CATEGORIES
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownCategories">
                                @foreach ($categories as $category)
                                    <li><a class="dropdown-item" href="{{ route('categories.posts', $category->slug) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                
                    {{-- 2. SEARCH FORM --}}
                    <li class="nav-item me-3">
                        <form class="d-flex" method="GET" action="{{ route('posts.search') }}">
                            <input class="form-control form-control-sm me-2" type="search" placeholder="Search blog..." name="query" aria-label="Search" required>
                            <button class="btn btn-sm btn-outline-light" type="submit">Search</button>
                        </form>
                    </li>

                    {{-- 3. AUTH LINKS --}}
                    @auth
                        <!-- Authenticated User Links -->
                        @if (auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name ?? 'User' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest User Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content Section -->
    <main class="py-4">
        <div class="container">
            @yield('content') 
        </div>
    </main>

    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>