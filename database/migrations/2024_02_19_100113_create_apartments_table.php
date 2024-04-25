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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->string('title')->unique();
            $table->string('slug');
            $table->decimal('price', 8, 2)->unsigned();
            $table->string('address');
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->smallInteger('dimension_mq')->unsigned();
            $table->tinyInteger('rooms_number')->unsigned();
            $table->tinyInteger('beds_number')->unsigned();
            $table->tinyInteger('bathrooms_number')->unsigned();
            $table->boolean('is_visible')->default(1);
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
        Schema::dropIfExists('apartments');
    }
};
