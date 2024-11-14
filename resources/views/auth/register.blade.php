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

<style>
    @media screen and (max-width: 768px) {
        .m-btn {
            min-width: 100% !important;
        }
    }
</style>

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
                    <button type="submit"  style="background: #8B0000"
                        class="btn btn-transparent text-white w-25 m-btn">Submit</button>
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
            <h5 class="modal-title text-center" id="termsModalLabel" style="font-size: 1.5rem; font-weight: bold;">TERMS AND CONDITIONS</h5>
            <!-- Modal Body with Terms -->
            <div class="modal-body">
                <h6><strong>Acceptance of Terms</strong></h6>
                <p>• By accessing and using the "Bind Together" system, you agree to comply with and be bound by these Terms and Conditions. If you do not agree, you must not use the platform.</p>

                <h6><strong>Eligibility</strong></h6>
                <p>• Only registered admins, superadmin, coaches/advisers, students, athletes, performers, and artists of BPSU are eligible to use this platform. You must provide accurate information during the registration process. Failure to do so may result in the suspension or termination of your account.</p>

                <h6><strong>Introduction</strong></h6>
                <p>The "Bind Together" system aims to streamline event management for organizers and students by enabling organizers to post events and manage schedules for practices, competitions, performances, and other social gatherings. The system ensures that students are promptly notified of these activities via SMS notifications, improving communication and participation across the university.</p>

                <h6><strong>Login Policy</strong></h6>
                <p><strong>&nbsp;Authorized Access</strong></p>
                <ul>
                    <li>Only registered users with valid BPSU email addresses (e.g., @bpsu.edu.ph) are authorized to access the system.</li>
                    <li>Users are required to use their BPSU email addresses to log in and interact with the platform.</li>
                </ul>

                <p><strong>&nbsp;Credentials Issuance</strong></p>
                <ul>
                    <li>After completing the registration process, the system will send the login credentials (email and temporary password) to the registered BPSU email address.</li>
                    <li>Users must check their BPSU email inbox to retrieve the login credentials provided.</li>
                </ul>

                <p><strong>&nbsp;Login Procedure</strong></p>
                <ul>
                    <li>Users must enter the provided BPSU email and password accurately to gain access to the system.</li>
                    <li>For security, users are encouraged to change their temporary password upon first login.</li>
                </ul>

                <p><strong>&nbsp;Security Reminder</strong></p>
                <ul>
                    <li>Users are advised to keep their login credentials secure and not share them with others.</li>
                    <li>For added security, users should log out after each session, especially on shared or public devices.</li>
                </ul>

                <h6><strong>Password Requirements</strong></h6>
                <ul>
                    <li>Passwords must be at least 8 characters long, containing a mix of letters, numbers, and special characters.</li>
                    <li>Users are encouraged to update their passwords periodically for security.</li>
                </ul>

                <h6><strong>Rate Limiting and Lockout Mechanism</strong></h6>
                <ul>
                    <li>To protect against unauthorized access and brute-force attacks, the system will temporarily lock users out after multiple unsuccessful login attempts.</li>
                    <li>Failed Login Attempts: After 6 consecutive failed login attempts, the account will be temporarily locked.</li>
                    <li>Lockout Duration: The account will remain locked for 45 minutes. During this time, users will see a message indicating the time remaining before they can try again.</li>
                </ul>

                <h6><strong>Account Recovery</strong></h6>
                <ul>
                    <li>Users who have forgotten their passwords can use the "Forgot Password" feature to reset it.</li>
                    <li>The system will send a password reset link to the registered BPSU email. Users must click the link and follow the instructions to reset their passwords.</li>
                </ul>

                <h6><strong>Reported Comments and Posts Policy:</strong></h6>
                <ul>
                    <li><strong>Reporting Process:</strong> When a user reports a comment or post, it triggers a review process to ensure compliance with our Bind Together Community Guidelines and Terms and Conditions.</li>
                    <li><strong>Content Evaluation:</strong> The reported content is reviewed by the moderation team to determine if it violates platform rules.</li>
                    <li><strong>Actions for Violations:</strong> If content is found to be inappropriate, possible actions include:</li>
                    <ol>
                        <li>Issuing warnings</li>
                        <li>Removing the content</li>
                        <li>Suspending or banning the user</li>
                    </ol>
                    <li><strong>Notification of Outcome:</strong> Users who report content will be notified of the outcome, but the specifics of actions taken may remain confidential.</li>
                    <li><strong>Questions and Concerns:</strong> For any questions or concerns about the process, users can contact the system administrator or provide feedback through the system.</li>
                </ul>

                <h6><strong>Account Responsibilities</strong></h6>
                <p>• You are responsible for maintaining the confidentiality of your account login information and are fully responsible for all activities that occur under your account. Notify the system administrator immediately if you suspect any unauthorized use of your account.</p>

                <h6><strong>Content Ownership and Use</strong></h6>
                <p>• All content you post on the "Bind Together" platform, including text, images, and videos, remains your intellectual property. However, by posting content, you grant "Bind Together" a non-exclusive, royalty-free license to use, distribute, modify, and display that content in connection with the operation of the platform.</p>
                <p>• You must not post any content that infringes on the intellectual property rights of others. If found in violation, your content may be removed, and your account may be suspended or terminated.</p>

                <h6><strong>Prohibited Activities</strong></h6>
                <p><strong>&nbsp;You agree not to:</strong></p>
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
                <p>• For any questions or concerns regarding these Terms and Conditions, please contact the system administrator at bpsu.bindtogether@gmail.com</p>

                <h6><strong>Community Guidelines</strong></h6>
                <p>Our goal is to create a safe, respectful, and supportive space for BPSU athletes, performers, and artists to connect, collaborate, and share. By following these guidelines, you contribute to fostering a positive community. Violations of these guidelines may result in content removal, warnings, or account suspension/banning.</p>
                <p><strong>1. Respectful Communication</strong></p>
                <ul>
                    <li><strong>Respect All Members</strong>: Treat every member with respect and courtesy, regardless of their background, experience level, or opinions.</li>
                    <li><strong>No Harassment or Bullying</strong>: Harassment, bullying, or intimidation in any form is strictly prohibited. Personal attacks, insults, or derogatory language will not be tolerated.</li>
                    <li><strong>Constructive Engagement</strong>: Avoid making inflammatory or offensive comments that disrupt the harmony of the community.</li>
                </ul>

                <p><strong>2. Inclusive and Supportive Environment</strong></p>
                <ul>
                    <li><strong>Celebrate Diversity</strong>: We encourage an inclusive environment where all individuals are respected. Discriminatory content based on race, gender, sexuality, religion, or any personal characteristic will not be accepted.</li>
                    <li><strong>Support and Encouragement</strong>: Show support for fellow community members, recognizing their efforts and achievements. Celebrate success together!</li>
                </ul>

                <p><strong>3. Appropriate Content</strong></p>
                <ul>
                    <li><strong>Safe Content</strong>: All content shared should be appropriate for all ages. Avoid sharing content that is offensive, sexually explicit, or inappropriate for an academic or professional setting.</li>
                    <li><strong>Prohibited Content</strong>: Content promoting violence, illegal activities, or harmful behavior, including spam, phishing, and scams, will be removed immediately.</li>
                </ul>

                <p><strong>4. Truthful and Accurate Information</strong></p>
                <ul>
                    <li><strong>Honesty is the Key</strong>: Only share truthful, accurate, and constructive information. Avoid spreading false or misleading information, rumors, or unverified content.</li>
                    <li><strong>No Misinformation</strong>: Ensure all shared content is well-founded. Content that misleads or misrepresents facts could harm others and will not be tolerated.</li>
                </ul>

                <p><strong>5. Privacy and Confidentiality</strong></p>
                <ul>
                    <li><strong>Respect Privacy</strong>: Do not share personal or confidential information of yourself or others without consent. This includes contact details, private messages, and other sensitive data.</li>
                    <li><strong>Confidential Information</strong>: Keep sensitive information shared within the community confidential. Breaches of privacy are taken seriously.</li>
                </ul>

                <p><strong>6. Follow Posting Etiquette</strong></p>
                <ul>
                    <li><strong>Be Respectful</strong>: Share content thoughtfully and avoid posting anything that disrupts the flow of the community. Engage in discussions that add value and foster a positive atmosphere for everyone.</li>
                </ul>

                <p><strong>7. Participation and Engagement</strong></p>
                <ul>
                    <li><strong>Active Participation</strong>: Engage actively in discussions, events, and activities. Share your knowledge and experiences to help others grow and connect.</li>
                    <li><strong>Constructive Inquiry</strong>: Provide inquiries that is aimed at helping others improve, rather than criticizing for the sake of negativity.</li>
                </ul>

                <p><strong>8. Reporting Violations</strong></p>
                <ul>
                    <li><strong>Report Inappropriate Behavior</strong>: If you come across content or behavior that violates these guidelines, please report it to the moderation team.</li>
                    <li><strong>No Retaliation</strong>: Retaliating against someone who reports a violation is strictly prohibited and will result in severe consequences.</li>
                </ul>

                <p><strong>9. Consequences of Violations</strong></p>
                <ul>
                    <li><strong>Warnings and Bans</strong>: Violations may result in warnings, temporary bans, or permanent removal from the community, depending on the severity.</li>
                </ul>

                <p><strong>10. Community Support</strong></p>
                <ul>
                    <li><strong>Support One Another</strong>: Offer support and encouragement to fellow members. We are a community built on mutual respect and helping each other succeed.</li>
                    <li><strong>Seeking Assistance</strong>: If you need help, don’t hesitate to reach out to the community or system administrators.</li>
                </ul>

                <p><strong>11. Modifications to Guidelines</strong></p>
                <ul>
                    <li><strong>Updates</strong>: These guidelines may be updated periodically to reflect the evolving needs of the community. Users will be notified of any significant changes.</li>
                </ul>

                <p>By adhering to these guidelines, we ensure that <strong>Bind Together</strong> remains a thriving, supportive, and respectful space for all BPSU athletes, performers, and artists.</p>
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