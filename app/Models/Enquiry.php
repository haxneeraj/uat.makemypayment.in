<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table = 'enquiries';

    protected $fillable = [
        'business_name',
        'full_name',
        'email',
        'phone',
        'message',
        'type',
    ];
}
