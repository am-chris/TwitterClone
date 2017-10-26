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
                        <div class="media-body truncate">
                            <h6 class="mt-0 mb-1">
                                <a href="{{ url('/' . $user->username) }}" class="text-dark" title="{{ $user->name }}">
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
                    <a class="nav-link active" href="#">Profile</a>
                    <a class="nav-link" href="{{ url('/' . $user->username . '/following') }}">Following</a>
                    <a class="nav-link" href="{{ url('/' . $user->username . '/followers') }}">Followers</a>
                    @if (Auth::id() == $user->id)
                        <a class="nav-link ml-auto btn btn-outline-primary" href="{{ url('/' . $user->username . '/edit') }}">Edit Profile</a>
                    @endif
                </nav>
                <div class="bg-white">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
