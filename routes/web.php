<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Site\HomeComponent;
use App\Livewire\Site\AboutComponent;
use App\Livewire\Site\ContactComponent;
use App\Livewire\Site\ServiceComponent;
use App\Livewire\Site\PrivacyPolicyComponent;
use App\Livewire\Site\RefundPolicyComponent;
use App\Livewire\Site\TermsComponent;


use App\Livewire\Auth\LoginComponent;
use App\Livewire\Auth\RegisterComponent;
use App\Livewire\Auth\ForgotPasswordComponent;
use App\Livewire\Auth\PasswordResetComponent;


use App\Livewire\Admin\DashboardComponent as AdminDashboardComponent;
use App\Livewire\Admin\MerchantComponent as AdminMerchantComponent;
use App\Livewire\Admin\ViewMerchantComponent as AdminViewMerchantComponent;
use App\Livewire\Admin\EditMerchantComponent as AdminEditMerchantComponent;
use App\Livewire\Admin\ReportComponent as AdminReportComponent;
use App\Livewire\Admin\InwardReport as AdminInwardReportComponent;

use App\Livewire\Admin\SettingComponent as AdminSettingComponent;
use App\Livewire\Admin\PayoutComponent as AdminPayoutComponent;
use App\Livewire\Admin\PendingKycComponent as AdminPendingKycComponent;
use App\Livewire\Admin\ViewKycComponent as AdminViewKycComponent;
use App\Livewire\Admin\RoleComponent as AdminRoleComponent;
use App\Livewire\Admin\UpdateOrCreateRoleComponent as AdminUpdateOrCreateRoleComponent;
use App\Livewire\Admin\StaffComponent as AdminStaffComponent;
use App\Livewire\Admin\UpdateOrCreateStaffComponent as AdminUpdateOrCreateStaffComponent;
use App\Livewire\Admin\MerchantIPAndWebhookRequest as AdminMerchantIPAndWebhookRequestComponent;
use App\Livewire\Admin\Category as AdminCategoryComponent;
use App\Livewire\Admin\SubCategory as AdminSubCategoryComponent;
use App\Livewire\Admin\SouceAccountVerification as AdminSouceAccountVerificationComponent;


use App\Livewire\Merchant\DashboardComponent as MerchantDashboardComponent;
use App\Livewire\Merchant\PayoutComponent;
use App\Livewire\Merchant\BulkPayout;
use App\Livewire\Merchant\WalletComponent;
use App\Livewire\Merchant\OrganizationComponent;
use App\Livewire\Merchant\DepositComponent;
use App\Livewire\Merchant\ReportComponent;
use App\Livewire\Merchant\InwardReport;

use App\Livewire\Merchant\KycComponent;
use App\Livewire\Merchant\KycStatusComponent;
use App\Livewire\Merchant\SettingComponent;
use App\Livewire\Merchant\Invoice;
use App\Livewire\Merchant\SourceAccount as MerchantSourceAccount;

Route::get('/', HomeComponent::class)->name('site.home');
Route::get('/about', AboutComponent::class)->name('site.about');
Route::get('/contact', ContactComponent::class)->name('site.contact');  
Route::get('/service', ServiceComponent::class)->name('site.service');  

Route::get('/terms-and-conditions', TermsComponent::class)->name('site.terms');
Route::get('privacy-policy', PrivacyPolicyComponent::class)->name('site.privacy');
Route::get('refund-policy', RefundPolicyComponent::class)->name('site.refund');

Route::get('/login', LoginComponent::class)->name('login');
Route::get('/register', RegisterComponent::class)->name('register');

// forgot password
Route::get('/forgot-password', ForgotPasswordComponent::class)->name('password.request');
Route::get('/reset-password/{token}', PasswordResetComponent::class)->name('password.reset');

// Google login routes
Route::get('login/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])
    ->name('auth.google');
Route::get('login/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);

// Admin Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:sanctum',config('jetstream.auth_session'),'verified', 'onlyAdmin'] ], function(){
    Route::get('/dashboard', AdminDashboardComponent::class)->name('dashboard');
    Route::get('/merchants', AdminMerchantComponent::class)->name('merchants');
    Route::get('/merchants/{merchant_id}', AdminViewMerchantComponent::class)->name('merchants.view');
    Route::get('/merchants/{merchant_id}/edit', AdminEditMerchantComponent::class)->name('merchants.edit');
    Route::get('/pending-kyc', AdminPendingKycComponent::class)->name('pending-kyc');
    Route::get('/ip-and-callback-requests', AdminMerchantIPAndWebhookRequestComponent::class)->name('ip-and-callback-requests');
    Route::get('/view-kyc/{merchant_id}', AdminViewKycComponent::class)->name('view-kyc');
    Route::get('/payouts', AdminPayoutComponent::class)->name('payouts');
    Route::get('/reports', AdminReportComponent::class)->name('reports');
    Route::get('/inwards/reports', AdminInwardReportComponent::class)->name('inwards.reports');

    // Source Account Verification
    Route::get('/source-accounts', AdminSouceAccountVerificationComponent::class)->name('source-accounts');

    // Category and Sub-category routes
    Route::get('/categories', AdminCategoryComponent::class)->name('categories');
    Route::get('/sub-categories', AdminSubCategoryComponent::class)->name('sub-categories');


    Route::get('/roles', AdminRoleComponent::class)->name('roles');
    Route::get('/roles/update-or-create/{role_id?}', AdminUpdateOrCreateRoleComponent::class)->name('roles.update-or-create');

    Route::get('/staffs', AdminStaffComponent::class)->name('staffs');
    Route::get('/staffs/update-or-create/{staff_id?}', AdminUpdateOrCreateStaffComponent::class)->name('staffs.update-or-create');

    Route::get('/settings', AdminSettingComponent::class)->name('settings');
});

// Merchant Route
Route::group(['prefix' => 'payout', 'as' => 'merchant.', 'middleware' => ['auth:sanctum',config('jetstream.auth_session'),'verified', 'onlyMerchant'] ], function(){
    // KYC
    Route::get('/kyc', KycComponent::class)->name('kyc');
    Route::get('/kyc/status', KycStatusComponent::class)->name('kyc.status');


    Route::get('/', MerchantDashboardComponent::class)->name('dashboard');
    Route::get('/payouts', PayoutComponent::class)->name('payouts');
    Route::get('/bulk-payout', BulkPayout::class)->name('bulk-payout');
    Route::get('/wallet', WalletComponent::class)->name('wallet');
    Route::get('/organization', OrganizationComponent::class)->name('organization');
    Route::get('/deposits', DepositComponent::class)->name('deposits');
    Route::get('/reports', ReportComponent::class)->name('reports');
    Route::get('/inwards/reports', InwardReport::class)->name('inwards.reports');
    
    Route::get('/invoices', Invoice::class)->name('invoices'); 
    Route::get('/settings', SettingComponent::class)->name('settings');
    Route::get('/source-accounts', MerchantSourceAccount::class)->name('source-accounts');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/merchant/invoices', App\Livewire\Merchant\Invoice::class)->name('merchant.invoices');
});
