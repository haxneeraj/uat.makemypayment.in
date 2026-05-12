<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DirectoryTree\Authorization\Traits\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use App\Notifications\Auth\SendPasswordResetNotification;

class User extends Authenticatable
{
    use HasApiTokens;
    use Authorizable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'merchant_id',
        'email',
        'phone',
        'password',
        'role',
        'daily_transfer_limit',
        'min_transfer_limit',
        'max_transfer_limit',
        'below_thousand_charge',
        'above_thousand_charge',
        'max_source_accounts',
        'profile_photo',
        'api_key',
        'api_secret',
        'status',
        'kyc_status',
        'van_status',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Custom Password Reset Notification
     * 
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SendPasswordResetNotification($token));
    }

    /**
     * Get the merchant virtual accounts associated with the user.
     */
    public function merchantVirtualAccount()
    {
        return $this->hasOne(MerchantVirtualAccount::class);
    }

    /**
     * Get the merchant KYC information associated with the user.
     */
    public function merchantKyc()
    {
        return $this->hasOne(MerchantKyc::class);
    }
    /**
     * Get the merchant CallbakAndIP Requests associated with the user.
     */
    public function merchantCallbackAndIP()
    {
        return $this->hasOne(APIActivationRequest::class);
    }

    /**
     * Get the merchant Source Accounts associated with the user.
     */
    public function merchantSourceAccounts()
    {
        return $this->hasMany(SourceAccount::class);
    }

    /**
     * Get the deposits associated with the user.
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get the payouts associated with the user.
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * Get the webhook logs associated with the user.
     */
    public function webhookLogs()
    {
        return $this->hasMany(WebhookLog::class);
    }

}
