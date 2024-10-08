<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $table = "Business";
    public $timestamps = false;
    public $primaryKey = false;
    public $incrementing = false;
    protected $keyType = "string";
    protected $guarded = [];
}
