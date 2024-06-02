<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Category;
use App\Models\Order;



class Product extends Model
{
    use HasFactory;
    use Translatable;

    protected $guarded=[];
    public $translatedAttributes = ['name', 'description'];

    protected $appends =['image_path', 'profit_percent'];

    public function getImagePathAttribute(){
        return asset('uploads/product_images/' . $this->image);

    }
    public function getProfitPercentAttribute(){
        $profit= $this->sale_price - $this->purchase_price;
        $profit_percentage= $profit * 100 / $this->purchase_price;
        return number_format($profit_percentage ,2);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function orders(){
        return $this->belongsToMany(Order::class, 'product_order');
    }

}
