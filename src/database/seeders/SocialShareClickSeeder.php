<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use App\Models\SocialShareClick;
use Illuminate\Database\Seeder;

class SocialShareClickSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        SocialShareClick::truncate();

        $platforms = [
            SocialShareClick::FACEBOOK,
            SocialShareClick::TWITTER,
            SocialShareClick::WHATSAPP,
            SocialShareClick::TELEGRAM,
            SocialShareClick::EMAIL,
        ];

        // Platform weights (some platforms are more popular)
        $platformWeights = [
            SocialShareClick::FACEBOOK => 30,
            SocialShareClick::WHATSAPP => 25,
            SocialShareClick::TWITTER => 20,
            SocialShareClick::TELEGRAM => 15,
            SocialShareClick::EMAIL => 10,
        ];

        // Get all published news posts
        $newsPosts = NewsPost::where('status', 'published')->get();

        if ($newsPosts->isEmpty()) {
            $this->command->warn('No published news posts found. Please run NewsPostSeeder first.');

            return;
        }

        $this->command->info('Generating social share analytics data...');

        $totalShares = 0;

        // Generate shares for news articles
        $this->command->info('Generating shares for news articles...');

        foreach ($newsPosts as $index => $post) {
            // Base shares per article (0-30)
            $articleShares = rand(0, 30);

            // Some articles are more popular (viral effect - 20% chance)
            if (rand(1, 10) <= 2) {
                $articleShares = rand(50, 150);
            }

            // Recent articles tend to get more shares (published in last 7 days)
            $daysSincePublished = $post->published_at
                ? now()->diffInDays($post->published_at)
                : rand(0, 30);

            if ($daysSincePublished <= 7) {
                $articleShares = (int) ($articleShares * 1.5);
            }

            for ($i = 0; $i < $articleShares; $i++) {
                $platform = $this->getRandomPlatform($platforms, $platformWeights);

                // Shares are more likely to happen shortly after publication
                $createdAt = $this->getRandomDateWeighted($post->published_at ?? now()->subDays(30));

                SocialShareClick::create([
                    'platform' => $platform,
                    'page_url' => url('/news/'.$post->slug),
                    'page_type' => SocialShareClick::PAGE_TYPE_NEWS,
                    'news_post_id' => $post->id,
                    'ip_address' => $this->generateRandomIp(),
                    'user_agent' => $this->generateRandomUserAgent(),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $totalShares++;
            }
        }

        // Generate shares for home page (30% of total shares)
        $homeShareCount = (int) ($totalShares * 0.3);
        $this->command->info("Generating {$homeShareCount} shares for home page...");

        for ($i = 0; $i < $homeShareCount; $i++) {
            $platform = $this->getRandomPlatform($platforms, $platformWeights);
            $createdAt = $this->getRandomDate(now()->subDays(30));

            SocialShareClick::create([
                'platform' => $platform,
                'page_url' => url('/'),
                'page_type' => SocialShareClick::PAGE_TYPE_HOME,
                'news_post_id' => null,
                'ip_address' => $this->generateRandomIp(),
                'user_agent' => $this->generateRandomUserAgent(),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $totalShares++;
        }

        $this->command->info("Successfully generated {$totalShares} social share clicks!");
        $this->command->info('  - News articles: '.SocialShareClick::where('page_type', SocialShareClick::PAGE_TYPE_NEWS)->count());
        $this->command->info('  - Home page: '.SocialShareClick::where('page_type', SocialShareClick::PAGE_TYPE_HOME)->count());

        // Show breakdown by platform
        $this->command->info("\nShares by platform:");
        foreach ($platforms as $platform) {
            $count = SocialShareClick::where('platform', $platform)->count();
            $this->command->info('  - '.ucfirst($platform).": {$count}");
        }
    }

    /**
     * Get a random platform based on weights
     */
    private function getRandomPlatform(array $platforms, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $current = 0;

        foreach ($platforms as $platform) {
            $current += $weights[$platform];
            if ($random <= $current) {
                return $platform;
            }
        }

        return $platforms[0];
    }

    /**
     * Generate a random date within the last 30 days from the base date
     */
    private function getRandomDate($baseDate): \DateTime
    {
        $daysAgo = rand(0, 30);
        $hoursAgo = rand(0, 23);
        $minutesAgo = rand(0, 59);

        return \Carbon\Carbon::parse($baseDate)
            ->subDays($daysAgo)
            ->subHours($hoursAgo)
            ->subMinutes($minutesAgo);
    }

    /**
     * Generate a random date weighted towards recent dates (more shares happen soon after publication)
     */
    private function getRandomDateWeighted($baseDate): \DateTime
    {
        $base = \Carbon\Carbon::parse($baseDate);
        $now = now();

        // Calculate days since publication
        $daysSincePublication = $base->diffInDays($now);

        // If published in the future or very recently, use current time as max
        $maxDays = min(30, max(0, $daysSincePublication));

        // Weight: 40% chance in first 3 days, 30% in days 4-7, 20% in days 8-14, 10% in days 15-30
        $random = rand(1, 100);

        if ($random <= 40) {
            $daysAfter = min(rand(0, 3), $maxDays);
        } elseif ($random <= 70) {
            $daysAfter = min(rand(4, 7), $maxDays);
        } elseif ($random <= 90) {
            $daysAfter = min(rand(8, 14), $maxDays);
        } else {
            $daysAfter = min(rand(15, 30), $maxDays);
        }

        $hoursAgo = rand(0, 23);
        $minutesAgo = rand(0, 59);

        $shareDate = $base->copy()->addDays($daysAfter)->subHours($hoursAgo)->subMinutes($minutesAgo);

        // Ensure we don't go into the future
        if ($shareDate->isFuture()) {
            $shareDate = $now->copy()->subHours(rand(0, 23))->subMinutes(rand(0, 59));
        }

        return $shareDate;
    }

    /**
     * Generate a random IP address
     */
    private function generateRandomIp(): string
    {
        // Generate realistic IP addresses
        if (rand(1, 2) === 1) {
            // IPv4
            return rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255);
        } else {
            // IPv6 (simplified)
            return sprintf(
                '%04x:%04x:%04x:%04x:%04x:%04x:%04x:%04x',
                rand(0, 0xFFFF), rand(0, 0xFFFF), rand(0, 0xFFFF), rand(0, 0xFFFF),
                rand(0, 0xFFFF), rand(0, 0xFFFF), rand(0, 0xFFFF), rand(0, 0xFFFF)
            );
        }
    }

    /**
     * Generate a random user agent
     */
    private function generateRandomUserAgent(): string
    {
        $browsers = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPad; CPU OS 17_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Mobile/15E148 Safari/604.1',
        ];

        return $browsers[array_rand($browsers)];
    }
}
