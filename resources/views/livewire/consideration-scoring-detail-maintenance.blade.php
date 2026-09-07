<div>
    <x-slot:title>Consideration Scoring Detail Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Consideration Scoring Detail Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Scoring Detail</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Factor for Consideration</th>
                        <th>Particular</th>
                        <th>Guide Points</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scoring_details as $detail)
                    <tr class="table-row">
                        <td>{{ $detail->factorConsideration->name ?? 'N/A' }}</td>
                        <td>{{ $detail->particular }}</td>
                        <td>{{ $detail->guide_points }}</td>
                        <td>
                            <span class="badge {{ $detail->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $detail->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $detail->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $detail->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Scoring Detail' to create one.</td>
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
                <h3>{{ $isEditMode ? 'Edit Scoring Detail' : 'Add New Scoring Detail' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="factor_consideration_id" class="form-label">Factor for Consideration</label>
                    <select id="factor_consideration_id" wire:model="factor_consideration_id" class="form-input">
                        <option value="">-- Select Factor --</option>
                        @foreach($factors as $factor)
                            <option value="{{ $factor->id }}">{{ $factor->name }}</option>
                        @endforeach
                    </select>
                    @error('factor_consideration_id') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="particular" class="form-label">Particular</label>
                    <input type="text" id="particular" wire:model="particular" class="form-input" placeholder="e.g. Bedridden, With Pension">
                    @error('particular') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="guide_points" class="form-label">Guide Points</label>
                    <input type="number" id="guide_points" wire:model="guide_points" class="form-input" placeholder="e.g. 5">
                    @error('guide_points') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
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
