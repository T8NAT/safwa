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
        Schema::table('car_bookings', function (Blueprint $table) {
            $table->string('trip_id')->after('email')->nullable();
            $table->decimal('amount',28,8)->after('destination')->default(0);
            $table->decimal('distance',28,8)->after('amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_bookings', function (Blueprint $table) {
            $table->dropColumn('trip_id');
            $table->dropColumn('amount');
            $table->dropColumn('distance');
        });
    }
};
