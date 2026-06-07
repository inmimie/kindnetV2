<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;
use App\Models\Payment;
use App\Models\CharityType;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_users' => User::where('role', 'applicant')->count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'total_payments' => Payment::sum('amount') ?? 0,
        ];

        $query = Application::with(['user', 'charityType']);

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

        $applications = $query->paginate(10)->withQueryString();
        $charityTypes = CharityType::all();
        
        return view('admin.dashboard', compact('stats', 'applications', 'charityTypes'));
    }
}
