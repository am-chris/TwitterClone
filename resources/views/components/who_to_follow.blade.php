<div class="bg-white p-3 mb-3">
    <h5>Who to follow</h5>
    @forelse ($followSuggestions as $followSuggestion)
        <div class="media">
            <a href="{{ url($followSuggestion->username) }}">
                <img class="d-flex align-self-start rounded-circle mr-3" src="{{ Storage::url($followSuggestion->photo_url) }}" style="max-width: 32px; max-height: 32px;" alt="User photo">
            </a>
            <div class="media-body truncate">
                <h6 class="mt-0 mb-1">
                    <a class="text-primary-hover" href="{{ url($followSuggestion->username) }}" style="color: #555;">
                        {{ $followSuggestion->name }}
                    </a>
                    @if ($followSuggestion->private)
                        <i class="fa fa-lock text-dark" rel="tooltip" data-original-title="Private"></i>
                    @endif
                    @if ($followSuggestion->verified)
                        <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
                    @endif
                    <span class="text-muted font-weight-regular" style="font-size: 13px;" title="{{ $followSuggestion->username }}">{{ '@' . $followSuggestion->username }}</span>
                </h6>
                <user-follow 
                    :o-following="{{ json_encode(Redis::zscore("following:" . Auth::id(), $followSuggestion->id) ? true : false) }}" 
                    :o-requested="{{ json_encode(Auth::user()->followRequested($followSuggestion->id)) }}" 
                    :private="{{ $followSuggestion->private }}" 
                    :user-id="{{ $followSuggestion->id }}" 
                    :current-user-id="{{ Auth::id() }}"
                ></user-follow>
            </div>
        </div>
        <hr class="mt-2 mb-2">
    @empty
        No suggestions.
    @endforelse
    <a href="#">Search by interests</a>
</div>
