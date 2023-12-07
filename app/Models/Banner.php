<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['image','url', 'text', 'subtext', 'colour','order','mobile_image','category_id'];


public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

}
