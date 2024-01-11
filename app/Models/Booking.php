<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table='booking';

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'email',
        'phone',
        'image'
    ];

}
