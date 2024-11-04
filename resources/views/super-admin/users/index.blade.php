@extends('layouts.app')

@section('content')
    <style>
        .active-status {
            background-color: #007bff;
            /* Blue */
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .deactivated-status {
            background-color: #6c757d;
            /* Grey */
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>

    <div class="main py-4">
        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <div class="row">
                <div class="col">
                    <h2 class="mb-4 h5" style="text-transform: uppercase">
                        @if ($role == 'admin_org')
                            Administrators
                        @else
                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                        @endif
                    </h2>
                </div>
                <div class="col text-end">
                    <!-- Trigger the Create Modal -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">Add
                        New</button>
                </div>
            </div>
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="border-gray-200">{{ __('Avatar') }}</th>
                        <th class="border-gray-200">{{ __('Name') }}</th>
                        <th class="border-gray-200">{{ __('Gender') }}</th>
                        <th class="border-gray-200">{{ __('Email') }}</th>
                        @if ($role == 'coach')
                            <th class="border-gray-200">{{ __('Sport') }}</th>
                        @elseif ($role == 'adviser')
                            <th class="border-gray-200">{{ __('Organization') }}</th>
                        @endif
                        <th class="border-gray-200">{{ __('Status') }}</th>
                        <th class="border-gray-200">{{ __('Role') }}</th>
                        <th class="border-gray-200">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-center">
                            <img class="rounded-circle"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar ) : asset('images/avatar/image_place.jpg') }}"
                                    alt="avatar"
                                    height="30">
                            </td>
                            <td><span class="fw-normal">{{ $user->firstname }} {{ $user->lastname }}</span></td>
                            <td><span class="fw-normal">{{ $user->gender }}</span></td>
                            <td><span class="fw-normal">{{ $user->email }}</span></td>
                            @if ($role == 'coach')
                                <td class="border-gray-200">{{ $user->sport->name ?? 'N/A' }}</td>
                            @elseif ($role == 'adviser')
                                <td class="border-gray-200">{{ $user->organization->name ?? 'N/A' }}</td>
                            @endif
                            <td>
                                @if ($user->is_active == 1)
                                    <span class="active-status">Active</span>
                                @else
                                    <span class="deactivated-status">Deactivated</span>
                                @endif
                            </td>
                            <td><span
                                    class="fw-normal">{{ ucfirst(str_replace('_', ' ', $user->getRoleNames()->first())) }}</span>
                            </td>
                            <td class="d-flex gap-2">
                                @if ($user->is_active != 0)
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                        onclick="deleteUser({{ $user->id }})">Deactivate</button>
                                @else
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#activateUserModal"
                                        onclick="activateUser({{ $user->id }})">Activate</button>
                                @endif
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewUserModal"
                                    onclick="viewUser({{ $user->id }})">View</button>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                    onclick="editUser({{ $user->id }})">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">
                            @if ($role == 'admin_org')
                                New Administrator
                            @else
                                New {{ ucfirst(str_replace('_', ' ', $role)) }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstname" placeholder="Enter First Name"
                                    required>
                                @error('firstname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="middlename" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middlename"
                                    placeholder="Enter Middle Name">
                                @error('middlename')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastname" placeholder="Enter Last Name"
                                    required>
                                @error('lastname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <input type="text" class="form-control" name="suffix"
                                    placeholder="Enter Suffix (e.g., Jr, Sr)">
                                @error('suffix')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="birthdate" class="form-label">Birthday</label>
                                <input type="date" class="form-control" name="birthdate" id="birthdate"
                                    placeholder="DD/MM/YYYY" required>
                                @error('birthdate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($role != 'admin_org')
                                <input type="hidden" name="role" value="{{ $role }}">
                            @endif
                        </div>
                        @if ($role == 'coach')
                            <div class="form-group mt-3">
                                <label for="">Sport</label>
                                <select name="sport_id" id="" class="form-select">
                                    <option value="" selected disabled>Select Sport</option>
                                    @foreach ($sports as $sport)
                                        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif ($role == 'adviser')
                            <div class="form-group mt-3">
                                <label for="">Organization</label>
                                <select name="organization_id" id="addOrganization" class="form-select" onchange="otherSpecify(true);">
                                    <option value="" selected disabled>Select Organization</option>
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endforeach
                                    <option value="select_other">Other</option>
                                </select>
                                <div id="main-layout">
                                    <input type="text" class="form-control mtop-1" name="txtAddSelectOther" id="txt-add-other-specify" 
                                    placeholder="Please specify">
                                </div>
                            </div>
                        @endif
                        <div class="mt-3 row">
                            <div class="form-group col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email"
                                    required>
                                <small class="text-danger">Domain must be @bpsu.edu.ph</small>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($role == 'admin_org')
                                <div class="form-group col">
                                    <label for="role">Role</label>
                                    <select name="role" id="" class="form-select">
                                        <option value="admin_org">Admin Org</option>
                                        <option value="admin_sport">Admin Sport</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter Password" required>
                                    <button class="btn btn-outline-secondary create-toggle-password" type="button"
                                        data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordError" class="text-danger"></div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Retype Password" required>
                                    <button class="btn btn-outline-secondary create-toggle-password" type="button"
                                        data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="confirmPasswordError" class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">
                            @if ($role == 'admin_org')
                                Edit Administrator
                            @else
                                Edit {{ ucfirst(str_replace('_', ' ', $role)) }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="row">
                            @if($role == 'student')
                            <div class="col-md-6">
                                <label for="editFirstname" class="form-label">Student Number</label>
                                <input type="text" class="form-control" id="editStudentNumber" name="student_number"
                                    placeholder="Enter First Name" required>
                                @error('student_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                            <div class="col-md-6">
                                <label for="editFirstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstname" name="firstname"
                                    placeholder="Enter First Name" required>
                                @error('firstname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="editMiddlename" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="editMiddlename" name="middlename"
                                    placeholder="Enter Middle Name">
                                @error('middlename')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="editLastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastname" name="lastname"
                                    placeholder="Enter Last Name" required>
                                @error('lastname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="editSuffix" class="form-label">Suffix</label>
                                <input type="text" class="form-control" id="editSuffix" name="suffix"
                                    placeholder="Enter Suffix (E.G., Jr, Sr)">
                                @error('suffix')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="editBirthdate" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="editBirthdate" name="birthdate"
                                    placeholder="Enter Birthdate" required>
                                @error('birthdate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="editGender" class="form-label">Gender</label>
                                <select class="form-select" id="editGender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($role === 'adviser')
                                <div class="col-md-6 mt-3">
                                    <label for="editOrganization" class="form-label">Organizations</label>
                                    <select class="form-select" id="editOrganization" name="organization_id" onchange="otherSpecify();" required>
                                        <option value="" selected disabled>Select Organization</option>
                                        @foreach ($organizations as $organization)
                                            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                        @endforeach
                                        <option value="select_other">Other</option>
                                    </select>
                                    <div id="main-layout">
                                        <input type="text" class="form-control mtop-1" name="txtSelectOther" id="txt-other-specify" 
                                        placeholder="Please specify">
                                    </div>
                                    @error('organization')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                        </div>
                        <div class=" mt-3 row">
                            <div class="form-group col">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email"
                                    placeholder="Enter Email" required>
                            </div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror





                            <div class="form-group col coach">
                                <label for="">Sport</label>
                                <select name="sport_id" id="" class="form-select">
                                    <option value="" selected disabled>Select Sport</option>
                                    @foreach ($sports as $sport)
                                        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="mt-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editPassword" name="password"
                                    placeholder="Enter New Password (if changing)">
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="editPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <!-- This is where the validation error message will appear -->
                            <div id="passwordError" class="text-danger mt-1"></div>

                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label for="editPasswordConfirmation" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editPasswordConfirmation"
                                    name="password_confirmation" placeholder="Confirm Password (if changing)">
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="editPasswordConfirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <!-- This is where the confirmation validation error message will appear -->
                            <div id="confirmPasswordError" class="text-danger mt-1"></div>

                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel">
                        {{ $role == 'admin_org' ? 'Administrator' : ucfirst(str_replace('_', ' ', $role)) }} Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Avatar Section -->
                        <div class="col-md-4 text-center">
                            <img src="" id="viewAvatar" class="img-fluid rounded-circle mb-3" alt="User Avatar"
                                width="120">
                            <h4 id="viewFullname"></h4>
                            <span class="badge bg-secondary">
                                {{ $role == 'admin_org' ? 'Administrator' : ucfirst(str_replace('_', ' ', $role)) }}
                            </span>

                            <p class="mt-2"><strong>Birthday:</strong> <span id="viewBirthdate">YYYY-MM-DD</span></p>
                            <p><strong>Age:</strong> <span id="viewAge">XX</span></p>
                        </div>

                        <!-- Details Section -->
                        <div class="col-md-8">
                            <h6 class="text-primary">Basic Information</h6>
                            <hr>
                            <p><strong>Gender:</strong> <span id="viewGender">Female</span></p>
                            <p><strong>Address:</strong> <span id="viewAddress">Address Details</span></p>

                            <h6 class="text-primary">Contact Details</h6>
                            <hr>
                            <p><strong>Contact:</strong> <span id="viewContact">+XX XXXXXXXXXX</span></p>
                            <p><strong>Email:</strong> <span id="viewEmail">example@domain.com</span></p>
                            @if ($role === 'coach')
                                <h6 class="text-primary">Assigned Sport</h6>
                                <hr>
                                <p><strong>Sport Name:</strong> <span
                                        id="viewSport">{{ $sport ?? 'Sport Details' }}</span></p>
                            @endif


                            @if ($role === 'adviser')
                                <h6 class="text-primary">Assigned Organization</h6>
                                <hr>
                                <p><strong>Organization Name:</strong> <span
                                        id="viewOrganization">{{ $organization ?? 'Sport Details' }}</span></p>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteUserForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Deactivate Superadmin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to deactivate this user?</p>
                        <input type="hidden" id="deleteUserId" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Deactivate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activate User Modal -->
    <div class="modal fade" id="activateUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="activateUserForm" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Activate Superadmin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to activate this user?</p>
                        <input type="hidden" id="deleteUserId" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Activate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                // scrollX: true
            });
        });

        function otherSpecify(is_add = false){

           const org_id   = document.getElementById(is_add ? 'addOrganization' : 'editOrganization');
           const other_id = document.getElementById(is_add ? 'txt-add-other-specify' :'txt-other-specify');
           
           other_id.style.marginTop = '10px';

           if(org_id.value == 'select_other'){
                other_id.style.display = 'block';
                other_id.setAttribute('required', true);
           }else{
                other_id.style.display = 'none';
                other_id.removeAttribute('required');
           }

           if(is_add){
                document.getElementById('txt-other-specify').style.display = 'none';
                document.getElementById('txt-other-specify').removeAttribute('required');
           }else{
                document.getElementById('txt-add-other-specify').style.display = 'none';
                document.getElementById('txt-add-other-specify').removeAttribute('required');
           }

        }

        window.onload = function() {
            otherSpecify();
            otherSpecify(true);
        };

        function editUser(id) {
            $.get('/users/' + id, function(user) {
                $('#editUserId').val(user.user.id);
                $('')
                $('#viewFullname').text(user.user.firstname + user.user.lastname);
                $('#editStudentNumber').val(user.user.student_number);
                $('#editFirstname').val(user.user.firstname);
                $('#editMiddlename').val(user.user.middlename);
                $('#editLastname').val(user.user.lastname);
                $('#editSuffix').val(user.user.suffix);
                $('#roleId').val(user.roles[0]);
                const birthdate = user.user.birthdate.split(' ')[0];
                $('#editBirthdate').val(birthdate);
                $('#editEmail').val(user.user.email);
                $('#editGender').val(user.user.gender);
                $('#editOrganization').val(user.user.organization_id);

                $('#editPassword').val('');
                $('#editPasswordConfirmation').val('');

                $('#editUserForm').attr('action', '/users/' + id);

                const role = user.roles[0];

                function toggleSportDropdown(role) {
                    console.log('Role being checked:', role); // Debugging line
                    if (role === 'coach') {
                        $('.coach').show();
                        $('.role').hide();
                    } else {
                        $('.coach').hide();
                        $('.role').show(); // Ensure you show it back for non-coach roles
                    }
                }

                toggleSportDropdown(role);
            });
        }


        function viewUser(id) {
            $.get('/users/' + id, function(user) {
                // console.log(user.roles[0])
                $('#view_user_page').attr('href', '/view-user/' + id);
                $('#viewUserId').val(user.user.id);
                $('#viewFullname').text(user.user.firstname + ' ' + user.user.lastname);
                $('#viewMiddlename').val(user.user.middlename || 'N/A');
                $('#viewLastname').val(user.user.lastname);
                $('#viewSuffix').val(user.user.suffix || '');

                // Split the birthdate to get the date part only
                const birthdate = user.user.birthdate.split(' ')[0];
                $('#viewBirthdate').text(birthdate);

                $('#viewEmail').text(user.user.email);

                if (user.user.avatar) {
                    $('#viewAvatar').attr('src', '{{ url('') }}' + '/storage/' + user.user.avatar);
                } else {
                    $('#viewAvatar').attr('src',
                        '{{ url('') }}' + '/images/avatar/image_place.jpg');
                }

                const age = calculateAge(birthdate);
                $('#viewAge').text(age);

                $('#viewGender').text(user.user.gender);
                $('#viewContact').text(user.user.contact || 'N/A');
                $('#viewAddress').text(user.user.address || 'N/A');

                $('#viewSport').text(user.user.sport ? user.user.sport.name : 'N/A');
                $('#viewOrganization').text(user.user.organization ? user.user.organization.name : 'N/A');

                // Show the modal
                $('#viewUserModal').modal('show');
            });
        }

        function calculateAge(birthdate) {
            const birthDate = new Date(birthdate);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            // If the birthdate has not yet occurred this year, subtract one from the age
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        function deleteUser(id) {
            $('#deleteUserForm').attr('action', '/users/' + id);
        }

        function activateUser(id) {
            $('#activateUserForm').attr('action', '/users/activate/' + id);
        }

        const birthdateInput = $('#birthdate');

        const today = new Date();
        const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

        const day = ('0' + eighteenYearsAgo.getDate()).slice(-2);
        const month = ('0' + (eighteenYearsAgo.getMonth() + 1)).slice(-2); // Months are zero-based
        const year = eighteenYearsAgo.getFullYear();

        const maxDate = `${year}-${month}-${day}`;

        birthdateInput.attr('max', maxDate);

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        // Real-time password validation
        // $('#password').on('input', function() {
        //     const password = $(this).val();
        //     const passwordError = $('#passwordError');

        //     if (!passwordRegex.test(password)) {
        //         passwordError.text(
        //             "Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, one number, and one special character."
        //         );
        //         $(this).addClass('is-invalid');
        //     } else {
        //         passwordError.text(""); // Clear error if valid
        //         $(this).removeClass('is-invalid');
        //     }
        // });

        $('.create-toggle-password').on('click', function() {
            var target = $(this).data('target'); // Get the input field associated with the button
            var input = $('#' + target); // Target the specific input
            var icon = $(this).find('i'); // Target the icon inside the button

            // Toggle between password and text
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash'); // Change the icon to 'eye-slash'
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye'); // Change the icon back to 'eye'
            }
        });

        $('.toggle-password').on('click', function() {
            var target = $(this).data('target');
            var input = $('#' + target);
            var icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#editPassword').on('input', function() {
            var password = $(this).val();
            var errorMessage = $('#passwordError');
            var inputField = $(this);

            // Real-time feedback for the password field
            if (!passwordRegex.test(password)) {
                errorMessage.text(
                    'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.'
                );
                inputField.addClass('is-invalid'); // Add red border to indicate invalid input
            } else {
                errorMessage.text(''); // Clear the error message when valid
                inputField.removeClass('is-invalid'); // Remove the red border when valid
            }
        });

        // Confirm password validation
        $('#editPasswordConfirmation').on('input', function() {
            var password = $('#editPassword').val();
            var confirmPassword = $(this).val();
            var errorMessage = $('#confirmPasswordError');
            var inputField = $(this);

            // Check if passwords match and display error message
            if (password !== confirmPassword) {
                errorMessage.text('Passwords do not match.');
                inputField.addClass('is-invalid');
            } else if (password === confirmPassword && password !== '') {
                errorMessage.text(''); // Clear the error message when valid
                inputField.removeClass('is-invalid');
            }
        });

        // Real-time confirm password validation
        $('#password_confirmation').on('input', function() {
            const password = $('#password').val();
            const confirmPassword = $(this).val();
            const confirmPasswordError = $('#confirmPasswordError');

            if (password !== confirmPassword) {
                confirmPasswordError.text("Passwords do not match.");
                $(this).addClass('is-invalid');
            } else {
                confirmPasswordError.text(""); // Clear error if passwords match
                $(this).removeClass('is-invalid');
            }
        });
    </script>
@endpush
