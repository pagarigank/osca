<div>
    <div class="content-header mb-4">
        <h2 class="text-2xl font-bold text-slate-800">Payout Cutoffs Maintenance</h2>
        <p class="text-slate-500 text-sm">Manage payout cutoffs for various funding sources.</p>
    </div>

    <div class="card p-6 bg-white rounded-xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-slate-800">Payout Cutoff List</h3>
            <button wire:click="openModal" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                + New Cutoff
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-y border-slate-200">
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Funding Source</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">From Date</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">To Date</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cutoffs as $cutoff)
                        @if($cutoff->is_active)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 text-sm text-slate-800 font-medium">{{ $cutoff->funding->fund_source ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $cutoff->from_date->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $cutoff->to_date->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $cutoff->status === 'OPEN' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $cutoff->status === 'FREEZE' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $cutoff->status === 'CLOSE' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $cutoff->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-slate-600">
                                <div class="flex gap-2">
                                    <button wire:click="edit({{ $cutoff->id }})" class="text-blue-500 hover:text-blue-700">Edit</button>
                                    <button wire:click="delete({{ $cutoff->id }})" class="text-red-500 hover:text-red-700" onclick="confirm('Are you sure you want to deactivate this cutoff?') || event.stopImmediatePropagation()">Delete</button>
                                    <a href="/payout-cutoff/{{ $cutoff->id }}/transactions" wire:navigate class="text-emerald-600 hover:text-emerald-800 ml-2 font-semibold">Transactions &rarr;</a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 text-sm">No payout cutoffs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">{{ $isEditMode ? 'Edit Cutoff' : 'Create Cutoff' }}</h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            
            <form wire:submit.prevent="store" class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Funding Source</label>
                    <select wire:model="funding_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">Select Funding...</option>
                        @foreach($fundings as $f)
                            <option value="{{ $f->id }}">{{ $f->fund_source }} ({{ $f->provider }})</option>
                        @endforeach
                    </select>
                    @error('funding_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1">From Date</label>
                        <input type="date" wire:model="from_date" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @error('from_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1">To Date</label>
                        <input type="date" wire:model="to_date" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @error('to_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select wire:model="status" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="OPEN">OPEN - Accepting transactions</option>
                        <option value="FREEZE">FREEZE - Temporarily halted</option>
                        <option value="CLOSE">CLOSE - Permanently closed</option>
                    </select>
                    @error('status') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Cutoff</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
