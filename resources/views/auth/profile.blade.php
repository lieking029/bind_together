@extends('layouts.app')

@section('content')

    <!-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

    <style>
        /* Custom styling for the active tab */
        .nav-tabs .nav-link.active {
            background-color: #800000;
            /* Dark red background */
            color: white;
            /* White text color for active tab */
        }

        /* Custom styling for the inactive tabs */
        .nav-tabs .nav-link {
            color: #6c757d;
            /* Gray text color for inactive tabs */
        }

        /* Remove bottom border of active tab */
        .nav-tabs .nav-link.active {
            border-color: transparent;
            /* Remove bottom border for active tab */
        }
    </style>

    <div class="main py-4" >
        <div class="row">
            <div class="col-12 col-xl-12" >
                <div class="border-0  mb-4" style="margin-left:20px; font-family:'Poppins', sans serif;">
                    <h2 class="h5 mb-4">{{ __('My profile') }}</h2>

                    <div class="row">
                        <div class="col-3 card pb-4">
                            <div class="text-center">
                                <br>
                                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/default.jpg') }}"
                                    class="rounded-circle" height="100" width="100" alt="AVATAR">
                                <br>
                                <label for="">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</label>
                                <br>
                                <span
                                    class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first())) }}</span>
                            </div>
                        </div>
                        <div class="col-8" style="margin-left: 40px">
                            <div class="card">
                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="margin-bottom:20px;">
                                    @csrf
                                    @method('PUT')
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="profile-info-tab" data-bs-toggle="tab"
                                                href="#profile-info" role="tab" aria-controls="profile-info"
                                                aria-selected="true">Profile info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="update-info-tab" data-bs-toggle="tab"
                                                href="#update-info" role="tab" aria-controls="update-info"
                                                aria-selected="false">Update info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="profile-update-tab" data-bs-toggle="tab"
                                                href="#profile-update" role="tab" aria-controls="profile-update"
                                                aria-selected="false">Profile update</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="account-security-tab" data-bs-toggle="tab"
                                                href="#account-security" role="tab" aria-controls="account-security"
                                                aria-selected="false">Account security</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content mt-3">
                                        <div class="tab-pane fade show active" id="profile-info" role="tabpanel"
                                            aria-labelledby="profile-info-tab">
                                            {{-- <h5 class="text-primary"></h5> --}}
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-info">Basic information</th>
                                                        <th></th>
                                                    </tr>
                                                    @student
                                                        <tr>
                                                            <th>Student Number</th>
                                                            <td>{{ auth()->user()->student_number }}</td>
                                                        </tr>
                                                    @endstudent
                                                    <tr>
                                                        <th>Full Name</th>
                                                        <td>{{ auth()->user()->firstname }} {{ auth()->user()->middlename }}
                                                            {{ auth()->user()->lastname }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date of Birth</th>
                                                        <td>{{ \Carbon\Carbon::parse(auth()->user()->birthdate)->format('F j, Y') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Gender</th>
                                                        <td>{{ auth()->user()->gender }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            {{-- <h5 class="text-primary">Contact information</h5> --}}
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-info">Contact Information</th>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact number</th>
                                                        <td>{{ auth()->user()->contact }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email</th>
                                                        <td>{{ auth()->user()->email }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @if(auth()->user()->roles[0]->id == 5)
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-info">Assigned Sports</th>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 200px !important;">Sport Name: </th>
                                                        <td style="width: 200px !important;">{{ auth()->user()->load('sport')->sport->name ?? 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                            @if(auth()->user()->roles[0]->id == 4)
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-info">Assigned Organization</th>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <th>Organization Name:</th>
                                                        <td>{{ isset(auth()->user()->load('organization')["organization"]["name"] ) ? auth()->user()->load('organization')["organization"]["name"] : 'N/A'}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif

                                            <!-- School Information Section -->
                                            {{-- <h5 class="text-primary">School information</h5> --}}
                                            @student
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th class="text-info">School Information</th>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Campus name</th>
                                                            <td>{{ auth()->user()->campus->name ?? '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Program name</th>
                                                            <td>{{ auth()->user()->program->name ?? '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Year level</th>
                                                            <td>
                                                                @if (auth()->user()->year_level == 1)
                                                                    1<sup>st</sup> Year
                                                                @elseif (auth()->user()->year_level == 2)
                                                                    2<sup>nd</sup> Year
                                                                @elseif (auth()->user()->year_level == 3)
                                                                    3<sup>rd</sup> Year
                                                                @elseif (auth()->user()->year_level == 4)
                                                                    4<sup>th</sup> Year
                                                                @elseif (auth()->user()->year_level == 5)
                                                                    5<sup>th</sup> Year    
                                                                @else
                                                                    {{ auth()->user()->year_level ?? '' }} Year
                                                                @endif
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            @endstudent
                                        </div>
                                        <div class="tab-pane fade" id="update-info" role="tabpanel"
                                            aria-labelledby="update-info-tab">
                                            <div class="container mt-4">


                                                <h5 class="text-primary">Basic information</h5>
                                                <div class="row mb-3">
                                                    @student
                                                        <div class="col-md-6">
                                                            <label for="student_number" class="form-label">Student
                                                                Number</label>
                                                            <input type="text" class="form-control" id="student_number"
                                                                name="student_number"
                                                                value="{{ auth()->user()->student_number }}" readonly>
                                                        </div>
                                                    @endstudent
                                                    <div class="col-md-6">
                                                        <label for="firstname" class="form-label">First name</label>
                                                        <input type="text" class="form-control" id="firstname"
                                                            name="firstname" value="{{ auth()->user()->firstname }}"
                                                            readonly>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="middlename" class="form-label">Middle name</label>
                                                        <input type="text" class="form-control" id="middlename"
                                                            name="middlename" value="{{ auth()->user()->middlename }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label for="lastname" class="form-label">Last name</label>
                                                        <input type="text" class="form-control" id="lastname"
                                                            name="lastname" value="{{ auth()->user()->lastname }}"
                                                            readonly>
                                                    </div>

                                                    <div class="col-md-6 mt-2">
                                                        <label for="suffix" class="form-label">Suffix</label>
                                                        <input type="text" class="form-control" id="suffix"
                                                            name="suffix" value="{{ auth()->user()->suffix }}"
                                                            placeholder="Enter Suffix (E.G., Jr, Sr)" readonly>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label for="dob" class="form-label">Date of birth</label>
                                                        <input type="date" class="form-control" id="dob"
                                                            name="birthdate"
                                                            value="{{ auth()->user()->birthdate ? \Illuminate\Support\Carbon::parse(auth()->user()->birthdate)->format('Y-m-d') : '' }}">

                                                    </div>

                                                    <div class="col-md-6 mt-2">
                                                        <label for="gender" class="form-label">Gender</label>
                                                        <select class="form-select" id="gender" name="gender">
                                                            
                                                            <option value="Female"
                                                                {{ auth()->user()->gender == 'Female' ? 'selected' : '' }}>
                                                                Female</option>
                                                            <option value="Male"
                                                                {{ auth()->user()->gender == 'Male' ? 'selected' : '' }}>
                                                                Male</option>
                                                            <option value="Prefer Not to Say"
                                                                {{ auth()->user()->gender == 'Prefer Not to say' ? 'selected' : '' }}>
                                                                Prefer not to say</option>
                                                        </select>
                                                    </div>
                                                    <div class="col mt-2">
                                                        <label for="address" class="form-label">Address</label>
                                                        <input type="text" class="form-control" id="address"
                                                            name="address" value="{{ auth()->user()->address }}">
                                                    </div>
                                                </div>

                                                <!-- Contact Information Section -->
                                                <h5 class="text-primary mt-2">Contact information</h5>
                                                <div class="row mb-3 mt-2">
                                                    <div class="col-md-6 mt-2">
                                                        <label for="contact_number" class="form-label">Contact
                                                            number</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">+63</span>
                                                            <input type="number" class="form-control" maxlength="10"
                                                                id="contact_number" name="contact"
                                                                value="{{ auth()->user()->contact }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email"
                                                            name="email" value="{{ auth()->user()->email }}" readonly>
                                                    </div>
                                                </div>

                                                <!-- School Information Section -->
                                                @student
                                                    <h5 class="text-primary mt-2">School information</h5>
                                                    <div class="row mb-3">
                                                        {{-- <div class="col-md-6">
                                                        <label for="college" class="form-label">College</label>
                                                        <input type="text" value="CCST" class="form-control">
                                                    </div> --}}
                                                        <div class="col-md-6 mt-2">
                                                            <label for="campus_name" class="form-label">Campus
                                                                name</label>
                                                            <select name="campus_id" id="" class="form-select">
                                                                <option value="" selected disabled>Select Campus
                                                                </option>
                                                                @foreach ($campuses as $campus)
                                                                    <option value="{{ $campus->id }}"
                                                                        {{ auth()->user()->campus_id == $campus->id ? 'selected' : '' }}>
                                                                        {{ $campus->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <label for="program" class="form-label">Program name</label>
                                                            <select name="program_id" id="" class="form-select">
                                                                <option value="" selected disabled>Select Program
                                                                </option>
                                                                @foreach ($programs as $program)
                                                                    <option value="{{ $program->id }}"
                                                                        {{ auth()->user()->program_id == $program->id ? 'selected' : '' }}>
                                                                        {{ $program->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col mt-2">
                                                            <label for="year_level" class="form-label">Year level</label>
                                                            <select class="form-select" id="year_level" name="year_level">
                                                            <option value="5"
                                                                    {{ auth()->user()->year_level == '5' ? 'selected' : '' }}>
                                                                    5th Year</option>
                                                                <option value="4"
                                                                    {{ auth()->user()->year_level == '4' ? 'selected' : '' }}>
                                                                    4th Year</option>
                                                                <option value="3"
                                                                    {{ auth()->user()->year_level == '3' ? 'selected' : '' }}>
                                                                    3rd Year</option>
                                                                <option value="2"
                                                                    {{ auth()->user()->year_level == '2' ? 'selected' : '' }}>
                                                                    2nd Year</option>
                                                                <option value="1"
                                                                    {{ auth()->user()->year_level == '1' ? 'selected' : '' }}>
                                                                    1st Year</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endstudent
                                                <!-- Submit Button -->
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="profile-update" role="tabpanel"
                                            aria-labelledby="profile-update-tab">
                                            <div class="container mt-5">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="profile" class="form-label">Update
                                                                profile</label>
                                                                <p style="font-size: 14px;">PNG, JPG OR ETC</p>
                                                                <input class="form-control" type="file" id="profile" name="avatar" accept=".png,.jpg,.jpeg">

                                                            <small class="text-danger">Max. 5MB</small>
                                                        </div>
                                                    </div>

                                                    <!-- Preview Section -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Preview:</label>
                                                        <div class="mb-3">
                                                            <img id="preview"
                                                                src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                                                alt="Profile Preview" class="img-fluid rounded"
                                                                style="max-height: 300px;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="account-security" role="tabpanel"
                                            aria-labelledby="account-security-tab">
                                            @if ($errors->any())
                                                <div class="alert alert-danger" style="margin-left: 10px; margin-right: 10px;">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="container mt-5">
                                                <!-- Old Password -->
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="old_password" class="form-label">Old password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="old_password"
                                                                name="old_password" placeholder="Old password">
                                                            <span class="input-group-text toggle-password"
                                                                data-target="old_password">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- New Password -->
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="new_password" class="form-label">New password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="new_password"
                                                                name="password" placeholder="Password">
                                                            <span class="input-group-text toggle-password"
                                                                data-target="new_password">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                        <small class="form-text text-muted">Password must be at least 8
                                                            characters, with at least one uppercase letter, lowercase
                                                            letter, number, and special character.</small>
                                                    </div>
                                                </div>

                                                <!-- Confirm Password -->
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="password_confirmation" class="form-label">Confirm
                                                            password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control"
                                                                id="password_confirmation" name="password_confirmation"
                                                                placeholder="Confirm password">
                                                            <span class="input-group-text toggle-password"
                                                                data-target="password_confirmation">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.toggle-password').on('click', function() {
                var target = $(this).data('target');
                var input = $('#' + target);
                var type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
@endpush
