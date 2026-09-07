<div>
    <x-slot:title>Factor for Consideration Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Factor for Consideration Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Factor</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Factor for Consideration Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factor_considerations as $factor)
                    <tr class="table-row">
                        <td>{{ $factor->name }}</td>
                        <td>
                            <span class="badge {{ $factor->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $factor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $factor->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $factor->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Factor' to create one.</td>
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
                <h3>{{ $isEditMode ? 'Edit Factor for Consideration' : 'Add New Factor for Consideration' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="form-label">Factor for Consideration Name</label>
                    <input type="text" id="name" wire:model="name" class="form-input" placeholder="e.g. Health Condition, Economic Status">
                    @error('name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
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
