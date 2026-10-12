<div>
    <x-slot:title>Payout Cutoffs | OSCA Panel</x-slot:title>

    <div class="page-container">
        <div class="page-header">
            <div>
                <h2 class="page-title">Payout Cutoffs Maintenance</h2>
                <p style="font-size:0.85rem; color:var(--text-muted);">Manage payout cutoffs for various funding sources.</p>
            </div>
            <button wire:click="openModal" class="btn btn-primary">+ New Cutoff</button>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold" style="margin-bottom:1rem;">Payout Cutoff List</h3>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Funding Source</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cutoffs as $cutoff)
                            @if($cutoff->is_active)
                            <tr>
                                <td style="font-weight:600;">{{ $cutoff->funding->fund_source ?? 'N/A' }}</td>
                                <td>{{ $cutoff->from_date ? $cutoff->from_date->format('M d, Y') : '-' }}</td>
                                <td>{{ $cutoff->to_date ? $cutoff->to_date->format('M d, Y') : '-' }}</td>
                                <td>
                                    @if($cutoff->status === 'OPEN')
                                        <span class="badge badge-active">OPEN</span>
                                    @elseif($cutoff->status === 'FREEZE')
                                        <span class="badge" style="background:#fef3c7; color:#b45309; border:1px solid #fde68a;">FREEZE</span>
                                    @else
                                        <span class="badge badge-inactive">{{ $cutoff->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.5rem; align-items:center;">
                                        <button wire:click="edit({{ $cutoff->id }})" class="btn btn-sm btn-info">Edit</button>
                                        <button wire:click="delete({{ $cutoff->id }})"
                                            wire:confirm="Are you sure you want to deactivate this cutoff?"
                                            class="btn btn-sm btn-danger">Delete</button>
                                        <a href="/payout-cutoff/{{ $cutoff->id }}/transactions" wire:navigate
                                            style="font-weight:600; color:var(--primary-mid); text-decoration:none;">Transactions &rarr;</a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:2rem; color:var(--text-muted);">No payout cutoffs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="modal-backdrop">
        <div class="modal-box" style="max-width:520px;">
            <div class="modal-header">
                <h3>{{ $isEditMode ? 'Edit Cutoff' : 'Create Cutoff' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="store">
                    <div class="form-group">
                        <label class="form-label">Funding Source <span class="required">*</span></label>
                        <select wire:model="funding_id" class="form-input">
                            <option value="">-- Select Funding --</option>
                            @foreach($fundings as $f)
                                <option value="{{ $f->id }}">{{ $f->fund_source }} ({{ $f->provider }})</option>
                            @endforeach
                        </select>
                        @error('funding_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">From Date <span class="required">*</span></label>
                            <input type="date" wire:model="from_date" class="form-input">
                            @error('from_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">To Date <span class="required">*</span></label>
                            <input type="date" wire:model="to_date" class="form-input">
                            @error('to_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select wire:model="status" class="form-input">
                            <option value="OPEN">OPEN - Accepting transactions</option>
                            <option value="FREEZE">FREEZE - Temporarily halted</option>
                            <option value="CLOSE">CLOSE - Permanently closed</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-footer" style="margin-top:1rem;">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Cutoff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
