<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoAndCountryCountryCodeToProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_profiles', function (Blueprint $table) {
            $table->string('logo',300);
            $table->string('country',50);
            $table->string('country_code',50);
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
            $table->dropColumn('logo');
            $table->dropColumn('country');
            $table->dropColumn('country_code');
        });
    }
}
