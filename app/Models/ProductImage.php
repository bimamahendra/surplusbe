<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';
    protected $primaryKey = "id";
    protected $fillable = [
        'product_id',
        'image_id'
    ];

    public $timestamps = false;

    use HasFactory;
}