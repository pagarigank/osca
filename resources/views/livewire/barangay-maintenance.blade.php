<div>
    <x-slot:title>Barangay Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Barangay Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Barangay</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Barangay Name</th>
                        <th>Barangay Prefix</th>
                        <th>Barangay ID Sequence</th>
                        <th>Pay Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangays as $barangay)
                    <tr class="table-row">
                        <td>{{ $barangay->name }}</td>
                        <td>{{ $barangay->prefix ?? '-' }}</td>
                        <td>{{ $barangay->id_sequence }}</td>
                        <td>{{ $barangay->pay_type ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $barangay->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $barangay->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $barangay->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $barangay->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Barangay' to create one.</td>
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
                <h3>{{ $isEditMode ? 'Edit Barangay' : 'Add New Barangay' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="form-label">Barangay Name</label>
                    <input type="text" id="name" wire:model="name" class="form-input" placeholder="e.g. San Jose, Poblacion">
                    @error('name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="prefix" class="form-label">Barangay Prefix</label>
                    <input type="text" id="prefix" wire:model="prefix" class="form-input" placeholder="Optional code or prefix">
                    @error('prefix') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <label for="id_sequence" class="form-label">ID Sequence</label>
                        <input type="number" id="id_sequence" wire:model="id_sequence" class="form-input" placeholder="0">
                        @error('id_sequence') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="pay_type" class="form-label">Pay Type</label>
                        <input type="text" id="pay_type" wire:model="pay_type" class="form-input" placeholder="e.g. Standard, Premium">
                        @error('pay_type') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
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
