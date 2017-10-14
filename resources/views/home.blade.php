@extends('layouts.app')

@section('content')
<div class="pv-1">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-fluid" src="http://placehold.it/600x250" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                <div class="bg-white p-3 mb-3">
                    <div class="media">
                        <a href="{{ url('/' . Auth::user()->username) }}">
                            <img class="d-flex align-self-start rounded-circle mr-3" src="{{ Auth::user()->photo(Auth::id()) }}" style="max-width: 48px; max-height: 48px;" alt="Profile photo">
                        </a>
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">
                                <a href="{{ url('/' . Auth::user()->username) }}" class="text-dark">
                                    {{ Auth::user()->name }}
                                </a>
                            </h6>
                            <span class="text-muted" style="font-size: 14px;">{{ '@' . Auth::user()->username }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <a class="text-bold text-muted text-primary-hover" href="{{ url('/' . Auth::user()->username) }}" style="font-size: 13px;">Posts</a><br>
                            <b>{{ number_shorten(Auth::user()->posts->count(), 0) }}</b>
                        </div>
                        <div class="col-lg-4">
                            <a class="text-bold text-muted text-primary-hover" href="{{ url('/' . Auth::user()->username . '/following') }}" style="font-size: 13px;">Following</a><br>
                            <b>{{ number_shorten(count(Auth::user()->follows)) }}</b>
                        </div>
                        <div class="col-lg-4">
                            <a class="text-bold text-muted text-primary-hover" href="{{ url('/' . Auth::user()->username . '/followers') }}" style="font-size: 13px;">Followers</a><br>
                            <b>{{ number_shorten(count(Auth::user()->followers)) }}</b>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-3 mb-3">
                    <h5>United States trends</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="#">#Laravel</a>
                            <br>
                            <span class="text-muted" style="font-size: 13px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est.</span>
                        </li>
                        <li class="mb-2">
                            <a href="#">#Laravel</a>
                            <br>
                            <span class="text-muted" style="font-size: 13px;">{{ number_format(rand(1340, 2885)) }} posts</span>
                        </li>
                        <li class="mb-2">
                            <a href="#">#Laravel</a>
                            <br>
                            <span class="text-muted" style="font-size: 13px;">{{ number_format(rand(1340, 2885)) }} posts</span>
                        </li>
                        <li class="mb-2">
                            <a href="#">#Laravel</a>
                            <br>
                            <span class="text-muted" style="font-size: 13px;">{{ number_format(rand(1340, 2885)) }} posts</span>
                        </li>
                        <li>
                            <a href="#">#Laravel</a>
                            <br>
                            <span class="text-muted" style="font-size: 13px;">{{ number_format(rand(1340, 2885)) }} posts</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                @if (count(Auth::user()->follows) <= 3)
                    <div class="p-3 mb-0 bg-warning">
                        To get the full {{ config('app.name') }} experience, follow some people.
                    </div>
                @endif
                <div class="p-3 mb-0" style="background-color: #FCFCFC; border: 1px solid #DDD; border-bottom: 0;">
                    <new-post :user-id="{{ Auth::id() }}"></new-post>
                </div>
                <div class="bg-white">
                    <posts :current-user-id="{{ Auth::id() }}"></posts>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-3 mb-3">
                    <h5>Who to follow</h5>
                    @if (count($follow_suggestions))
                        @foreach ($follow_suggestions as $follow_suggestion)
                            <div class="media">
                                <a href="{{ url('/' . $follow_suggestion->username) }}">
                                    <img class="d-flex align-self-start rounded-circle mr-3" src="{{ $follow_suggestion->photo($follow_suggestion->id) }}" style="max-width: 48px; max-height: 48px;" alt="User photo">
                                </a>
                                <div class="media-body" style="text-overflow: ellipsis;">
                                    <h6 class="mt-0" style="margin-bottom: 3px;">
                                        <a class="text-primary-hover" href="{{ url('/' . $follow_suggestion->username) }}" style="color: #555;">
                                            {{ $follow_suggestion->name }}
                                        </a>
                                        <span class="text-muted" style="font-size: 13px; font-weight: 400;">{{ '@' . $follow_suggestion->username }}</span>
                                    </h6>
                                    <user-follow :original-following="false" :user-id="{{ $follow_suggestion->id }}" :current-user-id="{{ Auth::id() }}"></user-follow>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2">
                        @endforeach
                        <a href="#">Search by interests</a>
                    @else
                        No suggestions.
                    @endif
                </div>
                <div class="bg-white p-3">
                    <ul class="list-inline mb-0" style="font-size: 13px;">
                        <li class="list-inline-item">
                            &copy; {{ date('Y') }} {{ config('app.name') }}
                        </li>
                        <li class="list-inline-item">
                            <a href="#">About</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Help Center</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Terms</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Privacy Policy</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Cookies</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Blog</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Advertise with {{ config('app.name') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
