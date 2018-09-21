@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">

                @include('components.trends')

            </div>
            <div class="col-md-6">
                @if (Auth::user()->private && count($followRequests) > 0)
                    <div class="bg-white p-3 mb-3 border-gray">
                        <h6>Follow Requests</h6>
                        @if (count($followRequests))
                            <ul class="list-inline mb-0">
                                @foreach ($followRequests as $followRequest)
                                    <li class="list-inline-item">
                                        <a href="{{ route('users.show', $followRequest->follower->username) }}">
                                            <img 
                                                class="d-flex align-self-start mr-3 rounded-circle" 
                                                src="{{ Storage::url($followRequest->follower->photo_url) }}" 
                                                style="max-width: 48px; max-height: 48px;" 
                                                alt="User photo" 
                                                rel="tooltip" 
                                                data-original-title="{{ '@' . $followRequest->follower->username }}"
                                            >
                                        </a>
                                        <follow-request-actions
                                            :user-id="{{ $followRequest->follower->id }}"
                                            :current-user-id="{{ Auth::id() }}"
                                        ></follow-request-actions>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @if (count($posts))
                    <div class="bg-white">
                        <ul class="list-unstyled posts">
                            @foreach ($posts as $post)
                                <li class="post">
                                    <a href="{{ route('posts.show', $post->id) }}" style="text-decoration: none;">
                                        <div class="media">
                                            <a href="{{ route('users.show', $post->user->username) }}">
                                                <img 
                                                    class="d-flex align-self-start mr-3 rounded-circle" 
                                                    src="{{ Storage::url($post->user->photo_url) }}" 
                                                    style="max-width: 48px; max-height: 48px;" 
                                                    alt="User photo"
                                                >
                                            </a>
                                            <div class="media-body" style="text-overflow: clip;">
                                                @if (!is_null($post->post_id))
                                                    <div class="text-muted">Replying to </div>
                                                @endif
                                                <h6 class="mt-0">
                                                    <a class="text-primary-hover" href="{{ route('users.show', $post->user->username) }}" style="color: #555;">
                                                        {{ $post->user->name }}
                                                    </a>
                                                    @if ($post->user->verified)
                                                        <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
                                                    @endif
                                                    <ul class="list-inline text-muted d-inline-block font-weight-regular" style="font-size: 13px;">
                                                        <li class="list-inline-item">
                                                            {{ '@' . $post->user->username }}
                                                        </li>
                                                    </ul>
                                                </h6>
                                                <p class="mb-1" style="font-family: 'Roboto', arial; font-size: 14px; white-space: pre-wrap;">{{ $post->content }}</p>
                                                <ul class="list-inline text-dark">
                                                    <li class="list-inline-item mr-3">
                                                        <a class="text-muted text-primary-hover text-no-underline" href="{{ route('posts.show', $post->id) }}">
                                                            <i class="fa fa-comment"></i> {{ $post->comment_count }}
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item mr-3">
                                                        <post-share 
                                                            :post-id="{{ $post->id }}" 
                                                            :user-id="{{ Auth::id() }}" 
                                                            :count-original="{{ $post->share_count }}" 
                                                            :private="{{ $post->user->private }}"
                                                        ></post-share>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <post-like 
                                                            :post-id="{{ $post->id }}" 
                                                            :user-id="{{ Auth::id() }}" 
                                                            :count-original="{{ $post->like_count }}"
                                                        ></post-like>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-md-3">

                @include('components.who_to_follow')

                @include('components.site_info')

            </div>
        </div>
    </div>
</div>
@endsection
