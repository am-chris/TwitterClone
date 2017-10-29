@extends('layouts.app')

@section('content')
<div class="pv-1">
    <div class="container">
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-6">
                <ul class="list-unstyled">
                    <div class="bg-white p-3 mb-0" style="border: 1px solid #DDD; border-bottom: none;">
                        <div class="media">
                            <a href="{{ url('/' . $post->user->username) }}">
                                <img class="d-flex align-self-start mr-3 rounded-circle" src="{{ url('storage/' . $post->user->photo_url) }}" style="max-width: 48px; max-height: 48px;" alt="Generic placeholder image">
                            </a>
                            <div class="media-body">
                                <h6 class="mt-0 mb-3">
                                    <a class="text-primary-hover" href="{{ url('/' . $post->user->username) }}" style="color: #555;">
                                        {{ $post->user->name }}
                                    </a>
                                    @if ($post->user->verified == 1)
                                        <i class="fa fa-check-circle text-primary"></i>
                                    @endif
                                    <ul class="list-inline text-muted d-inline-block" style="font-size: 13px; font-weight: 400;">
                                        <li class="list-inline-item">
                                            {{ '@' . $post->user->username }}
                                        </li>
                                    </ul>
                                    <ul class="list-inline text-muted d-inline-block float-right" style="font-size: 13px; font-weight: 400;">
                                        @if (Auth::id() !== $post->user->id)
                                            <li class="list-inline-item">
                                                @if (Auth::check())
                                                    <user-follow :user-id="{{ $post->user->id }}" :current-user-id="{{ Auth::id() }}" :original-following="{{ json_encode(Auth::user()->followingUser($post->user->id)) }}"></user-follow>
                                                @endif
                                            </li>
                                        @endif
                                    </ul>
                                </h6>
                                <p>{!! nl2br(e($post->content)) !!}</p>
                                <ul class="list-inline text-dark">
                                    <li class="list-inline-item mr-3">
                                        <a class="text-muted text-primary-hover text-no-underline">
                                            <i class="fa fa-comment"></i> {{ $post->comment_count }}
                                        </a>
                                    </li>
                                    <li class="list-inline-item mr-3">
                                        <post-share :post-id="{{ $post->id }}" :user-id="{{ json_encode(Auth::id()) }}" :count-original="{{ json_encode($post->share_count) }}" :shared-original="{{ $post->sharedByUser(Auth::id()) }}"></post-share>
                                    </li>
                                    <li class="list-inline-item">
                                        <post-like :post-id="{{ $post->id }}" :user-id="{{ json_encode(Auth::id()) }}" :count-original="{{ json_encode($post->like_count) }}" :liked-original="{{ $post->likedByUser(Auth::id()) }}"></post-like>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 mb-0" style="background-color: #FAFAFA; border: 1px solid #DDD; border-bottom: 0;">
                        {{ Form::open(['url' => 'p', 'method' => 'POST']) }}
                            {{ Form::hidden('post_id', $post->id) }}
                            <div class="form-group">
                                {{ Form::textarea('content', null, ['class' => 'form-control autosize', 'placeholder' => 'Reply to this post', 'required' => 'required', 'rows' => 1]) }}
                            </div>
                            {{ Form::submit('Post', ['class' => 'btn btn-sm btn-primary']) }}
                            <ul class="list-inline float-right">
                                <li class="list-inline-item">
                                    <i class="fa fa-image text-muted"></i>
                                </li>
                                <li class="list-inline-item">
                                    <i class="fa fa-play text-muted"></i>
                                </li>
                            </ul>
                        {{ Form::close() }}
                    </div>
                    <ul class="list-unstyled posts bg-white" style="border: 1px solid #DDD;">
                        @foreach ($comments as $comment)
                            <li class="p-3 post">
                                <div class="media">
                                    <a href="{{ url('/p/' . $comment->id) }}" style="text-decoration: none;">
                                        <img class="d-flex align-self-start mr-3 rounded-circle" src="{{ url('storage/' . $comment->user->photo_url) }}" style="max-width: 48px; max-height: 48px;" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <h6 class="mt-0 text-dark">
                                                {{ $comment->user->name }}
                                                @if ($post->user->verified == 1)
                                                    <i class="fa fa-check-circle text-primary"></i>
                                                @endif
                                                <ul class="list-inline text-muted d-inline-block" style="font-size: 13px; font-weight: 400;">
                                                    <li class="list-inline-item">
                                                        {{ '@' . $comment->user->username }}
                                                    </li>
                                                </ul>
                                            </h6>
                                            <p class="text-dark">{!! nl2br(e($comment->content)) !!}</p>
                                            <ul class="list-inline text-dark">
                                                <li class="list-inline-item mr-3">
                                                    <a class="text-muted text-primary-hover text-no-underline">
                                                        <i class="fa fa-comment"></i> {{ $post->comment_count }}
                                                    </a>
                                                </li>
                                                <li class="list-inline-item mr-3">
                                                    <post-share :post-id="{{ $comment->id }}" :user-id="{{ json_encode(Auth::id()) }}" :count-original="{{ json_encode($comment->share_count) }}"></post-share>
                                                </li>
                                                <li class="list-inline-item">
                                                    <post-like :post-id="{{ $comment->id }}" :user-id="{{ json_encode(Auth::id()) }}" :count-original="{{ json_encode($comment->like_count) }}"></post-like>
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </ul>
            </div>
            <div class="col-md-3">

            </div>
        </div>
    </div>
</div>
@endsection
