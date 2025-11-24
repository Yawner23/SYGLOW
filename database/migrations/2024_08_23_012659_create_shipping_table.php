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
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payment')->onDelete('cascade'); // Linking to payment
            $table->string('courier')->nullable(); // e.g., #143143
            $table->string('date_of_shipping')->nullable(); // e.g., 450 (Date format needs correction)
            $table->string('shipping_fee')->nullable(); // Shipping fee (450)
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
        Schema::dropIfExists('shipping');
    }
};
