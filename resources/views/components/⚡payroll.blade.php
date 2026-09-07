<div>
    <x-slot:title>Payroll | OSCA Panel</x-slot:title>

    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Payroll</h2>

            @if($selected_funding_id)
            <a href="{{ route('print.payroll', ['funding_id' => $selected_funding_id, 'barangay_id' => $selected_barangay_id, 'purok_id' => $selected_purok_id, 'purok_leader_id' => $selected_purok_leader_id]) }}" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print Report
            </a>
            @endif
        </div>

        {{-- Funding Selector --}}
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: var(--shadow-sm); margin-bottom: 1rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="funding" class="form-label" style="font-weight: 600;">Select Funding Source</label>
                <select id="funding" wire:model.live="selected_funding_id" class="form-input" style="max-width: 400px;">
                    <option value="">-- Choose Funding --</option>
                    @foreach($fundings as $funding)
                        <option value="{{ $funding->id }}">{{ $funding->provider }} - {{ $funding->fund_source }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Location Filters (Barangay, Purok, Purok Leader) --}}
        @if($selected_funding_id)
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: var(--shadow-sm); margin-bottom: 1rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                
                {{-- Barangay Filter --}}
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label for="barangay" class="form-label">Barangay</label>
                    <select id="barangay" wire:model="selected_barangay_id" class="form-input">
                        <option value="">-- All Barangays --</option>
                        @foreach($barangays as $barangay)
                            <option value="{{ $barangay->id }}" {{ $selected_barangay_id == $barangay->id ? 'selected' : '' }}>
                                {{ $barangay->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Purok Filter --}}
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label for="purok" class="form-label">Purok</label>
                    <select id="purok" wire:model="selected_purok_id" class="form-input" {{ $puroks->isNotEmpty() ? '' : 'disabled' }}>
                        <option value="">-- All Puroks --</option>
                        @foreach($puroks as $purok)
                            <option value="{{ $purok->id }}" {{ $selected_purok_id == $purok->id ? 'selected' : '' }}>
                                {{ $purok->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($puroks->isEmpty() && $selected_barangay_id)
                        <small style="color: var(--text-secondary);">No puroks in this barangay</small>
                    @endif
                </div>

                {{-- Purok Leader Filter --}}
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label for="purok_leader" class="form-label">Purok Leader</label>
                    <select id="purok_leader" wire:model="selected_purok_leader_id" class="form-input" {{ $purokLeaders->isEmpty() ? 'disabled' : '' }}>
                        <option value="">-- All Leaders --</option>
                        @foreach($purokLeaders as $leader)
                            <option value="{{ $leader->id }}" {{ $selected_purok_leader_id == $leader->id ? 'selected' : '' }}>
                                {{ $leader->last_name }}, {{ $leader->first_name }} {{ $leader->middle_name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @if($purokLeaders->isEmpty() && $selected_purok_id)
                        <small style="color: var(--text-secondary);">Select a purok first</small>
                    @endif
                </div>

            </div>
        </div>
        @endif

        {{-- Slot Summary Cards (only shown when a funding is selected) --}}
        @if($selected_funding_id && $selectedFunding)
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">

            {{-- Total Slots --}}
            <div style="flex: 1; min-width: 160px; background: white; border-radius: 0.75rem; box-shadow: var(--shadow-sm); padding: 1.25rem 1.5rem; border-left: 4px solid #6366f1;">
                <div style="font-size: 0.72rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.35rem;">Total Slots</div>
                <div style="font-size: 2rem; font-weight: 800; color: #6366f1; line-height: 1;">{{ number_format($totalSlots) }}</div>
                <div style="font-size: 0.72rem; color: #9ca3af; margin-top: 0.25rem;">Budgeted beneficiaries</div>
            </div>

            {{-- Occupied Slots --}}
            <div style="flex: 1; min-width: 160px; background: white; border-radius: 0.75rem; box-shadow: var(--shadow-sm); padding: 1.25rem 1.5rem; border-left: 4px solid #f59e0b;">
                <div style="font-size: 0.72rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.35rem;">Occupied Slots</div>
                <div style="font-size: 2rem; font-weight: 800; color: #f59e0b; line-height: 1;">{{ number_format($occupiedSlots) }}</div>
                <div style="font-size: 0.72rem; color: #9ca3af; margin-top: 0.25rem;">Enrolled senior citizens</div>
            </div>

            {{-- Available Slots --}}
            <div style="flex: 1; min-width: 160px; background: white; border-radius: 0.75rem; box-shadow: var(--shadow-sm); padding: 1.25rem 1.5rem; border-left: 4px solid {{ $availableSlots > 0 ? '#10b981' : '#ef4444' }};">
                <div style="font-size: 0.72rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.35rem;">Available Slots</div>
                <div style="font-size: 2rem; font-weight: 800; color: {{ $availableSlots > 0 ? '#10b981' : '#ef4444' }}; line-height: 1;">{{ number_format($availableSlots) }}</div>
                <div style="font-size: 0.72rem; color: #9ca3af; margin-top: 0.25rem;">
                    @if($availableSlots > 0) Open for enrollment @else Fully occupied @endif
                </div>
            </div>

            {{-- Total Budget --}}
            <div style="flex: 1; min-width: 160px; background: white; border-radius: 0.75rem; box-shadow: var(--shadow-sm); padding: 1.25rem 1.5rem; border-left: 4px solid #3b82f6;">
                <div style="font-size: 0.72rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.35rem;">Total Budget</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #3b82f6; line-height: 1;">₱{{ number_format($selectedFunding->total ?? 0, 2) }}</div>
                <div style="font-size: 0.72rem; color: #9ca3af; margin-top: 0.25rem;">@ ₱{{ number_format($selectedFunding->amount ?? 0, 2) }}/beneficiary</div>
            </div>

        </div>
        @endif

        {{-- Beneficiary Table --}}
        <div style="overflow-x: auto; background: white; border-radius: 0.75rem; box-shadow: var(--shadow-sm);">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Purok</th>
                        <th style="text-align: right;">Amount</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiaries as $i => $ben)
                    <tr class="table-row">
                        <td style="color: #9ca3af; font-size: 0.8rem;">{{ $i + 1 }}</td>
                        <td>{{ $ben->seniorCitizen->id_number ?? $ben->seniorCitizen->new_id_number }}</td>
                        <td style="font-weight: 500;">{{ $ben->seniorCitizen->last_name }}, {{ $ben->seniorCitizen->first_name }} {{ $ben->seniorCitizen->middle_name }}</td>
                        <td>{{ $ben->seniorCitizen->barangay->name ?? 'N/A' }}</td>
                        <td>{{ $ben->seniorCitizen->purok ?? 'N/A' }}</td>
                        <td style="text-align: right; font-weight: 600;">₱{{ number_format($ben->funding->amount ?? 0, 2) }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('print.payroll.stub', ['funding_id' => $selected_funding_id, 'senior_citizen_id' => $ben->seniorCitizen->id]) }}" target="_blank" class="btn btn-success btn-sm" style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="6" height="6" rx="1"></rect><path d="M11 12H9v-2h2v2zm0 4h2v-2h-2v2zm0-8h2V5h-2v2z"></path></svg>
                                Stub
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            @if($selected_funding_id)
                                No beneficiaries found for this funding source.
                                @if($selected_barangay_id || $selected_purok_id)
                                    <br><small>Try clearing some filters to see more results.</small>
                                @endif
                            @else
                                Please select a funding source to view the payroll list.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($beneficiaries->count() > 0)
                <tfoot>
                    <tr style="background: #f9fafb; font-weight: 700;">
                        <td colspan="6" style="padding: 0.75rem 1rem; font-size: 0.85rem; color: #374151;">
                            Total: {{ $occupiedSlots }} / {{ $totalSlots }} slots filled
                            &nbsp;&mdash;&nbsp;
                            @if($availableSlots > 0)
                                <span style="color: #10b981;">{{ $availableSlots }} slot(s) still available</span>
                            @else
                                <span style="color: #ef4444;">Fully occupied</span>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>