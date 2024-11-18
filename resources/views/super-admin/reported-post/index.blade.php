@extends('layouts.app')

@section('content')
<style>
    /* To style the pagination buttons */
    .dataTables_paginate .paginate_button {
        padding: 0;
        margin: 0;
    }

    /* To style individual page items */
    .dataTables_paginate .paginate_button.page-item {
        padding: 0px;
        margin: 0px;
    }

    /* To style the 'Next' and 'Previous' buttons */
    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next {
        padding: 0;
        margin: 0;
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    /* To style the active page button */
    .dataTables_paginate .paginate_button.current {
        background-color: #343a40;
        color: white !important;
        border-radius: 4px;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Reported Post</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Newsfeed</th>
                            <th>Owner</th>
                            <th>Reported By</th>
                            <th>Report Reason</th>
                            <th>Report Counts</th>
                            <th>Media</th>
                            <th>Status</th>
                            <th>Date Reported</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportedNewsfeeds as $reportedNewsfeed)
                        <tr>
                            <td>{{ $reportedNewsfeed->description }}</td>
                            <td>{{ $reportedNewsfeed->user->firstname }} {{ $reportedNewsfeed->user->lastname }}</td>
                            <td>
                                @php
                                $names = $reportedNewsfeed->reportedPosts->map(function($reported_post) {
                                return $reported_post->user->firstname . " " . $reported_post->user->lastname;
                                })->implode(', ');
                                @endphp
                                {{ $names }}
                            </td>

                            <td>
                                @php
                                $string = "";

                                foreach($reportedNewsfeed->reportedPosts as $reported_post){
                                $string .= $reported_post->other_reason ? $reported_post->other_reason : $reported_post->reason . " ";
                                }

                                $words = explode(' ', strtolower($string));
                                $uniqueWords = array_unique($words);
                                $result = implode(' ', $uniqueWords);

                                @endphp
                                {{ucfirst($result);}}
                            </td>
                            <td>{{ $reportedNewsfeed->report_count }}</td>
                            <td>
                                @if($reportedNewsfeed->reportedPosts)
                                @foreach($reportedNewsfeed->reportedPosts as $index => $post)
                                    <a href="storage/{{$post->media}}" target="_blank" style="color: blue;">File {{$index + 1}} </a> {{count($reportedNewsfeed->reportedPosts) > 1 ? ',' :''}}
                            
                                @endforeach
                                @else
                                    <p style="font-size: 14px;">N/A</p>
                                @endif
                            </td>
                            <td>
                                @if ($reportedNewsfeed->status == 1)
                                <span class="badge text-black" style="background: yellow">Pending</span>
                                @elseif ($reportedNewsfeed->status == 0)
                                <span class="badge bg-danger">Declined</span>
                                @elseif ($reportedNewsfeed->status == 2)
                                <span class="badge bg-success">Approved</span>
                                @endif
                            </td>
                           
                            <td>{{ $reportedNewsfeed->created_at }}</td>
                            {{-- <td>{{ $reportedNewsfeed->created_at }}</td> --}}
                            @php
                            $isDisabled = $reportedNewsfeed->status != 1;
                            @endphp
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actionModal"
                                    onclick="setStatus(2, {{ $reportedNewsfeed->id }})" {{ $isDisabled ? 'disabled' : '' }}>Approve</button>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal"
                                    onclick="setStatus(0, {{ $reportedNewsfeed->id }}, true)" {{ $isDisabled ? 'disabled' : '' }}>Decline</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="actionModalLabel">Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to approve?</p>
                    <input type="hidden" name="status" id="statusInput">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form inside modal -->
                <form action="" id="declineForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label for="title" class="form-label">Reason</label>
                            <input type="hidden" name="status" id="statusInputDec">
                            <input type="text" class="form-control" placeholder="Type here" name="reason" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(() => {
        $('#datatable').DataTable();
    })

    function setStatus(status, newsfeed_id, is_decline = false) {
        if (!is_decline) {
            document.getElementById('statusInput').value = status;
            document.getElementById('statusForm').action = `/reported-post-update/${newsfeed_id}`;
        } else {
            document.getElementById('statusInputDec').value = status;
            document.getElementById('declineForm').action = `/reported-post-update/${newsfeed_id}`;
        }

    }
</script>
@endpush