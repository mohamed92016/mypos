<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    use Translatable;

    protected $guarded=[];
    public $translatedAttributes = ['name',];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
