@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Registered Participants</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Year Level</th>
                            <th>Campus</th>
                            <th>Email</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Person to Contact</th>
                            <th>Emergency Contact</th>
                            <th>Relationship</th>
                            <th>COR</th>
                            <th>ID</th>
                            
                                <th>Other File</th>
                            

                            
                            <th>Status</th>
                            <th>Date Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($auditions as $audition)
                                                @php
                                                    if (!function_exists('ordinal')) {
                                                        function ordinal($number)
                                                        {
                                                            $suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
                                                            if ((int) $number % 100 >= 11 && (int) $number % 100 <= 13) {
                                                                return $number . 'th';
                                                            }
                                                            return $number . $suffixes[$number % 10];
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $audition->user->firstname ?? 'Unknown' }} {{ $audition->user->lastname ?? '' }}</td>
                                                    <td>{{ ordinal($audition->user->year_level ?? 'N/A') }} Year</td>
                                                    <td>{{ $audition->user->campus->name ?? 'N/A' }}</td>
                                                    <td>{{ $audition->user->email ?? 'N/A' }}</td>
                                                    <td>{{ $audition->height }}</td>
                                                    <td>{{ $audition->weight }}</td>
                                                    <td>{{ $audition->contact_person ?? 'N/A' }}</td>
                                                    <td>{{ $audition->emergency_contact }}</td>
                                                    <td>{{ $audition->relationship }}</td>
                                                    <td><img src="{{ asset('storage/' . $audition->certificate_of_registration) }}" alt=""></td>
                                                    <td><img src="{{ asset('storage/' . $audition->photo_copy_id) }}" alt=""></td>
                                    
                                                        <td><img src="{{ asset('storage/' . $audition->other_file) }}" alt=""></td>
                                                    
                                                        
                                                    
                                                    <td>{{ $audition->status == 0 ? 'Pending' : 'Approved' }}</td>
                                                    <td>{{ $audition->created_at }}</td>
                                                    <td>
                                                        @if ($audition->status == 0)
                                                        <button class="btn btn-primary approveBtn" type="button" data-bs-toggle="modal"
                                                            data-bs-target="#approveModal" data-id="{{ $audition->id }}">Approve</button>
                                                        <button class="btn btn-secondary declineBtn" type="button" data-bs-toggle="modal"
                                                            data-bs-target="#declineModal" data-id="{{ $audition->id }}">Decline</button>
                                                    @endif
                                                    
                                                    <!-- Additional View and Delete Buttons -->
                                                    <button type="button" class="btn btn-info viewBtn" data-bs-toggle="modal"
                                                            data-bs-target="#viewAuditionModal" data-id="{{ $audition->id }}">
                                                        View
                                                    </button>
                                    
                                                    <button type="button" class="btn btn-danger deleteBtn" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal" data-id="{{ $audition->id }}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Approve -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="approveForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    Are you sure you want to approve this user?
                    <input type="hidden" name="status" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Decline -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="declineForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    Are you sure you want to decline this user?
                    <input type="hidden" name="status" value="2">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View --}}
<div class="modal fade" id="viewAuditionModal" tabindex="-1" aria-labelledby="viewAuditionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAuditionModalLabel">View Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display Audition Data as Form Inputs -->
                <form>
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label for="user-fullname" class="form-label">Full Name</label>
                            <input type="text" id="user-fullname" class="form-control" readonly>
                        </div>

                        <!-- Year Level -->
                        <div class="col-md-6 mb-3">
                            <label for="user-year-level" class="form-label">Year Level</label>
                            <input type="text" id="user-year-level" class="form-control" readonly>
                        </div>

                        <!-- Campus -->
                        <div class="col-md-6 mb-3">
                            <label for="user-campus" class="form-label">Campus</label>
                            <input type="text" id="user-campus" class="form-control" readonly>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="user-email" class="form-label">Email</label>
                            <input type="email" id="user-email" class="form-control" readonly>
                        </div>

                        <!-- Height -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-height" class="form-label">Height</label>
                            <input type="text" id="audition-height" class="form-control" readonly>
                        </div>

                        <!-- Weight -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-weight" class="form-label">Weight</label>
                            <input type="text" id="audition-weight" class="form-control" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="audition-contact-person" class="form-label">Contact Person</label>
                            <input type="text" id="audition-contact-person" class="form-control" readonly>
                        </div>
                        
                        <!-- Emergency Contact -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-emergency-contact" class="form-label">Emergency Contact</label>
                            <input type="text" id="audition-emergency-contact" class="form-control" readonly>
                        </div>

                        <!-- Relationship -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-relationship" class="form-label">Relationship</label>
                            <input type="text" id="audition-relationship" class="form-control" readonly>
                        </div>

                        <!-- Certificate of Registration -->
                        <div class="col-md-6 mb-3">
                            <label for="certificate-of-registration" class="form-label">Certificate of
                                Registration</label>
                            <img id="certificate-of-registration" class="img-fluid" src=""
                                alt="Certificate of Registration">
                        </div>

                        <!-- Photo Copy of ID -->
                        <div class="col-md-6 mb-3">
                            <label for="photo-copy-id" class="form-label">Photo Copy of ID</label>
                            <img id="photo-copy-id" class="img-fluid" src="" alt="Photo Copy ID">
                        </div>

                        <!-- Conditional Files: Other File or Parent Consent -->
                        <div class="col-md-6 mb-3" id="other-file-row">
                            <label for="other-file" class="form-label">Other File</label>
                            <img id="other-file" class="img-fluid" src="" alt="Other File">
                        </div>

                        <div class="col-md-6 mb-3" id="parent-consent-row">
                            <label for="parent-consent" class="form-label">Parent Consent</label>
                            <img id="parent-consent" class="img-fluid" src="" alt="Parent Consent">
                        </div>

                        <!-- Type -->
                        {{-- <div class="col-md-6 mb-3">
                            <label for="audition-type" class="form-label">Type</label>
                            <input type="text" id="audition-type" class="form-control" readonly>
                        </div> --}}

                        <!-- Status -->
                        {{-- <div class="col-md-6 mb-3">
                            <label for="audition-status" class="form-label">Status</label>
                            <input type="text" id="audition-status" class="form-control" readonly>
                        </div> --}}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- View --}}
<div class="modal fade" id="viewAuditionModal" tabindex="-1" aria-labelledby="viewAuditionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAuditionModalLabel">View Audition Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label for="user-fullname" class="form-label">Full Name</label>
                            <input type="text" id="user-fullname" class="form-control" readonly>
                        </div>

                        <!-- Year Level -->
                        <div class="col-md-6 mb-3">
                            <label for="user-year-level" class="form-label">Year Level</label>
                            <input type="text" id="user-year-level" class="form-control" readonly>
                        </div>

                        <!-- Campus -->
                        <div class="col-md-6 mb-3">
                            <label for="user-campus" class="form-label">Campus</label>
                            <input type="text" id="user-campus" class="form-control" readonly>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="user-email" class="form-label">Email</label>
                            <input type="email" id="user-email" class="form-control" readonly>
                        </div>

                        <!-- Height -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-height" class="form-label">Height</label>
                            <input type="text" id="audition-height" class="form-control" readonly>
                        </div>

                        <!-- Weight -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-weight" class="form-label">Weight</label>
                            <input type="text" id="audition-weight" class="form-control" readonly>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-emergency-contact" class="form-label">Emergency Contact</label>
                            <input type="text" id="audition-emergency-contact" class="form-control" readonly>
                        </div>

                        <!-- Relationship -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-relationship" class="form-label">Relationship</label>
                            <input type="text" id="audition-relationship" class="form-control" readonly>
                        </div>

                        <!-- Contact Person -->
                        <div class="col-md-6 mb-3">
                            <label for="audition-contact-person" class="form-label">Contact Person</label>
                            <input type="text" id="audition-contact-person" class="form-control" readonly>
                        </div>

                        <!-- Certificate of Registration -->
                        <div class="col-md-6 mb-3">
                            <label for="certificate-of-registration" class="form-label">Certificate of
                                Registration</label>
                            <img id="certificate-of-registration" class="img-fluid" src=""
                                alt="Certificate of Registration">
                        </div>

                        <!-- Photo Copy of ID -->
                        <div class="col-md-6 mb-3">
                            <label for="photo-copy-id" class="form-label">Photo Copy of ID</label>
                            <img id="photo-copy-id" class="img-fluid" src="" alt="Photo Copy ID">
                        </div>

                        <!-- Conditional Files: Other File or Parent Consent -->
                        <div class="col-md-6 mb-3" id="other-file-row">
                            <label for="other-file" class="form-label">Other File</label>
                            <img id="other-file" class="img-fluid" src="" alt="Other File">
                        </div>

                        <div class="col-md-6 mb-3" id="parent-consent-row">
                            <label for="parent-consent" class="form-label">Parent Consent</label>
                            <img id="parent-consent" class="img-fluid" src="" alt="Parent Consent">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(() => {
        $('#datatable').DataTable();

        $('.approveBtn').click(function () {
            $('#approveForm').attr('action', '/activity-registration/' + $(this).data('id'))
        });

        $('.declineBtn').click(function () {
            $('#declineForm').attr('action', '/activity-registration/' + $(this).data('id'))
        });

        $('.viewBtn').click(function () {
            fetch('fetch-activity/' + $(this).data('id'))
                .then(response => response.json())
                .then(audition => {
                    console.log(audition);
                    $('#user-fullname').val(audition.user.firstname + ' ' + audition.user.lastname);
                    $('#user-year-level').val(audition.user.year_level + ' Year');
                    $('#user-campus').val(audition.user.campus.name);
                    $('#user-email').val(audition.user.email);
                    $('#audition-height').val(audition.height);
                    $('#audition-weight').val(audition.weight);
                    $('#audition-emergency-contact').val(audition.emergency_contact);
                    $('#audition-relationship').val(audition.relationship);
                    $('#audition-contact-person').val(audition.person_to_contact);  // Set contact person

                    // Image sources
                    $('#certificate-of-registration').attr('src', '/storage/' + audition.certificate_of_registration);
                    $('#photo-copy-id').attr('src', '/storage/' + audition.photo_copy_id);

                    // Conditional rendering based on status
                    if (audition.status == 0) {
                        $('#other-file-row').show();
                        $('#parent-consent-row').hide();
                        $('#other-file').attr('src', '/storage/' + audition.other_file);
                    } else {
                        $('#other-file-row').hide();
                        $('#parent-consent-row').show();
                        $('#parent-consent').attr('src', '/storage/' + audition.parent_consent);
                    }

                    $('#audition-type').text(audition.type ? audition.type : '');
                    $('#audition-status').text(audition.status == 0 ? 'Pending' : 'Declined');
                });
        });
    });
</script>
@endpush
