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
        Schema::create('social_share_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // facebook, twitter, whatsapp, telegram, email
            $table->string('page_url', 2048); // stores the shared page URL
            $table->string('page_type')->nullable(); // 'home' or 'news' for filtering
            $table->foreignId('news_post_id')->nullable()->constrained('news_posts')->onDelete('set null');
            $table->string('ip_address', 45)->nullable(); // IPv6 support
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes for query performance
            $table->index('platform');
            $table->index('page_type');
            $table->index('created_at');
            $table->index(['platform', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_share_clicks');
    }
};
