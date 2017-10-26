<div class="bg-white p-3 mb-3">
    <h5>Who to follow</h5>
    @if (count($follow_suggestions))
        @foreach ($follow_suggestions as $follow_suggestion)
            <div class="media">
                <a href="{{ url('/' . $follow_suggestion->username) }}">
                    <img class="d-flex align-self-start rounded-circle mr-3" src="{{ $follow_suggestion->photo($follow_suggestion->id) }}" style="max-width: 48px; max-height: 48px;" alt="User photo">
                </a>
                <div class="media-body truncate">
                    <h6 class="mt-0 mb-1">
                        <a class="text-primary-hover" href="{{ url('/' . $follow_suggestion->username) }}" style="color: #555;">
                            {{ $follow_suggestion->name }}
                        </a>
                        @if ($follow_suggestion->verified > 0)
                            <i class="fa fa-check-circle text-primary"></i>
                        @endif
                        <span class="text-muted" style="font-size: 13px; font-weight: 400;">{{ '@' . $follow_suggestion->username }}</span>
                    </h6>
                    <user-follow :original-following="false" :user-id="{{ $follow_suggestion->id }}" :current-user-id="{{ Auth::id() }}"></user-follow>
                </div>
            </div>
            <hr class="mt-2 mb-2">
        @endforeach
        <a href="#">Search by interests</a>
    @else
        No suggestions.
    @endif
</div>
