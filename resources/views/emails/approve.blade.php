<p>Dear {{ $data['user']->firstname }} {{ $data['user']->lastname }},</p>
<p>We are pleased to inform you that your registration for {{ $data['activity']->title }} has been approved!</p>
<p>Best regards,<br>{{ $data['admin']->firstname }} {{ $data['admin']->lastname }}<br>Admin</p>
