<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactUs extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $table='contact_us';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'message',
    ];
}
