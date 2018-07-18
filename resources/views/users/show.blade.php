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
                    <a class="nav-link text-center active" href="#">
                        <div class="profile-nav-link">Posts</div>
                        <span class="text-bold">{{ number_shorten($user->posts->count(), 0) }}</span>
                    </a>
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username . '/following') }}">
                        <div class="profile-nav-link">Following</div>
                        <span class="text-bold">{{ number_shorten($user->follows->count(), 0) }}</span>
                    </a>
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username . '/followers') }}">
                        <div class="profile-nav-link">Followers</div>
                        <span class="text-bold">{{ number_shorten($user->followers->count(), 0) }}</span>
                    </a>
                    @if (Auth::id() == $user->id || Auth::check() && Auth::user()->hasRole('admin'))
                        <user-edit></user-edit>
                    @else
                        <span class="ml-auto">
                            @if (Auth::check())
                                <user-follow 
                                    :o-following="{{ json_encode(Auth::user()->followingUser($user->id)) }}" 
                                    :o-requested="{{ json_encode(Auth::user()->followRequested($user->id)) }}" 
                                    :private="{{ $user->private }}" 
                                    :user-id="{{ $user->id }}" 
                                    :current-user-id="{{ Auth::id() }}"
                                ></user-follow>
                            @endif
                        </span>
                    @endif
                </nav>
                @if (Auth::check())
                    @if ($user->private == 0 || $user->private == 1 && Auth::user()->followingUser($user->id) != 0 || Auth::id() == $user->id)
                        <div class="bg-white">
                            <user-posts :current-user-id="{{ json_encode(Auth::id()) }}" :user-id="{{ json_encode($user->id) }}"></user-posts>
                        </div>
                    @else
                        <h5>This account's Posts are private.</h5>
                        <p>Only confirmed followers can view {{ '@' . $user->username }}'s Posts and profile. Click the Follow button to send a follow request.</p>
                    @endif
                @else
                    @if ($user->private)
                        <h5>This account's Posts are private.</h5>
                        <p>Only confirmed followers can view {{ '@' . $user->username }}'s Posts and profile. Click the Follow button to send a follow request.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
