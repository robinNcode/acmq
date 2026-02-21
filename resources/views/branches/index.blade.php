@extends('layouts.app')

@section('title', 'Branches')
@section('page-title', 'Branches')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Branch List</h2>
            <a href="{{ route('branches.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Branch
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Code</th>
                    <th class="p-3 border">Branch Name</th>
                    <th class="p-3 border">Location</th>
                    <th class="p-3 border">District</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($branches as $branch)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration }}</td>
                        <td class="p-3 border">{{ $branch->branch_code }}</td>
                        <td class="p-3 border">{{ $branch->name }}</td>
                        <td class="p-3 border">{{ $branch->address }}</td>
                        <td class="p-3 border">{{ $branch->district }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $branches->links() }}
        </div>
    </div>
@endsection
