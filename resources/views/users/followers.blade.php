@extends('layouts.app')

@section('content')
<img src="{{ url('storage/' . $user->cover_photo_url) }}" style="width: 100%; max-height: 500px;">
<div class="py-2">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3" style="position: relative; margin-top: -100px;">
                    <a href="{{ url('/' . $user->username) }}">
                        <img class="d-flex align-self-start rounded-circle mr-3 mx-auto" src="{{ url('storage/' . $user->photo_url) }}" style="max-width: 200px; max-height: 200px; border: 4px solid #FFF;" rel="tooltip" data-original-title="{{ $user->username }}" alt="Profile photo">
                    </a>
                </div>
                <h5>
                    <a href="{{ url('/' . $user->username) }}" style="color: #555;">{{ $user->name }}</a>
                    @if ($user->verified)
                        <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
                    @endif
                </h5>
                <a href="{{ url('/' . $user->username) }}" class="text-muted">{{ '@' . $user->username }}</a>
                <p>{{ $user->bio }}</p>
                <ul class="list-unstyled">
                    @if (!is_null($user->created_at))
                        <li>
                            <i class="fa fa-calendar-o"></i> Joined {{ Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('M j Y') }}
                        </li>
                    @endif
                </ul>
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
                            @if (Auth::check())
                                <user-follow :user-id="{{ $user->id }}" :current-user-id="{{ Auth::id() }}" :original-following="{{ json_encode(Auth::user()->followingUser($user->id)) }}"></user-follow>
                            @endif
                        </span>
                    @endif
                </nav>
                <div class="row">
                    @foreach ($user->followers as $follower)
                        <div class="col-md-4">
                            <a href="{{ url('/' . $follower->username) }}">
                                <img class="img-fluid" src="{{ url('storage/' . $follower->cover_photo_url) }}" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                            </a>
                            <div class="bg-white p-3 mb-4">
                                <div class="media mb-1">
                                    <a href="{{ url('/' . $follower->username) }}">
                                        <img class="d-flex align-self-start mr-3 rounded-circle" src="{{ url('storage/' . $follower->photo_url) }}" style="max-width: 48px; max-height: 48px;" alt="Generic placeholder image">
                                    </a>
                                    <div class="media-body" style="text-overflow: clip;">
                                        @if (Auth::id() !== $follower->id && Auth::check())
                                            <user-follow :user-id="{{ $follower->id }}" :current-user-id="{{ Auth::id() }}" :original-following="{{ json_encode(Auth::user()->followingUser($follower->id)) }}"></user-follow>
                                        @endif
                                    </div>
                                </div>
                                <div class="truncate">
                                    <h6 class="mt-0 mb-1">
                                        <a class="text-dark" href="{{ url('/' . $follower->username) }}" title="{{ $follower->name }}">
                                            {{ $follower->name }}
                                        </a>
                                        @if ($follower->verified > 0)
                                            <i class="fa fa-check-circle text-primary"></i>
                                        @endif
                                    </h6>
                                </div>
                                <span class="text-muted" style="font-size: 13px; font-weight: 400;">
                                    {{ '@' . $follower->username }}
                                </span>
                                <p class="mb-0" style="height: 4.4em; overflow-wrap: break-word; word-wrap: break-word; overflow: hidden;">
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
