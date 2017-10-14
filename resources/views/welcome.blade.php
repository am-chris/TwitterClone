@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4>Live Video</h4>
                <img src="http://placehold.it/1200x400" class="img-fluid">
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <h4>New to {{ config('app.name') }}?</h4>
                    <p>Sign up now to personalize your timeline.</p>
                    <a href="{{ route('login') }}" class="btn btn-default">
                        Sign up
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
