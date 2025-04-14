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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // neve a terméknek
            $table->text('description'); // leírás a details oldalon jelenik meg
            $table->integer('price'); // termék ár
            $table->integer('stock'); // raktáron lévő darabszám
            $table->integer('discount')->default(0); // %-ban megadott kedvezmény értéke
            $table->string('category'); // Kategóriában vesszővel elválasztott lista tárolódik amit a a categories oldalon listázunk
            $table->string('similar')->nullable(); // Azonos termékekre szolgál (pl. felsők esetén fekete-fehér-szürke etc.)
            $table->json('sizes')->nullable(); // Ruházati cikkek méreteire szolgál
            $table->timestamps();
            $table->boolean('status')->default(true); // státusza a terméknek, ha true megjelenik az oldaon

        });

        Schema::create('item_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_images');
        Schema::dropIfExists('items');
    }
};
