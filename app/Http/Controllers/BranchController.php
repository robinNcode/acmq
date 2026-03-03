<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = \App\Models\Branch::orderBy('id', 'desc')->paginate(30);
        return view('branches.index', ['branches' => $branches]);
    }

    public function create()
    {
        return view('branches.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_code' => 'required|string|max:10|unique:branches,branch_code',
            'name' => 'required|string|max:255',
            'thana' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string',
        ]);

        \App\Models\Branch::create($data);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('branches.index');
    }

    public function edit(string $id)
    {
        $branch = \App\Models\Branch::findOrFail($id);
        return view('branches.form', compact('branch'));
    }

    public function update(Request $request, string $id)
    {
        $branch = \App\Models\Branch::findOrFail($id);

        $data = $request->validate([
            'branch_code' => 'required|string|max:10|unique:branches,branch_code,' . $branch->id,
            'name' => 'required|string|max:255',
            'thana' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string',
        ]);

        $branch->update($data);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(string $id)
    {
        $branch = \App\Models\Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
