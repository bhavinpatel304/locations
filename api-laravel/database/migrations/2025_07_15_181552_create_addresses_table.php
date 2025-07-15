<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('address', 50);
            $table->string('address2', 50)->nullable();
            $table->string('postal_code', 8);
            $table->string('city', 20);
            $table->string('province', 20);
            $table->unsignedBigInteger('id_country');
            $table->string('phone', 10);
            $table->boolean('deleted');
            $table->timestamps();

            $table->foreign('id_country')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('addresses');
    }
};
