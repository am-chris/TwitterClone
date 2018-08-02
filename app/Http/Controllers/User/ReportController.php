<?php

namespace App\Http\Controllers\User;

use Auth;
use Carbon\Carbon;
use App\User;
use App\Models\User\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        if (Auth::user()->id == $userId) {
            abort(403);
        }

        // Prevent users from spamming reports
        $hourReportCount = Report::where('reporter_id', Auth::id())
            ->whereBetween('created_at', [Carbon::now()->subHours(1)->toDateTimeString(), Carbon::now()->toDateTimeString()])
            ->select('id')
            ->get();

        $todaysReportCount = Report::where('reporter_id', Auth::id())
            ->whereBetween('created_at', [Carbon::now()->subHours(24)->toDateTimeString(), Carbon::now()->toDateTimeString()])
            ->select('id')
            ->get();

        if ($hourReportCount->count() >= config('settings.max_reports_per_hour') || $todaysReportCount->count() >= config('settings.max_reports_per_hour')) {
            abort(403);
        }

        Report::create([
            'reporter_id' => Auth::user()->id,
            'reported_id' => $userId,
            'type' => $request->type,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
