<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'user_info';

    protected $fillable = [
        'id',
        'emp_info_id',
        'emp_id',
        'user_idline',
        'user_id',
        'password',
        'user_role',
        'user_banned',
        'status_active',
        'create_by',
        'create_date',
        'update_by',
        'update_date'
    ];

    public $timestamps = false; // ไม่ใช้ timestamps ของ Laravel

}
