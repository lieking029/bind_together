<p>Dear {{ $data['user']->firstname }} {{ $data['user']->lastname }},</p>
<p>Thank you for registering for {{ $data['activity']->title }}. We regret to inform you that your registration has not been approved.</p>
<p>Best regards,<br>{{ $data['admin']->firstname }} {{ $data['admin']->lastname }}<br>Admin</p>
