@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Report Reason</div>
                    <div class="card-body">
                        <form action="{{ route('admin.reports.reasons.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="name">Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="name">Description <span class="required">*</span></label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection