<?php

namespace App\Services\Insights;

use App\Models\Site;
use App\Services\Social\FacebookClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrendsCollector
{
    public function collectTrendsData(Site $site, bool $forceRefresh = false): array
    {
        $cacheKey = "trends_data_{$site->id}_" . now()->format('Y_W_H'); // Lägg till timme för mer frequent updates

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addHours(3), function () use ($site) {
            Log::info("Collecting fresh trends data for site: {$site->id}");

            $data = [
                'trending_topics' => $this->getTrendingTopics($site),
                'industry_trends' => $this->getIndustryTrends($site),
                'popular_content' => $this->getPopularContent($site),
                'recommended_hashtags' => $this->getRecommendedHashtags($site),
                'seasonal_trends' => $this->getSeasonalTrends($site),
                'collected_at' => now()->toISOString(),
                'cache_info' => [
                    'generated_fresh' => true,
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'site_industry' => $site->industry,
                ]
            ];

            Log::info("Generated trends data", [
                'site_id' => $site->id,
                'trending_topics_count' => count($data['trending_topics']),
                'hashtags_count' => count($data['recommended_hashtags']),
            ]);

            return $data;
        });
    }

    private function getTrendingTopics(Site $site): array
    {
        $keywords = array_merge(
            $site->effectiveKeywords(),
            array_filter([$site->industry]),
            array_filter(explode(' ', $site->business_description ?? ''))
        );

        $topics = [];

        // 1. Försök hämta från Google Trends via SerpApi
        $googleTrends = $this->getGoogleTrends($keywords);
        if (!empty($googleTrends)) {
            $topics = array_merge($topics, $googleTrends);
            Log::info("Got Google Trends data", ['count' => count($googleTrends)]);
        }

        // 2. Lägg till dynamiska simulerade topics baserat på datum/tid för variation
        $dynamicTopics = $this->getDynamicTrendingTopics($site->industry);
        $topics = array_merge($topics, $dynamicTopics);

        return array_slice(array_unique($topics, SORT_REGULAR), 0, 5);
    }

    private function getGoogleTrends(array $keywords): array
    {
        if (empty($keywords) || !config('services.serpapi.key')) {
            Log::info("No SERPAPI key or keywords, skipping Google Trends");
            return [];
        }

        try {
            // Använder SerpApi för Google Trends data
            $keyword = implode(' ', array_slice($keywords, 0, 3));

            $response = Http::timeout(15)->get('https://serpapi.com/search', [
                'engine' => 'google_trends',
                'q' => $keyword,
                'geo' => 'SE', // Sverige
                'date' => 'today 3-m', // Senaste 3 månaderna
                'api_key' => config('services.serpapi.key'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("SerpApi response received", ['status' => $response->status()]);

                $trends = [];

                // Extrahera trending topics från SerpApi response
                if (isset($data['interest_over_time']['timeline_data'])) {
                    $recentData = array_slice($data['interest_over_time']['timeline_data'], -4);
                    $avgInterest = 0;

                    foreach ($recentData as $timePoint) {
                        $avgInterest += $timePoint['values'][0]['value'] ?? 0;
                    }
                    $avgInterest = $avgInterest / count($recentData);

                    if ($avgInterest > 20) {
                        $trends[] = [
                            'topic' => $keyword,
                            'trend_score' => round($avgInterest),
                            'why' => 'Växande sökintresse enligt Google Trends'
                        ];
                    }
                }

                // Extrahera relaterade sökningar
                if (isset($data['related_queries']['rising'])) {
                    foreach (array_slice($data['related_queries']['rising'], 0, 2) as $related) {
                        $trends[] = [
                            'topic' => $related['query'],
                            'trend_score' => 85,
                            'why' => 'Relaterad stigande sökning'
                        ];
                    }
                }

                return $trends;
            } else {
                Log::warning("SerpApi failed", ['status' => $response->status()]);
            }
        } catch (\Exception $e) {
            Log::warning('Google Trends API error', ['error' => $e->getMessage()]);
        }

        return [];
    }

    private function getDynamicTrendingTopics(string $industry): array
    {
        // Skapa variation baserat på tid och datum
        $dayOfWeek = now()->dayOfWeek;
        $hour = now()->hour;
        $dayOfMonth = now()->day;

        // Base topics per industry
        $baseTopics = match($industry) {
            'restaurang', 'mat', 'food' => [
                ['topic' => 'Hållbar mat', 'base_score' => 85],
                ['topic' => 'Lokala råvaror', 'base_score' => 78],
                ['topic' => 'Veganska alternativ', 'base_score' => 72],
                ['topic' => 'Säsongsmat', 'base_score' => 65],
                ['topic' => 'Food trucks', 'base_score' => 60],
            ],
            'teknologi', 'tech', 'IT' => [
                ['topic' => 'AI-verktyg', 'base_score' => 92],
                ['topic' => 'Cybersäkerhet', 'base_score' => 81],
                ['topic' => 'Remote work', 'base_score' => 75],
                ['topic' => 'Cloud-lösningar', 'base_score' => 70],
                ['topic' => 'Automation', 'base_score' => 68],
            ],
            default => [
                ['topic' => 'Hållbarhet', 'base_score' => 80],
                ['topic' => 'Digital transformation', 'base_score' => 75],
                ['topic' => 'Kundupplevelse', 'base_score' => 70],
                ['topic' => 'Social media marketing', 'base_score' => 65],
                ['topic' => 'E-handel', 'base_score' => 62],
            ],
        };

        // Lägg till variation baserat på tid
        $dynamicTopics = [];
        foreach (array_slice($baseTopics, 0, 3) as $topic) {
            // Variera score baserat på dag, timme, etc.
            $scoreVariation = ($dayOfWeek * 3) + ($hour % 10) + ($dayOfMonth % 5);
            $dynamicScore = min(95, max(50, $topic['base_score'] + $scoreVariation));

            $dynamicWhy = $this->getDynamicReason($topic['topic'], $dayOfWeek, $hour);

            $dynamicTopics[] = [
                'topic' => $topic['topic'],
                'trend_score' => $dynamicScore,
                'why' => $dynamicWhy
            ];
        }

        return $dynamicTopics;
    }

    private function getDynamicReason(string $topic, int $dayOfWeek, int $hour): string
    {
        $reasons = [
            'Växande intresse denna vecka',
            'Populärt ämne just nu',
            'Hög aktivitet i sociala medier',
            'Säsongstrend som ökar',
            'Många diskuterar detta idag',
            'Trending i branschen',
        ];

        // Välj reason baserat på topic och tid
        $index = (strlen($topic) + $dayOfWeek + $hour) % count($reasons);
        return $reasons[$index];
    }

    private function getIndustryTrends(Site $site): array
    {
        if (!$site->industry) {
            return [];
        }

        // Kombinera riktiga trender med dynamiska simulerade
        $realTrends = $this->getRealIndustryTrends($site->industry);
        $dynamicTrends = $this->getDynamicIndustryTrends($site->industry);

        return array_merge($realTrends, $dynamicTrends);
    }

    private function getRealIndustryTrends(string $industry): array
    {
        try {
            // Här skulle vi integrera med industri-specifika API:er
            $linkedinTrends = $this->getLinkedInIndustryTrends($industry);
            if (!empty($linkedinTrends)) {
                return $linkedinTrends;
            }
        } catch (\Exception $e) {
            Log::warning('LinkedIn trends error', ['error' => $e->getMessage()]);
        }

        return [];
    }

    private function getDynamicIndustryTrends(string $industry): array
    {
        // Variera trends baserat på datum och bransch
        $month = now()->month;
        $dayOfWeek = now()->dayOfWeek;

        $baseTrends = [
            'growth_sectors' => ['E-handel', 'Hållbarhet', 'Digital marknadsföring'],
            'declining_trends' => ['Traditionell print-reklam', 'Fysiska events'],
            'emerging_opportunities' => ['TikTok-marknadsföring', 'Influencer-samarbeten', 'UGC-content'],
        ];

        // Lägg till månad-specifika trender
        if ($month >= 11 || $month <= 1) {
            $baseTrends['growth_sectors'][] = 'Julkampanjer';
            $baseTrends['emerging_opportunities'][] = 'Årsavslutning-content';
        } elseif ($month >= 3 && $month <= 5) {
            $baseTrends['growth_sectors'][] = 'Vårkampanjer';
            $baseTrends['emerging_opportunities'][] = 'Förnyelseteman';
        }

        return $baseTrends;
    }

    private function getLinkedInIndustryTrends(string $industry): array
    {
        if (!config('services.linkedin.client_id')) {
            return [];
        }

        // Detta skulle kräva LinkedIn Marketing API access
        return [];
    }

    private function getPopularContent(Site $site): array
    {
        $content = [];

        // 1. Försök hämta från Facebook Insights om integrationen finns
        $fbContent = $this->getFacebookPopularContent($site);
        if (!empty($fbContent)) {
            $content = array_merge($content, $fbContent);
        }

        // 2. Lägg till dynamiskt varierat simulerat innehåll
        $dynamicContent = $this->getDynamicPopularContent($site);
        $content = array_merge($content, $dynamicContent);

        return array_slice($content, 0, 4);
    }

    private function getDynamicPopularContent(Site $site): array
    {
        $contentTypes = ['video', 'carousel', 'single_image', 'text_post'];
        $platforms = ['Instagram', 'Facebook', 'LinkedIn', 'TikTok'];

        $hour = now()->hour;
        $day = now()->day;

        // Variera content baserat på tid
        $selectedType = $contentTypes[($hour + $day) % count($contentTypes)];
        $selectedPlatform = $platforms[($hour * 2 + $day) % count($platforms)];

        $baseScore = 60 + ($hour % 30) + ($day % 10);

        return [
            [
                'type' => $selectedType,
                'title' => $this->getContentTitle($selectedType, $site->industry),
                'engagement_score' => $baseScore,
                'platform' => $selectedPlatform,
                'why' => $this->getContentReason($selectedType, $selectedPlatform)
            ]
        ];
    }

    private function getContentTitle(string $type, ?string $industry): string
    {
        $titles = match($type) {
            'video' => [
                'Bakom kulisserna',
                'Så här gör vi',
                'En dag på jobbet',
                'Kundens resa',
            ],
            'carousel' => [
                'Tips för din bransch',
                'Steg-för-steg guide',
                '5 saker att tänka på',
                'Vanliga misstag',
            ],
            'single_image' => [
                'Inspirerande citat',
                'Produktfokus',
                'Team spotlight',
                'Workspace tour',
            ],
            default => [
                'Dagens reflektion',
                'Branschinsikter',
                'Kundhistoria',
                'Kommande trender',
            ]
        };

        return $titles[array_rand($titles)];
    }

    private function getContentReason(string $type, string $platform): string
    {
        return match($platform) {
            'Instagram' => 'Visuellt innehåll presterar bäst på Instagram',
            'LinkedIn' => 'Professionellt innehåll når rätt målgrupp',
            'Facebook' => 'Bred räckvidd och hög interaktion',
            'TikTok' => 'Kort format engagerar yngre målgrupp',
            default => 'Plattformen favoriserar denna typ av innehåll'
        };
    }

    private function getFacebookPopularContent(Site $site): array
    {
        try {
            $fbIntegration = $site->integrations()
                ->where('provider', 'facebook')
                ->where('status', 'active')
                ->first();

            if (!$fbIntegration || !$fbIntegration->access_token) {
                return [];
            }

            $response = Http::timeout(15)->get("https://graph.facebook.com/v19.0/{$fbIntegration->page_id}/posts", [
                'access_token' => $fbIntegration->access_token,
                'fields' => 'message,created_time,engagement',
                'since' => now()->subDays(30)->toISOString(),
                'limit' => 10
            ]);

            if ($response->successful()) {
                $posts = $response->json()['data'] ?? [];
                $popularContent = [];

                foreach (array_slice($posts, 0, 2) as $post) {
                    if (!empty($post['message'])) {
                        $popularContent[] = [
                            'type' => 'social_post',
                            'title' => \Illuminate\Support\Str::limit($post['message'], 50),
                            'engagement_score' => $post['engagement']['count'] ?? 0,
                            'platform' => 'Facebook',
                            'why' => 'Högt engagemang på din Facebook-sida'
                        ];
                    }
                }

                Log::info("Got Facebook content", ['count' => count($popularContent)]);
                return $popularContent;
            }
        } catch (\Exception $e) {
            Log::warning('Facebook content fetch error', ['error' => $e->getMessage()]);
        }

        return [];
    }

    private function getRecommendedHashtags(Site $site): array
    {
        $hashtags = [];

        // 1. Branschspecifika hashtags
        if ($site->industry) {
            $industryHashtags = $this->getIndustryHashtags($site->industry);
            $hashtags = array_merge($hashtags, $industryHashtags);
        }

        // 2. Dynamiska trending hashtags
        $trendingHashtags = $this->getDynamicTrendingHashtags();
        $hashtags = array_merge($hashtags, $trendingHashtags);

        // 3. Säsongsbaserade hashtags
        $seasonalHashtags = $this->getSeasonalHashtags();
        $hashtags = array_merge($hashtags, $seasonalHashtags);

        return array_unique(array_slice($hashtags, 0, 12));
    }

    private function getDynamicTrendingHashtags(): array
    {
        $day = now()->day;
        $month = now()->month;

        $baseHashtags = ['#sverige', '#företag'];

        // Lägg till varierade hashtags baserat på datum
        $dynamicHashtags = [
            '#trend' . (2024 + ($day % 2)),
            '#' . now()->format('Y') . 'goals',
            '#innovation' . ($month % 3 ? '' : '2024'),
            '#digital' . ($day % 2 ? 'transformation' : 'marketing'),
        ];

        return array_merge($baseHashtags, array_slice($dynamicHashtags, 0, 3));
    }

    private function getSeasonalHashtags(): array
    {
        $month = now()->month;
        return match(true) {
            in_array($month, [12, 1, 2]) => ['#vinter', '#nyår', '#vinterkampanj'],
            in_array($month, [3, 4, 5]) => ['#vår', '#påsk', '#vårkampanj'],
            in_array($month, [6, 7, 8]) => ['#sommar', '#semester', '#sommarkampanj'],
            in_array($month, [9, 10, 11]) => ['#höst', '#skola', '#höstkampanj'],
        };
    }

    private function getSeasonalTrends(Site $site): array
    {
        $month = now()->month;
        $season = match(true) {
            in_array($month, [12, 1, 2]) => 'winter',
            in_array($month, [3, 4, 5]) => 'spring',
            in_array($month, [6, 7, 8]) => 'summer',
            in_array($month, [9, 10, 11]) => 'autumn',
        };

        return $this->getSeasonalTopics($season, $site->industry);
    }

    private function getIndustryHashtags(string $industry): array
    {
        return match($industry) {
            'restaurang', 'mat' => ['#restaurang', '#mat', '#food', '#lokaltproducerat', '#sverige'],
            'teknologi', 'tech' => ['#tech', '#innovation', '#digitalisering', '#startup', '#ai'],
            'frisör', 'skönhet' => ['#frisör', '#skönhet', '#hår', '#beauty', '#style'],
            default => ['#företag', '#service', '#kvalitet', '#sverige'],
        };
    }

    private function getSeasonalTopics(string $season, ?string $industry): array
    {
        $seasonal = match($season) {
            'winter' => ['Jul', 'Nyår', 'Vintererbjudanden', 'Värme', 'Mys'],
            'spring' => ['Vår', 'Förnyelse', 'Vårstädning', 'Påsk', 'Tillväxt'],
            'summer' => ['Sommar', 'Semester', 'Utomhus', 'Festival', 'Avkoppling'],
            'autumn' => ['Höst', 'Skola', 'Tillbaka till jobbet', 'Mys', 'Halloween'],
        };

        return array_slice($seasonal, 0, 3);
    }
}
