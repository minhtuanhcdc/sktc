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
        Schema::create('weight_age_boys', function (Blueprint $table) {
            $table->id();
            $table->integer('thang');
            $table->double('binhthuong_max');
            $table->double('binhthuong_min');
            $table->double('suydd_max');
            $table->double('suydd_min');
            $table->double('suyddnang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_age_boys');
    }
};
