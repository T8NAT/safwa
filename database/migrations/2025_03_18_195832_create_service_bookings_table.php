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
        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("service_id");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string('slug');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->string('location');
            $table->string('destination');
            $table->time('pickup_time');
            $table->time('round_pickup_time')->nullable();
            $table->date('pickup_date');
            $table->date('round_pickup_date')->nullable();
            $table->text('message')->nullable();
            $table->decimal('original_price', 28, 8)->default(0);
            $table->decimal('discount_amount', 28, 8)->default(0);
            $table->decimal('final_price', 28, 8)->default(0);
            $table->tinyInteger("status")->default(0)->comment("0: Default, 1: Booked, 2: OnGoing, 3: Completed");
            $table->timestamps();
            $table->foreign("service_id")->references("id")->on("services")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_bookings');
    }
};
