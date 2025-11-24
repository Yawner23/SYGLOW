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
        Schema::create('distributors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('distributor_type', ['Regional', 'Provincial', 'City', 'Reseller']);
            $table->string('region');
            $table->string('province');
            $table->string('city');
            $table->string('brgy');
            $table->string('name');
            $table->string('code');
            $table->string('valid_id_path');
            $table->string('selfie_with_id_path');
            $table->string('photo_with_background_path');

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
        Schema::dropIfExists('distributors');
    }
};
