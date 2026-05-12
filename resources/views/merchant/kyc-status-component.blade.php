<div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">KYC Verification Status</h2>

        {{-- This is a placeholder for the actual status variable from your component logic --}}
        @php
            // You should replace this with the actual status from your component's data.
            // e.g., $kycStatus = $this->user->kyc_status;
            // Possible values: 'approved', 'pending', 'rejected', 'incomplete'
            $kycStatus = 'pending'; // Example status
        @endphp

        @if($kycStatus == 'approved')
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                <div class="flex items-center">
                    <span class="font-medium">Approved!</span>
                    <p class="ml-2">Your KYC verification has been successfully completed.</p>
                </div>
            </div>
            <div class="text-gray-600">
                <p>All features are now unlocked. You can start accepting payments without any restrictions.</p>
            </div>
        @elseif($kycStatus == 'pending')
            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                <div class="flex items-center">
                    <span class="font-medium">Pending!</span>
                    <p class="ml-2">Your KYC documents are under review.</p>
                </div>
            </div>
            <div class="text-gray-600">
                <p>This process usually takes 1-2 business days. We will notify you once the review is complete. No action is required from your side at the moment.</p>
            </div>
        @elseif($kycStatus == 'rejected')
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <div class="flex items-center">
                    <span class="font-medium">Rejected!</span>
                    <p class="ml-2">Your KYC verification was not successful.</p>
                </div>
            </div>
            <div class="text-gray-600">
                <p class="font-semibold">Reason for rejection:</p>
                <p class="mb-4">[Rejection reason will be displayed here, e.g., Blurry ID document].</p>
                <p>Please review your submitted documents and information, and resubmit them.</p>
                <a href="#" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Resubmit KYC
                </a>
            </div>
        @else {{-- 'incomplete' or not started --}}
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                <div class="flex items-center">
                    <span class="font-medium">Action Required!</span>
                    <p class="ml-2">You have not completed your KYC verification yet.</p>
                </div>
            </div>
            <div class="text-gray-600">
                <p>To unlock all features and remove transaction limits, please complete your KYC verification.</p>
                <a href="#" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Complete KYC
                </a>
            </div>
        @endif
    </div>
</div>
