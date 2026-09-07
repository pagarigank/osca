<div>
    <x-slot:title>Position Maintenance | OSCA Panel</x-slot:title>

    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Position Maintenance</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New Position</button>
        </div>

        {{-- ── Office Filter Bar ─────────────────────────────────── --}}
        <div style="background: white; border-radius: 0.75rem; padding: 1rem 1.25rem; box-shadow: var(--shadow-md); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); white-space: nowrap;">Filter by Office:</label>
            <select wire:model.live="filter_office_id" id="filter_office_id" class="form-input" style="max-width: 320px;">
                <option value="">— All Offices —</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                @endforeach
            </select>
            @if($filter_office_id)
                <button wire:click="$set('filter_office_id', '')" class="btn btn-sm btn-secondary">Clear Filter</button>
            @endif
        </div>

        {{-- ── Data Table ────────────────────────────────────────── --}}
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Position Name</th>
                        <th>Office</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($positions as $position)
                    <tr class="table-row">
                        <td style="font-weight: 600;">{{ $position->name }}</td>
                        <td>
                            <span style="background: #eff6ff; color: #1d4ed8; font-size: 0.8rem; font-weight: 600; padding: 0.2rem 0.625rem; border-radius: 0.375rem;">
                                {{ $position->office?->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $position->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $position->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <button wire:click="edit({{ $position->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="delete({{ $position->id }})"
                                    wire:confirm="Are you sure you want to deactivate this position?"
                                    class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">
                            No positions found.
                            @if($filter_office_id) Try clearing the office filter. @else Click 'Add New Position' to create one. @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- ── Modal ──────────────────────────────────────────────────── --}}
    @if($isModalOpen)
    <div class="modal-backdrop">
        <div class="modal-box" style="max-width: 480px;">
            <div class="modal-header">
                <h3>{{ $isEditMode ? 'Edit Position' : 'Add New Position' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">

                {{-- Office (parent, required) --}}
                <div class="form-group">
                    <label for="pos_office_id" class="form-label">
                        Office <span style="color:red;">*</span>
                    </label>
                    <select id="pos_office_id" wire:model="office_id" class="form-input">
                        <option value="">— Select Office —</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                        @endforeach
                    </select>
                    @error('office_id') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                </div>

                {{-- Position Name --}}
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="pos_name" class="form-label">
                        Position Name <span style="color:red;">*</span>
                    </label>
                    <input type="text" id="pos_name" wire:model="name" class="form-input"
                           placeholder="e.g. Social Welfare Officer, Encoder, Supervisor">
                    @error('name') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                </div>

                {{-- Active Status --}}
                <div class="form-group" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="pos_is_active" wire:model="is_active">
                    <label for="pos_is_active" style="margin: 0;">Active</label>
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
