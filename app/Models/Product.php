<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Product extends Eloquent
{   
    protected $table = 'products';
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'description',
        'enable'
    ];
    public $timestamps = false;
    
    use HasFactory;
}