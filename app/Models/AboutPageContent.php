<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageContent extends Model
{
    protected $table = 'about_page_contents';

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'header' => [
                'title' => 'About Us',
                'subtitle' => 'Learn more about our mission to bring people together.',
            ],
            'mission' => [
                'title' => 'Our Mission',
                'description' => 'At Matrimony, we believe that everyone deserves to find their perfect life partner. Our platform is built on the foundation of trust, privacy, and cultural sensitivity. We understand the importance of family values, traditions, and compatibility in building lasting relationships.',
            ],
            'offers' => [
                [
                    'title' => 'Verified Profiles',
                    'description' => 'Every profile is reviewed and approved by our admin team to ensure authenticity.',
                ],
                [
                    'title' => 'Privacy First',
                    'description' => 'Your personal information is protected and only visible to approved members.',
                ],
                [
                    'title' => 'Advanced Filters',
                    'description' => 'Search by religion/jaath, city, age, and more to find your ideal match.',
                ],
                [
                    'title' => 'Growing Community',
                    'description' => 'Join thousands of members actively looking for their perfect partner.',
                ],
            ],
            'values' => [
                'title' => 'Our Values',
                'items' => [
                    'Trust & Safety - We verify every profile to create a safe matchmaking environment.',
                    'Cultural Respect - We honor diverse traditions, religions, and family values.',
                    'Transparency - No hidden fees, no fake profiles, just genuine connections.',
                ],
            ],
        ];
    }

    public function normalizedContent(): array
    {
        return array_replace_recursive(self::defaults(), $this->content ?? []);
    }
}
