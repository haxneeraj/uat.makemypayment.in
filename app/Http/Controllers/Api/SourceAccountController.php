<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SourceAccount;

class SourceAccountController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()->sourceAccounts;
        return response()->json($accounts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:source_accounts,account_number',
            'ifsc_code' => 'required|string|max:11',
            'bank' => 'required|string|max:255',
            'is_primary' => 'boolean'
        ]);

        // If first account or is_primary is true
        if ($request->is_primary || !auth()->user()->sourceAccounts()->exists()) {
            auth()->user()->sourceAccounts()->update(['is_primary' => false]);
            $validated['is_primary'] = true;
        }

        $account = auth()->user()->sourceAccounts()->create($validated);
        return response()->json($account, 201);
    }

    public function show(SourceAccount $account)
    {
        $this->authorize('view', $account);
        return response()->json($account);
    }

    public function destroy(SourceAccount $account)
    {
        $this->authorize('delete', $account);
        $account->delete();
        return response()->json(null, 204);
    }

    public function makePrimary(SourceAccount $account)
    {
        $this->authorize('update', $account);
        
        auth()->user()->sourceAccounts()->update(['is_primary' => false]);
        $account->update(['is_primary' => true]);

        return response()->json($account);
    }
}