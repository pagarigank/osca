<div>
    <div class="mb-4">
        <a href="/payout-cutoff" wire:navigate class="text-blue-600 hover:underline text-sm font-medium">&larr; Back to Cutoffs</a>
    </div>

    <div class="content-header mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Payout Transactions</h2>
        <p class="text-slate-500 text-sm">
            Funding: <span class="font-semibold text-slate-700">{{ $cutoff->funding->fund_source ?? 'N/A' }}</span> | 
            Period: <span class="font-semibold text-slate-700">{{ $cutoff->from_date->format('M d, Y') }} - {{ $cutoff->to_date->format('M d, Y') }}</span>
        </p>
        
        <div class="mt-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                {{ $cutoff->status === 'OPEN' ? 'bg-green-100 text-green-700' : '' }}
                {{ $cutoff->status === 'FREEZE' ? 'bg-amber-100 text-amber-700' : '' }}
                {{ $cutoff->status === 'CLOSE' ? 'bg-red-100 text-red-700' : '' }}">
                Status: {{ $cutoff->status }}
            </span>
        </div>
    </div>

    <div class="card p-6 bg-white rounded-xl shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="data-table w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-y border-slate-200">
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">ID Number</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Beneficiary Name</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Received Date</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Amount</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($beneficiaries as $beneficiary)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 text-sm font-medium text-slate-800">{{ $beneficiary->seniorCitizen->id_number }}</td>
                            <td class="py-3 px-4 text-sm text-slate-700">
                                {{ $beneficiary->seniorCitizen->last_name }}, {{ $beneficiary->seniorCitizen->first_name }} {{ $beneficiary->seniorCitizen->middle_name }}
                            </td>
                            <td class="py-3 px-4 text-sm text-slate-600">
                                {{ $beneficiary->transaction?->received_date?->format('M d, Y h:i A') ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-sm text-slate-600 font-medium">
                                @if($beneficiary->transaction)
                                    ₱{{ number_format($beneficiary->transaction->amount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($beneficiary->transaction && $beneficiary->transaction->is_received)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        RECEIVED
                                    </span>
                                @else
                                    <button 
                                        wire:click="openTransactionModal({{ $beneficiary->id }})" 
                                        @if($cutoff->status !== 'OPEN') disabled @endif
                                        class="btn btn-sm px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                                        {{ $cutoff->status === 'OPEN' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed' }}">
                                        Process Payout
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 text-sm">No beneficiaries assigned to this funding source.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaction Modal -->
    @if($isModalOpen && $activeBeneficiary)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" x-data="webcamCapture()">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center p-4 border-b border-slate-100 bg-slate-50/50 shrink-0">
                <h3 class="text-lg font-bold text-slate-800">Process Payout</h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <div class="bg-slate-50 p-4 rounded-lg mb-6 border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-1">Beneficiary</p>
                    <p class="font-bold text-slate-800 text-lg">{{ $activeBeneficiary->seniorCitizen->last_name }}, {{ $activeBeneficiary->seniorCitizen->first_name }}</p>
                    <p class="text-sm text-slate-600">ID: {{ $activeBeneficiary->seniorCitizen->id_number }}</p>
                </div>

                <form wire:submit.prevent="processTransaction">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Amount Received (₱)</label>
                        <input type="number" step="0.01" wire:model="amount" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                        @error('amount') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Proof of Receipt (Photo)</label>
                        
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center">
                            
                            <!-- Webcam Feed -->
                            <div x-show="isCameraOpen" style="display: none;" class="mb-4">
                                <video x-ref="video" class="w-full rounded-lg bg-black aspect-video object-cover" autoplay playsinline></video>
                                <button type="button" @click="capturePhoto" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium w-full hover:bg-blue-700">📸 Capture Photo</button>
                            </div>
                            
                            <!-- Captured Image Preview -->
                            <div x-show="capturedImage" style="display: none;" class="mb-4 relative">
                                <img :src="capturedImage" class="w-full rounded-lg border border-slate-200">
                                <button type="button" @click="retakePhoto" class="absolute top-2 right-2 bg-slate-900/70 text-white rounded-full p-2 hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <!-- Options -->
                            <div x-show="!isCameraOpen && !capturedImage">
                                <p class="text-sm text-slate-500 mb-4">Take a photo using your camera or upload a file.</p>
                                <div class="flex gap-2 justify-center">
                                    <button type="button" @click="openCamera" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg text-sm hover:bg-slate-200 border border-slate-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Open Camera
                                    </button>
                                    <label class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg text-sm hover:bg-slate-200 border border-slate-200 flex items-center gap-2 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Upload File
                                        <input type="file" wire:model="proof_upload" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                @if($proof_upload)
                                    <p class="text-xs text-emerald-600 font-semibold mt-3">File selected: {{ $proof_upload->getClientOriginalName() }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 shrink-0">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Confirm Payout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function webcamCapture() {
            return {
                isCameraOpen: false,
                capturedImage: null,
                stream: null,
                
                openCamera() {
                    this.isCameraOpen = true;
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(stream => {
                            this.stream = stream;
                            this.$refs.video.srcObject = stream;
                        })
                        .catch(err => {
                            console.error("Camera error:", err);
                            alert("Could not access camera. Please ensure permissions are granted.");
                            this.isCameraOpen = false;
                        });
                },
                
                capturePhoto() {
                    const canvas = document.createElement('canvas');
                    const video = this.$refs.video;
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    this.capturedImage = canvas.toDataURL('image/jpeg', 0.8);
                    
                    // Stop stream
                    this.stopCamera();
                    
                    // Pass to livewire
                    @this.set('webcam_image_base64', this.capturedImage);
                },
                
                retakePhoto() {
                    this.capturedImage = null;
                    @this.set('webcam_image_base64', null);
                    this.openCamera();
                },
                
                stopCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                    }
                    this.isCameraOpen = false;
                }
            }
        }
    </script>
    @endif
</div>
