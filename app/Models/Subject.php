<?php

namespace App\Models;

use App\Core\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'code'
    ];
}
