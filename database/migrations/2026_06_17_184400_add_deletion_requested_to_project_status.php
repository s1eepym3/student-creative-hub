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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected', 'deletion_requested') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
