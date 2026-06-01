<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'applicant')->count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'total_payments' => Payment::sum('amount') ?? 0,
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}
