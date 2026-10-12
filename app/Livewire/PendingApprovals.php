<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SeniorCitizenChangeRequest;
use App\Models\SeniorCitizen;

#[Layout('layouts.app')]
class PendingApprovals extends Component
{
    public function approve($id)
    {
        $request = SeniorCitizenChangeRequest::findOrFail($id);
        
        // Update the senior citizen record
        $sc = $request->seniorCitizen;
        if ($sc) {
            $changes = $request->changes;
            $updates = [];
            foreach ($changes as $field => $val) {
                $updates[$field] = $val['new'];
            }
            $sc->update($updates);
        }

        $request->update([
            'status' => 'APPROVED',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->dispatch('notify', message: 'Change Request Approved.');
    }

    public function reject($id)
    {
        $request = SeniorCitizenChangeRequest::findOrFail($id);
        
        $request->update([
            'status' => 'REJECTED',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->dispatch('notify', message: 'Change Request Rejected.');
    }

    public function render()
    {
        $requests = SeniorCitizenChangeRequest::with(['seniorCitizen', 'requestedBy'])
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        $history = SeniorCitizenChangeRequest::with(['seniorCitizen', 'requestedBy', 'reviewedBy'])
            ->where('status', '!=', 'PENDING')
            ->orderBy('reviewed_at', 'desc')
            ->take(20)
            ->get();

        return view('livewire.pending-approvals', compact('requests', 'history'));
    }
}
