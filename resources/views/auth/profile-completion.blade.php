<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Completion</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="margin:20px; font-family: 'Poppins', sans-serif;">
        @csrf
        @method('PUT')
        <div class="container mt-4" style="font-family: 'Poppins', sans-serif;">
            <h2 style="text-align:center;">Complete Your Profile</h2>
            <h5 class="text-primary">Basic Information</h5>
            <div class="row mb-3">
                @student
                <div class="col-md-6">
                    <label for="student_number" class="form-label">Student Number</label>
                    <input type="text" class="form-control" id="student_number" name="student_number"
                        value="{{ auth()->user()->student_number }}" readonly>
                </div>
                @endstudent

                <div class="col-md-6">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname"
                        value="{{ auth()->user()->firstname }}" readonly>
                </div>

                <div class="col-md-6 mt-2">
                    <label for="middlename" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middlename" name="middlename"
                        value="{{ auth()->user()->middlename }}" readonly>
                </div>

                <div class="col-md-6 mt-2">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname"
                        value="{{ auth()->user()->lastname }}" readonly>
                </div>

                <div class="col-md-6 mt-2">
                    <label for="suffix" class="form-label">Suffix</label>
                    <input type="text" class="form-control" id="suffix" name="suffix"
                        value="{{ auth()->user()->suffix }}" placeholder="Enter Suffix (E.g., Jr, Sr)" readonly>
                </div>

                <div class="col-md-6 mt-2">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="birthdate"
                        value="{{ auth()->user()->birthdate ? \Illuminate\Support\Carbon::parse(auth()->user()->birthdate)->format('Y-m-d') : '' }}" required>
                </div>

                <div class="col-md-6 mt-2">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="" disabled>Select Gender</option>
                        <option value="Female" {{ auth()->user()->gender == 'Female' ? 'selected' : '' }}>Female
                        </option>
                        <option value="Male" {{ auth()->user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    </select>
                </div>

                <div class="col mt-2">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="{{ auth()->user()->address }}" required>
                </div>
            </div>

            <h5 class="text-primary mt-2">Contact Information</h5>
            <div class="row mb-3 mt-2">
                <div class="col-md-6 mt-2">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+63</span>
                        <input type="text" class="form-control" maxlength="10" id="contact_number" name="contact"
                            value="{{ auth()->user()->contact }}" inputmode="numeric" pattern="\d*" required>
                    </div>
                </div>

                <div class="col-md-6 mt-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ auth()->user()->email }}" readonly>
                </div>
            </div>




            @student
            <h5 class="text-primary mt-2">School Information</h5>
            <div class="row mb-3">
                <div class="col-md-6 mt-2">
                    <label for="campus_id" class="form-label">Campus Name</label>
                    <select name="campus_id" id="campus_id" class="form-control">
                        <option value="" selected disabled>Select Campus</option>
                        @foreach ($campuses as $campus)
                        <option value="{{ $campus->id }}"
                            {{ auth()->user()->campus_id == $campus->id ? 'selected' : '' }}>
                            {{ $campus->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mt-2">
                    <label for="program_id" class="form-label">Program Name</label>
                    <select name="program_id" id="program_id" class="form-control" onchange="toggleOtherProgramInput(this)">
                        <option value="" selected disabled>Select Program</option>
                        @foreach ($programs as $program)
                        <option value="{{ $program->id }}"
                            {{ auth()->user()->program_id == $program->id ? 'selected' : '' }}>
                            {{ $program->name }}
                        </option>
                        @endforeach
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="col-md-6 mt-2" id="other_program_input" style="display: none;">
                    <label for="other_program" class="form-label">Enter Program Name</label>
                    <input type="text" class="form-control" id="other_program" name="other_program">
                </div>

                <div class="col mt-2">
                    <label for="year_level" class="form-label">Year Level</label>
                    <select class="form-control" id="year_level" name="year_level" required>
                        <option value="1" {{ auth()->user()->year_level == 1 ? 'selected' : '' }}>1st Year
                        </option>
                        <option value="2" {{ auth()->user()->year_level == 2 ? 'selected' : '' }}>2nd Year
                        </option>
                        <option value="3" {{ auth()->user()->year_level == 3 ? 'selected' : '' }}>3rd Year
                        </option>
                        <option value="4" {{ auth()->user()->year_level == 4 ? 'selected' : '' }}>4th Year
                        </option>
                    </select>
                </div>
            </div>
            @endstudent


            <h5 class="text-primary mt-2">Personal Details and Verification</h5>
            <div class="row mb-3">
                <div class="col mt-2">
                    <label for="person_to_contact" class="form-label">Contact Person</label>
                    <input type="text" class="form-control" id="person_to_contact" name="person_to_contact"
                        value="{{ auth()->user()->person_to_contact ?? ''}}" placeholder="Type here" required>
                </div>
                <div class="col mt-2">
                    <label for="person_to_contact" class="form-label">Relationship</label>
                    <input type="text" class="form-control" id="person_to_contact" name="relationship"
                        value="{{ auth()->user()->person_to_contact ?? ''}}" placeholder="Type here" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col mt-2">
                    <label for="person_to_contact" class="form-label">Emergency Contact Number</label>
                    <input type="text" class="form-control" id="person_to_contact" name="emergency_contact"
                        value="{{ auth()->user()->person_to_contact ?? ''}}" placeholder="Type here" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col mt-2">
                    <div class="form-group">
                        <label for="">Certificate of Registration</label>
                        <input type="file" name="cert_registration" class="form-control">
                    </div>
                </div>
                <div class="col mt-2">
                    <div class="form-group">
                        <label for="">Photocopy of ID</label>
                        <input type="file" name="photo_id" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Profile Picture</label>
                <input type="file" name="avatar" class="form-control">
            </div>

            <!-- Submit Button -->
            <div class="text-end">
                <button type="submit" class="btn btn-danger">Submit</button>
            </div>
        </div>
    </form>

    <!-- Bootstrap JS and Popper.js (optional, for Bootstrap's JavaScript features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function toggleOtherProgramInput(select) {
            const otherProgramInput = document.getElementById('other_program_input');
            if (select.value === 'other') {
                otherProgramInput.style.display = 'block';
                document.getElementById('other_program').setAttribute('required', 'required');
            } else {
                otherProgramInput.style.display = 'none';
                document.getElementById('other_program').removeAttribute('required');
            }
        }
    </script>
</body>

</html>