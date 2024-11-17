<style>
     @media screen and (max-width: 768px) {
        
        #navbarSupportedContent {
            justify-content: right !important;
        }

        .m-profile {
            display: flex;
            width: unset;
            justify-content: right !important;
        }
     }
</style>

<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-end w-100" id="navbarSupportedContent">
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link m-profile dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="media d-flex align-items-center" style="float:right;">
                            <!-- Avatar Image -->
                            <img class="avatar rounded-circle m-profile" 
                                src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/image_place.jpg') }}"
                                alt="{{ Auth::user()->firstname }}"
                                style="width: 40px; height: 40px;"
                                onerror="this.onerror=null; this.src='{{ asset('images/avatar/image_place.jpg') }}';">

                            <!-- Username -->
                            <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                                <span class="mb-0 font-small fw-bold text-gray-900">{{ auth()->user()->firstname }}</span>
                            </div>
                        </div>
                    </a>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu dropdown-menu-end mt-2 py-1" style="width: 250px; padding: 0;">
                        <!-- Profile Header Section -->
                        <div class="p-3 text-center" style="background-color: #a40000; color: white;">
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/image_place.jpg') }}"
                            alt="Profile"
                            class="rounded-circle mb-2"
                            width="70"
                            height="70"
                            style="border: 3px solid white;"
                            onerror="this.onerror=null; this.src='{{ asset('images/avatar/image_place.jpg') }}';">

                            <h5 class="mb-0" style="color: white">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h5>
                            <small style="color: #fff;">{{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first())) }}</small>
                        </div>

                        <!-- Member Information -->
                        <div class="text-center my-2">
                            <small style="color: #6c757d;">Member since
                                {{ Auth::user()->created_at->format('F d, Y') }}</small>
                        </div>

                        <div class="dropdown-divider"></div>

                        <!-- Profile Button -->
                        <a class="dropdown-item text-center" href="{{ route('profile.show') }}" style="padding: 10px 0;">Profile</a>

                        <!-- Sign Out Button -->
                        <a class="dropdown-item text-center text-danger" href="#" id="logout-link" style="padding: 10px 0;">
                            Sign out
                        </a>
                        <form method="POST" id="logout-form" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Include SweetAlert2 (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('logout-link').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default action

        // Display SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure you want to logout?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the logout form
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
