<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\SmsService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['user', 'charityType'])
            ->where('status', '!=', 'rejected');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Charity Type Filter
        if ($request->filled('charity_type_id')) {
            $query->where('charity_type_id', $request->input('charity_type_id'));
        }

        // Sort Filter
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $applications = $query->get();
        $charityTypes = \App\Models\CharityType::all();

        return view('admin.applications.index', compact('applications', 'charityTypes'));
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
