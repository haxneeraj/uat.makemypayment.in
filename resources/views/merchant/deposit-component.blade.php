<div class="p-6">
    <!-- Top Card -->
    <div class="bg-[#eaf2ff] rounded-2xl p-8 mb-8 border border-blue-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-8">
        <div>
            <div class="text-xs font-bold text-gray-500 mb-2 tracking-widest uppercase">TOTAL DEPOSITED</div>
            <div class="text-4xl font-bold text-gray-900 mb-2">₹1,25,000.00</div>
            <div class="text-xs text-gray-500">Last updated: 02 Aug, 2025</div>
        </div>
        <div class="flex gap-4 mt-6 md:mt-0">
            <button class="bg-themeSecondary text-white px-6 py-3 rounded-lg font-semibold text-sm shadow hover:bg-themePrimary transition">Add Funds</button>
            <button class="bg-white text-themeSecondary border border-themeSecondary px-6 py-3 rounded-lg font-semibold text-sm shadow hover:bg-themeSecondary hover:text-white transition">Withdraw</button>
        </div>
    </div>
    <!-- Tabs -->
    <div class="mt-8 mb-2">
        <div class="flex items-center gap-6 border-b border-gray-200">
            <button class="pb-2 border-b-2 border-black text-black font-semibold text-sm">All Deposits</button>
            <button class="pb-2 text-gray-500 font-semibold text-sm">Pending</button>
            <button class="pb-2 text-gray-500 font-semibold text-sm">Failed</button>
        </div>
    </div>
    <!-- Filters and Actions -->
    <div class="flex flex-wrap items-center gap-3 mb-4 mt-4">
        <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white text-gray-700">
            <option>Reference No</option>
            <option>Bank Name</option>
            <option>UTR</option>
        </select>
        <input type="text" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white text-gray-700" placeholder="Search by Reference No">
        <button class="flex items-center gap-1 px-3 py-2 border border-gray-200 rounded-lg text-gray-700 bg-white text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0 6a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z"/>
            </svg>
            Filter
        </button>
        <button class="bg-black text-white px-4 py-2 rounded-lg font-semibold text-sm shadow hover:bg-gray-900 transition">Generate Report</button>
    </div>
    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Reference No</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Bank Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500">UTR</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Amount</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Date</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-4 py-3">REF123456789</td>
                    <td class="px-4 py-3">BANK OF BARODA</td>
                    <td class="px-4 py-3">UTR123456789</td>
                    <td class="px-4 py-3">₹50,000.00</td>
                    <td class="px-4 py-3">02 Aug, 2025</td>
                    <td class="px-4 py-3">
                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-semibold">Completed</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-3">REF987654321</td>
                    <td class="px-4 py-3">UNION</td>
                    <td class="px-4 py-3">UTR987654321</td>
                    <td class="px-4 py-3">₹25,000.00</td>
                    <td class="px-4 py-3">01 Aug, 2025</td>
                    <td class="px-4 py-3">
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-3">REF112233445</td>
                    <td class="px-4 py-3">BANK OF INDIA</td>
                    <td class="px-4 py-3">UTR112233445</td>
                    <td class="px-4 py-3">₹50,000.00</td>
                    <td class="px-4 py-3">30 Jul, 2025</td>
                    <td class="px-4 py-3">
                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">Failed</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-100 text-sm">
            <div class="flex items-center gap-2">
                <span class="text-gray-400">&larr; Prev</span>
                <span>Jump to</span>
                <input type="number" min="1" value="1" class="w-14 border border-gray-200 rounded-lg px-2 py-1 text-center">
                <span>/ 120 page</span>
                <span class="text-gray-400">Next &rarr;</span>
            </div>
        </div>
    </div>
</div>
