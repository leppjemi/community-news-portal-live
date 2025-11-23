<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // MySQL supports MODIFY COLUMN with ENUM
            DB::statement("ALTER TABLE news_posts MODIFY COLUMN status ENUM('pending', 'approved', 'published', 'rejected') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('news_posts', function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE news_posts MODIFY COLUMN status ENUM('pending', 'approved', 'published') NOT NULL DEFAULT 'pending'");
        } else {

            Schema::table('news_posts', function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->change();
            });
        }
    }
};
