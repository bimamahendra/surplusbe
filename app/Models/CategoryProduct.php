<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'category_products';
    protected $primaryKey = "id";
    protected $fillable = [
        'product_id',
        'category_id'
    ];
    public $timestamps = false;
    
    use HasFactory;
}