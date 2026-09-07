<div>
    <x-slot:title>Location Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Location Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Location</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Province</th>
                        <th>City or Municipalities</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                    <tr class="table-row">
                        <td>{{ $location->province }}</td>
                        <td>{{ $location->city_municipality }}</td>
                        <td>
                            <span class="badge {{ $location->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $location->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $location->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $location->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Location' to create one.</td>
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
                <h3>{{ $isEditMode ? 'Edit Location' : 'Add New Location' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="province" class="form-label">Province</label>
                    <input type="text" id="province" wire:model="province" class="form-input" placeholder="e.g. Cebu, Metro Manila">
                    @error('province') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="city_municipality" class="form-label">City or Municipalities</label>
                    <input type="text" id="city_municipality" wire:model="city_municipality" class="form-input" placeholder="e.g. Cebu City, Quezon City">
                    @error('city_municipality') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
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
