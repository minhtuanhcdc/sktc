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
        Schema::create('infobases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sex');
            $table->date('birthday');
            $table->string('parent');
            $table->string('phone');
            $table->string('address');
            $table->string('ward');
            $table->string('district');
            $table->string('province');
            $table->boolean('weightbirth');
            $table->integer('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infobases');
    }
};
