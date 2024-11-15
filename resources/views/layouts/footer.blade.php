<footer class="bg-white rounded shadow p-5 mb-4 mt-4">
    <div class="row">
        <div class="col-12 col-md-4 col-xl-6 mb-4 mb-md-0">
            <p class="mb-0 text-center text-lg-start">© 2024</span> <a
                    class="text-primary fw-normal" href="http://bpsubindtogether.online/" target="_blank"><strong>Bind Together
               </strong> </a></p>
                <div class="footer-contact pt-3">
          <p>Bataan Peninsula State University</p>
          <p><strong>Email:</strong> <span>bpsu.bindtogether@gmail.com</span></p>
        </div>
        </div>
        <div class="col-12 col-md-8 col-xl-6 text-center text-lg-start">
            <!-- List -->
            <ul class="list-inline list-group-flush list-group-borderless text-md-end mb-0">
                <li class="list-inline-item px-0 px-sm-2">
                    <a href="http://bpsubindtogether.online/">About</a>
                </li>
                <li class="list-inline-item px-0 px-sm-2">
                    <a href="https://bpsu.edu.ph/">BPSU SITE</a>
                </li>

                @if(auth()->user()->hasRole('student'))
                <li class="list-inline-item px-0 px-sm-2">
                    <a href="/feedback/create">Contact</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</footer>
