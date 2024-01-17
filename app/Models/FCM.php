<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCM extends Model
{
    use HasFactory;
    public $table = 'fcm_token';
    protected $fillable = ['id', 'token', 'user_id'];
}
