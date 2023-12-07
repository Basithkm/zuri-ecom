<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_values', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id');
            $table->integer('user_id');
            $table->decimal('front_neck_depth', 5, 2);
            $table->decimal('back_neck_depth', 5, 2);
            $table->decimal('upper_bust', 5, 2);
            $table->decimal('bust', 5, 2);
            $table->decimal('under_bust', 5, 2);
            $table->decimal('mid_waist', 5, 2);
            $table->decimal('lower_waist', 5, 2);
            $table->decimal('hip', 5, 2);
            $table->decimal('thigh_circumference', 5, 2);
            $table->decimal('knee_circumference', 5, 2);
            $table->decimal('calf_circumference', 5, 2);
            $table->decimal('ankle_circumference', 5, 2);
            $table->integer('arm_hole');
            $table->decimal('sleeve_length', 5, 2);
            $table->decimal('sleeve_circumference', 5, 2);
            $table->decimal('top_length', 5, 2);
            $table->decimal('bottom_length', 5, 2);
            $table->decimal('full_length', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_values');
    }
};
