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
        Schema::create('books', function (Blueprint $table) {
            $table->string("isbn")->primary()->unique();
            $table->string("title");

            $table->integer("author");
            //$table->foreign("author")->references("id")->on("authors");

            $table->integer("editor");
            //$table->foreign("editor")->references("id")->on("editors");

            $table->integer("keyword");
            //$table->foreign("keyword")->references("id")->on("keywords");

            $table->text("summary");
            $table->integer("publish_year");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
