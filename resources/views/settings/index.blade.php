@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-password-tab" data-toggle="pill" href="#v-password" role="tab" aria-controls="v-password" aria-selected="true">
                        Password
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-password" role="tabpanel" aria-labelledby="v-password-tab">
                        <div class="card">
                            <div class="card-header">Password</div>
                            <div class="card-body">
                                <form action="{{ route('settings.passwords.update', Auth::user()->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Repeat New Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection