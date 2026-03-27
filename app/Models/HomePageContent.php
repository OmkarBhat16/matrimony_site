<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageContent extends Model
{
    protected $table = 'home_page_contents';

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'hero' => [
                'title' => 'Find Your Perfect Match',
                'highlight' => 'Perfect Match',
                'description' => 'Join our trusted community of verified profiles. We help you connect with like-minded individuals who share your values, culture, and interests.',
                'register_button' => 'Get Started Free',
                'browse_button' => 'Browse Profiles',
            ],
            'stats' => [
                ['value' => '1000+', 'label' => 'Verified Profiles'],
                ['value' => '500+', 'label' => 'Successful Matches'],
                ['value' => '100%', 'label' => 'Privacy Focused'],
                ['value' => '24/7', 'label' => 'Support Available'],
            ],
            'featured' => [
                'title' => 'Featured Profiles',
                'subtitle' => 'Meet some of our verified community members',
            ],
            'how_it_works' => [
                'title' => 'How It Works',
                'subtitle' => 'Finding your partner is simple with us',
                'steps' => [
                    [
                        'title' => '1. Create Profile',
                        'description' => 'Register and build your detailed profile with your preferences and background.',
                    ],
                    [
                        'title' => '2. Browse & Filter',
                        'description' => 'Search verified profiles by religion/jaath, city, age, and more to find compatible matches.',
                    ],
                    [
                        'title' => '3. Connect',
                        'description' => 'View full profiles, download details, and take the next step towards your future together.',
                    ],
                ],
            ],
            'cta' => [
                'title' => 'Ready to Begin Your Journey?',
                'description' => "Join our growing community and find the partner you've been looking for.",
                'button' => "Register Now - It's Free",
            ],
        ];
    }

    public function normalizedContent(): array
    {
        return array_replace_recursive(self::defaults(), $this->content ?? []);
    }
}
