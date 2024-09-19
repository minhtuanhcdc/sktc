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
        Schema::create('paraminputs', function (Blueprint $table) {
            $table->id();
            $table->time('input_date')->nullable()->default(new DateTime());
            $table->integer('month');
            $table->float('length');
            $table->float('weight');
            $table->float('BMI');
            $table->json('classify');
            $table->integer('placeofbalance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paraminputs');
    }
};
