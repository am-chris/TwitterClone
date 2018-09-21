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
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username . '/following') }}">
                        <div class="profile-nav-link">Following</div>
                        <span class="text-bold">{{ number_shorten(count($user->following()), 0) }}</span>
                    </a>
                    <a class="nav-link text-center active" href="#">
                        <div class="profile-nav-link">Followers</div>
                        <span class="text-bold">{{ number_shorten(count($user->followers()), 0) }}</span>
                    </a>
                    @if (Auth::id() == $user->id)
                        <a class="nav-link ml-auto btn btn-outline-primary" href="{{ url('/' . $user->username . '/edit') }}" style="max-height: 40px;">Edit Profile</a>
                    @else
                        <span class="ml-auto">
                            @if (Auth::check())
                                <user-follow 
                                    :o-following="{{ json_encode(Redis::zscore("following:" . Auth::id(), $user->id) ? true : false) }}" 
                                    :o-requested="{{ json_encode(Auth::user()->followRequested($user->id)) }}" 
                                    :private="{{ $user->private }}" 
                                    :user-id="{{ $user->id }}" 
                                    :current-user-id="{{ Auth::id() }}"
                                ></user-follow>
                            @endif
                        </span>
                    @endif
                </nav>
                @foreach ($followers->chunk(3) as $chunk)
                    <div class="row">
                        @foreach ($chunk as $follower)
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
                                                <user-follow 
                                                    :o-following="{{ json_encode(Auth::user()->followingUser($follower)) }}" 
                                                    :o-requested="{{ json_encode(Auth::user()->followRequested($follower)) }}" 
                                                    :private="{{ $follower->private }}" 
                                                    :user-id="{{ $follower->id }}" 
                                                    :current-user-id="{{ Auth::id() }}"
                                                ></user-follow>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="truncate">
                                        <h6 class="mt-0 mb-1">
                                            <a class="text-dark" href="{{ url('/' . $follower->username) }}" title="{{ $follower->name }}">
                                                {{ $follower->name }}
                                            </a>
                                            @if ($follower->private == true)
                                                <i class="fa fa-lock text-dark" rel="tooltip" data-original-title="Private"></i>
                                            @endif
                                            @if ($follower->verified)
                                                <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
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
                @endforeach

                {{ $followers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
