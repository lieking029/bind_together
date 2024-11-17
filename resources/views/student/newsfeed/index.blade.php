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
<style>
     @media screen and (max-width: 768px) {
        .m-wd {
            width: unset;
        }
     }

     .show-description {
        font-family: "Segoe UI Emoji", "Apple Color Emoji", sans-serif;
        overflow: visible;  
        white-space: normal; 
    }

 
</style>
<div class="container my-4">
    <!-- Create Post Section -->
    <div class="card mb-4">
        <div class="card-body row">
            <div class="col-1 m-wd">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/image_place.jpg') }}"  width="50" height="50"
                    class="rounded-circle me-3" alt="User Avatar">
            </div>
            <div class="col-11 mt-1">
                <input type="text" style="cursor: pointer" class="form-control rounded-pill me-2" data-bs-toggle="modal"
                    data-bs-target="#newsfeedModal" placeholder="What's on your mind, {{ auth()->user()->firstname }}?">
            </div>
            <div class="text-end mt-2">
                <button class="btn btn-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#newsfeedModal"><i
                        class="fas fa-image"></i> Create Post</button>
            </div>
        </div>
    </div>

    @foreach ($newsfeeds->reverse() as $newsfeed)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/image_place.jpg') }}" width="50" height="50"
                        class="rounded-circle me-3" alt="User Avatar">
                    <div>
                        <h6 class="mb-0">{{ $newsfeed->user->firstname }} {{ $newsfeed->user->lastname }}</h6>
                        <small>{{ $newsfeed->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @php
                    $userHasReportedPost = \App\Models\ReportedPost::where('newsfeed_id', $newsfeed->id)
                        ->where('user_id', auth()->id())
                        ->exists();

                    $buttonText = $userHasReportedPost ? 'Reported' : 'Report';
                    $isDisabledReportPost = $userHasReportedPost ? 'disabled' : '';
                    $buttonClass = $userHasReportedPost ? 'btn-danger' : 'btn-outline-primary';
                @endphp

                <div class="dropdown" {{ $isDisabledReportPost }}>
                    <button class="btn btn-link text-muted" style="float: right;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        @if ($newsfeed->user_id == auth()->id())
                            <li>
                            <button type="button" class="dropdown-item editBtn" data-bs-toggle="modal" 
                                data-bs-target="#editPostModal" data-id="{{ $newsfeed->id }}" 
                                onclick="showText({{ json_encode($newsfeed->description) }});">
                            Edit
                        </button>

                            </li>
                            <li>
                                <button class="dropdown-item text-danger deleteBtn" data-bs-toggle="modal"
                                    data-bs-target="#deleteNewsfeedModal" data-id="{{ $newsfeed->id }}">
                                    Delete
                                </button>
                            </li>
                        @else
                            @student
                            <li>
                                <button type="button" class="dropdown-item reportBtn {{ $buttonClass }}" data-bs-toggle="modal"
                                    data-bs-target="#reportPostModal" data-id="{{ $newsfeed->id }}" {{ $isDisabledReportPost }}>
                                    {{ $buttonText }}
                                </button>
                            </li>
                            @endstudent
                            @admin_org
                            <li>
                                <button type="button" class="dropdown-item reportBtn {{ $buttonClass }}" data-bs-toggle="modal"
                                    data-bs-target="#reportPostModal" data-id="{{ $newsfeed->id }}" {{ $isDisabledReportPost }}>
                                    {{ $buttonText }}
                                </button>
                            </li>
                            @endadmin_org
                            @admin_sport
                            <li>
                                <button type="button" class="dropdown-item reportBtn {{ $buttonClass }}" data-bs-toggle="modal"
                                    data-bs-target="#reportPostModal" data-id="{{ $newsfeed->id }}" {{ $isDisabledReportPost }}>
                                    {{ $buttonText }}
                                </button>
                            </li>
                            @endadmin_sport
                            @adviser
                            <li>
                                <button type="button" class="dropdown-item reportBtn {{ $buttonClass }}" data-bs-toggle="modal"
                                    data-bs-target="#reportPostModal" data-id="{{ $newsfeed->id }}" {{ $isDisabledReportPost }}>
                                    {{ $buttonText }}
                                </button>
                            </li>
                            @endadviser
                            @coach
                            <li>
                                <button type="button" class="dropdown-item reportBtn {{ $buttonClass }}" data-bs-toggle="modal"
                                    data-bs-target="#reportPostModal" data-id="{{ $newsfeed->id }}" {{ $isDisabledReportPost }}>
                                    {{ $buttonText }}
                                </button>
                            </li>
                            @endcoach
                        @endif
                    </ul>

                </div>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap;">{{ $newsfeed->description }}</p>

                <div class="row">
                    @foreach ($newsfeed->newsfeedFiles as $file)
                        <div class="col-md-4 mb-3">
                            @if (Str::startsWith($file->file_type, 'image'))
                                <img src="{{ asset('storage/' . $file->file_path) }}" class="img-fluid rounded" alt="Media Image">
                            @elseif (Str::startsWith($file->file_type, 'video'))
                                <video class="img-fluid rounded" controls>
                                    <source src="{{ asset('storage/' . $file->file_path) }}" type="{{ $file->file_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Comment Section -->
                @if ($newsfeed->comments)
                    <div id="comments-list-{{ $newsfeed->id }}">
                        @foreach ($newsfeed->comments as $comments)
                                @php
                                    $userHasReported = \App\Models\ReportedComment::where('comments_id', $comments->id)
                                        ->where('user_id', auth()->id())
                                        ->exists();

                                    $isDisabled = $userHasReported ? 'disabled' : '';
                                @endphp
                                <div class="d-flex">
                                    <img src="{{ asset('storage/' . $comments->user->avatar) }}" class="rounded-circle me-2" height="40"
                                        width="40" alt="User Avatar">
                                    <div class="bg-light p-3 rounded-3 w-100">
                                        <form id="editCommentForm-{{ $comments->id }}" action="" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex justify-content-between">
                                                <div id="comment-display-{{ $comments->id }}">
                                                    <strong>{{ $comments->user->firstname }}
                                                        {{ $comments->user->lastname }}</strong>
                                                    @if ($comments->user_id == auth()->id())
                                                        <button type="button"
                                                            class="border-0 bg-transparent text-primary ms-2 text-info edit-btn"
                                                            data-id="{{ $comments->id }}" id="edit-btn-{{ $comments->id }}">Edit</button>
                                                        <button type="submit" hidden
                                                            class="border-0 bg-transparent text-primary ms-2 text-info"
                                                            data-id="{{ $comments->id }}" id="saveButton-{{ $comments->id }}">Save</button>
                                                        <button class="border-0 bg-transparent text-danger ms-2 delete-btn"
                                                            data-bs-toggle="modal" id="comment-{{ $comments->id }}"
                                                            data-bs-target="#deleteCommentModal"
                                                            data-id="{{ $comments->id }}">Delete</button>

                                                        {{-- <div id="comment-{{ $comments->id }}" class="comment">
                                                            <!-- Comment content here -->
                                                            <button class="border-0 bg-transparent text-danger ms-2 delete-btn"
                                                                data-bs-toggle="modal" data-bs-target="#deleteCommentModal"
                                                                data-id="{{ $comments->id }}">Delete</button>
                                                        </div> --}}
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $comments->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 comment-text" id="comment-text-{{ $comments->id }}">
                                                {{ $comments->description }}
                                            </p>
                                            <textarea name="description" class="form-control comment-textarea"
                                                id="comment-textarea-{{ $comments->id }}" rows="2"
                                                hidden>{{ $comments->description }}</textarea>
                                        </form>
                                        <div class="d-flex mt-2">
                                            @if ($comments->user_id != auth()->id())

                                                <button class="border-0 bg-transparent text-danger" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#reportCommentModal" onclick="setCommentId({{ $comments->id }})" {{ $isDisabled }}>
                                                    <i class="fas fa-flag"></i>
                                                    {{ $userHasReported ? 'Reported' : 'Report' }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <form id="comment-form-{{ $newsfeed->id }}" action="" method="POST">
                    @csrf
                    <input type="hidden" name="newsfeed_id" value="{{ $newsfeed->id }}">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control" name="description"
                            placeholder="Comment as {{ auth()->user()->firstname }}">
                        <button class="btn btn-danger ms-2" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
                <div class="d-flex justify-content-between mt-2">
                    <div>
                        <!-- Like button -->
                        <a href="#" class="btn {{ $newsfeed->user_liked ? 'btn-primary' : 'btn-light' }} like-btn"
                            data-newsfeed-id="{{ $newsfeed->id }}" data-status="1">
                            <i class="fas fa-thumbs-up"></i>
                        </a>

                        <!-- Dislike button -->
                        <a href="#" class="btn {{ $newsfeed->user_disliked ? 'btn-primary' : 'btn-light' }} dislike-btn"
                            data-newsfeed-id="{{ $newsfeed->id }}" data-status="0">
                            <i class="fas fa-thumbs-down"></i>
                        </a>
                    </div>

                    <small class="text-muted">
                        <!-- Count of likes -->
                        <span id="like-count-{{ $newsfeed->id }}">
                            {{ $newsfeed->likes_count }}
                        </span> Likes •

                        <!-- Count of dislikes -->
                        <span id="dislike-count-{{ $newsfeed->id }}">
                            {{ $newsfeed->dislikes_count }}
                        </span> Dislikes
                    </small>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal -->
<div class="modal fade" id="newsfeedModal" tabindex="-1" aria-labelledby="newsfeedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background-color: #2E3236; color: white;">
            <form action="{{ route('newsfeed.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title col" id="newsfeedModalLabel" style="color: white;">Create post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="filter: invert(1);"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/avatar/image_place.jpg') }}" class="rounded-circle"
                                 style="width: 40px; height: 40px;" alt="Avatar">
                            <span class="ms-2 fw-bold">{{ auth()->user()->firstname }}
                                {{ auth()->user()->lastname }}</span>
                        </div>
                        @if (!auth()->user()->hasRole('student'))
                            <div class="">
                                <!-- <select name="campus_id" id="campus" class="form-select mb-2" style="float: right">
                                    <option value="" selected disabled>Select Campus</option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select> -->
                                <style>
                                        .dropdown {
                                            position: relative;
                                            width: 200px;

                                        }
                                        .dropdown .menu-list ul{
                                            position: absolute;
                                            background: white;
                                            border-radius: 4px;
                                            max-height: 300px;
                                            list-style: none;
                                        }

                                        .dropdown .menu{
                                            cursor: pointer;
                                            width: 100%;
                                            background: white;
                                            border-radius: 4px;
                                            color: #2E3236;
                                            padding: 10px;
                                            display: flex;
                                            justify-content: space-between;
                                            align-items: center;
                                        }

                                        .dropdown .menu p{
                                            font-size: 14px;
                                            margin-bottom: unset !important;
                                        }

                                        .dropdown .menu-list ul{
                                            width: 100%;
                                            margin-top: 2px;
                                            border: 1px solid white;
                                            list-style: none;
                                            padding-left: unset;
                                            padding: 5px 10px;
                                            overflow-y: auto;
                                            overflow-x: hidden;
                                        }

                                        .dropdown .menu-list ul li{
                                            display: flex !important;
                                            gap: 5px;
                                            align-items: center;
                                            color: #2E3236;
                                            font-size: 14px;
                                            padding: 5px 0;
                                            cursor: pointer;
                                        }
                                        
                                        .dropdown .menu-list {
                                            display: none;
                                        }
                                        .active {
                                            display: block !important;
                                        }

                                        #drop-icon-2 {
                                            display: none;
                                        }
                                        
                                        
                                    </style>
                                   @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdminSport() || auth()->user()->isAdminOrg())
                                <input type="hidden" name="txtCampuses" id="txt-campuses"><br>
                                <div class="dropdown">
                                    <div class="menu" onclick="campusChange()">
                                        <p>Select Campus</p>
                                        <span id="drop-icon-1">▼</span>
                                        <span id="drop-icon-2">▲</span>
                                    </div>
                                    <div class="menu-list">
                                            <ul>
                                                @foreach ($campuses as $campus)
                                                    <li>
                                                        <input type="checkbox" id="cbox-{{$campus->id}}" onclick="campusSave({{$campus->id}})">
                                                        <span>{{$campus->name}}</span>
                                                    </li>
                                                @endforeach

                                            </ul>
                                    </div>
                                </div>
                                <!-- Target audience select (Initially hidden) -->
                                <select name="target_player" id="target_audience" class="form-select mt-2"
                                        style="background-color: #4B4F54; color: white; border: none;  float: right">
                                    <option value="0">All Students</option>
                                    <option value="1">
                                        @if(auth()->user()->isAdminOrg())
                                            Official Performers
                                        @else
                                            Official Players
                                        @endif
                                    </option>
                                </select>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- New Target Audience Selection -->

                    <div class="form-group mb-3">
                        <textarea class="form-control" name="description" id="description"
                                  placeholder="What's on your mind, {{ auth()->user()->firstname }}?" rows="3"
                                  style="background-color: #3E4348; color: white; border: none;"></textarea>
                    </div>

                    <input type="file" name="attachments[]" id="attachments" class="d-none" multiple>

                    <div class="add-photos">
                        <button type="button" class="btn btn-secondary w-100 py-3"
                                style="background-color: #4B4F54; color: white; border: none;"
                                onclick="document.getElementById('attachments').click();">
                            <i class="fas fa-plus-circle"></i> Add Photos/Videos
                        </button>
                    </div>

                    <div id="preview" class="row mt-3"></div>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-between">
                    @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdminSport() || auth()->user()->isAdminOrg())
                        <div>
                            <button type="button" hidden class="btn btn-primary" id="sendMessageButton"></button>
                            <button type="button" class="btn btn-primary" onclick="sendViaSMS()" id="sendSMS">Send Message</button>
                        </div>
                    @else
                    <div>

                    </div>
                    @endif
                    <div>
                        <button type="submit" class="btn btn-danger" id="postButton">Post</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Report Comment Modal -->
<div class="modal fade" id="reportCommentModal" tabindex="-1" aria-labelledby="reportCommentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportCommentModalLabel">Report Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="comments_id" id="commentId">

                    <div class="form-group">
                        <label for="reason">Reason for Reporting:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Nudity" id="nudity">
                            <label class="form-check-label" for="nudity">Nudity</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Hate Speech"
                                id="hateSpeech">
                            <label class="form-check-label" for="hateSpeech">Hate Speech</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Threats"
                                id="threats">
                            <label class="form-check-label" for="threats">Threats</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Vulgar" id="vulgar">
                            <label class="form-check-label" for="vulgar">Vulgar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input
                                <input class=" form-check-input" type="checkbox" name="reasons[]" value="Abusive"
                                id="abusive">
                            <label class="form-check-label" for="abusive">Abusive</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Violence"
                                id="violence">
                            <label class="form-check-label" for="violence">Violence</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Others" id="others">
                            <label class="form-check-label" for="others">Others</label>
                        </div>

                        <div id="othersTextArea" class="form-group mt-3" style="display: none;">
                            <label for="otherReason">Please specify:</label>
                            <textarea class="form-control" name="other_reason" id="otherReason" rows="3"
                                placeholder="Enter reason"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" form="reportForm">Submit Report</button>
            </div>
        </div>
    </div>
</div>


<!-- Report Post Modal -->
<div class="modal fade" id="reportPostModal" tabindex="-1" aria-labelledby="reportPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportPostModalLabel">Report Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportPostForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="newsfeed_id" id="newsfeedReportId">

                    <div class="form-group">
                        <label for="reason">Reason for Reporting:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Nudity" id="nudity">
                            <label class="form-check-label" for="nudity">Nudity</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Hate Speech"
                                id="hateSpeech">
                            <label class="form-check-label" for="hateSpeech">Hate Speech</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Threats"
                                id="threats">
                            <label class="form-check-label" for="threats">Threats</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Vulgar" id="vulgar">
                            <label class="form-check-label" for="vulgar">Vulgar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input
                                <input class=" form-check-input" type="checkbox" name="reasons[]" value="Abusive"
                                id="abusive">
                            <label class="form-check-label" for="abusive">Abusive</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Violence"
                                id="violence">
                            <label class="form-check-label" for="violence">Violence</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reasons[]" value="Others"
                                id="othersPost">
                            <label class="form-check-label" for="others">Others</label>
                        </div>

                        <div id="othersPostTextArea" class="form-group mt-3" style="display: none;">
                            <label for="otherReason">Please specify:</label>
                            <textarea class="form-control" name="other_reason" id="otherReason" rows="3"
                                placeholder="Enter reason"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Submit Report</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- delete modal --}}
<div class="modal fade" id="deleteNewsfeedModal" tabindex="-1" aria-labelledby="deleteNewsfeedModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="" id="newsfeedDelete" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteNewsfeedModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- delete comment modal --}}
<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="" id="commentDelete" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCommentModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this comment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #2E3236; color: white;">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="editPostForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <textarea class="form-control show-description" name="description" id="description" rows="3"
                            style="background-color: #3E4348; color: white; border: none;"></textarea>
                    </div>

                    <div id="editPostFiles" class="mb-3 row">
                    </div>
                    <input type="hidden" name="deleted_files" id="deletedFiles" value="">

                    <div class="add-photos">
                        <input type="file" name="attachments[]" id="editAttachments" class="d-none" multiple>
                        <button type="button" class="btn btn-secondary w-100 py-3"
                            style="background-color: #4B4F54; color: white; border: none;"
                            onclick="document.getElementById('editAttachments').click();">
                            <i class="fas fa-plus-circle"></i> Add Photos/Videos
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editPostForm" class="btn btn-danger">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>

        function sendViaSMS(){
            document.getElementById('sendMessageButton').click();
        }

        function campusChange() {
            const element = document.querySelector('.menu-list'); 
            if (element && element.classList.contains('active')) {
                element.classList.remove('active');
                document.getElementById('drop-icon-1').style.display = 'block';
                document.getElementById('drop-icon-2').style.display = 'none';
            } else if (element) {
                element.classList.add('active');
                document.getElementById('drop-icon-1').style.display = 'none';
                document.getElementById('drop-icon-2').style.display = 'block';
            }
        }

        function campusDropClose () {
            const element = document.querySelector('.menu-list'); 
            if (element && element.classList.contains('active')) {
                element.classList.remove('active');
                document.getElementById('drop-icon-1').style.display = 'block';
                document.getElementById('drop-icon-2').style.display = 'none';
            } 
        }

        if(localStorage.getItem('campus_ids')){
            let ids = JSON.parse(localStorage.getItem('campus_ids'));
            ids.forEach((el) => {
                document.getElementById('cbox-' + el).checked  = true;
            });
        }else{
            localStorage.setItem('campus_ids', JSON.stringify([]));
        }

        function campusSave(id) {
            if (!localStorage.getItem('campus_ids')) {
                localStorage.setItem('campus_ids', JSON.stringify([id]));
            } else {
                let ids = JSON.parse(localStorage.getItem('campus_ids'));

                if (ids.includes(id)) {
                    ids = ids.filter(item => item !== id);
                } else {
                    ids.push(id);
                }
                localStorage.setItem('campus_ids', JSON.stringify(ids));
            }
        }



        function showText(text) {
            const elements = document.getElementsByClassName('show-description');
            
            if (elements.length > 0) {
                    const textarea = elements[0];
                    textarea.value = text; 
            }
        }

        function setCommentId(commentId) {
            $('#commentId').val(commentId);
        }

        function toggleLike(commentId) {
            $.ajax({
                url: '{{ route('liked-comments') }}', // This points to the correct route for toggling likes
                method: 'POST',
                data: {
                    comments_id: commentId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        const likeButton = $('a[data-comment-id="' + commentId + '"]');
                        likeButton.html('<b><i class="far fa-thumbs-up"></i> ' + (response.liked ? 'Unlike' :
                            'Like') + '</b>');
                    } else {
                        alert('Failed to toggle like.');
                    }
                },
                error: function (xhr) {
                    alert('An error occurred. Please try again.');
                    console.log(xhr.responseText); // Log the error for debugging
                }
            });
        }

        const deletedFiles = [];

        function removeFile(fileId) {
            let deletedFiles = $('#deletedFiles').val() ? $('#deletedFiles').val().split(',') : [];

            if (!deletedFiles.includes(fileId.toString())) {
                deletedFiles.push(fileId);
            }

            $('#deletedFiles').val(deletedFiles.join(','));

            $('.file-preview-' + fileId).remove();
        }

        $(document).ready(function () {


            $('#campus').on('change', function () {
                // Get the target audience select element
                var targetAudienceSelect = $('#target_audience');

                // Show the target audience dropdown when a campus is selected
                if ($(this).val() !== "") {
                    targetAudienceSelect.show(); // Show the dropdown
                } else {
                    targetAudienceSelect.hide(); // Hide if no campus is selected
                }
            });

            // Like button functionality
            $('.like-btn').on('click', function (e) {
                e.preventDefault();

                var newsfeedId = $(this).data('newsfeed-id');
                var status = 1; // Status for like (1)

                // Check if the like button is already active
                var isLiked = $(this).hasClass('btn-primary');

                // If already liked, the user is "unliking"
                if (isLiked) {
                    status = null; // Set status to null for removing the like
                }

                $.ajax({
                    url: '/newsfeed-like',
                    type: 'POST',
                    data: {
                        newsfeed_id: newsfeedId,
                        status: status, // Sending null will remove the reaction
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Update the like and dislike counts dynamically
                        $('#like-count-' + newsfeedId).text(response.likes_count);
                        $('#dislike-count-' + newsfeedId).text(response.dislikes_count);

                        // Toggle the like button class based on current status
                        if (status === 1) {
                            $('.like-btn[data-newsfeed-id="' + newsfeedId + '"]').addClass(
                                'btn-primary').removeClass('btn-light');
                            $('.dislike-btn[data-newsfeed-id="' + newsfeedId + '"]')
                                .removeClass('btn-primary').addClass('btn-light');
                        } else {
                            $('.like-btn[data-newsfeed-id="' + newsfeedId + '"]').removeClass(
                                'btn-primary').addClass('btn-light');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error occurred:', xhr.responseText);
                    }
                });
            });

            // Dislike button functionality
            $('.dislike-btn').on('click', function (e) {
                e.preventDefault();

                var newsfeedId = $(this).data('newsfeed-id');
                var status = 0; // Status for dislike (0)

                // Check if the dislike button is already active
                var isDisliked = $(this).hasClass('btn-primary');

                // If already disliked, the user is "undisliking"
                if (isDisliked) {
                    status = null; // Set status to null for removing the dislike
                }

                $.ajax({
                    url: '/newsfeed-like',
                    type: 'POST',
                    data: {
                        newsfeed_id: newsfeedId,
                        status: status, // Sending null will remove the reaction
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#like-count-' + newsfeedId).text(response.likes_count);
                        $('#dislike-count-' + newsfeedId).text(response.dislikes_count);

                        if (status === 0) {
                            $('.dislike-btn[data-newsfeed-id="' + newsfeedId + '"]').addClass(
                                'btn-primary').removeClass('btn-light');
                            $('.like-btn[data-newsfeed-id="' + newsfeedId + '"]').removeClass(
                                'btn-primary').addClass('btn-light');
                        } else {
                            $('.dislike-btn[data-newsfeed-id="' + newsfeedId + '"]')
                                .removeClass('btn-primary').addClass('btn-light');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error occurred:', xhr.responseText);
                    }
                });
            });


            $('.edit-btn').on('click', function (e) {
                e.preventDefault();

                var commentId = $(this).data('id');

                // Update the form action dynamically
                var form = $('#editCommentForm-' + commentId);
                form.attr('action', '/comments/' + commentId);

                // Show the save button, hide the edit button
                $('#edit-btn-' + commentId).hide();
                $('#saveButton-' + commentId).removeAttr('hidden');

                // Hide the comment text and show the textarea
                $('#comment-text-' + commentId).hide();
                $('#comment-textarea-' + commentId).removeAttr('hidden');
            });

            // Handle form submission via AJAX
            $('form[id^="editCommentForm"]').on('submit', function (e) {
                e.preventDefault(); // Prevent the form from reloading the page

                var form = $(this);
                var commentId = form.find('.edit-btn').data('id'); // Get comment ID from button

                // Perform the AJAX request
                $.ajax({
                    url: form.attr('action'), // Get the form action dynamically
                    type: 'POST',
                    data: form.serialize(), // Serialize the form data
                    success: function (response) {
                        if (response.success) {
                            console.log(response)
                            // Update the comment text without reloading
                            var updatedText = $('#comment-textarea-' + commentId).val();
                            $('#comment-text-' + commentId).text(updatedText)
                                .show(); // Update and show the <p>
                            $('#comment-textarea-' + commentId).hide(); // Hide the textarea
                            $('#saveButton-' + commentId).attr('hidden',
                                true); // Hide save button
                            $('#edit-btn-' + commentId).show(); // Show the edit button
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX error:', error);
                    }
                });
            });

            $('.reportBtn').click(function () {
                const newsfeedId = $(this).data('id');
                console.log(newsfeedId)
                $('#newsfeedReportId').val(newsfeedId);
                $('#reportPostForm').attr('action', '/reported-post/');
            });

            $('#reportPostForm').submit(function (event) {
                event.preventDefault();
                console.log($(this));
                var formData = $(this).serialize();
                var actionUrl = $(this).attr('action');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, // Missing comma added here
                    url: actionUrl,
                    type: 'POST',
                    data: formData, // Send the serialized form data
                    success: function (response) {
                        $('#reportPostModal').modal(
                            'hide'); // Hide the modal after successful submission
                        location.reload();

                        // Optionally show a success message
                        console.log('success')
                    },
                    error: function (xhr) {
                        // Handle error response
                        alert(
                            'An error occurred while submitting the report. Please try again.'
                        );
                    }
                });

            });

            $('.editBtn').click(function () {
                const postId = $(this).data('id');

                fetch('/newsfeed/' + postId)
                    .then(response => response.json())
                    .then(newsfeed => {
                        $('#description').val(newsfeed.description);

                        var filesContainer = $('#editPostFiles');
                        filesContainer.empty(); // Clear previous files before adding new ones

                        // Load existing files from the newsfeed
                        if (Array.isArray(newsfeed.newsfeed_files)) {
                            newsfeed.newsfeed_files.forEach(function (file) {
                                if (file.file_type.startsWith('image')) {
                                    filesContainer.append(`
                            <div class="col-4 mb-3 position-relative file-preview-${file.id}">
                                <img src="/storage/${file.file_path}" alt="file" class="img-fluid rounded">
                                <button type="button" class="btn btn-danger position-absolute top-0 start-100 translate-middle" style="border-radius: 50%; padding: 4px 8px; font-size: 14px;" onclick="removeFile(${file.id})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                                } else if (file.file_type.startsWith('video')) {
                                    filesContainer.append(`
                            <div class="col-4 mb-3 position-relative file-preview-${file.id}">
                                <video controls class="img-fluid rounded">
                                    <source src="/storage/${file.file_path}" type="${file.file_type}">
                                    Your browser does not support the video tag.
                                </video>
                                <button type="button" class="btn btn-danger position-absolute top-0 start-100 translate-middle" style="border-radius: 50%; padding: 4px 8px; font-size: 14px;" onclick="removeFile(${file.id})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                                }
                            });
                        }

                        $('#editPostForm').attr('action', '/newsfeed/' + postId);
                    });
            });

            $('#editPostForm').submit(function (event) {
                event.preventDefault();

                var formData = new FormData(this);
                var actionUrl = $(this).attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#editPostModal').modal('hide');

                        location.reload();
                    },
                    error: function (xhr) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });

            // Preview for newly selected attachments (keeps existing files in the preview)
            $('#editAttachments').on('change', function (event) {
                const preview = $('#editPostFiles'); // Same container where existing files are loaded
                const files = event.target.files; // Get the selected files

                $.each(files, function (index, file) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const mediaPreview = $('<div>', {
                            class: 'col-4 mb-3 position-relative'
                        });

                        // Check the file type for image or video and create corresponding preview elements
                        if (file.type.startsWith('image')) {
                            mediaPreview.append(`
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                    `);
                        } else if (file.type.startsWith('video')) {
                            mediaPreview.append(`
                        <video class="img-fluid rounded" controls style="max-height: 150px; object-fit: cover;">
                            <source src="${e.target.result}" type="${file.type}">
                            Your browser does not support the video tag.
                        </video>
                    `);
                        }

                        // Append the media preview to the preview container (without clearing existing files)
                        preview.append(mediaPreview);
                    };

                    reader.readAsDataURL(file); // Read the file as a DataURL to show the preview
                });
            });

            $('#attachments').on('change', function (event) {
                const preview = $('#preview');
                preview.empty(); // Clear previous previews
                const files = event.target.files; // Get the selected files

                $.each(files, function (index, file) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const mediaPreview = $('<div>', {
                            class: 'col-4 mb-3'
                        });

                        // Check the file type for image or video and create corresponding preview elements
                        if (file.type.startsWith('image')) {
                            mediaPreview.append(`
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                    `);
                        } else if (file.type.startsWith('video')) {
                            mediaPreview.append(`
                        <video class="img-fluid rounded" controls style="max-height: 150px; object-fit: cover;">
                            <source src="${e.target.result}" type="${file.type}">
                            Your browser does not support the video tag.
                        </video>
                    `);
                        }

                        // Append the media preview to the preview container
                        preview.append(mediaPreview);
                    };

                    reader.readAsDataURL(file); // Read the file as a DataURL to show the preview
                });
            });

            $('.deleteBtn').click(function () {
                $('#newsfeedDelete').attr('action', '/newsfeed/' + $(this).data('id'))
            })

            $('.delete-btn').click(function () {
                const commentId = $(this).data('id');
                $('#commentDelete').attr('action', '/comments/' + commentId);
            });

            // Submit the delete form via AJAX
            $('#commentDelete').submit(function (event) {
                event.preventDefault(); // Prevent the default form submission

                var actionUrl = $(this).attr('action'); // Get the form action URL

                // Save the current scroll position
                var scrollPosition = $(window).scrollTop();

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: $(this).serialize(), // Serialize the form data (including CSRF token)
                    success: function (response) {
                        $('#deleteCommentModal').modal(
                            'hide');

                        location.reload();

                        // Restore the scroll position after the page is reloaded
                        $(window).on('load', function () {
                            $(window).scrollTop(scrollPosition);
                        });
                    },
                    error: function (xhr) {
                        alert(
                            'An error occurred while trying to delete the comment. Please try again.'
                        );
                    }
                });
            });


            $('form[id^="comment-form-"]').on('submit', function (e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();
                var postID = form.find('input[name="newsfeed_id"]').val();

                $.ajax({
                    url: '{{ route('comments.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            var commentSection = $('#comments-list-' +
                                postID);
                            commentSection.append(response
                                .comment_html);
                            form.find('input[name="description"]').val(
                                '');
                        } else {
                            showSweetAlert("Error", response.message,
                                "error");
                        }
                    },
                    error: function (xhr) {
                        alert('An error occurred. Please try again.');
                        console.log(xhr.status);
                        console.log(xhr
                            .responseText);
                    }
                });
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#reportForm').on('submit', function (e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: '{{ route('reported-comment.store') }}', // Ensure the correct route is defined
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to report comment.'
                            });
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText); // Log the error for debugging
                        alert('An error occurred while reporting the comment.');
                    }
                });
            });

            $('#others').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#othersTextArea').show();
                } else {
                    $('#othersTextArea').hide();
                }
            });

            $('#othersPost').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#othersPostTextArea').show();
                } else {
                    $('#othersPostTextArea').hide();
                }
            });
        });

        $(document).ready(function() {
            // Handle the send message button click
            $('#sendMessageButton').on('click', function() {
                
                let campuses = localStorage.getItem('campus_ids') ?? [];

                var campusId = campuses;
                var description = $('#description').val(); // Get the description text using the id
                var targetAudience = $('#target_audience').val(); // Get the selected target audience
                console.log(campusId, description, targetAudience);

                if (description.length > 255) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'The description may not be greater than 255 characters.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return; // Stop further execution
                }

                if (campusId) {
                    var url = '/send-message?campus_ids=' + campusId; // Default URL for all students

                    if (targetAudience == 1) { // Check if "Official Players" is selected
                        url = '/send-message/oficialplayer?campus_ids=' + campusId;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST', // Changed to POST to match the controller method
                        data: {
                            description: description, // Pass the description text
                            _token: '{{ csrf_token() }}' // Include CSRF token for security
                        },
                        success: function(response) {
                            // Handle the response here
                            console.log(response);
                            // Show a SweetAlert notification
                            Swal.fire({
                                title: 'Success!',
                                text: 'Message has been sent.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Trigger the post button click
                                    $('#postButton').click();
                                }
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching users:', xhr.responseText);
                            // Show a SweetAlert notification for errors
                            Swal.fire({
                                title: 'Error!',
                                text: JSON.parse(xhr.responseText).error,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });

            // Handle the post button click
            $('#postButton').on('click', function() {
                var form = $('#newsfeedModal form');
                form.submit();
            });
        });

      

    </script>

@endpush
