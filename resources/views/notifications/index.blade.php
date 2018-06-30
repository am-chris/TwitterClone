@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">

                @include('components.trends')

            </div>
            <div class="col-md-6">
                @if (Auth::user()->private && count($follow_requests) > 0)
                    <div class="bg-white p-3 mb-3 border-gray">
                        <h6>Follow Requests</h6>
                        @if (count($follow_requests))
                            <ul class="list-inline mb-0">
                                @foreach ($follow_requests as $follow_request)
                                    <li class="list-inline-item">
                                        <a href="{{ url('/' . $follow_request->follower->username) }}">
                                            <img class="d-flex align-self-start mr-3 rounded-circle" src="{{ url('storage/' . $follow_request->follower->photo_url) }}" style="max-width: 48px; max-height: 48px;" alt="User photo" rel="tooltip" data-original-title="{{ '@' . $follow_request->follower->username }}">
                                        </a>
                                        <follow-request-actions :user-id="{{ $follow_request->follower->id }}" :current-user-id="{{ Auth::id() }}"></follow-request-actions>
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
                                    <a href="{{ url('/p/' . $post->id) }}" style="text-decoration: none;">
                                        <div class="media">
                                            <a href="{{ url('/' . $post->user->username) }}">
                                                <img class="d-flex align-self-start mr-3 rounded-circle" src="{{ url('storage/' . $post->user->photo_url) }}" style="max-width: 48px; max-height: 48px;" alt="User photo">
                                            </a>
                                            <div class="media-body" style="text-overflow: clip;">
                                                @if (!is_null($post->post_id))
                                                    <div class="text-muted">Replying to </div>
                                                @endif
                                                <h6 class="mt-0">
                                                    <a class="text-primary-hover" href="{{ url('/' . $post->user->username) }}" style="color: #555;">
                                                        {{ $post->user->name }}
                                                    </a>
                                                    @if ($post->user->verified)
                                                        <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
                                                    @endif
                                                    <ul class="list-inline text-muted d-inline-block" style="font-size: 13px; font-weight: 400;">
                                                        <li class="list-inline-item">
                                                            {{ '@' . $post->user->username }}
                                                        </li>
                                                    </ul>
                                                </h6>
                                                <pre class="mb-1" style="font-family: 'Roboto', arial; font-size: 14px; white-space: pre-wrap;">{{ $post->content }}</pre>
                                                <ul class="list-inline text-dark">
                                                    <li class="list-inline-item mr-3">
                                                        <a class="text-muted text-primary-hover text-no-underline" href="{{ url('/p/' . $post->id) }}">
                                                            <i class="fa fa-comment"></i> {{ $post->comment_count }}
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item mr-3">
                                                        <post-share :post-id="{{ $post->id }}" :user-id="{{ Auth::id() }}" :count-original="{{ $post->share_count }}" :private="{{ $post->user->private }}"></post-share>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <post-like :post-id="{{ $post->id }}" :user-id="{{ Auth::id() }}" :count-original="{{ $post->like_count }}"></post-like>
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
