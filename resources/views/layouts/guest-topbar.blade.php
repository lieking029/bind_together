<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

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

  .navmenu ul {
    margin: 0;
    padding: 0;
    display: flex;
    list-style: none;
    align-items: center;
  }

  .navmenu li {
    position: relative;
  }

  .navmenu>ul>li {
    white-space: nowrap;
    padding: 15px 14px;
  }

  .navmenu>ul>li:last-child {
    padding-right: 0;
  }

  .navmenu a,
  .navmenu a:focus {
    color: #2c4964;
    font-size: 15px;
    padding: 0 2px;
    font-family: "Raleway", sans-serif;
    font-weight: 400;
    display: flex;
    align-items: center;
    justify-content: space-between;
    white-space: nowrap;
    transition: 0.3s;
    position: relative;
  }

  .navmenu a i,
  .navmenu a:focus i {
    font-size: 12px;
    line-height: 0;
    margin-left: 5px;
    transition: 0.3s;
  }

  .navmenu>ul>li>a:before {
    content: "";
    position: absolute;
    width: 100%;
    height: 2px;
    bottom: -6px;
    left: 0;
    background-color: #800000;
    visibility: hidden;
    width: 0px;
    transition: all 0.3s ease-in-out 0s;
  }

  .navmenu a:hover:before,
  .navmenu li:hover>a:before,
  .navmenu .active:before {
    visibility: visible;
    width: 100%;
  }

  .navmenu li:hover>a,
  .navmenu .active,
  .navmenu .active:focus {
    color: #800000;
  }

  @media screen and (max-width: 768px) {
    .tm-nav {
      width: 100%;
      flex-wrap: wrap;
      flex-grow: 1;
    }

    .nav ul {
      flex-wrap: wrap;
      flex-grow: 1;
    }

    .m-nav {
      margin-left: 0 !important;
    }
  }
</style>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="topbar text-center text-md-start">
  <div class="container">
    <i class="bi bi-envelope"></i> <a href="mailto:bpsu.bindtogether@gmail.com"
      class="text-white">bpsu.bindtogether@gmail.com</a>
  </div>
</div>
<header class=" bg-white shadow-sm">
  <div class="container d-flex align-items-center justify-content-between tm-nav">
    <!-- Logo and Site Name -->
    <a href="#" class="logo navbar-expand-md d-flex align-items-center" style="text-decoration: none; ">
      <img src="{{ asset('images/bindtogether-logo.png') }}" alt="BPSU Logo">
      <h1 class="sitename" style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #2c4964; font-size: 25px;">Bataan Peninsula State University</h1>
    </a>

    <!-- Navigation -->
    <nav id="navmenu" class="navmenu" style="font-family: 'Poppins', sans-serif;">
      <ul>
        <li><a href="/" style="margin-left: 70vh; text-decoration:none;" class="m-nav">Home</a></li>
        <li><a href="/register" style="margin-right: 10px; text-decoration:none;">Sign Up</a></li>
        <li> <a href="{{ route('login') }}" class="btn"
            style="background-color: #800000;
        color: white;
        border-radius: 25px;
        padding: 6px 20px;">Login</a></li>
      </ul>

    </nav>



  </div>
</header>