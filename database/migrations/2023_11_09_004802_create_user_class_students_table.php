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
        Schema::create('user_class_students', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->string('person_name');
            $table->string('attr_1');
            $table->string('attr_2');
            $table->string('attr_3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_class_students');
    }
};
