<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_book')->constrained('books')->onDelete('cascade');
            $table->date('add_date');
            $table->date('read_date')->nullable();
            $table->text('comment')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();

            $table->unique(['id_user', 'id_book']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
