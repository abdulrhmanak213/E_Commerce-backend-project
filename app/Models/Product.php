<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table="products";
    protected $primaryKey="id";
    protected $timestemps=true;
    protected $fillable=['name',
    'price',
    'description',
    //'current_price',
    'exp_date',
    'img_url',
    'quantity',
    'category_id',
    'user_id',
    'view',
    'phone',
    ];
     public function category(){
             return $this->belongsTo( Category::class, 'category_id');}
    public function user(){
            return $this->belongsTo( User::class, 'user_id');}
     public $with = ['category'];

    public function discounts(){
     return $this->hasMany(Discount::class, 'product_id');
}
    public function comments(){
     return $this->hasMany(Comment::class, 'product_id');
}
public function likes(){
    return $this->hasMany(Like::class, 'product_id');
}

}

