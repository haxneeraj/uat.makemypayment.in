<div class="p-6">

    {{-- ─── Page Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Bulk Payout</h1>
            <p class="text-sm text-gray-500 mt-0.5">Upload an Excel file to initiate multiple payouts at once.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Download sample --}}
            <a href="{{ asset('mmp-bulk-payout-sample.xlsx') }}"
               download
               class="inline-flex items-center gap-2 border border-gray-200 bg-white text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Download Sample
            </a>
            {{-- Upload button --}}
            <button wire:click="openModal"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-semibold shadow hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
                New Bulk Payout
            </button>
        </div>
    </div>

    {{-- ─── Batch History Table ──────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Batch ID</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Count</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Total Amount</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Accepted</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Rejected</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Success Rate</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500 whitespace-nowrap">Initiated At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($batches as $batch)
                @php
                    $successCount = $batch->payouts->whereIn('status', ['success'])->count();
                    $failedCount  = $batch->payouts->whereIn('status', ['failed'])->count();
                    $rate         = $batch->batch_count > 0 ? round(($successCount / $batch->batch_count) * 100) : 0;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-appPrimary font-semibold">{{ $batch->batch_id }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $batch->batch_count }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900">₹{{ number_format($batch->batch_amount, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">{{ $batch->accepted_count }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-semibold">{{ $batch->rejected_count }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: {{ $rate }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">{{ $rate }}%</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                        {{ $batch->created_at->format('d M Y, h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        No bulk payouts yet. Upload your first batch above.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $batches->links() }}
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL                                                               --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 backdrop-blur-sm py-8 overflow-y-auto">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full mx-4 p-8"
             style="max-width: {{ $step === 2 ? '900px' : '560px' }}">

            {{-- Close --}}
            <button wire:click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Step title --}}
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Bulk Payout</h3>

            {{-- Step indicator --}}
            <div class="flex items-center gap-2 mb-6">
                @foreach(['Upload','Preview','Verify','Done'] as $idx => $label)
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-medium
                        {{ $step > $idx+1 ? 'bg-green-500 text-white' : ($step === $idx+1 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500') }}">
                        {{ $idx+1 }} {{ $label }}
                    </span>
                    @if($idx < 3)
                        <span class="text-gray-300 text-xs">›</span>
                    @endif
                @endforeach
            </div>

            {{-- Flash messages --}}
            @if(session('bulkMessage'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2 rounded-lg">
                    {{ session('bulkMessage') }}
                </div>
            @endif
            @if(session('bulkError'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-2 rounded-lg">
                    {{ session('bulkError') }}
                </div>
            @endif

            {{-- ──────────────────────────────────────────────────── --}}
            {{-- STEP 1 : Drag & Drop Upload                         --}}
            {{-- ──────────────────────────────────────────────────── --}}
            @if($step === 1)
            <div x-data="{
                    isDragging: false,
                    uploading: false,
                    progress: 0,
                    handleDrop(e) {
                        this.isDragging = false;
                        const file = e.dataTransfer?.files[0];
                        if (!file) return;
                        // Feed the file into Livewire's file input
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        const input = this.$refs.fileInput;
                        input.files = dt.files;
                        input.dispatchEvent(new Event('change'));
                    }
                }"
                x-on:livewire-upload-start="uploading = true; progress = 0"
                x-on:livewire-upload-finish="uploading = false; progress = 100"
                x-on:livewire-upload-error="uploading = false; progress = 0"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
                >

                {{-- Drop zone --}}
                <div
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop($event)"
                    :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'"
                    class="border-2 border-dashed rounded-xl p-10 text-center cursor-pointer transition-colors"
                    @click="$refs.fileInput.click()"
                >
                    <template x-if="!uploading">
                        <div>
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <p class="text-gray-600 font-medium">Drag &amp; drop your Excel file here</p>
                            <p class="text-gray-400 text-sm mt-1">or click to browse — .xlsx / .xls, max 5 MB</p>
                        </div>
                    </template>
                    <template x-if="uploading">
                        <div class="space-y-3">
                            <svg class="w-10 h-10 mx-auto text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            <p class="text-blue-600 font-medium text-sm">Processing file…</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                                     :style="'width:'+progress+'%'"></div>
                            </div>
                            <p class="text-xs text-gray-500" x-text="progress + '%'"></p>
                        </div>
                    </template>

                    <input
                        x-ref="fileInput"
                        type="file"
                        accept=".xlsx,.xls"
                        class="hidden"
                        wire:model="excelFile"
                    >
                </div>

                @error('excelFile')
                    <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                @enderror

                {{-- File name preview --}}
                @if($excelFile && !$uploading)
                    <div class="mt-3 flex items-center gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $excelFile->getClientOriginalName() }}
                    </div>
                @endif

                {{-- Row errors summary --}}
                @if(!empty($rowErrors))
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-800 max-h-40 overflow-y-auto">
                        <p class="font-semibold mb-1">⚠ Some rows have issues (skipped):</p>
                        @foreach($rowErrors as $rowNum => $errs)
                            <p class="text-xs">Row {{ $rowNum }}: {{ implode(', ', $errs) }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ asset('mmp-bulk-payout-sample.xlsx') }}" download
                       class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Download sample Excel
                    </a>
                    <button wire:click="parseExcel"
                            wire:loading.attr="disabled"
                            :disabled="uploading || !$wire.excelFile"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-blue-700 transition disabled:opacity-50">
                        <span wire:loading wire:target="parseExcel">Parsing…</span>
                        <span wire:loading.remove wire:target="parseExcel">Parse & Preview →</span>
                    </button>
                </div>
            </div>
            @endif

            {{-- ──────────────────────────────────────────────────── --}}
            {{-- STEP 2 : Preview & balance check                    --}}
            {{-- ──────────────────────────────────────────────────── --}}
            @if($step === 2)
            <div>
                {{-- Balance summary card --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <p class="text-xs text-blue-500 font-medium">VAN Balance</p>
                        <p class="text-xl font-bold text-blue-700 mt-1">₹{{ number_format($walletBalance, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 font-medium">Total Payout Amount</p>
                        <p class="text-xl font-bold text-gray-900 mt-1">₹{{ number_format($totalAmount, 2) }}</p>
                    </div>
                    <div class="rounded-xl p-4 {{ $totalAmount > $walletBalance ? 'bg-red-50' : 'bg-green-50' }}">
                        <p class="text-xs font-medium {{ $totalAmount > $walletBalance ? 'text-red-500' : 'text-green-600' }}">
                            {{ $totalAmount > $walletBalance ? 'Insufficient Balance' : 'Balance Sufficient' }}
                        </p>
                        <p class="text-xl font-bold mt-1 {{ $totalAmount > $walletBalance ? 'text-red-700' : 'text-green-700' }}">
                            {{ count($parsedRows) }} payouts
                        </p>
                    </div>
                </div>

                {{-- Payouts preview table --}}
                <div class="overflow-x-auto max-h-72 rounded-xl border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500">#</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500 whitespace-nowrap">Beneficiary</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500 whitespace-nowrap">Account No.</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500">IFSC</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500">Bank</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500">Mobile</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500">City</th>
                                <th class="px-3 py-2 text-right font-semibold text-gray-500">Amount</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-500">Purpose</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($parsedRows as $i => $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 font-medium text-gray-800 whitespace-nowrap">{{ $row['account_holder'] }}</td>
                                <td class="px-3 py-2 text-gray-600 font-mono whitespace-nowrap">{{ $row['account_number'] }}</td>
                                <td class="px-3 py-2 text-gray-600 whitespace-nowrap">{{ $row['ifsc_code'] }}</td>
                                <td class="px-3 py-2 text-gray-600 whitespace-nowrap">{{ $row['bank_name'] }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $row['mobile'] }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $row['city'] }}</td>
                                <td class="px-3 py-2 text-right font-semibold text-gray-900 whitespace-nowrap">₹{{ number_format($row['amount'], 2) }}</td>
                                <td class="px-3 py-2 text-gray-500 capitalize">{{ $row['purpose'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-5">
                    <button wire:click="$set('step', 1)" class="text-sm text-gray-500 hover:text-gray-700">← Back</button>
                    <button wire:click="requestOtp"
                            @disabled($totalAmount > $walletBalance)
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-blue-700 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        <span wire:loading wire:target="requestOtp">Sending OTP…</span>
                        <span wire:loading.remove wire:target="requestOtp">Verify & Proceed →</span>
                    </button>
                </div>
            </div>
            @endif

            {{-- ──────────────────────────────────────────────────── --}}
            {{-- STEP 3 : OTP Verification                           --}}
            {{-- ──────────────────────────────────────────────────── --}}
            @if($step === 3)
            <div>
                <div class="bg-blue-50 rounded-xl p-5 text-center mb-6">
                    <svg class="w-10 h-10 mx-auto text-blue-500 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 4.5h3"/>
                    </svg>
                    <p class="text-gray-600 text-sm">An OTP has been sent to your registered mobile number.</p>
                    <p class="text-gray-500 text-xs mt-1">Enter the 6-digit OTP to confirm this batch of
                        <span class="font-semibold text-gray-800">{{ count($parsedRows) }} payouts</span>
                        totalling <span class="font-semibold text-gray-800">₹{{ number_format($totalAmount, 2) }}</span>.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Enter OTP</label>
                        <input wire:model="otp"
                               type="text"
                               maxlength="6"
                               placeholder="_ _ _ _ _ _"
                               class="w-full text-center text-2xl tracking-[0.5em] font-bold border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500">
                        @error('otp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-between items-center">
                        <button wire:click="$set('step', 2)" class="text-sm text-gray-500 hover:text-gray-700">← Back</button>
                        <button wire:click="verifyAndProcess"
                                wire:loading.attr="disabled"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-blue-700 transition disabled:opacity-50">
                            <span wire:loading wire:target="verifyAndProcess" class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                                Processing…
                            </span>
                            <span wire:loading.remove wire:target="verifyAndProcess">Confirm & Process Payouts</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- ──────────────────────────────────────────────────── --}}
            {{-- STEP 4 : Success                                    --}}
            {{-- ──────────────────────────────────────────────────── --}}
            @if($step === 4)
            <div class="text-center py-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-1">Batch Submitted!</h4>
                <p class="text-gray-500 text-sm mb-5">
                    {{ count($resultIds) }} payouts have been queued for processing.
                    You can track their status in the history table.
                </p>
                <div class="bg-gray-50 rounded-xl p-4 text-left max-h-48 overflow-y-auto mb-5">
                    @foreach($resultIds as $txnId)
                        <div class="flex items-center gap-2 py-1 border-b border-gray-100 last:border-0">
                            <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                            <span class="font-mono text-xs text-gray-600">{{ $txnId }}</span>
                        </div>
                    @endforeach
                </div>
                <button wire:click="closeModal"
                        class="bg-blue-600 text-white px-8 py-2 rounded-lg font-semibold text-sm hover:bg-blue-700 transition">
                    Close
                </button>
            </div>
            @endif

        </div>
    </div>
    @endif

</div>

