@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Deleted Comment</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Content</th>
                                <th>Owner</th>
                                <th>Reason for Deletion</th>
                                <th>Date Posted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deletedComments as $deletedComment)
                                <tr>
                                    <td>{{ $deletedComment->description }}</td>
                                    <td>{{ $deletedComment->user->firstname }} {{ $deletedComment->user->lastname }} </td>
                                    <td>{{ $deletedComment->reportedComments[0]->reason ?? '' }}
                                        {{ $deletedComment->reportedComments[0]->other_reason ?? '' }} </td>
                                    <td>{{ $deletedComment->created_at }} </td>
                                    <td>
                                        <button type="button" class="btn btn-danger restore" data-bs-toggle="modal"
                                            data-bs-target="#restoreModal"
                                            data-id="{{ $deletedComment->id }}">Restore</button>
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
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="restoreForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="restoreModalLabel">Decline Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Are you sure you want to restore it?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
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

            $('.restore').click(function() {
                $('#restoreForm').attr('action', '/deleted-comment/' + $(this).data('id'));
            });

        })
    </script>
@endpush
