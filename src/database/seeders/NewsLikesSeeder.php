<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = NewsPost::all();

        if ($users->isEmpty() || $posts->isEmpty()) {
            $this->command->warn('No users or posts found. Skipping likes seeding.');
            return;
        }

        $totalLikes = 0;

        foreach ($posts as $post) {
            // Determine a random number of likes for this post
            // Most posts get few likes, some get many (viral effect)
            $likeCount = $this->getWeightedLikeCount($users->count());

            if ($likeCount > 0) {
                // Get random users to like this post
                $likers = $users->random(min($likeCount, $users->count()));

                $likesData = [];
                foreach ($likers as $liker) {
                    $likesData[] = [
                        'user_id' => $liker->id,
                        'news_post_id' => $post->id,
                        'created_at' => now()->subMinutes(rand(1, 10000)),
                        'updated_at' => now(),
                    ];
                }

                // Insert likes
                DB::table('news_likes')->insertOrIgnore($likesData);

                // Update post likes count
                $post->update(['likes_count' => count($likesData)]);

                $totalLikes += count($likesData);
            } else {
                $post->update(['likes_count' => 0]);
            }
        }

        $this->command->info("Successfully generated {$totalLikes} likes across {$posts->count()} articles.");
    }

    /**
     * Get a weighted random like count.
     * Skews towards lower numbers but allows for some high numbers.
     */
    private function getWeightedLikeCount(int $maxUsers): int
    {
        $rand = rand(1, 100);

        if ($rand <= 50) {
            // 50% chance of 0-5 likes
            return rand(0, min(5, $maxUsers));
        } elseif ($rand <= 80) {
            // 30% chance of 6-20 likes (or max users)
            return rand(6, min(20, $maxUsers));
        } elseif ($rand <= 95) {
            // 15% chance of 21-50 likes (or max users)
            return rand(21, min(50, $maxUsers));
        } else {
            // 5% chance of viral (up to max users)
            return rand(51, $maxUsers);
        }
    }
}
