<div>
    <x-slot:title>Purok Leader Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Purok Leader Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Leader</button>
        </div>

        <!-- Filter Bar -->
        <div style="margin-bottom: 1rem; display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="filterPurokId" class="form-label">Filter by Purok</label>
                <select id="filterPurokId" wire:model.live="filterPurokId" class="form-input">
                    <option value="">All Puroks</option>
                    @foreach($puroks as $purok)
                    <option value="{{ $purok->id }}">{{ $purok->name }} ({{ $purok->barangay->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Purok (Barangay)</th>
                        <th>Name</th>
                        <th>Mobile No.</th>
                        <th>Civil Status</th>
                        <th>Date of Birth</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaders as $leader)
                    <tr class="table-row">
                        <td>{{ $leader->purok->name ?? '-' }} ({{ $leader->purok->barangay->name ?? '-' }})</td>
                        <td>{{ $leader->last_name }}, {{ $leader->first_name }} {{ $leader->middle_name ?? '' }} {{ $leader->suffix ?? '' }}</td>
                        <td>{{ $leader->mobile_no ?? '-' }}</td>
                        <td>{{ $leader->civilStatus->name ?? '-' }}</td>
                        <td>{{ $leader->date_of_birth?->format('M d, Y') ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $leader->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $leader->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $leader->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $leader->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Leader' to create one.</td>
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
                <h3>{{ $isEditMode ? 'Edit Purok Leader' : 'Add New Purok Leader' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="purok_id" class="form-label">Purok</label>
                    <select id="purok_id" wire:model="purok_id" class="form-input">
                        <option value="">Select Purok</option>
                        @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}">{{ $purok->name }} ({{ $purok->barangay->name ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('purok_id') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" wire:model="last_name" class="form-input" placeholder="Last Name">
                    @error('last_name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" wire:model="first_name" class="form-input" placeholder="First Name">
                    @error('first_name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" id="middle_name" wire:model="middle_name" class="form-input" placeholder="Middle Name (Optional)">
                        @error('middle_name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="suffix" class="form-label">Suffix</label>
                        <input type="text" id="suffix" wire:model="suffix" class="form-input" placeholder="Jr., Sr., III">
                        @error('suffix') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <label for="mobile_no" class="form-label">Mobile No.</label>
                        <input type="text" id="mobile_no" wire:model="mobile_no" class="form-input" placeholder="09XX-XXX-XXXX">
                        @error('mobile_no') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" id="date_of_birth" wire:model="date_of_birth" class="form-input">
                        @error('date_of_birth') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="civil_status_id" class="form-label">Civil Status</label>
                    <select id="civil_status_id" wire:model="civil_status_id" class="form-input">
                        <option value="">Select Civil Status</option>
                        @foreach($civilStatuses as $cs)
                        <option value="{{ $cs->id }}">{{ $cs->name }}</option>
                        @endforeach
                    </select>
                    @error('civil_status_id') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
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
