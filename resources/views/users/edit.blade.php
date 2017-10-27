@extends('layouts.app')

@section('content')
<div class="pv-1">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                {{ Form::open(['url' => 'u/' . $user->id . '/cover_photo', 'method' => 'POST', 'files' => true]) }}
                    <div class="form-group">
                        {{ Form::label('file', 'Cover Photo (1500x500)') }}
                        {{ Form::file('file') }}
                    </div>
                    {{ Form::submit('Upload', ['class' => 'btn btn-sm btn-success mb-2']) }}
                {{ Form::close() }}
                {{ Form::open(['url' => 'u/' . $user->id . '/cover_photo', 'method' => 'DELETE']) }}
                    <div class="form-group">
                        {{ Form::submit('Remove', ['class' => 'btn btn-sm btn-danger']) }}
                    </div>
                {{ Form::close() }}
                <img src="{{ url('storage/' . $user->cover_photo_url) }}" class="img-fluid" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                <div class="bg-white p-3 mb-3">
                    {{ Form::open(['url' => $user->username, 'method' => 'PUT']) }}
                        <div class="media">
                            <a href="{{ url('/' . $user->username) }}">
                                <img class="d-flex align-self-start rounded-circle mr-3" src="{{ $user->photo($user->id) }}" style="max-width: 48px; max-height: 48px;" alt="Profile photo">
                            </a>
                            <div class="media-body">
                                {{ Form::text('name', $user->name, ['class' => 'form-control mb-1', 'required' => 'required']) }}
                                {{ Form::text('username', '@' . $user->username, ['class' => 'form-control form-control-sm mb-1', 'required' => 'required']) }}
                                {{ Form::submit('Save', ['class' => 'btn btn-sm btn-success']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
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
                    <a class="nav-link ml-auto btn btn-outline-danger" href="{{ url('/' . $user->username) }}">Cancel</a>
                </nav>
                <div class="bg-white">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
