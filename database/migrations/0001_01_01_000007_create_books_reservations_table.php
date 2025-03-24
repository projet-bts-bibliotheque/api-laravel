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
        Schema::create('books_reservations', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('book_id');
            $table->foreign('book_id')
                  ->references('isbn')
                  ->on('books')
                  ->onDelete('cascade');

            $table->date('start');
            $table->date('return_date')->nullable();
            $table->boolean('reminder_mail_sent')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books_reservations');
    }
};
