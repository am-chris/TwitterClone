@if ($trendingHashtags)
    <div class="bg-white p-3 mb-3">
        <h5>United States trends</h5>
        <ul class="list-unstyled mb-0">
            @foreach ($trendingHashtags as $key => $value)
                <li class="mb-2">
                    <a href="{{ route('hashtags.index', strtolower($key)) }}">#{{ $key }}</a>
                    <div class="text-muted">
                        {{ $value }} {{ str_plural('post', $value) }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
