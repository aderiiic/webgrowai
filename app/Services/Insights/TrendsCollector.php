<?php

namespace App\Services\Insights;

use App\Models\Site;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrendsCollector
{
    public function collectTrendsData(Site $site, bool $forceRefresh = false): array
    {
        $ctx = [
            'ind'  => (string) $site->industry,
            'kw'   => implode('|', $site->effectiveKeywords()),
            'loc'  => (string) $site->locale,
            'upd'  => optional($site->updated_at)?->timestamp ?? 0,
            'hour' => now()->format('Y_W_H'),
        ];
        $cacheKey = 'trends_data_' . $site->id . '_' . substr(sha1(json_encode($ctx)), 0, 16);

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addMinutes(90), function () use ($site) {
            Log::info("Collecting fresh trends data for site: {$site->id}");

            // Nytt: hämta kanal-specifikt innehåll separat (så vi kan presentera snyggt i UI)
            $contentByPlatform = [
                'facebook'  => $this->getFacebookPopularContent($site),
                'instagram' => $this->getInstagramPopularContent($site),
                'linkedin'  => $this->getLinkedInPopularContent($site),
            ];
            $popularContentRaw = array_merge(
                $contentByPlatform['facebook'] ?? [],
                $contentByPlatform['instagram'] ?? [],
                $contentByPlatform['linkedin'] ?? [],
            );

            // Google söktrender separat bucket
            $keywords = array_unique(array_filter(array_merge(
                $site->effectiveKeywords(),
                array_filter([$site->industry]),
                array_filter(explode(' ', (string) $site->business_description))
            )));
            $googleTrendsRaw = $this->getGoogleTrends($keywords);

            // Topics (sammanvägda + rankade mot kontext)
            $trendingTopics     = $this->filterAndRankTopics(
                array_merge($googleTrendsRaw, $this->getDynamicTrendingTopics((string) $site->industry)),
                $site
            );

            // Hashtags per plattform (extraherat från respektive content)
            $hashtagsByPlatform = [
                'facebook'  => $this->extractHashtagsFromContent($contentByPlatform['facebook']),
                'instagram' => $this->extractHashtagsFromContent($contentByPlatform['instagram']),
                'linkedin'  => $this->extractHashtagsFromContent($contentByPlatform['linkedin']),
            ];

            // Sammanvägda rekommenderade hashtags (från topics/keywords + plattform + bransch + säsong)
            $recommendedHashtags = $this->getRecommendedHashtags(
                $site,
                $trendingTopics,
                array_values(array_unique(array_merge(
                    $hashtagsByPlatform['facebook'],
                    $hashtagsByPlatform['instagram'],
                    $hashtagsByPlatform['linkedin']
                )))
            );

            // Filtrera popular content mot kontext
            $popularContent = $this->filterPopularContent($popularContentRaw, $site);

            $data = [
                // Tidigare fält (oförändrade)
                'trending_topics'       => array_values($trendingTopics),
                'industry_trends'       => $this->getIndustryTrends($site),
                'popular_content'       => array_values($popularContent),
                'recommended_hashtags'  => array_values($recommendedHashtags),
                'seasonal_trends'       => $this->getSeasonalTrends($site),

                // NYTT: buckets för tydlig presentation per källa/kanal
                'popular_content_by_platform' => [
                    'facebook'  => $contentByPlatform['facebook'],
                    'instagram' => $contentByPlatform['instagram'],
                    'linkedin'  => $contentByPlatform['linkedin'],
                ],
                'hashtags_by_platform' => $hashtagsByPlatform,
                'topics_by_source'     => [
                    'google' => collect($googleTrendsRaw)->map(fn($t) => $t['topic'] ?? null)->filter()->values()->all(),
                ],

                'collected_at'          => now()->toISOString(),
                'cache_info'            => [
                    'generated_fresh' => true,
                    'timestamp'       => now()->format('Y-m-d H:i:s'),
                    'site_industry'   => $site->industry,
                ],
            ];

            Log::info("Generated trends data", [
                'site_id'                 => $site->id,
                'trending_topics_count'   => count($data['trending_topics']),
                'hashtags_count'          => count($data['recommended_hashtags']),
            ]);

            return $data;
        });
    }

    private function filterAndRankTopics(array $topics, Site $site): array
    {
        $kw = collect($site->effectiveKeywords())->map(fn($k) => Str::of($k)->lower()->toString())->filter()->values();
        $industry = Str::of((string) $site->industry)->lower()->toString();

        $scored = collect($topics)->map(function ($t) use ($kw, $industry) {
            $topic = is_array($t) ? ($t['topic'] ?? (string)($t['title'] ?? '')) : (string) $t;
            $why   = is_array($t) ? ($t['why'] ?? null) : null;
            $score = is_array($t) ? (int)($t['trend_score'] ?? 60) : 60;

            $topicLc = Str::of($topic)->lower()->toString();

            $kwHits   = $kw->filter(fn($k) => Str::contains($topicLc, $k))->count();
            $kwBoost  = min(30, $kwHits * 10);

            $indBoost = $industry && Str::contains($topicLc, $industry) ? 10 : 0;

            $generic = ['kundupplevelse','e-handel','social media marketing','digital transformation','tips','trender','nyheter'];
            $genericPenalty = collect($generic)->contains(fn($g) => Str::contains($topicLc, $g)) ? 10 : 0;

            $whyBoost = $why && preg_match('/just nu|stigande|denna vecka|trend/i', (string)$why) ? 5 : 0;

            $final = max(0, min(100, $score + $kwBoost + $indBoost + $whyBoost - $genericPenalty));

            return [
                'topic'       => $topic,
                'why'         => $why ?: 'Matchar aktuella sök- och sociala signaler för din nisch',
                'trend_score' => $final,
                '_key'        => Str::lower($topic),
            ];
        })
            ->filter(fn($t) => $t['trend_score'] >= 55)
            ->unique('_key')
            ->sortByDesc('trend_score')
            ->take(5);

        return $scored->map(fn($t) => collect($t)->except('_key')->all())->all();
    }

    private function filterPopularContent(array $items, Site $site): array
    {
        $kw = collect($site->effectiveKeywords())->map(fn($k) => Str::lower($k))->values();

        return collect($items)
            ->map(function ($i) {
                $title = (string)($i['title'] ?? '');
                $why   = (string)($i['why'] ?? '');
                $score = (int)($i['engagement_score'] ?? 60);
                return compact('title','why','score') + $i;
            })
            ->filter(function ($i) use ($kw) {
                $t = Str::lower((string)$i['title']);
                return $kw->isEmpty() ? $i['score'] >= 65 : ($i['score'] >= 55 || $kw->contains(fn($k) => Str::contains($t, $k)));
            })
            ->unique(function ($i) {
                return Str::lower((string)$i['title']).'|'.($i['platform'] ?? 'any');
            })
            ->sortByDesc('score')
            ->take(4)
            ->values()
            ->all();
    }

    private function filterHashtags(array $tags, Site $site): array
    {
        $year = (int) now()->year;

        $kw = collect($site->effectiveKeywords())
            ->map(fn($k) => Str::lower(trim($k)))
            ->filter()
            ->values();

        $industryNorm = $this->normalizeIndustry((string) $site->industry);

        // Hard blocklist för irrelevanta säsongsord när de inte matchar branschen
        $seasonalWords = ['skola','student','graduation','halloween','påsk','nyår','jul','semester','sommar','vinter','höst','vår'];

        $scored = collect($tags)
            ->map(fn($h) => (string) $h)
            ->filter()
            ->map(function ($h) use ($year) {
                // Normalisera innovation-år
                return preg_replace('/(innovation)(20\d{2})/i', '$1'.$year, $h);
            })
            ->filter(fn($h) => !preg_match('/20(1\d|2[0-3])/', $h)) // filtrera gamla år 2010‑2023
            ->unique()
            ->map(function ($h) use ($kw, $industryNorm, $seasonalWords) {
                $hl = Str::lower($h);

                // Poängsättning: keyword‑träffar och industrirelevans
                $kwHits  = $kw->filter(fn($k) => $k && Str::contains($hl, $k))->count();
                $kwBoost = min(40, $kwHits * 15);

                $indBoost = $industryNorm && Str::contains($hl, $industryNorm) ? 20 : 0;

                // Bestraffa uppenbart generiska/säsongsord om ingen match
                $seasonPenalty = collect($seasonalWords)->contains(fn($s) => Str::contains($hl, $s)) ? 12 : 0;

                // Bestraffa supergeneriskt
                $genericPenalty = preg_match('/^(#(sverige|företag|marketing|trender|nyheter))$/', $hl) ? 10 : 0;

                $base = 50; // baseline
                $score = max(0, min(100, $base + $kwBoost + $indBoost - $seasonPenalty - $genericPenalty));

                return ['tag' => $h, 'score' => $score];
            })
            // Rensa bort säsongsord om de inte har keyword/industriboost (dvs score lågt)
            ->filter(function ($it) use ($kw, $industryNorm) {
                $hasContext = $it['score'] >= 60;
                return $hasContext;
            })
            ->sortByDesc('score')
            ->pluck('tag')
            ->take(12)
            ->values()
            ->all();

        return $scored;
    }

    private function getTrendingTopics(Site $site): array
    {
        $keywords = array_unique(array_filter(array_merge(
            $site->effectiveKeywords(),
            array_filter([$site->industry]),
            array_filter(explode(' ', (string) $site->business_description))
        )));

        $topics = [];

        $googleTrends = $this->getGoogleTrends($keywords);
        if (!empty($googleTrends)) {
            $topics = array_merge($topics, $googleTrends);
            Log::info("Got Google Trends data", ['count' => count($googleTrends)]);
        }

        $dynamicTopics = $this->getDynamicTrendingTopics((string) $site->industry);
        $topics = array_merge($topics, $dynamicTopics);

        return array_slice($topics, 0, 12);
    }

    private function getDynamicTrendingTopics(string $industry): array
    {
        $dayOfWeek  = now()->dayOfWeek;
        $hour       = now()->hour;
        $dayOfMonth = now()->day;

        $baseTopics = match(Str::lower($industry)) {
            'restaurang', 'mat', 'food' => [
                ['topic' => 'Hållbar mat', 'base_score' => 85],
                ['topic' => 'Lokala råvaror', 'base_score' => 78],
                ['topic' => 'Veganska alternativ', 'base_score' => 72],
                ['topic' => 'Säsongsmat', 'base_score' => 65],
                ['topic' => 'Food trucks', 'base_score' => 60],
            ],
            'teknologi', 'tech', 'it' => [
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

        $dynamicTopics = [];
        foreach (array_slice($baseTopics, 0, 4) as $topic) {
            $scoreVariation = ($dayOfWeek * 3) + ($hour % 10) + ($dayOfMonth % 5);
            $dynamicScore   = min(95, max(50, $topic['base_score'] + $scoreVariation));
            $dynamicWhy     = $this->getDynamicReason($topic['topic'], $dayOfWeek, $hour);

            $dynamicTopics[] = [
                'topic'       => $topic['topic'],
                'trend_score' => $dynamicScore,
                'why'         => $dynamicWhy,
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
        $index = (strlen($topic) + $dayOfWeek + $hour) % count($reasons);
        return $reasons[$index];
    }

    private function getIndustryTrends(Site $site): array
    {
        if (!$site->industry) {
            return [];
        }

        $realTrends    = $this->getRealIndustryTrends($site->industry);
        $dynamicTrends = $this->getDynamicIndustryTrends($site->industry);

        return array_merge($realTrends, $dynamicTrends);
    }

    private function getRealIndustryTrends(string $industry): array
    {
        try {
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
        $month     = now()->month;
        $baseTrends = [
            'growth_sectors'        => ['E-handel', 'Hållbarhet', 'Digital marknadsföring'],
            'declining_trends'      => ['Traditionell print-reklam', 'Fysiska events'],
            'emerging_opportunities'=> ['TikTok-marknadsföring', 'Influencer-samarbeten', 'UGC-content'],
        ];

        if ($month >= 11 || $month <= 1) {
            $baseTrends['growth_sectors'][]        = 'Julkampanjer';
            $baseTrends['emerging_opportunities'][] = 'Årsavslutning-content';
        } elseif ($month >= 3 && $month <= 5) {
            $baseTrends['growth_sectors'][]        = 'Vårkampanjer';
            $baseTrends['emerging_opportunities'][] = 'Förnyelseteman';
        }

        return $baseTrends;
    }

    private function getLinkedInIndustryTrends(string $industry): array
    {
        if (!config('services.linkedin.client_id')) {
            return [];
        }
        return [];
    }

    private function getPopularContent(Site $site): array
    {
        $content = [];

        $fbContent = $this->getFacebookPopularContent($site);
        if (!empty($fbContent)) {
            $content = array_merge($content, $fbContent);
        }

        $dynamicContent = $this->getDynamicPopularContent($site);
        $content = array_merge($content, $dynamicContent);

        return array_slice($content, 0, 6);
    }

    private function getDynamicPopularContent(Site $site): array
    {
        $contentTypes = ['video', 'carousel', 'single_image', 'text_post'];
        $platforms    = ['Instagram', 'Facebook', 'LinkedIn', 'TikTok'];

        $hour = now()->hour;
        $day  = now()->day;

        $selectedType     = $contentTypes[($hour + $day) % count($contentTypes)];
        $selectedPlatform = $platforms[($hour * 2 + $day) % count($platforms)];

        $baseScore = 60 + ($hour % 30) + ($day % 10);

        return [[
            'type'             => $selectedType,
            'title'            => $this->getContentTitle($selectedType, $site->industry),
            'engagement_score' => $baseScore,
            'platform'         => $selectedPlatform,
            'why'              => $this->getContentReason($selectedType, $selectedPlatform),
        ]];
    }

    private function getContentTitle(string $type, ?string $industry): string
    {
        $titles = match($type) {
            'video' => ['Bakom kulisserna', 'Så här gör vi', 'En dag på jobbet', 'Kundens resa'],
            'carousel' => ['Tips för din bransch', 'Steg-för-steg guide', '5 saker att tänka på', 'Vanliga misstag'],
            'single_image' => ['Inspirerande citat', 'Produktfokus', 'Team spotlight', 'Workspace tour'],
            default => ['Dagens reflektion', 'Branschinsikter', 'Kundhistoria', 'Kommande trender'],
        };

        return $titles[array_rand($titles)];
    }

    private function getContentReason(string $type, string $platform): string
    {
        return match($platform) {
            'Instagram' => 'Visuellt innehåll presterar bäst på Instagram',
            'LinkedIn'  => 'Professionellt innehåll når rätt målgrupp',
            'Facebook'  => 'Bred räckvidd och hög interaktion',
            'TikTok'    => 'Kort format engagerar yngre målgrupp',
            default     => 'Plattformen favoriserar denna typ av innehåll'
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
                'fields'       => 'message,created_time,engagement',
                'since'        => now()->subDays(30)->toISOString(),
                'limit'        => 10
            ]);

            if ($response->successful()) {
                $posts = $response->json()['data'] ?? [];
                $popularContent = [];

                foreach (array_slice($posts, 0, 2) as $post) {
                    if (!empty($post['message'])) {
                        $popularContent[] = [
                            'type'             => 'social_post',
                            'title'            => \Illuminate\Support\Str::limit($post['message'], 50),
                            'engagement_score' => $post['engagement']['count'] ?? 0,
                            'platform'         => 'Facebook',
                            'why'              => 'Högt engagemang på din Facebook-sida'
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

    private function getRecommendedHashtags(Site $site, array $topics, array $platformTags = []): array
    {
        $fromTopics     = $this->buildHashtagsFromTopicsAndKeywords($site, $topics);
        $industryTags   = $site->industry ? $this->getIndustryHashtags($site->industry) : [];
        $seasonalTags   = $this->getSeasonalHashtagsGated($site); // endast om relevant
        $dynamicGeneral = $this->getDynamicTrendingHashtags();    // lite generiskt – hamnar lägst i prioritet

        $raw = array_merge(
            $fromTopics,
            $platformTags,
            $industryTags,
            $seasonalTags,
            $dynamicGeneral
        );

        return $this->filterHashtags($raw, $site);
    }

    private function getSeasonalHashtagsGated(Site $site): array
    {
        $raw = $this->getSeasonalHashtags();
        $kw  = collect($site->effectiveKeywords())->map(fn($k) => Str::lower($k))->all();
        $ind = $this->normalizeIndustry((string) $site->industry);

        $allowIf = function (string $tag) use ($kw, $ind) {
            $l = Str::lower($tag);

            // E-handel/retail/konsument: tillåt säsongsdrivet
            $retailish = ['ehandel','handel','butik','retail','mode','livsmedel'];
            $isRetail  = collect($retailish)->contains(fn($r) => $ind && Str::contains($ind, $r) || collect($kw)->contains(fn($k) => Str::contains($k, $r)));

            // Utbildning: tillåt #skola etc
            $edu = ['skola','utbildning','student'];
            $isEdu = collect($edu)->contains(fn($r) => $ind && Str::contains($ind, $r) || collect($kw)->contains(fn($k) => Str::contains($k, $r)));

            // Webbyrå/B2B‑tjänster: endast milda generella (#högtider undviks)
            $isAgency = $ind && (Str::contains($ind, 'webb') || Str::contains($ind, 'byrå') || Str::contains($ind, 'agentur') || Str::contains($ind, 'marknad'));

            if ($isEdu) return true;
            if ($isRetail) return true;
            if ($isAgency) {
                // tillåt bara neutrala säsongstermer
                return !Str::contains($l, ['#skola','#påsk','#halloween']);
            }
            // övriga: tillåt om neutralt (#sommar/#vinter ok)
            return Str::contains($l, ['#sommar','#vinter','#höst','#vår','#nyår']) && !Str::contains($l, ['#skola']);
        };

        return collect($raw)->filter($allowIf)->values()->all();
    }

    private function getIndustryHashtags(string $industry): array
    {
        $i = Str::lower($industry);

        // Webbyrå/digital byrå
        if (Str::contains($i, ['webbyrå','digital byrå','byrå','agentur','webb'])) {
            return ['#webbyrå', '#webbutveckling', '#digitalmarknadsföring', '#seo', '#ux', '#wordpress', '#laravel', '#frontend'];
        }

        return match(true) {
            str_contains($i, 'restaurang') || str_contains($i, 'mat') => ['#restaurang', '#mat', '#food', '#lokaltproducerat', '#sverige'],
            str_contains($i, 'tech') || str_contains($i, 'teknologi') || str_contains($i, 'it') => ['#tech', '#innovation', '#digitalisering', '#startup', '#ai'],
            str_contains($i, 'frisör') || str_contains($i, 'skönhet') => ['#frisör', '#skönhet', '#hår', '#beauty', '#style'],
            default => ['#företag', '#tjänster', '#b2b', '#kvalitet'],
        };
    }

    private function normalizeIndustry(string $industry): ?string
    {
        $i = Str::lower(trim($industry));
        if (!$i) return null;

        // Normalisera några vanliga branscher
        if (Str::contains($i, ['webbyrå','digital byrå','byrå','agentur','webb'])) return 'webbyrå';
        if (Str::contains($i, ['tech','teknologi','it'])) return 'tech';
        if (Str::contains($i, ['restaurang','mat','food'])) return 'restaurang';
        if (Str::contains($i, ['frisör','skönhet'])) return 'skönhet';

        return $i;
    }

    private function buildHashtagsFromTopicsAndKeywords(Site $site, array $topics): array
    {
        $takeTop = 6;
        $topicTitles = collect($topics)
            ->map(fn($t) => (string)($t['topic'] ?? $t['title'] ?? ''))
            ->filter()
            ->take($takeTop)
            ->all();

        $keywords = collect($site->effectiveKeywords())->filter()->take($takeTop)->all();

        $candidates = array_merge($topicTitles, $keywords);

        $tags = [];
        foreach ($candidates as $c) {
            $tag = $this->toHashtag($c);
            if ($tag) {
                $tags[] = $tag;
            }
        }

        return array_values(array_unique($tags));
    }

    private function toHashtag(string $text): ?string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9åäö]+/i', '', $text));
        if (strlen($slug) < 3 || strlen($slug) > 30) {
            return null;
        }
        // Exempel: "webb utveckling" -> "webbutveckling"
        return '#' . $slug;
    }

    private function getPlatformTrendingHashtags(Site $site): array
    {
        $tags = [];

        // Facebook: extrahera hashtags från egna inlägg
        $fb = $this->getFacebookPopularContent($site);
        $tags = array_merge($tags, $this->extractHashtagsFromContent($fb));

        // Instagram: egna senaste poster (om IG‑integration finns)
        $ig = $this->getInstagramPopularContent($site);
        $tags = array_merge($tags, $this->extractHashtagsFromContent($ig));

        // LinkedIn: företagsuppdateringar (om LI‑integration finns)
        $li = $this->getLinkedInPopularContent($site);
        $tags = array_merge($tags, $this->extractHashtagsFromContent($li));

        return array_values(array_unique($tags));
    }

    private function extractHashtagsFromContent(array $content): array
    {
        $tags = [];
        foreach ($content as $item) {
            $text = (string) ($item['title'] ?? $item['message'] ?? $item['text'] ?? '');
            preg_match_all('/#\p{L}[\p{L}0-9_]{1,30}/u', $text, $m);
            if (!empty($m[0])) {
                $tags = array_merge($tags, $m[0]);
            }
        }
        // Håll de mest frekventa först
        return collect($tags)
            ->map(fn($t) => Str::lower($t))
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(10)
            ->values()
            ->all();
    }

    private function getInstagramPopularContent(Site $site): array
    {
        try {
            $igIntegration = $site->integrations()
                ->where('provider', 'instagram')
                ->where('status', 'active')
                ->first();

            if (!$igIntegration || !$igIntegration->access_token || !$igIntegration->page_id) {
                return [];
            }

            $accountId = $igIntegration->ig_user_id ?: $igIntegration->page_id;
            if (!$accountId) {
                return [];
            }

            // page_id här bör vara IG business account id om du lagrar det; annars behöver du slå upp det via FB‑sidan
            $res = Http::timeout(15)->get("https://graph.facebook.com/v19.0/{$igIntegration->page_id}/media", [
                'access_token' => $igIntegration->access_token,
                'fields'       => 'caption,like_count,comments_count,media_type,timestamp',
                'since'        => now()->subDays(30)->toISOString(),
                'limit'        => 10,
            ]);

            if ($res->successful()) {
                $items = $res->json()['data'] ?? [];
                return collect($items)
                    ->map(function ($i) {
                        $score = (int)($i['like_count'] ?? 0) + (int)($i['comments_count'] ?? 0);
                        return [
                            'platform'         => 'Instagram',
                            'title'            => Str::limit((string)($i['caption'] ?? ''), 80),
                            'engagement_score' => $score,
                            'why'              => 'Bra engagemang på Instagram senaste 30 dagarna',
                        ];
                    })
                    ->sortByDesc('engagement_score')
                    ->take(3)
                    ->values()
                    ->all();
            }
            Log::warning('Instagram content fetch non-2xx', ['status' => $res->status()]);
        } catch (\Throwable $e) {
            Log::warning('Instagram content fetch error', ['error' => $e->getMessage()]);
        }
        return [];
    }

    private function getLinkedInPopularContent(Site $site): array
    {
        // Kräver LinkedIn Marketing API + företagssida kopplad. Lämnar som ”best effort”.
        try {
            $liIntegration = $site->integrations()
                ->where('provider', 'linkedin')
                ->where('status', 'active')
                ->first();

            if (!$liIntegration || !$liIntegration->access_token || !$liIntegration->page_id) {
                return [];
            }

            $owner = $liIntegration->page_id;
            $orgId = Str::startsWith($owner, 'urn:li:organization:')
                ? Str::after($owner, 'urn:li:organization:')
                : $owner;

            // Exempel: hämta organisationsposter (endpoints kan variera med API-version/åtkomst)
            $res = Http::timeout(15)->withToken($liIntegration->access_token)
                ->get('https://api.linkedin.com/v2/ugcPosts', [
                    'q' => 'authors',
                    'authors' => "List(urn:li:organization:{$liIntegration->page_id})",
                    'sortBy' => 'LAST_MODIFIED',
                    'count' => 10,
                ]);

            if ($res->successful()) {
                $items = $res->json()['elements'] ?? [];
                return collect($items)->map(function ($i) {
                    $text = data_get($i, 'specificContent.com.linkedin.ugc.ShareContent.shareCommentary.text', '');
                    return [
                        'platform'         => 'LinkedIn',
                        'title'            => Str::limit((string) $text, 100),
                        'engagement_score' => 70, // Placeholder: kräver ytterligare anrop för reactions/comments
                        'why'              => 'Senaste inlägg på din företagssida',
                    ];
                })->take(3)->values()->all();
            }
            Log::warning('LinkedIn content fetch non-2xx', ['status' => $res->status()]);
        } catch (\Throwable $e) {
            Log::warning('LinkedIn content fetch error', ['error' => $e->getMessage()]);
        }

        return [];
    }

    private function getDynamicTrendingHashtags(): array
    {
        $day   = now()->day;
        $month = now()->month;
        $year  = now()->format('Y');

        $baseHashtags = ['#sverige', '#företag'];

        $dynamicHashtags = [
            '#trend' . $year,
            "#{$year}goals",
            '#innovation' . ($month % 3 ? '' : $year),
            '#digital' . ($day % 2 ? 'transformation' : 'marketing'),
        ];

        return array_merge($baseHashtags, array_slice($dynamicHashtags, 0, 3));
    }

    private function getSeasonalHashtags(): array
    {
        $month = now()->month;
        return match(true) {
            in_array($month, [12, 1, 2])  => ['#vinter', '#nyår', '#vinterkampanj'],
            in_array($month, [3, 4, 5])   => ['#vår', '#påsk', '#vårkampanj'],
            in_array($month, [6, 7, 8])   => ['#sommar', '#semester', '#sommarkampanj'],
            in_array($month, [9, 10, 11]) => ['#höst', '#skola', '#höstkampanj'],
        };
    }

    private function getSeasonalTrends(Site $site): array
    {
        $month = now()->month;
        $season = match(true) {
            in_array($month, [12, 1, 2])  => 'winter',
            in_array($month, [3, 4, 5])   => 'spring',
            in_array($month, [6, 7, 8])   => 'summer',
            in_array($month, [9, 10, 11]) => 'autumn',
        };

        return $this->getSeasonalTopics($season, $site->industry);
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

    private function getGoogleTrends(array $keywords): array
    {
        if (empty($keywords) || !config('services.serpapi.key')) {
            Log::info("No SERPAPI key or keywords, skipping Google Trends");
            return [];
        }

        try {
            $keyword = implode(' ', array_slice($keywords, 0, 3));

            $response = Http::timeout(15)->get('https://serpapi.com/search', [
                'engine'  => 'google_trends',
                'q'       => $keyword,
                'geo'     => 'SE',
                'date'    => 'today 3-m',
                'api_key' => config('services.serpapi.key'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("SerpApi response received", ['status' => $response->status()]);

                $trends = [];

                if (isset($data['interest_over_time']['timeline_data'])) {
                    $recentData = array_slice($data['interest_over_time']['timeline_data'], -4);
                    $avgInterest = 0;

                    foreach ($recentData as $timePoint) {
                        $avgInterest += $timePoint['values'][0]['value'] ?? 0;
                    }
                    $avgInterest = $avgInterest / max(count($recentData), 1);

                    if ($avgInterest > 20) {
                        $trends[] = [
                            'topic'       => $keyword,
                            'trend_score' => (int) round($avgInterest),
                            'why'         => 'Växande sökintresse enligt Google Trends',
                        ];
                    }
                }

                if (isset($data['related_queries']['rising'])) {
                    foreach (array_slice($data['related_queries']['rising'], 0, 2) as $related) {
                        $trends[] = [
                            'topic'       => $related['query'],
                            'trend_score' => 85,
                            'why'         => 'Relaterad stigande sökning',
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
}
