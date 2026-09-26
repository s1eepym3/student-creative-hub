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
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->morphs('viewable'); // viewable_type, viewable_id
            $table->string('visitor_hash', 64)->nullable(); // SHA-256 fingerprint (ip + user_agent)
            $table->string('source', 30)->default('direct'); // direct, qr, internal, external
            $table->timestamp('created_at')->useCurrent();

            // Indexes for rapid aggregation and timeline filtering
            $table->index(['mahasiswa_id', 'viewable_type', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
