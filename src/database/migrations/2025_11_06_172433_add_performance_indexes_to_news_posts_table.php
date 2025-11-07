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
        Schema::table('news_posts', function (Blueprint $table) {
            // Add indexes for foreign keys to improve join performance
            // Note: Foreign keys may already have indexes, but we ensure they exist
            try {
                $table->index('category_id', 'news_posts_category_id_index');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }

            try {
                $table->index('user_id', 'news_posts_user_id_index');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }

            // Add composite index for common query pattern: status + published_at
            // This is used in HomeController for filtering published posts
            try {
                $table->index(['status', 'published_at'], 'news_posts_status_published_at_index');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_posts', function (Blueprint $table) {
            try {
                $table->dropIndex('news_posts_status_published_at_index');
            } catch (\Exception $e) {
                // Index might not exist, ignore
            }

            try {
                $table->dropIndex('news_posts_category_id_index');
            } catch (\Exception $e) {
                // Index might not exist, ignore
            }

            try {
                $table->dropIndex('news_posts_user_id_index');
            } catch (\Exception $e) {
                // Index might not exist, ignore
            }
        });
    }
};
