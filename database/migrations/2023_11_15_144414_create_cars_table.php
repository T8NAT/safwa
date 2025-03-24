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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("car_area_id");
            $table->unsignedBigInteger("car_type_id");
            $table->string('slug');
            $table->string('car_model');
            $table->integer('seat');
            $table->integer('experience');
            $table->string('car_number');
            $table->decimal('fees',28,8)->default(0);
            $table->string('image')->nullable();
            $table->boolean("status")->default(true);
            $table->timestamps();


            $table->foreign("car_area_id")->references("id")->on("car_areas")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("car_type_id")->references("id")->on("car_types")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
