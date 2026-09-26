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
        Schema::create('project_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('thumbnail')->nullable();
            
            $table->string('project_url')->nullable();
            $table->string('github_url')->nullable();
            
            $table->enum('visibility', ['public', 'private'])->default('public');
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
        });

        Schema::create('project_revision_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_revision_id')->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamps();
        });

        Schema::create('project_revision_technologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_revision_id')->constrained()->onDelete('cascade');
            $table->string('technology_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_revision_technologies');
        Schema::dropIfExists('project_revision_media');
        Schema::dropIfExists('project_revisions');
    }
};
