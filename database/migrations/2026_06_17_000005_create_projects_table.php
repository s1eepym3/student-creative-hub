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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->string('thumbnail');
            $table->string('project_url')->nullable();
            $table->string('github_url')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->index('judul');
            $table->index('status');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
