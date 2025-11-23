<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE news_posts MODIFY COLUMN status ENUM('pending', 'approved', 'published', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE news_posts MODIFY COLUMN status ENUM('pending', 'approved', 'published') NOT NULL DEFAULT 'pending'");
    }
};
