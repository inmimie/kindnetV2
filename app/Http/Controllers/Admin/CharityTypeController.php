<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CharityType;
use Illuminate\Http\Request;

class CharityTypeController extends Controller
{
    public function index()
    {
        $charityTypes = CharityType::all();
        return view('admin.charity-types.index', compact('charityTypes'));
    }

    public function create()
    {
        return view('admin.charity-types.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string']);
        CharityType::create($request->all());
        return redirect()->route('admin.charity-types.index')->with('success', 'Charity Type created successfully.');
    }

    public function show(CharityType $charityType)
    {
        return view('admin.charity-types.show', compact('charityType'));
    }

    public function edit(CharityType $charityType)
    {
        return view('admin.charity-types.edit', compact('charityType'));
    }

    public function update(Request $request, CharityType $charityType)
    {
        $request->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string']);
        $charityType->update($request->all());
        return redirect()->route('admin.charity-types.index')->with('success', 'Charity Type updated successfully.');
    }

    public function destroy(CharityType $charityType)
    {
        $charityType->delete();
        return redirect()->route('admin.charity-types.index')->with('success', 'Charity Type deleted successfully.');
    }
}
