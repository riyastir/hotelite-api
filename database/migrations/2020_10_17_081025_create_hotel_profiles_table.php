<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('locality', 260);
            $table->string('city', 100);
            $table->string('state', 100);
            $table->double('lat', 10, 2);
            $table->double('lng', 10, 2);
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
        Schema::table('hotel_profiles', function (Blueprint $table) {
            $table->dropForeign('hotel_profiles_user_id_foreign');
            $table->dropIndex('hotel_profiles_user_id_index');
            $table->dropColumn('user_id');
        });

        Schema::dropIfExists('hotel_profiles');
    }
}
