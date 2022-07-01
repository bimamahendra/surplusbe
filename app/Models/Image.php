<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Image extends Eloquent
{
    protected $table = 'images';
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'file',
        'enable'
    ];

    public $timestamps = false;
    use HasFactory;
}