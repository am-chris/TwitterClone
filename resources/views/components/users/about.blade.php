<div class="mb-3" style="position: relative; margin-top: -100px;">
    <a href="{{ url('/' . $user->username) }}">
        <img class="d-flex align-self-start rounded-circle mr-3 mx-auto" src="{{ url('storage/' . $user->photo_url) }}" style="max-width: 200px; max-height: 200px; border: 4px solid #FFF;" rel="tooltip" data-original-title="{{ $user->username }}" alt="Profile photo">
    </a>
</div>
<h5>
    <a href="{{ url('/' . $user->username) }}" style="color: #555;">{{ $user->name }}</a>
    @if ($user->private)
        <i class="fa fa-lock text-dark" rel="tooltip" data-original-title="Private"></i>
    @endif
    @if ($user->verified)
        <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
    @endif
</h5>
<a href="{{ url('/' . $user->username) }}" class="text-muted">{{ '@' . $user->username }}</a>
<p style="overflow-wrap: break-word; word-wrap: break-word;">{{ $user->bio }}</p>
<ul class="list-unstyled">
    @if (!is_null($user->created_at))
        <li>
            <i class="fa fa-calendar-o"></i> Joined {{ Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('M j Y') }}
        </li>
    @endif
</ul>