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

                {{ Form::open(['url' => $user->username, 'method' => 'PUT']) }}
                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', $user->name, ['class' => 'form-control', 'maxlength' => 18, 'required' => 'required']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('username', 'Username') }}
                        {{ Form::text('username', '@' . $user->username, ['class' => 'form-control mb-2', 'maxlength' => 18, 'required' => 'required']) }}
                        @if ($user->verified)
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-circle text-danger"></i> If you change your username, your account will lose it's Verification status.
                            </div>
                        @endif
                    </div>
                    <div class="form-group mb-4">
                        {{ Form::label('bio', 'Bio') }}
                        {{ Form::textarea('bio', $user->bio, ['class' => 'form-control autosize maxlength', 'maxlength' => 100, 'rows' => 2]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::submit('Save', ['class' => 'btn btn-sm btn-success']) }}
                    </div>
                {{ Form::close() }}

                {{ Form::open(['url' => 'u/' . $user->id . '/cover_photo', 'method' => 'DELETE']) }}
                    <div class="form-group">
                        {{ Form::submit('Remove', ['class' => 'btn btn-sm btn-danger']) }}
                    </div>
                {{ Form::close() }}

                {{ Form::open(['url' => 'u/' . $user->id . '/photo', 'method' => 'POST', 'files' => true]) }}
                    <div class="form-group">
                        {{ Form::label('file', 'Profile Photo (128x128)') }}
                        {{ Form::file('file') }}
                    </div>
                    {{ Form::submit('Upload', ['class' => 'btn btn-sm btn-success mb-2']) }}
                {{ Form::close() }}

                {{ Form::open(['url' => 'u/' . $user->id . '/photo', 'method' => 'DELETE']) }}
                    <div class="form-group">
                        {{ Form::submit('Remove', ['class' => 'btn btn-sm btn-danger']) }}
                    </div>
                {{ Form::close() }}

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
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username) }}">
                        <div class="profile-nav-link">Posts</div>
                        <span class="text-bold">{{ number_shorten(count($user->posts), 0) }}</span>
                    </a>
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username . '/following') }}">
                        <div class="profile-nav-link">Following</div>
                        <span class="text-bold">{{ number_shorten(count($user->follows), 0) }}</span>
                    </a>
                    <a class="nav-link text-center text-dark" href="{{ url('/' . $user->username . '/followers') }}">
                        <div class="profile-nav-link">Followers</div>
                        <span class="text-bold">{{ number_shorten(count($user->followers), 0) }}</span>
                    </a>
                    <a class="nav-link ml-auto btn btn-outline-danger" href="{{ url('/' . $user->username) }}" style="max-height: 40px;">Return to Profile</a>
                </nav>
                <div class="bg-white">
                    <user-posts :current-user-id="{{ json_encode(Auth::id()) }}" :user-id="{{ json_encode($user->id) }}"></user-posts>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
