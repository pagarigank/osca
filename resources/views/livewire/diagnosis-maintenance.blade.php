<div>
    <x-slot:title>Diagnosis Maintenance | OSCA Panel</x-slot:title>
    
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Diagnosis Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Diagnosis</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Generic Diagnosis Name</th>
                        <th>ICD10-CM Code</th>
                        <th>ICD10-CM Short Name</th>
                        <th>ICD-10CM Long Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($diagnoses as $diagnosis)
                    <tr class="table-row">
                        <td>{{ $diagnosis->generic_name }}</td>
                        <td>{{ $diagnosis->icd10_code ?? '-' }}</td>
                        <td>{{ $diagnosis->icd10_short_name ?? '-' }}</td>
                        <td>{{ $diagnosis->icd10_long_name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $diagnosis->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $diagnosis->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="edit({{ $diagnosis->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $diagnosis->id }})" wire:confirm="Are you sure you want to deactivate this record?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No records found. Click 'Add New Diagnosis' to create one.</td>
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
                <h3>{{ $isEditMode ? 'Edit Diagnosis' : 'Add New Diagnosis' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="generic_name" class="form-label">Generic Diagnosis Name</label>
                    <input type="text" id="generic_name" wire:model="generic_name" class="form-input" placeholder="e.g. Hypertension">
                    @error('generic_name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <label for="icd10_code" class="form-label">ICD10-CM Code</label>
                        <input type="text" id="icd10_code" wire:model="icd10_code" class="form-input" placeholder="e.g. I10">
                        @error('icd10_code') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label for="icd10_short_name" class="form-label">ICD10-CM Short Name</label>
                        <input type="text" id="icd10_short_name" wire:model="icd10_short_name" class="form-input" placeholder="e.g. Essential hypertension">
                        @error('icd10_short_name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="icd10_long_name" class="form-label">ICD-10CM Long Name</label>
                    <textarea id="icd10_long_name" wire:model="icd10_long_name" class="form-input" rows="3" placeholder="e.g. Essential (primary) hypertension"></textarea>
                    @error('icd10_long_name') <span class="text-danger" style="font-size: 0.85rem; color: red;">{{ $message }}</span> @enderror
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
