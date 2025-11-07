<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialShareClick extends Model
{
    // Platform constants
    public const FACEBOOK = 'facebook';

    public const TWITTER = 'twitter';

    public const WHATSAPP = 'whatsapp';

    public const TELEGRAM = 'telegram';

    public const EMAIL = 'email';

    // Page type constants
    public const PAGE_TYPE_HOME = 'home';

    public const PAGE_TYPE_NEWS = 'news';

    protected $fillable = [
        'platform',
        'page_url',
        'page_type',
        'news_post_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the news post that this share click belongs to (if applicable).
     */
    public function newsPost(): BelongsTo
    {
        return $this->belongsTo(NewsPost::class);
    }

    /**
     * Scope a query to filter by platform.
     */
    public function scopeByPlatform(Builder $query, string $platform): Builder
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange(Builder $query, ?string $startDate = null, ?string $endDate = null): Builder
    {
        $tableName = (new static)->getTable();
        if ($startDate) {
            $query->whereDate($tableName.'.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate($tableName.'.created_at', '<=', $endDate);
        }

        return $query;
    }

    /**
     * Scope a query to filter by page type.
     */
    public function scopeByPageType(Builder $query, ?string $pageType): Builder
    {
        if ($pageType) {
            return $query->where('page_type', $pageType);
        }

        return $query;
    }

    /**
     * Get all available platforms.
     */
    public static function getPlatforms(): array
    {
        return [
            self::FACEBOOK,
            self::TWITTER,
            self::WHATSAPP,
            self::TELEGRAM,
            self::EMAIL,
        ];
    }
}
