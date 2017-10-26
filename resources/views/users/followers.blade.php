@extends('layouts.app')

@section('content')
<div class="pv-1">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-fluid" src="http://placehold.it/600x250" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                <div class="bg-white p-3 mb-3">
                    <div class="media">
                        <a href="{{ url('/' . $user->username) }}">
                            <img class="d-flex align-self-start rounded-circle mr-3" src="{{ $user->photo($user->id) }}" style="max-width: 48px; max-height: 48px;" alt="Profile photo">
                        </a>
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">
                                <a href="{{ url('/' . $user->username) }}" class="text-dark">
                                    {{ $user->name }}
                                </a>
                            </h6>
                            <span class="text-muted" style="font-size: 14px;">{{ '@' .$user->username }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <a class="text-bold text-muted text-primary-hover" href="{{ url('/' . $user->username) }}" style="font-size: 13px;">Posts</a><br>
                            <b>{{ number_shorten($user->posts->count(), 0) }}</b>
                        </div>
                        <div class="col-lg-4">
                            <a class="text-bold text-muted text-primary-hover" href="{{ url('/' . $user->username . '/following') }}" style="font-size: 13px;">Following</a><br>
                            <b>{{ number_shorten(count($user->follows)) }}</b>
                        </div>
                        <div class="col-lg-4">
                            <a class="text-bold text-muted text-primary-hover" href="{{ url('/' . $user->username . '/followers') }}" style="font-size: 13px;">Followers</a><br>
                            <b>{{ number_shorten(count($user->followers)) }}</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <nav class="nav nav-pills nav-justified bg-white p-2 mb-3 rounded">
                    <a class="nav-link" href="{{ url('/' . $user->username) }}">Profile</a>
                    <a class="nav-link" href="{{ url('/' . $user->username . '/following') }}">Following</a>
                    <a class="nav-link active" href="#">Followers</a>
                    @if (Auth::id() == $user->id)
                        <a class="nav-link ml-auto btn btn-outline-primary" href="{{ url('/' . $user->username . '/edit') }}">Edit Profile</a>
                    @else
                        <span class="ml-auto">
                            <user-follow :user-id="{{ $user->id }}" :current-user-id="{{ Auth::id() }}" :original-following="{{ json_encode(Auth::user()->followingUser($user->id)) }}"></user-follow>
                        </span>
                    @endif
                </nav>
                <div class="row">
                    @foreach ($user->followers as $follower)
                        <div class="col-md-4">
                            <a href="{{ url('/' . $follower->username) }}">
                                <img class="img-fluid" src="http://placehold.it/600x250" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                            </a>
                            <div class="bg-white p-3 mb-4">
                                <div class="media mb-1">
                                    <a href="{{ url('/' . $follower->username) }}">
                                        <img class="d-flex align-self-start mr-3 rounded-circle" src="{{ $follower->photo_url }}" style="max-width: 48px; max-height: 48px;" alt="Generic placeholder image">
                                    </a>
                                    <div class="media-body" style="text-overflow: clip;">
                                        <user-follow :user-id="{{ $follower->id }}" :current-user-id="{{ Auth::id() }}" :original-following="{{ json_encode(Auth::user()->followingUser($follower->id)) }}"></user-follow>
                                    </div>
                                </div>
                                <h6 class="mt-0 mb-1">
                                    <a class="text-dark" href="{{ url('/' . $follower->username) }}">
                                        {{ $follower->name }}
                                    </a>
                                    @if ($follower->verified > 0)
                                        <i class="fa fa-check-circle text-primary"></i>
                                    @endif
                                </h6>
                                <span class="text-muted" style="font-size: 13px; font-weight: 400;">
                                    {{ '@' . $follower->username }}
                                </span>
                                <p class="mb-0" style="height: 4.2em;">
                                    {{ $follower->bio }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
