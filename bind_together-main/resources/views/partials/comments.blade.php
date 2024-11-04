<div class="d-flex">
    <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="rounded-circle me-2" height="40" width="40"
        alt="User Avatar">
    <div class="bg-light p-3 rounded-3 w-100">
        <form id="editCommentForm-{{ $comment->id }}" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex justify-content-between">
                <div id="comment-display-{{ $comment->id }}">
                    <strong>{{ $comment->user->firstname }}
                        {{ $comment->user->lastname }}</strong>
                    @if ($comment->user_id == auth()->id())
                        <button type="button" class="border-0 bg-transparent text-primary ms-2 text-info edit-btn"
                            data-id="{{ $comment->id }}" id="edit-btn-{{ $comment->id }}">Edit</button>
                        <button type="submit" hidden class="border-0 bg-transparent text-primary ms-2 text-info"
                            data-id="{{ $comment->id }}" id="saveButton-{{ $comment->id }}">Save</button>
                        <button class="border-0 bg-transparent text-danger ms-2 delete-btn" data-bs-toggle="modal"
                            data-bs-target="#deleteCommentModal" data-id="{{ $comment->id }}">Delete</button>
                    @endif
                </div>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <p class="mb-0 comment-text" id="comment-text-{{ $comment->id }}">
                {{ $comment->description }}</p>
            <textarea name="description" class="form-control comment-textarea" id="comment-textarea-{{ $comment->id }}"
                rows="2" hidden>{{ $comment->description }}</textarea>
        </form>
        <div class="d-flex mt-2">
            @if ($comment->user_id != auth()->id())
                
                <button class="border-0 bg-transparent text-danger" type="button" data-bs-toggle="modal"
                    data-bs-target="#reportCommentModal" onclick="setCommentId({{ $comment->id }})"
                    {{ $isDisabled }}>
                    <i class="fas fa-flag"></i>
                    {{ $userHasReported ? 'Reported' : 'Report' }}
                </button>
            @endif
        </div>
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

<script>
    $(() => {
        $('.edit-btn').on('click', function(e) {
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
        $('form[id^="editCommentForm"]').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from reloading the page

            var form = $(this);
            var commentId = form.find('.edit-btn').data('id'); // Get comment ID from button

            // Perform the AJAX request
            $.ajax({
                url: form.attr('action'), // Get the form action dynamically
                type: 'POST',
                data: form.serialize(), // Serialize the form data
                success: function(response) {
                    if (response.success) {
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
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        });

        $('.delete-btn').click(function() {
            const commentId = $(this).data('id');
            $('#commentDelete').attr('action', '/comments/' + commentId);
        });

        // Submit the delete form via AJAX
        $('#commentDelete').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            var actionUrl = $(this).attr('action'); // Get the form action URL

            // Save the current scroll position
            var scrollPosition = $(window).scrollTop();

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: $(this).serialize(), // Serialize the form data (including CSRF token)
                success: function(response) {
                    $('#deleteCommentModal').modal(
                    'hide'); // Hide the modal after successful delete

                    // Reload the page but preserve the scroll position
                    location.reload();

                    // Restore the scroll position after the page is reloaded
                    $(window).on('load', function() {
                        $(window).scrollTop(scrollPosition);
                    });
                },
                error: function(xhr) {
                    alert(
                        'An error occurred while trying to delete the comment. Please try again.');
                }
            });
        });


    })
</script>
