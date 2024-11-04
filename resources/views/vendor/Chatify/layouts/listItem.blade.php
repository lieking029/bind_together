{{-- -------------------- Saved Messages -------------------- --}}
@if ($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="saved-messages avatar av-m">
                    <span class="far fa-bookmark"></span>
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Saved Messages <span>You</span></p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Contact list -------------------- --}}
@if ($get == 'users' && !!$lastMessage)
    <?php
    $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
    $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8') . '..' : $lastMessageBody;
    $userAvatar = $user && $user->avatar ? asset('storage/' . $user->avatar) : '';
    ?>
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0" class="user-row" data-user-id="{{ $user->id }}">
            {{-- Avatar side --}}
            <td style="position: relative">
                @if ($user->active_status)
                    <span class="activeStatus"></span>
                @endif
                <div class="avatar av-m" style="background-image: url('{{ $user->avatar }}')">
                </div>
            </td>
            {{-- Center side --}}
            <td>
                <p data-id="{{ $user->id }}" data-type="user">
                    {{ strlen($user->firstname . ' ' . $user->middlename . ' ' . $user->lastname) > 12
                        ? trim(substr($user->firstname . ' ' . $user->middlename . ' ' . $user->lastname, 0, 12)) . '..'
                        : $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname }}
                    <span class="contact-item-time" data-time="{{ $lastMessage->created_at }}">
                        {{ $lastMessage->timeAgo }}
                    </span>
                </p>
                <span>
                    {{-- Last Message user indicator --}}
                    {!! $lastMessage->from_id == Auth::user()->id ? '<span class="lastMessageIndicator">You :</span>' : '' !!}
                    {{-- Last message body --}}
                    @if ($lastMessage->attachment == null)
                        {!! $lastMessageBody !!}
                    @else
                        <span class="fas fa-file"></span> Attachment
                    @endif
                </span>
                {{-- New messages counter --}}
                {!! $unseenCounter > 0 ? '<b>' . $unseenCounter . '</b>' : '' !!}
            </td>
        </tr>
    </table>
@endif


{{-- -------------------- Search Item -------------------- --}}
@if ($get == 'search_item')
    <a href="{{ url('chatify/' . $user->id) }}" class="" style="text-decoration: none">
        <table class="messenger-list-item" data-contact="{{ $user->id }}">
            <tr data-action="0">
                {{-- Avatar side --}}
                <td>
                    <div class="avatar av-m" style="background-image: url('{{ asset('storage/' . $user->avatar) }}')">
                        {{-- <img src="{{ $avatar }}" alt="User Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"> --}}
                    </div>
                </td>
                {{-- center side --}}
                <td>
                    <p data-id="{{ $user->id }}" data-type="user">
                        {{-- Show full name with ellipsis if too long --}}
                        {{ strlen($user->full_name) > 25 ? trim(substr($user->full_name, 0, 25)) . '..' : $user->full_name }}
                    </p>
                    {{-- Optional: Show individual name parts --}}
                    <span class="text-muted small">
                        {{ $user->firstname }}
                        {{ $user->middlename ? $user->middlename . ' ' : '' }}
                        {{ $user->lastname }}
                    </span>
                </td>
            </tr>
        </table>
    </a>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if ($get == 'sharedPhoto')
    <div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif
