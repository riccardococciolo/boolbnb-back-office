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
        Schema::create('apartment_sponsor', function(Blueprint $table){
            $table->id();

            $table->unsignedBigInteger('apartment_id');
            $table->unsignedBigInteger('sponsor_id');
            
            $table->foreign('apartment_id')->references('id')->on('apartments')->cascadeOnDelete();
            $table->foreign('sponsor_id')->references('id')->on('sponsors')->cascadeOnDelete();

            $table->dateTime('activation_date');
            $table->dateTime('expiration_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartment_sponsor');
    }
};
