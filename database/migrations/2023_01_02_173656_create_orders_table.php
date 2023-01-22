<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('total_price');
            $table->integer('courier_price');
            $table->integer('total_paid');

            $table->foreignId('user_id');
            $table->foreignId('address_id');

            $table->integer('discounts')->default(0);
            $table->integer('tax')->default(0);

            $table->string('description')->nullable();

            $table->dateTime('printed_at')->nullable();
            $table->dateTime('sending_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
};
