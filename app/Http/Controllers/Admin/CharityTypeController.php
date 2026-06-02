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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('charity_types', 'public');
        }

        CharityType::create($validated);
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            if ($charityType->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($charityType->image);
            }
            $validated['image'] = $request->file('image')->store('charity_types', 'public');
        }

        $charityType->update($validated);
        return redirect()->route('admin.charity-types.index')->with('success', 'Charity Type updated successfully.');
    }

    public function destroy(CharityType $charityType)
    {
        $charityType->delete();
        return redirect()->route('admin.charity-types.index')->with('success', 'Charity Type deleted successfully.');
    }
}
