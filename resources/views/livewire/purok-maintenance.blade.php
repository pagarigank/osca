<div>
    <x-slot:title>Purok Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Purok Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Purok</button>
        </div>

        <!-- Filter Bar -->
        <div style="margin-bottom: 1rem; display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="filterBarangayId" class="form-label">Filter by Barangay</label>
                <select id="filterBarangayId" wire:model.live="filterBarangayId" class="form-input">
                    <option value="">All Barangays</option>
                    @foreach($barangays as $barangay)
                    <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Barangay</th>
                        <th>Purok Name</th>
                        <th>Purok Code</th>
                        <th>Description</th>
                        <th>Prefix</th>
                        <th>ID Sequence</th>
                        <th>Pay Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($puroks as $purok)
                    <tr class="table-row">
                        <td>{{ $purok->barangay->name ?? '-' }}</td>
                        <td>{{ $purok->name }}</td>
                        <td>{{ $purok->purok_code ?? '-' }}</td>
                        <td>{{ $purok->description ? \Illuminate\Support\Str::limit($purok->description, 60) : '-' }}</td>
                        <td>{{ $purok->prefix ?? '-' }}</td>
                        <td>{{ $purok->id_sequence }}</td>
                        <td>{{ $purok->pay_type ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $purok->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $purok->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $purok->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $purok->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Purok' to create one.</td>
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
                <h3>{{ $isEditMode ? 'Edit Purok' : 'Add New Purok' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="barangay_id" class="form-label">Barangay</label>
                    <select id="barangay_id" wire:model="barangay_id" class="form-input">
                        <option value="">Select Barangay</option>
                        @foreach($barangays as $barangay)
                        <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                        @endforeach
                    </select>
                    @error('barangay_id') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="name" class="form-label">Purok Name</label>
                    <input type="text" id="name" wire:model="name" class="form-input" placeholder="e.g. Purok 1, Purok 2, Riverside">
                    @error('name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="purok_code" class="form-label">Purok Code</label>
                    <input type="text" id="purok_code" wire:model="purok_code" class="form-input" placeholder="e.g. PRK-01 (unique per barangay)">
                    @error('purok_code') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="description" class="form-label">Purok Description</label>
                    <textarea id="description" wire:model="description" class="form-input" rows="3" placeholder="Optional description / notes about this purok"></textarea>
                    @error('description') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <label for="prefix" class="form-label">Prefix</label>
                        <input type="text" id="prefix" wire:model="prefix" class="form-input" placeholder="Optional code or prefix">
                        @error('prefix') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="pay_type" class="form-label">Pay Type</label>
                        <input type="text" id="pay_type" wire:model="pay_type" class="form-input" placeholder="e.g. Standard, Premium">
                        @error('pay_type') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="id_sequence" class="form-label">ID Sequence</label>
                    <input type="number" id="id_sequence" wire:model="id_sequence" class="form-input" placeholder="0">
                    @error('id_sequence') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
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
