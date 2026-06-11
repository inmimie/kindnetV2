<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\SmsService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['user', 'charityType'])->latest()->get();
        return view('admin.applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        $application->load(['user', 'charityType', 'payment']);
        return view('admin.applications.show', compact('application'));
    }

    public function edit(Application $application)
    {
        return view('admin.applications.edit', compact('application'));
    }

    public function update(Request $request, Application $application, SmsService $smsService)
    {
        if (in_array($application->status, ['approved', 'rejected'])) {
            return redirect()->route('admin.applications.show', $application)
                ->with('error', 'Cannot update status of an application that is already approved or rejected.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $oldStatus = $application->status;
        $application->update([
            'status' => $request->status,
            'approved_at' => $request->status === 'approved' ? now() : null,
        ]);

        if ($oldStatus !== $request->status && in_array($request->status, ['approved', 'rejected'])) {
            if ($application->user->phone_number) {
                $smsService->sendSms(
                    $application->user->phone_number, 
                    "Your charity application #{$application->id} has been {$request->status}."
                );
            }
        }

        return redirect()->route('admin.applications.show', $application)->with('success', 'Application status updated.');
    }
}
