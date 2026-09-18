<div>
    <x-slot:title>Gender Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Gender Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">+ Add Gender</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($genders as $gender)
                    <tr class="table-row">
                        <td style="font-weight: 500;">{{ $gender->name }}</td>
                        <td>
                            <span class="badge {{ $gender->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $gender->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.4rem;">
                                <button wire:click="edit({{ $gender->id }})" class="btn btn-sm btn-info">Edit</button>
                                <button wire:click="delete({{ $gender->id }})" wire:confirm="Are you sure you want to delete this record?" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No records found. Click <strong>+ Add Gender</strong> to create one.
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
        <div class="modal-box" style="max-width: 400px;">
            <div class="modal-header">
                <h3>{{ $isEditMode ? 'Edit Gender' : 'Add New Gender' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="form-label">
                        Gender Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name" wire:model="name" class="form-input" placeholder="e.g. Male, Female">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
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
