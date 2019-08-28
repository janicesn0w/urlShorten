<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    // Model for Link table
    protected $fillable = [
        'url', 'code'
    ];
}
