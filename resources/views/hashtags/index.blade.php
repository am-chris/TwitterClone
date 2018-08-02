@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('components.trends')
            </div>
            <div class="col-md-6">
                @foreach ($posts as $post)
                    <li>{{ $post->user->name }} {{ $post->content }}</li>
                @endforeach
            </div>
            <div class="col-md-3">
                @include('components.who_to_follow')
            </div>
        </div>
    </div>
</div>
@endsection