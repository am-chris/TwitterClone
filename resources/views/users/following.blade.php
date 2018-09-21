@extends('layouts.app')

@section('content')
<user-cover-photo 
    :src="{{ json_encode(Storage::url($user->cover_photo_url)) }}" 
    :change-cover-photo-url="{{ json_encode(route('api.users.cover_photos.store', $user->id)) }}" 
    :user="{{ json_encode($user) }}"
></user-cover-photo>
<div class="py-2">
    <div class="container">
        <div class="row">
            <div class="col-md-3">

                @include('components.users.about')
                
            </div>
            <div class="col-md-9">
                <nav class="nav nav-pills nav-justified bg-white p-2 mb-3 rounded">
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username) }}">
                        <div class="profile-nav-link">Posts</div>
                        <span class="text-bold">{{ number_shorten(count($user->posts), 0) }}</span>
                    </a>
                    <a class="nav-link text-center active" href="#">
                        <div class="profile-nav-link">Following</div>
                        <span class="text-bold">{{ number_shorten(Redis::zcard('following:' . $user->id), 0) }}</span>
                    </a>
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username . '/followers') }}">
                        <div class="profile-nav-link">Followers</div>
                        <span class="text-bold">{{ number_shorten(Redis::zcard('followers:' . $user->id), 0) }}</span>
                    </a>
                    @if (Auth::id() == $user->id)
                        <a class="nav-link ml-auto btn btn-outline-primary" href="{{ url('/' . $user->username . '/edit') }}" style="max-height: 40px;">Edit Profile</a>
                    @else
                        <span class="ml-auto">
                            @if (Auth::check())
                                <user-follow 
                                    :o-following="{{ json_encode(Auth::user()->followingUser($user)) }}" 
                                    :o-requested="{{ json_encode(Auth::user()->followRequested($user)) }}" 
                                    :private="{{ $user->private }}" 
                                    :user-id="{{ $user->id }}" 
                                    :current-user-id="{{ Auth::id() }}"
                                ></user-follow>
                            @endif
                        </span>
                    @endif
                </nav>
                @foreach ($follows->chunk(3) as $chunk)
                    <div class="row">
                        @foreach ($chunk as $follow)
                            <div class="col-md-4">
                                <a href="{{ url('/' . $follow->username) }}">
                                    <img class="img-fluid" src="{{ Storage::url($follow->cover_photo_url) }}" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                                </a>
                                <div class="bg-white p-3 mb-4">
                                    <div class="media mb-1">
                                        <a href="{{ url('/' . $follow->username) }}">
                                            <img class="d-flex align-self-start mr-3 rounded-circle" src="{{ url('storage/' . $follow->photo_url) }}" style="max-width: 48px; max-height: 48px;" alt="Generic placeholder image">
                                        </a>
                                        <div class="media-body" style="text-overflow: clip;">
                                            @if (Auth::id() !== $follow->id && Auth::check())
                                                <user-follow 
                                                    :o-following="{{ json_encode(Auth::user()->followingUser($follow)) }}" 
                                                    :o-requested="{{ json_encode(Auth::user()->followRequested($follow)) }}" 
                                                    :private="{{ $follow->private }}" 
                                                    :user-id="{{ $follow->id }}" 
                                                    :current-user-id="{{ Auth::id() }}"
                                                ></user-follow>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="truncate">
                                        <h6 class="mt-0 mb-1">
                                            <a class="text-dark" href="{{ url('/' . $follow->username) }}" title="{{ $follow->name }}">
                                                {{ $follow->name }}
                                            </a>
                                            @if ($follow->private == true)
                                                <i class="fa fa-lock text-dark" rel="tooltip" data-original-title="Private"></i>
                                            @endif
                                            @if ($follow->verified)
                                                <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
                                            @endif
                                        </h6>
                                    </div>
                                    <span class="text-muted" style="font-size: 13px; font-weight: 400;">
                                        {{ '@' . $follow->username }}
                                    </span>
                                    <p class="mb-0" style="height: 4.4em; overflow-wrap: break-word; word-wrap: break-word; overflow: hidden;">
                                        {{ $follow->bio }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
