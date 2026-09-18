<div>
    <x-slot:title>Funding Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Funding Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">+ Add Funding Source</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>PROVIDER</th>
                        <th>FUND SOURCE</th>
                        <th style="text-align:right;">AMOUNT</th>
                        <th>EFFECTIVE DATE</th>
                        <th style="text-align:right;">BENEFICIARY</th>
                        <th style="text-align:right;">TOTAL</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fundings as $funding)
                    <tr class="table-row">
                        <td style="font-weight: 600;">{{ $funding->provider }}</td>
                        <td>{{ $funding->fund_source }}</td>
                        <td style="text-align: right; font-variant-numeric: tabular-nums;">₱{{ number_format($funding->amount, 2) }}</td>
                        <td>{{ $funding->effective_date ? \Carbon\Carbon::parse($funding->effective_date)->format('M d, Y') : '—' }}</td>
                        <td style="text-align: right;">{{ number_format($funding->beneficiary) }}</td>
                        <td style="text-align: right; font-weight: 600;">₱{{ number_format($funding->total, 2) }}</td>
                        <td>
                            <span class="badge {{ $funding->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $funding->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.4rem;">
                                <button wire:click="edit({{ $funding->id }})" class="btn btn-sm btn-info">Edit</button>
                                <button wire:click="delete({{ $funding->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No funding sources found. Click <strong>+ Add Funding Source</strong> to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="modal-backdrop">
        <div class="modal-box" style="max-width: 560px;">
            <div class="modal-header">
                <h3>{{ $isEditMode ? 'Edit Funding Source' : 'Add New Funding Source' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">

                <div class="form-row cols-2">
                    <div class="form-group">
                        <label for="provider" class="form-label">
                            Provider <span class="required">*</span>
                        </label>
                        <input type="text" id="provider" wire:model="provider" class="form-input" placeholder="e.g. NATIONAL, LGU">
                        @error('provider') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="fund_source" class="form-label">
                            Fund Source <span class="required">*</span>
                        </label>
                        <input type="text" id="fund_source" wire:model="fund_source" class="form-input" placeholder="e.g. DBM, GAD, OM">
                        @error('fund_source') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row cols-2">
                    <div class="form-group">
                        <label for="amount" class="form-label">
                            Amount (₱) <span class="required">*</span>
                        </label>
                        <div class="input-group has-prefix">
                            <span class="input-prefix">₱</span>
                            <input type="number" step="0.01" id="amount" wire:model.live="amount" class="form-input" placeholder="0.00">
                        </div>
                        @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="effective_date" class="form-label">
                            Effective Date <span class="required">*</span>
                        </label>
                        <input type="date" id="effective_date" wire:model="effective_date" class="form-input">
                        @error('effective_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row cols-2">
                    <div class="form-group">
                        <label for="beneficiary" class="form-label">
                            No. of Beneficiaries <span class="required">*</span>
                        </label>
                        <input type="number" id="beneficiary" wire:model.live="beneficiary" class="form-input" placeholder="0">
                        @error('beneficiary') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="total" class="form-label">
                            Total <span class="optional">(auto-computed)</span>
                        </label>
                        <div class="input-group has-prefix">
                            <span class="input-prefix">₱</span>
                            <input type="number" step="0.01" id="total" wire:model="total" class="form-input" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-check" for="is_active">
                        <input type="checkbox" id="is_active" wire:model="is_active">
                        <span class="form-check-label">Mark as Active</span>
                    </label>
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
