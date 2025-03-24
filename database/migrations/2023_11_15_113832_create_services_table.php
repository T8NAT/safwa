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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('size');
            $table->string('number_bottles');
            $table->string('slug');
            $table->unsignedBigInteger("service_area_id");
            $table->unsignedBigInteger("service_type_id");
            $table->string('image')->nullable();
            $table->decimal('price',28,8)->default(0);
            $table->boolean("status")->default(true);
            $table->foreign("service_area_id")->references("id")->on("service_areas")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("service_type_id")->references("id")->on("service_types")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('services');
    }
};
