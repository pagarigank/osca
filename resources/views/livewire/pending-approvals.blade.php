<div>
    <x-slot:title>Pending Approvals | OSCA</x-slot:title>

    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">Pending Edit Approvals</h2>
        </div>

        <div style="background: white; border-radius: 0.75rem; box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 2rem;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Senior Citizen</th>
                        <th>Requested By</th>
                        <th>Requested At</th>
                        <th>Changes Proposed</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr class="table-row">
                            <td style="font-weight: 600;">
                                {{ $req->seniorCitizen->last_name ?? '' }}, {{ $req->seniorCitizen->first_name ?? '' }}<br>
                                <span style="font-size: 0.8rem; color: #6b7280; font-weight: normal;">ID: {{ $req->seniorCitizen->id_number ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $req->requestedBy->name ?? 'Unknown' }}</td>
                            <td>{{ $req->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <ul style="margin: 0; padding-left: 1rem; font-size: 0.85rem;">
                                    @foreach($req->changes as $field => $change)
                                        <li>
                                            <strong>{{ str_replace('_', ' ', Str::title($field)) }}:</strong> 
                                            <span style="color: #ef4444; text-decoration: line-through;">{{ $change['old'] ?: 'Empty' }}</span> 
                                            &rarr; 
                                            <span style="color: #10b981; font-weight: bold;">{{ $change['new'] ?: 'Empty' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <button wire:click="approve({{ $req->id }})" wire:confirm="Are you sure you want to approve these changes?" class="btn btn-success btn-sm">Approve</button>
                                    <button wire:click="reject({{ $req->id }})" wire:confirm="Are you sure you want to reject these changes?" class="btn btn-danger btn-sm">Reject</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                No pending edit approvals.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="page-header">
            <h3 class="page-title" style="font-size: 1.25rem;">Approval History</h3>
        </div>

        <div style="background: white; border-radius: 0.75rem; box-shadow: var(--shadow-sm); overflow: hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Senior Citizen</th>
                        <th>Requested By</th>
                        <th>Reviewed By</th>
                        <th>Status</th>
                        <th>Reviewed At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                        <tr class="table-row">
                            <td>{{ $h->seniorCitizen->last_name ?? '' }}, {{ $h->seniorCitizen->first_name ?? '' }}</td>
                            <td>{{ $h->requestedBy->name ?? 'Unknown' }}</td>
                            <td>{{ $h->reviewedBy->name ?? 'Unknown' }}</td>
                            <td>
                                @if($h->status === 'APPROVED')
                                    <span style="color: #10b981; font-weight: bold;">APPROVED</span>
                                @else
                                    <span style="color: #ef4444; font-weight: bold;">REJECTED</span>
                                @endif
                            </td>
                            <td>{{ $h->reviewed_at ? $h->reviewed_at->format('M d, Y h:i A') : '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                No history available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
