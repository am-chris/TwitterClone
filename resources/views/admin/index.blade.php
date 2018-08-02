@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="bg-white border p-3 mb-4 rounded">
                    <h4>{{ number_format($users->count()) }} Users</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white border p-3 mb-4 rounded">
                    <h4>{{ number_format($posts->count()) }} Posts</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white border p-3 mb-4 rounded">
                    <h4>{{ number_format($shares->count()) }} Shares</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white border p-3 mb-4 rounded">
                    <h4>{{ number_format($likes->count()) }} Likes</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Reports</div>
                    <div class="card-body" style="max-height: 50vh; overflow: auto;">
                        @forelse ($reports as $report)
                            <div>
                                <p>{{ $report->reporter->name }} reported {{ $report->reported->name }}</p>
                                <p>{{ $report->type }} {{ $report->description }}</p>
                            </div>
                        @empty
                            No reports were found.
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection