<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cart;

class CustomValue extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id','user_id','front_neck_depth','back_neck_depth','waist','waist_round','upper_bust','bust','under_bust','mid_waist','lower_waist','hip','thigh_circumference','knee_circumference','calf_circumference','ankle_circumference','arm_hole','sleeve_length','sleeve_circumference','top_length','shoulder','bottom_length','full_length','order_notes','measurement'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
    return $this->belongsTo(Cart::class);
    }
}
