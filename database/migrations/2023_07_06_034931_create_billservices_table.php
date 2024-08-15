<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billservices', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bill');
            $table->integer('id_service');
            $table->double('don_gia', 10, 2);
            $table->integer('sl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billservices');
    }
};
