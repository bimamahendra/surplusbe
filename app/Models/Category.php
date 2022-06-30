<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Category extends Eloquent
{   
    protected $table = 'categories';
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'enable'
    ];
    public $timestamps = false;
    
    use HasFactory;
}