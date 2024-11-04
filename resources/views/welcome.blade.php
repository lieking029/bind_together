<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    #hero {
        position: relative;
        background: url('{{ asset('images/background 5.jpg') }}') no-repeat center center;
        background-size: cover;
        padding: 60px 0;
        color: white;
        min-height: 90vh;
    }

    #hero .why-box,
    #hero .icon-box {
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        color: white;
    }

    /* #hero .icon-box i {
        font-size: 40px;
        color: #ff5e57;
    } */

    .more-btn {
        display: inline-block;
        margin-top: 15px;
        color: #fff;
        background: #ff5e57;
        padding: 10px 25px;
        border-radius: 50px;
        text-decoration: none;
    }

    .more-btn:hover {
        background: #ff453a;
        text-decoration: none;
    }

    .text-light {
        color: #fff !important;
    }

    .icon-box {
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.icon-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.icon-box i {
    font-size: 50px;
    color: #800000;
    margin-bottom: 20px;
}

.icon-box h4 {
    font-size: 22px;
    font-weight: 700;
    color: #2c3e50; 
    margin-bottom: 15px;
}

.icon-box p {
    font-size: 16px;
    color: #6c757d;
    line-height: 1.6;
    margin: 0;
}

</style>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    @include('layouts.guest-topbar')
    <section id="hero" class="hero section" style="font-family: 'Poppins', sans-serif;">
        <div class="container position-relative">

            <div class="welcome text-start mb-5" data-aos="fade-down" data-aos-delay="100">
                <h1 class="text-light fw-bolder">WELCOME!</h1>
                <p class="text-light h5">Your Online Community Management System</p>
            </div>

            <div class="row gy-4">
                <!-- Why Box -->
                <div class="col-lg-4 d-flex align-items-stretch">
                    <div class="why-box text-start" data-aos="zoom-out" data-aos-delay="200"
                        style="background:#800000; color:white">
                        <h3>Bind Together</h3>
                        <p>
                            Bind Together is designed to streamline event management for active and aspiring student
                            athletes, performers, and artists at BPSU. Experience the ease of centralized data and
                            efficient processes for students, organizers, and administrators.
                        </p>
                        <div class="text-center">
                            <a href="#about" class="more-btn"><span>Learn More</span> <i
                                    class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 d-flex align-items-stretch">
                    <div class="row gy-4">
                        <div class="col-xl-4 d-flex align-items-stretch">
                            <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                                <i class="fas fa-clipboard" style="color: #800000"></i>
                                <h4>Centralized Data</h4>
                                <p>All event and participant data is stored in one place, making it easy to access and
                                    manage.</p>
                            </div>
                        </div>

                        <div class="col-xl-4 d-flex align-items-stretch">
                            <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                                <i class="fas fa-user-check"></i>
                                <h4>Efficient Processes</h4>
                                <p>Simplify and expedite tasks for students, organizers, and administrators.</p>
                            </div>
                        </div>

                        <div class="col-xl-4 d-flex align-items-stretch">
                            <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                                <i class="fas fa-trophy"></i>
                                <h4>Enhanced Performance</h4>
                                <p>Improve performance tracking and management with our comprehensive tools.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
