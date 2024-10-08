<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = "RoleList";
    public $primaryKey = 'RoleId';
    protected $guarded = [];
    public $timestamps = false;
    protected $keyType = 'string';
}
