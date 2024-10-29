@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Deleted Post</h4>
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
                            @foreach ($deletedPosts as $deletedPost)
                                <tr>
                                    <td>{{ $deletedPost->reason }}</td>
                                    <td>{{ $deletedPost->newsfeed->user->firstname }}
                                        {{ $deletedPost->newsfeed->user->lastname }} </td>
                                    <td>{{ $deletedPost->other_reason }} </td>
                                    <td>{{ $deletedPost->newsfeed->created_at }} </td>
                                    <td>
                                        <button type="button" class="btn btn-danger restoreBtn"
                                            data-id="{{ $deletedPost->id }}" data-bs-toggle="modal"
                                            data-bs-target="#restoreModal">Restore</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModal"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="" method="POST" id="restore-form">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Restore</h5>
                                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-2">Are you sure you want to restore this?</div>
                                <div class="modal-footer mt-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const dataTable = $('#datatable').DataTable();

        $('.restoreBtn').click(function() {
            $('#restore-form').attr('action', '/deleted-post/' + $(this).data('id'))
        })
    </script>
@endpush
