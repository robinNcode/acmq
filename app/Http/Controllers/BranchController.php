<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BranchService;

class BranchController extends Controller
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index()
    {
        $branches = $this->branchService->getPaginatedBranches(30);
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

        $this->branchService->createBranch($data);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('branches.index');
    }

    public function edit(string $id)
    {
        $branch = $this->branchService->getBranchById($id);
        return view('branches.form', compact('branch'));
    }

    public function update(Request $request, string $id)
    {
        $branch = $this->branchService->getBranchById($id);

        $data = $request->validate([
            'branch_code' => 'required|string|max:10|unique:branches,branch_code,' . $branch->id,
            'name' => 'required|string|max:255',
            'thana' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string',
        ]);

        $this->branchService->updateBranch($id, $data);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->branchService->deleteBranch($id);

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}

