@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-fluid" src="{{ url('storage/' . Auth::user()->cover_photo_url) }}" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                <div class="bg-white p-3 mb-3">
                    <div class="media">
                        <a href="{{ url('/' . Auth::user()->username) }}">
                            <img class="d-flex align-self-start rounded-circle mr-3" src="{{ url('storage/' . Auth::user()->photo_url) }}" style="max-width: 48px; max-height: 48px;" alt="Profile photo">
                        </a>
                        <div class="media-body truncate">
                            <h6 class="mt-0 mb-1">
                                <a href="{{ url('/' . Auth::user()->username) }}" class="text-dark">
                                    {{ Auth::user()->name }}
                                </a>
                                @if (Auth::user()->verified)
                                    <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account"></i>
                                @endif
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

                @include('components.trends')

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

                @include('components.who_to_follow')

                @include('components.site_info')

            </div>
        </div>
    </div>
</div>
@endsection
