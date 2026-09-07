<div>
    <x-slot:title>Funding Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Funding Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Funding Source</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>PROVIDER</th>
                        <th>FUND SOURCE</th>
                        <th>AMOUNT</th>
                        <th>EFFECTIVE DATE</th>
                        <th>BENEFICIARY</th>
                        <th>TOTAL</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fundings as $funding)
                    <tr class="table-row">
                        <td>{{ $funding->provider }}</td>
                        <td>{{ $funding->fund_source }}</td>
                        <td style="text-align: right;">{{ number_format($funding->amount, 2) }}</td>
                        <td>{{ $funding->effective_date ? \Carbon\Carbon::parse($funding->effective_date)->format('m/d/Y') : '' }}</td>
                        <td style="text-align: right;">{{ number_format($funding->beneficiary) }}</td>
                        <td style="text-align: right;">{{ number_format($funding->total, 2) }}</td>
                        <td>
                            <span class="badge {{ $funding->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $funding->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $funding->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $funding->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Funding Source' to create one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="modal-backdrop">
        <div class="modal-box">
            <div class="modal-header">
                <h3>{{ $isEditMode ? 'Edit Funding Source' : 'Add New Funding Source' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="provider" class="form-label">Provider</label>
                    <input type="text" id="provider" wire:model="provider" class="form-input" placeholder="e.g. NATIONAL, LGU">
                    @error('provider') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="fund_source" class="form-label">Fund Source</label>
                    <input type="text" id="fund_source" wire:model="fund_source" class="form-input" placeholder="e.g. DBM, GAD, OM">
                    @error('fund_source') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01" id="amount" wire:model.live="amount" class="form-input" placeholder="0.00">
                    @error('amount') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="effective_date" class="form-label">Effective Date</label>
                    <input type="date" id="effective_date" wire:model="effective_date" class="form-input">
                    @error('effective_date') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="beneficiary" class="form-label">Beneficiary</label>
                    <input type="number" id="beneficiary" wire:model.live="beneficiary" class="form-input" placeholder="0">
                    @error('beneficiary') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="total" class="form-label">Total</label>
                    <input type="number" step="0.01" id="total" wire:model="total" class="form-input" disabled style="background-color: #f3f4f6; cursor: not-allowed;">
                </div>
                <div class="form-group" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="is_active" wire:model="is_active">
                    <label for="is_active" style="margin: 0;">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="closeModal" class="btn btn-secondary">Cancel</button>
                <button wire:click="store" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
    @endif
</div>
