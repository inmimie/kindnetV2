<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CharityType;

class DashboardController extends Controller
{
    public function index()
    {
        $applications = auth()->user()->applications()->latest()->take(5)->get();
        $charityTypes = CharityType::all();
        return view('applicant.dashboard', compact('applications', 'charityTypes'));
    }
}
