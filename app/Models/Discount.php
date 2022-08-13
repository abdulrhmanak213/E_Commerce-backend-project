<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $table="discounts";
    protected $primaryKey="id";
    protected $timestemps=true;
    protected $fillable=['product_id',
    'discount_percentage',
    'date'];

}
