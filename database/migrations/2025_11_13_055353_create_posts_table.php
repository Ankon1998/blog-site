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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            // Includes user_id foreign key at creation time
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->nullable();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');

            $table->timestamps();
        });

        // Add the 'image' column here, as it was added in a later step
        Schema::table('posts', function (Blueprint $table) {
            $table->string('image')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};