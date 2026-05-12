<?php

namespace App\Services\Van;

use App\Models\MerchantVirtualAccount;

class VanService
{
    public function createVan($data)
    {
        $vanNumber = $this->generateVanNumber($data['mobile']);

        $van = MerchantVirtualAccount::updateOrCreate([
            'user_id' => $data['user_id'],
        ], [
            'van' => $vanNumber,
            'account_holder' => $data['account_holder'],
            'ifsc' => 'HDFC0000060',
            'purpose' => $data['purpose'],
            'start_date' => now(),
            'validity' => $data['validity'],
        ]);

        return $van;

    }

    public function generateVanNumber($mobileNumber)
    {
        $prefix = "MMPF52";
        $vanNumber = $prefix . $mobileNumber;
        
        while(MerchantVirtualAccount::where('van', $vanNumber)->exists()) {
            $vanNumber = $prefix . substr($mobileNumber, -4) . rand(1000, 9999);
        }

        return $vanNumber;
    }

    public function getVanByUserId($userId)
    {
        return MerchantVirtualAccount::where('user_id', $userId)->first();
    }

    public function getVanBalanceByUserId($userId)
    {
        $van = $this->getVanByUserId($userId);
        return $van ? $van->balance : 0;
    }
}