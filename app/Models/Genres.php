<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genres extends Model
{
    use SoftDeletes, Traits\Uuid;

    protected $fillable = ['name', 'is_active'];
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    public $incrementing = false;
}
