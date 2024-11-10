@extends('layouts.app')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

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
                    {{$activity->student_registrations}}
                    @if(($activity->type == 1 && $activity->target_player == 1 && $activity->student_registrations == null) || ($activity->type == 2 && $activity->student_registrations == null) || ($activity->type == 3 && $activity->target_player == 1 && $activity->student_registrations == null))

                    @else
                    <div class="col-md-4 mb-3 activity-card" data-title="{{ strtolower($activity->title) }}" style="<?php echo $hasJoinedPractice ? 'display:none;' : '' ?>">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $activity->title }}</h5>
                                <p class="card-text">
                                    <strong>Posted By:</strong> {{ $activity->user->firstname . '' . $activity->user->lastname }} <br>
                                    @if($activity->type == 0)
                                    <strong>Organization:</strong> {{ $activity->user->organization->name ?? 'N/A' }} <br>
                                    @endif
                                    @if($activity->type == 1 || $activity->type == 2)
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
                            placeholder="Enter Name Of Person To Contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="relationship" class="form-label">Relationship</label>
                        <input type="text" class="form-control" id="relationship" name="relationship"
                            placeholder="Enter Relationship" required>
                    </div>
                    <div class="mb-3">
                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                        <input type="tel" class="form-control" maxlength="11" id="emergency_contact"
                            name="emergency_contact" placeholder="Enter Emergency Contact Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="medical_certificate" class="form-label">Certificate of Registration</label>
                        <input type="file" class="form-control" id="medical_certificate"
                            name="certificate_of_registration" required>
                    </div>
                    <div class="mb-3">
                        <label for="parent_consent" class="form-label">Parent Consent</label>
                        <input type="file" class="form-control" id="parent_consent" name="parent_consent"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Photo Copy of Student ID</label>
                        <input type="file" class="form-control" id="student_id" name="photo_copy_id" required>
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
                            <li><strong>Sport Name:</strong> <span id="activity-sport-name"></span></li>
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
                            placeholder="Enter Name Of Person To Contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="relationship" class="form-label">Relationship</label>
                        <input type="text" class="form-control" id="relationship" name="relationship"
                            placeholder="Enter Relationship" required>
                    </div>
                    <div class="mb-3">
                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                        <input type="tel" class="form-control" maxlength="11" id="emergency_contact"
                            name="emergency_contact" placeholder="Enter Emergency Contact Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="medical_certificate" class="form-label">Certificate of Registration</label>
                        <input type="file" class="form-control" id="medical_certificate"
                            name="certificate_of_registration" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Photo Copy of Student ID</label>
                        <input type="file" class="form-control" id="student_id" name="photo_copy_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="other_file" class="form-label">Other File</label>
                        <input type="file" class="form-control" id="other_file" name="other_file" required>
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