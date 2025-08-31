<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->cascadeOnDelete();
            $table->string('name', 30);
            $table->text('content');
            $table->string('image_path')->nullable(); // /storage/images/... を格納
            $table->timestamps();
            $table->index(['board_id', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('posts');
    }
};
