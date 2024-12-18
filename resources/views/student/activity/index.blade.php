@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Activities</h4>
        </div>
        <div class="card-body">
            <div class="container my-4">
                <!-- Search Section -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="search" class="form-label fw-bold">Search</label>
                        <input type="text" id="search" class="form-control"
                            placeholder="Search For Activities...">
                    </div>
                </div>

                <!-- Activities Section -->
                <div class="row" id="activities-container">
                    @foreach ($activities as $activity)
                    @if($activity->id)
                    @php
                    $activityTypes = [
                    \App\Enums\ActivityType::Audition => 'Audition',
                    \App\Enums\ActivityType::Tryout => 'Tryout',
                    \App\Enums\ActivityType::Practice => 'Practice',
                    \App\Enums\ActivityType::Competition => 'Competition',
                    ];

                    $hasJoined = auth()
                    ->user()
                    ->joinedActivities->contains($activity->id);

                    $practice = $activity->practices->where('user_id', auth()->id())->first();
                    $hasJoinedPractice = $practice && $practice->status == 1; // Check if user has joined (status = 1)
                    $notGoing = $practice && $practice->status == 0;
                    @endphp

                    @if(!$activity->is_visible)


                    @else
                    <div class="col-md-4 mb-3 activity-card" data-title="{{ strtolower($activity->title) }}" style="<?php echo $hasJoinedPractice ? 'display:none;' : '' ?>">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $activity->title }}</h5>
                                <p class="card-text">
                                    <strong>Posted By:</strong> {{ $activity->user->firstname . '' . $activity->user->lastname }} <br>
                                    @if($activity->type == 0 || ($activity->type == 2 && $activity->user->hasRole('adviser')))
                                    <strong>Organization:</strong> {{ $activity->user->organization->name ?? 'N/A' }} <br>
                                    @endif
                                    @if($activity->type == 1 || ($activity->type == 2 && !$activity->user->hasRole('adviser')))
                                    <strong>Sport name:</strong> {{ $activity->user->sport->name ?? 'N/A' }} <br>
                                    @endif
                                    <strong>Type:</strong> {{ $activityTypes[$activity->type] ?? '' }} <br>
                                    <strong>Venue:</strong> {{ $activity->venue }} <br>
                                    <strong>Duration:</strong> {{ $activity->start_date }} -
                                    {{ $activity->end_date }}
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                @if ($activity->type == 2)
                                <button class="btn btn-danger join-practice" data-id="{{ $activity->id }}"
                                    data-bs-toggle="modal" data-bs-target="#joinPracticeModal" {{ $hasJoinedPractice ? 'disabled' : '' }} {{ $notGoing ? 'disabled' : '' }}>
                                    <i class="fas fa-check-circle"></i>
                                    Join
                                </button>
                                <button class="btn btn-danger not-going" data-id="{{ $activity->id }}"
                                    data-bs-toggle="modal" data-bs-target="#notGoingPracticeModal" {{ $notGoing ? 'disabled' : '' }} {{ $hasJoinedPractice ? 'disabled' : '' }}>
                                    <i class="fas fa-check-circle"></i>
                                    Not Going
                                </button>
                                @else
                                <button class="btn btn-danger join-button"
                                    data-activity-id="{{ $activity->id }}"
                                    data-activity-type="{{ $activity->type }}"
                                    {{ $hasJoined ? 'disabled' : '' }}>
                                    <i class="fas fa-check-circle"></i>
                                    {{ $hasJoined ? 'Already Joined' : 'Join' }}
                                </button>
                                @endif
                                <button class="btn btn-dark view-button" data-activity-id="{{ $activity->id }}"
                                    data-bs-toggle="modal" data-bs-target="#activityDetailsModal">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activity Registration Modal -->
<div class="modal fade" id="activityRegistrationModal" tabindex="-1" aria-labelledby="activityRegistrationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityRegistrationModalLabel">Activity Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('activity-registration.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="activity_id" name="activity_id">

                    @if ($errors->any())
                    <script>
                        $(document).ready(function() {
                            if (localStorage.getItem('activity_storage') !== null) {
                                const act = JSON.parse(localStorage.getItem('activity_storage'));
                                console.log(act.activity_id);
                                $('#activityRegistrationModal').modal('show');
                                document.getElementById('activity_id').value = act.activity_id;
                            }
                        });
                    </script>
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="height" class="form-label">Height (cm)</label>
                        <input type="number" class="form-control" id="height" name="height"
                            placeholder="Enter Your Height" required>
                    </div>
                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" class="form-control" id="weight" name="weight"
                            placeholder="Enter Your Weight" required>
                    </div>
                    <div class="mb-3">
                        <label for="person_to_contact" class="form-label">Person to Contact</label>
                        <input type="text" class="form-control" id="person_to_contact" value="{{auth()->user()->person_to_contact ?? ''}}" name="person_to_contact"
                            placeholder="Enter Name Of Person To Contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="relationship" class="form-label">Relationship</label>
                        <input type="text" class="form-control" id="relationship" value="{{auth()->user()->relationship ?? ''}}" name="relationship"
                            placeholder="Enter Relationship" required>
                    </div>
                    <div class="mb-3">
                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                        <input type="tel" class="form-control" maxlength="11" id="emergency_contact" value="{{auth()->user()->emergency_contact ?? ''}}"
                            name="emergency_contact" placeholder="Enter Emergency Contact Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="medical_certificate" class="form-label">Certificate of Registration (Image Only) 
                        | 
                        <span>
                            @if(auth()->user()->certificate_of_registration)
                            <a href="/storage/{{auth()->user()->certificate_of_registration}}" style="color: blue;" target="_blank">View</a>
                            @else
                            <label style="color: red;">No File</label>
                            @endif
                        </span>
                        </label>
                        <input type="file" class="form-control" accept="image/*" id="medical_certificate"
                            name="certificate_of_registration" {{auth()->user()->certificate_of_registration ?  '' : 'required'}}>
                    </div>
                    <div class="mb-3">
                        <label for="parent_consent" class="form-label">Parent Consent (Image Only)</label>
                        <input type="file" class="form-control" id="parent_consent" accept="image/*" name="parent_consent"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Photo Copy of Student ID (Image Only)
                        |
                        <span>
                            @if(auth()->user()->photo_copy_id)
                            <a href="/storage/{{auth()->user()->photo_copy_id}}" style="color: blue;" target="_blank">View</a>
                            @else
                            <label style="color: red;">No File</label>
                            @endif
                        </span>
                        </label>
                        <input type="file" class="form-control" id="student_id" name="photo_copy_id" accept="image/*" {{auth()->user()->photo_copy_id ? '' : 'required'}}>
                    </div>
                    <div class="mb-3">
                        <label style="display: block;" for="parent_consent" class="form-label">Date of Joining</label>
                        <select name="date_joining" required style="padding: 10px; margin-top: 5px; width: 100%; border: 1px solid #D1D5DB; outline: none; border-radius: 10px;" id="join-dates" ></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="activityDetailsModal" tabindex="-1" aria-labelledby="activityDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityDetailsModalLabel">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            {{-- <li><strong>Sport Name:</strong> <span id="activity-sport-name"></span></li> --}}
                            <li><strong>Title:</strong> <span id="activity-title"></span></li>
                            <li><strong>Target Players:</strong> <span id="activity-target-players"></span></li>
                            <li><strong>Content:</strong> <span id="activity-content"></span></li>
                            <li><strong>Activity Type:</strong> <span id="activity-type"></span></li>
                            <li><strong>Activity Duration:</strong> <span id="activity-duration"></span></li>
                            <li><strong>Venue:</strong> <span id="activity-venue"></span></li>
                            <li><strong>Address:</strong> <span id="activity-address"></span></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <img id="activity-image" src="" class="img-fluid" alt="Activity Image">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Tryouts/Audition Modal -->
<div class="modal fade" id="tryoutAuditionModal" tabindex="-1" aria-labelledby="tryoutAuditionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tryoutAuditionModalLabel">Tryouts/Audition Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('activity-registration.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="activityId" name="activity_id">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="height" class="form-label">Height (cm)</label>
                        <input type="number" class="form-control" id="height" name="height"
                            placeholder="Enter Your Height" required>
                    </div>
                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" class="form-control" id="weight" name="weight"
                            placeholder="Enter Your Weight" required>
                    </div>
                    <div class="mb-3">
                        <label for="person_to_contact" class="form-label">Person to Contact</label>
                        <input type="text" class="form-control" id="person_to_contact" name="person_to_contact"
                            placeholder="Enter Name Of Person To Contact" value="{{auth()->user()->person_to_contact ?? ''}}"  required>
                    </div>
                    <div class="mb-3">
                        <label for="relationship" class="form-label">Relationship</label>
                        <input type="text" class="form-control" value="{{auth()->user()->relationship ?? ''}}" id="relationship" name="relationship"
                            placeholder="Enter Relationship" required>
                    </div>
                    <div class="mb-3">
                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                        <input type="tel" class="form-control" value="{{auth()->user()->emergency_contact ?? ''}}" maxlength="11" id="emergency_contact"
                            name="emergency_contact" placeholder="Enter Emergency Contact Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="medical_certificate" class="form-label">Certificate of Registration (Image Only)
                        | 
                        <span>
                            @if(auth()->user()->certificate_of_registration)
                            <a href="/storage/{{auth()->user()->certificate_of_registration}}" style="color: blue;" target="_blank">View</a>
                            @else
                            <label style="color: red;">No File</label>
                            @endif
                        </span>
                        </label>
                        <input type="file" class="form-control" id="medical_certificate"
                            name="certificate_of_registration" accept="image/*" {{auth()->user()->certificate_of_registration ?  '' : 'required'}}>
                    </div>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Photo Copy of Student ID (Image Only)
                        |
                        <span>
                            @if(auth()->user()->photo_copy_id)
                            <a href="/storage/{{auth()->user()->photo_copy_id}}" style="color: blue;" target="_blank">View</a>
                            @else
                            <label style="color: red;">No File</label>
                            @endif
                        </span>
                        </label>
                        <input type="file" class="form-control" accept="image/*" id="student_id" name="photo_copy_id" {{auth()->user()->photo_copy_id ? '' : 'required'}}>
                    </div>
                    <div class="mb-3">
                        <label for="other_file" class="form-label">Other File (Image Only)</label>
                        <input type="file" class="form-control" accept="image/*" id="other_file" name="other_file">
                    </div>
                    <div class="mb-3">
                        <label style="display: block;" for="parent_consent" class="form-label">Date of Joining</label>
                        <select name="date_joining" required style="padding: 10px; margin-top: 5px; width: 100%; border: 1px solid #D1D5DB; outline: none; border-radius: 10px;" id="join-dates2" ></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="joinPracticeModal" tabindex="-1" aria-labelledby="joinPracticeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="joinPracticeModalLabel">Join Practice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="practiceForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to join?</p>
                    <input type="hidden" name="status" value="1">
                    <input type="hidden" name="activity_id" id="practiceActivityId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="notGoingPracticeModal" tabindex="-1" aria-labelledby="notGoingPracticeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notGoingPracticeModalLabel">Join Practice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="notGoingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Why are you not going?</p>
                    <input type="hidden" name="status" value="0">
                    <input type="hidden" name="activity_id" id="notGoingActivityId">
                    <textarea name="reason" id="" rows="2" class="form-control" placeholder="Reason"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
        </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
        $('#search').on('input', function() {
            var searchQuery = $(this).val()
                .toLowerCase(); // Get the search input and convert to lowercase

            // Loop through each activity card and show/hide based on the title
            $('.activity-card').each(function() {
                var title = $(this).data('title'); // Get the title from the data attribute
                if (title.includes(searchQuery)) {
                    $(this).show(); // Show the card if it matches the search
                } else {
                    $(this).hide(); // Hide the card if it doesn't match
                }
            });
        });

        $('.join-button').on('click', function() {
            var activityType = $(this).data('activity-type');
            var activityId = $(this).data('activity-id');
            console.log(activityId)
            

            localStorage.setItem('activity_storage', JSON.stringify({
                'activity_id': activityId
            }));

            $.ajax({
                url: '/activity/' + activityId,
                method: 'GET',
                success: function(data) {
                    // Clear existing options
                    document.getElementById('join-dates').options.length = 0;
                    document.getElementById('join-dates2').options.length = 0;

                    console.log(data);

                    // Parse start and end dates
                    const startDate = new Date(data.start_date);
                    const endDate = new Date(data.end_date);
                    endDate.setHours(23, 59, 59, 999); // Ensure the end date covers the full day

                    // Determine the target select element
                    let selectElement = null;
                    if (data.type == 3) {
                        selectElement = document.getElementById("join-dates");
                    } else {
                        selectElement = document.getElementById("join-dates2");
                    }

                    // Convert conflicts to a Set for faster lookup
                    const conflictDates = new Set(data.conflicts.map(conflict => new Date(conflict).toISOString().split('T')[0]));

                    // Loop through the date range and skip conflicted dates
                    let currentDate = startDate;
                    while (currentDate <= endDate) {
                        const formattedDate = currentDate.toISOString().split('T')[0];

                        // Skip if the date is in conflicts
                        if (!conflictDates.has(formattedDate)) {
                            const option = document.createElement("option");
                            option.value = formattedDate;
                            option.textContent = formattedDate;
                            selectElement.appendChild(option);
                        }

                        // Increment the date
                        currentDate.setDate(currentDate.getDate() + 1);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching activity details.');
                }
            });


            if (activityType == 3) { // Competition
                $('#activityRegistrationModal').modal('show');
                $('#activity_id').val(activityId);
            } else if (activityType == 0) { // Tryouts or Audition
                $('#tryoutAuditionModal').modal('show');
                $('#activityId').val(activityId); // Set the hidden input for the form
            } else if (activityType == 1) {
                $('#tryoutAuditionModal').modal('show');
                $('#activityId').val(activityId);
            }
        });

        $('.view-button').on('click', function() {
            var activityId = $(this).data('activity-id');
            var baseUrl = "{{ asset('/storage/') }}";

            $.ajax({
                url: '/activity/' + activityId,
                method: 'GET',
                success: function(data) {
                    $('#activity-sport-name').text((data.sport && data.sport.name) || '');
                    $('#activity-title').text(data.title);
                    $('#activity-target-players').text(data.target_player === 1 ?
                        'Official Players' : 'All Student');
                    $('#activity-content').text(data.content);
                    $('#activity-type').text(getTypeName(data.type));
                    $('#activity-duration').text(data.start_date + ' - ' + data.end_date);
                    $('#activity-venue').text(data.venue);
                    $('#activity-address').text(data.address);
                    $('#activity-image').attr('src', data.attachment ? baseUrl + '/' + data
                        .attachment : '/path-to-placeholder-image.jpg');
                },
                error: function(xhr) {
                    alert('Error fetching activity details.');
                }
            });
        });

        $('.join-practice').click(function() {
            const activityId = $(this).data('id');
            $('#practiceActivityId').val(activityId);
            $('#practiceForm').attr('action', '/practice/')
        })

        $('.not-going').click(function() {
            const activityId = $(this).data('id');
            $('#notGoingActivityId').val(activityId);
            $('#notGoingForm').attr('action', '/practice/')
        })

    });

    function getTypeName(id) {
        let type_name = null;

        if (id == 0) {
            type_name = "Audition";
        }

        if (id == 1) {
            type_name = "Tryout";
        }

        if (id == 2) {
            type_name = "Practice";
        }

        if (id == 3) {
            type_name = "Competition";
        }

        return type_name;
    }
</script>
@endpush