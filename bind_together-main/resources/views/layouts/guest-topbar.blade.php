<style>
    /* Top bar styling */
    .topbar {
        background-color: #800000;
        /* Dark red background */
        color: white;
        font-size: 14px;
        padding: 5px 0;
    }

    /* Navbar link styles */
    .navbar .nav-link {
        color: #333;
        font-size: 16px;
        font-weight: 500;
        margin-right: 20px;
    }

    .navbar .nav-link.active {
        color: #800000;
        font-weight: 700;
    }

    .navbar .nav-link.active::after {
        content: '';
        display: block;
        width: 30px;
        height: 2px;
        background-color: #800000;
        margin-top: 2px;
    }

    /* Login button styling */
    .login-btn {
        background-color: #800000;
        color: white;
        border-radius: 25px;
        padding: 6px 20px;
    }


    /* Logo styling */
    .logo img {
        height: 50px;
        margin-right: 10px;
    }

    .sitename {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }
</style>

<div class="topbar text-center text-md-start">
    <div class="container">
        <i class="bi bi-envelope"></i> <a href="mailto:bpsu.bindtogether@gmail.com"
            class="text-white">bpsu.bindtogether@gmail.com</a>
    </div>
</div>
<header class=" bg-white shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Logo and Site Name -->
        <a href="#" class="logo d-flex align-items-center">
            <img src="{{ asset('images/bindtogether-logo.png') }}" alt="BPSU Logo">
            <h1 class="sitename">Bataan Peninsula State University</h1>
        </a>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-md">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">About</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Sports/Organization</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>
        </nav>

        <a href="{{ route('login') }}" class="btn"
            style="background-color: #800000;
        color: white;
        border-radius: 25px;
        padding: 6px 20px;">Login</a>
    </div>
</header>