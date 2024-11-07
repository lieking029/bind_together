@extends('layouts.guest')

@section('content')

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS with Popper.js (for modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mb-5" style="font-family: 'Poppins', sans-serif;">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h3 class="text-center">Registration</h3>
                <p class="text-center">Fill in the required fields below</p>
            </div>
            <div class="card-body">
                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                    @csrf
                    <!-- Basic Information -->
                    <h5 class="mb-3 text-muted">Basic Information</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="student_number" class="form-label">Student number</label>
                            <input type="text" class="form-control" id="student_number" name="student_number"
                                placeholder="ex: 21-00000" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="firstname" class="form-label">First name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                placeholder="First name" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="middlename" class="form-label">Middle name</label>
                            <input type="text" class="form-control" id="middlename" name="middlename"
                                placeholder="Middle name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="lastname" class="form-label">Last name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                placeholder="Last name" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="suffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="suffix" name="suffix" maxlength="10"
                                placeholder="Enter suffix (e.g., Jr, Sr)">
                            <div class="text-danger" id="suffixError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled selected>Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <h5 class="mb-3 text-muted">Contact Information</h5>
                    <div class="row">
                        {{-- <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Contact number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="contact_number" name="contact"
                                    placeholder="09123456789" required>
                            </div>
                        </div> --}}
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="University email address" required>
                            <div class="text-danger" style="font-size: 12px" id="emailError"></div>
                        </div>
                    </div>

                    <!-- Account Password -->
                    <h5 class="mb-3 text-muted">Account Password</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                                <span class="input-group-text">
                                    <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                </span>
                            </div>
                            <div class="text-danger" style="font-size: 12px" id="passwordError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password"
                                    name="password_confirmation" placeholder="Retype password" required>
                                <span class="input-group-text">
                                    <i class="fas fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                                </span>
                            </div>
                            <div class="text-danger" style="font-size: 12px" id="confirmPasswordError"></div>
                        </div>
                    </div>

                    <!-- Profile Picture -->
                    {{-- <h5 class="mb-3 text-muted">Profile Picture</h5>
                    <div class="mb-3">
                        <label for="profile" class="form-label">Profile</label>
                        <input class="form-control" type="file" id="profile" name="profile" accept="image/*">
                        <div class="form-text">Max. 5MB</div>
                    </div> --}}

                    <div class="mb-3 form-check">
                        <label>
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required> I agree to the
                            <button type="button" class="border-0 bg-transparent" data-bs-toggle="modal"
                                data-bs-target="#termsModal" style="color: blue;">Terms and Conditions</button>
                        </label>
                    </div>

                    <div class="text-center">
                        <button type="submit" style="background: #8B0000"
                            class="btn btn-transparent text-white w-25">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header with Logo -->
            <div class="modal-header d-flex flex-column align-items-center">
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center">
            <img src="{{ asset('images/bindtogether-logo.png') }}" alt="University Logo" style="width: 100px;" class="mb-3">

            </div>
            <h5 class="modal-title text-center" id="termsModalLabel" style="font-size: 1.5rem; font-weight: bold;">Terms and Conditions</h5>
            <!-- Modal Body with Terms -->
            <div class="modal-body">
                <h6><strong>Acceptance of Terms</strong></h6>
                <p>• By accessing and using the "Bind Together" system, you agree to comply with and be bound by these Terms and Conditions. If you do not agree, you must not use the platform.</p>

                <h6><strong>Eligibility</strong></h6>
                <p>• Only registered admins, superadmin, coaches/advisers, students, athletes, performers, and artists of BPSU are eligible to use this platform. You must provide accurate information during the registration process. Failure to do so may result in the suspension or termination of your account.</p>

                <h6><strong>Introduction</strong></h6>
                <p>The "Bind Together" system aims to streamline event management for organizers and students by enabling organizers to post events and manage schedules for practices, competitions, performances, and other social gatherings. The system ensures that students are promptly notified of these activities via SMS notifications, improving communication and participation across the university.</p>

                <h6><strong>Login Policy</strong></h6>
                <p>Upon login, users have up to five login attempts before further attempts are temporarily blocked. If a user fails five login attempts, they must wait for 3 minutes before trying again. During this time, an email is sent to inform the user about the unsuccessful attempts and explain the 3-minute delay for security reasons.</p>

                <h6><strong>Reported Comments and Posts Policy</strong></h6>
                <p>When a user reports a comment or post on "Bind Together," it triggers a review process to ensure content compliance with our Community Guidelines and Terms and Conditions. The reported content is evaluated by our moderation team to determine if it violates platform rules. If the content is deemed inappropriate, actions such as issuing warnings, removing the content, or suspending or banning the user may be taken. Users who report content are notified of the outcome, but specifics of the actions taken may be kept confidential. Users can appeal moderation decisions through an established appeals process. The policy is subject to updates, and users will be informed of significant changes. For questions or concerns about this process, users may contact the system administrator.</p>

                <h6><strong>Account Responsibilities</strong></h6>
                <p>• You are responsible for maintaining the confidentiality of your account login information and are fully responsible for all activities that occur under your account. Notify the system administrator immediately if you suspect any unauthorized use of your account.</p>

                <h6><strong>Content Ownership and Use</strong></h6>
                <p>• All content you post on the "Bind Together" platform, including text, images, and videos, remains your intellectual property. However, by posting content, you grant "Bind Together" a non-exclusive, royalty-free license to use, distribute, modify, and display that content in connection with the operation of the platform.</p>
                <p>• You must not post any content that infringes on the intellectual property rights of others. If found in violation, your content may be removed, and your account may be suspended or terminated.</p>

                <h6><strong>Prohibited Activities</strong></h6>
                <p>• You agree not to:</p>
                <ul>
                    <li>Use the platform for any unlawful purpose.</li>
                    <li>Post or share any content that is abusive, threatening, obscene, defamatory, or otherwise objectionable.</li>
                    <li>Impersonate another person or entity or falsely represent your affiliation with a person or entity.</li>
                    <li>Engage in any activity that could harm the platform, its users, or its functionality, such as hacking, spreading viruses, or using bots.</li>
                </ul>

                <h6><strong>Termination of Access</strong></h6>
                <p>• "Bind Together" reserves the right to terminate or suspend your access to the platform at any time, without prior notice, for conduct that violates these Terms and Conditions or is otherwise harmful to the platform or other users.</p>

                <h6><strong>Disclaimers</strong></h6>
                <p>• The "Bind Together" platform is provided on an "as is" and "as available" basis. We make no warranties or representations, either express or implied, regarding the operation of the platform or the information, content, or materials included on it.</p>
                <p>• We do not guarantee that the platform will be uninterrupted, secure, or free from errors, viruses, or other harmful components.</p>

                <h6><strong>Limitation of Liability</strong></h6>
                <p>• In no event will "Bind Together" or its affiliates be liable for any damages, including but not limited to direct, indirect, incidental, punitive, or consequential damages arising from your use of or inability to use the platform.</p>

                <h6><strong>Modifications to Terms</strong></h6>
                <p>• "Bind Together" reserves the right to modify these Terms and Conditions at any time. You will be notified of significant changes, and your continued use of the platform constitutes acceptance of the updated Terms and Conditions.</p>

                <h6><strong>Contact Information</strong></h6>
                <p>• For any questions or concerns regarding these Terms and Conditions, please contact the system administrator at bind.together@gmail.com</p>

                <h6><strong>Community Guidelines</strong></h6>
                <p>1. <strong>Respect and Inclusivity</strong></p>
                <ul>
                    <li><strong>Respect All Members</strong>: Treat every member with respect, regardless of their background, experience level, or opinions. Discrimination, harassment, and bullying of any kind will not be tolerated.</li>
                    <li><strong>Inclusive Environment</strong>: We strive to create a welcoming and inclusive environment for everyone, including athletes, performers, artists, and community members.</li>
                </ul>
                <p>2. <strong>Content Guidelines</strong></p>
                <ul>
                    <li><strong>Appropriate Content</strong>: All content shared on the platform must be appropriate for all ages. Do not share or post any content that is offensive, explicit, or harmful.</li>
                    <li><strong>Accuracy</strong>: Ensure that all information you share is accurate and truthful. Misleading or false information can harm others and is not permitted.</li>
                    <li><strong>No Spam</strong>: Avoid posting repetitive, irrelevant, or promotional content that does not contribute to the community’s purpose.</li>
                </ul>
                <p>3. <strong>Privacy and Confidentiality</strong></p>
                <ul>
                    <li><strong>Respect Privacy</strong>: Do not share personal information of others without their explicit consent. This includes contact details, private messages, and other sensitive information.</li>
                    <li><strong>Confidential Information</strong>: Respect the confidentiality of any sensitive or proprietary information shared within the community.</li>
                </ul>
                <p>4. <strong>Participation and Engagement</strong></p>
                <ul>
                    <li><strong>Active Participation</strong>: Engage actively in discussions, events, and activities. Share your knowledge and experiences to contribute positively to the community.</li>
                    <li><strong>Constructive Feedback</strong>: When providing feedback, ensure it is constructive and aimed at helping others improve or grow. Avoid negative or destructive criticism.</li>
                </ul>
                <p>5. <strong>Reporting Violations</strong></p>
                <ul>
                    <li><strong>Report Inappropriate Behavior</strong>: If you encounter behavior that violates these guidelines, report it to the system administrators. We take all reports seriously and will take appropriate action.</li>
                    <li><strong>No Retaliation</strong>: Retaliation against anyone who reports a violation is strictly prohibited and will result in severe consequences.</li>
                </ul>
                <p>6. <strong>Consequences of Violations</strong></p>
                <ul>
                    <li><strong>Warnings and Bans</strong>: Violations of these guidelines may result in warnings, temporary bans, or permanent removal from the community, depending on the severity of the offense.</li>
                    <li><strong>Appeals</strong>: If you believe you have been unfairly penalized, you may appeal the decision through the appropriate channels.</li>
                </ul>
                <p>7. <strong>Community Support</strong></p>
                <ul>
                    <li><strong>Support Each Other</strong>: Offer support and encouragement to fellow members. We are a community built on mutual respect and helping each other succeed.</li>
                    <li><strong>Seek Help When Needed</strong>: If you need assistance or guidance, don’t hesitate to reach out to the community or system administrators.</li>
                </ul>
                <p>8. <strong>Modifications to Guidelines</strong></p>
                <p>• Updates: These guidelines may be updated periodically to reflect the evolving needs of the community. Users will be notified of significant changes.</p>
                <p>• Compliance: By continuing to use the platform, you agree to comply with the updated guidelines.</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        // Real-time email validation
        $('#email').on('input', function() {
            const email = $(this).val();
            const emailError = $('#emailError');
            if (!email.endsWith('@bpsu.edu.ph')) {
                emailError.text("Email must end with @bpsu.edu.ph");
                $(this).addClass('is-invalid');
            } else {
                emailError.text("");
                $(this).removeClass('is-invalid');
            }
        });

        // Real-time password validation
        $('#password').on('input', function() {
            const password = $(this).val();
            const passwordError = $('#passwordError');
            if (!passwordRegex.test(password)) {
                passwordError.text(
                    "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character."
                );
                $(this).addClass('is-invalid');
            } else {
                passwordError.text("");
                $(this).removeClass('is-invalid');
            }
        });

        // Real-time confirm password validation
        $('#confirm_password').on('input', function() {
            const password = $('#password').val();
            const confirmPassword = $(this).val();
            const confirmPasswordError = $('#confirmPasswordError');
            if (password !== confirmPassword) {
                confirmPasswordError.text("Passwords do not match!");
                $(this).addClass('is-invalid');
            } else {
                confirmPasswordError.text("");
                $(this).removeClass('is-invalid');
            }
        });

        // Form validation on submit
        $('#registrationForm').on('submit', function(event) {
            let valid = true;

            // Email validation on form submission
            const email = $('#email').val();
            const emailError = $('#emailError');
            if (!email.endsWith('@bpsu.edu.ph')) {
                valid = false;
                emailError.text("Email must end with @bpsu.edu.ph");
                $('#email').addClass('is-invalid');
            } else {
                emailError.text("");
                $('#email').removeClass('is-invalid');
            }

            // Password validation on form submission
            const password = $('#password').val();
            const passwordError = $('#passwordError');
            if (!passwordRegex.test(password)) {
                valid = false;
                passwordError.text(
                    "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character."
                );
                $('#password').addClass('is-invalid');
            } else {
                passwordError.text("");
                $('#password').removeClass('is-invalid');
            }

            // Confirm password validation on form submission
            const confirmPassword = $('#confirm_password').val();
            const confirmPasswordError = $('#confirmPasswordError');
            if (password !== confirmPassword) {
                valid = false;
                confirmPasswordError.text("Passwords do not match!");
                $('#confirm_password').addClass('is-invalid');
            } else {
                confirmPasswordError.text("");
                $('#confirm_password').removeClass('is-invalid');
            }

            if (!valid) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        // Toggle password visibility for password field
        $('#togglePassword').on('click', function() {
            const passwordField = $('#password');
            const icon = $(this);
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });

        // Toggle password visibility for confirm password field
        $('#toggleConfirmPassword').on('click', function() {
            const confirmPasswordField = $('#confirm_password');
            const icon = $(this);
            const type = confirmPasswordField.attr('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
