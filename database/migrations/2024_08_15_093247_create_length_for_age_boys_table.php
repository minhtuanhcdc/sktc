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
        Schema::create('length_for_age_boys', function (Blueprint $table) {
            $table->id();
            $table->integer('month');
            $table->double('L', 10, 1);
            $table->double('M', 10, 1);
            $table->double('S', 10, 1);
            $table->double('SD', 10, 1);
            $table->double('neg3SD', 10, 1);
            $table->double('neg2SD', 10, 1);
            $table->double('neg1SD', 10, 1);
            $table->double('median', 10, 1);
            $table->double('mot_SD', 10, 1);
            $table->double('hai_SD', 10, 1);
            $table->double('ba_SD', 10, 1);
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('length_for_age_boys');
    }
};
