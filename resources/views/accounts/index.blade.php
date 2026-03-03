@extends('layouts.app')

@section('title', 'Ledger Heads (Accounts)')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Ledger Heads</h1>
        <p class="text-sm text-gray-500 mt-1">Manage all your accounting heads, assets, liabilities, income, and expenses.</p>
    </div>
    <button onclick="openModal('create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-sm font-medium transition-colors duration-200">
        + New Account
    </button>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center shadow-sm">
    <svg class="h-5 w-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center shadow-sm">
    <svg class="h-5 w-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
    {{ session('error') }}
</div>
@endif

<!-- Accounts Table -->
<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50/75 border-b text-gray-500 text-sm uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4 font-medium">Code</th>
                <th class="px-6 py-4 font-medium">Account Name</th>
                <th class="px-6 py-4 font-medium">Type</th>
                <th class="px-6 py-4 font-medium">Branch ID</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            @forelse($accounts as $account)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 font-semibold text-gray-900">{{ $account->code }}</td>
                <td class="px-6 py-4">{{ $account->name }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if($account->type === 'asset') bg-blue-100 text-blue-800
                        @elseif($account->type === 'liability') bg-red-100 text-red-800
                        @elseif($account->type === 'equity') bg-purple-100 text-purple-800
                        @elseif($account->type === 'income') bg-green-100 text-green-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($account->type) }}
                    </span>
                </td>
                <td class="px-6 py-4">{{ $account->branch_id }}</td>
                <td class="px-6 py-4 text-right">
                    <button onclick="openModal('edit', {{ $account->toJson() }})" class="text-indigo-600 hover:text-indigo-900 font-medium mr-3 transition-colors">Edit</button>
                    <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this account?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-lg font-medium">No accounts found.</p>
                        <p class="text-sm mt-1">Get started by creating a new account head.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/60 hidden transition-opacity z-40 backdrop-blur-sm" aria-hidden="true" onclick="closeModal()"></div>

<!-- Modal Content -->
<div id="accountModal" class="fixed inset-0 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 w-full">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md scale-95 opacity-0 transition-transform transition-opacity duration-300 transform" id="modalPanel" onclick="event.stopPropagation()">
            <div class="border-b px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Create New Account</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="accountForm" method="POST" action="{{ route('accounts.store') }}" class="px-6 py-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="space-y-4">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Account Code</label>
                        <input type="text" name="code" id="code" required class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 outline-none transition-colors" placeholder="e.g. 1000">
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                        <input type="text" name="name" id="name" required class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 outline-none transition-colors" placeholder="e.g. Cash in Hand">
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                        <select name="type" id="type" required class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 outline-none transition-colors">
                            <option value="asset">Asset (e.g. Cash, Accounts Receivable)</option>
                            <option value="liability">Liability (e.g. Accounts Payable, Loans)</option>
                            <option value="equity">Equity (e.g. Capital, Retained Earnings)</option>
                            <option value="income">Income (e.g. Sales, Interest)</option>
                            <option value="expense">Expense (e.g. Rent, Utilities)</option>
                        </select>
                    </div>

                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Branch ID</label>
                        <input type="number" name="branch_id" id="branch_id" value="1" required class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border border-gray-200 outline-none transition-colors">
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">Cancel</button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm font-medium transition-colors tracking-wide">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modal = document.getElementById('accountModal');
    const modalPanel = document.getElementById('modalPanel');
    const accountForm = document.getElementById('accountForm');
    const formMethod = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');

    function openModal(mode, account = null) {
        // Reset form
        accountForm.reset();
        
        // Setup Form based on mode
        if (mode === 'create') {
            modalTitle.textContent = 'Create New Account';
            accountForm.action = '{{ route("accounts.store") }}';
            formMethod.value = 'POST';
            submitBtn.textContent = 'Save Account';
        } else if (mode === 'edit' && account) {
            modalTitle.textContent = 'Edit Account';
            accountForm.action = `/accounts/${account.id}`;
            formMethod.value = 'PUT';
            
            // Populate fields
            document.getElementById('code').value = account.code;
            document.getElementById('name').value = account.name;
            document.getElementById('type').value = account.type;
            document.getElementById('branch_id').value = account.branch_id;
            
            submitBtn.textContent = 'Update Account';
        }

        // Show modal with animation
        modalBackdrop.classList.remove('hidden');
        modal.classList.remove('hidden');
        
        // Trigger reflow to ensure the initial state is applied before class changes
        void modalPanel.offsetWidth;
        
        modalPanel.classList.remove('scale-95', 'opacity-0');
        modalPanel.classList.add('scale-100', 'opacity-100');
    }

    function closeModal() {
        // Hide modal with animation
        modalPanel.classList.remove('scale-100', 'opacity-100');
        modalPanel.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modalBackdrop.classList.add('hidden');
            modal.classList.add('hidden');
        }, 300); // Matches the duration-300 class
    }

    // Include basic form error handling display based on session data
    @if($errors->any())
        // If there are validation errors, we ideally want to show the modal again.
        // Doing this safely with vanilla JS.
        openModal('create');
        let errorMsg = @json($errors->first());
        alert('Validation Error: ' + errorMsg);
    @endif
</script>
@endsection
