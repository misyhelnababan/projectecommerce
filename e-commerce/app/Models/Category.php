<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','description'];
    protected $guarded = ['id'];

    // Relasi One-to-Many dengan Produk
    public function category()
    {
        return $this->hasMany(Product::class);
    }
}
