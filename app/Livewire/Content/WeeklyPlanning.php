<?php

namespace App\Livewire\Content;

use App\Models\WeeklyPlan;
use App\Support\CurrentCustomer;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WeeklyPlanning extends Component
{
    public array $latest = [
        'monday' => [],
        'friday' => [],
    ];

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $siteId = $current->getSiteId();

        $base = WeeklyPlan::query()
            ->where('customer_id', $customer->id)
            ->when($siteId, fn($q) => $q->where('site_id', $siteId));

        $plans = $base->latest()->get();

        $this->latest['monday'] = $this->groupByType($this->pickLatestByTag($plans, 'monday'));
        $this->latest['friday'] = $this->groupByType($this->pickLatestByTag($plans, 'friday'));
    }

    private function pickLatestByTag(Collection $plans, string $tag): Collection
    {
        $latestDate = optional(
            $plans->where('run_tag', $tag)->sortByDesc(fn($p) => $p->run_date)->first()
        )->run_date;

        if (!$latestDate) {
            return collect();
        }

        return $plans->where('run_tag', $tag)
            ->filter(fn($p) => $p->run_date->equalTo($latestDate));
    }

    private function groupByType(Collection $items): array
    {
        if ($items->isEmpty()) {
            return [];
        }

        $byType = $items->groupBy('type');
        $topicsMd = (string) optional($byType->get('topics')?->first())->content_md;

        return [
            'date'      => optional($items->first())->run_date?->format('Y-m-d'),
            'campaigns' => (string) optional($byType->get('campaigns')?->first())->content_md,
            'topics'    => [
                'md'     => $topicsMd,
                'list'   => $this->extractTopics($topicsMd),
            ],
            'next_week' => (string) optional($byType->get('next_week')?->first())->content_md,
        ];
    }

    private function extractTopics(?string $md): array
    {
        if (!$md) return [];

        $lines = preg_split('/\R/', $md);
        $topics = [];
        $skipNextLines = false;

        foreach ($lines as $i => $line) {
            $line = trim($line);

            // Skippa tomma rader
            if ($line === '') {
                $skipNextLines = false; // Reset skip när vi träffar tom rad
                continue;
            }

            // Hitta huvudrubriker (H1-H3 markdown eller starkt markerade rubriker)
            if (preg_match('/^#{1,3}\s+(.+)$/u', $line, $m)) {
                // Markdown rubrik (# ## ###)
                $title = trim(strip_tags($m[1]));
                $title = rtrim($title, " \t\n\r\0\x0B:—-*");
                if ($title !== '') {
                    $topics[] = mb_strimwidth($title, 0, 120, '…', 'UTF-8');
                    $skipNextLines = true; // Skippa eventuell förklarande text direkt efter
                }
                continue;
            }

            // Plocka upp punktlistor som ser ut som huvudämnen (första nivån)
            if (preg_match('/^\s*(?:[-*]|\d+\.)\s+\*\*(.+?)\*\*\s*$/u', $line, $m)) {
                // Bold punktlista (- **Rubrik**)
                $title = trim(strip_tags($m[1]));
                $title = rtrim($title, " \t\n\r\0\x0B:—-");
                if ($title !== '') {
                    $topics[] = mb_strimwidth($title, 0, 120, '…', 'UTF-8');
                    $skipNextLines = true;
                }
                continue;
            }

            // Plocka upp enkla punktlistor som ser ut som huvudämnen (men bara om de är korta och inte förklarande)
            if (!$skipNextLines && preg_match('/^\s*(?:[-*]|\d+\.)\s+([^a-z].{10,80}[^.])\s*$/u', $line, $m)) {
                // Punktlista som börjar med versal, är lagom lång (10-80 tecken) och slutar inte med punkt
                $title = trim(strip_tags($m[1]));
                $title = rtrim($title, " \t\n\r\0\x0B:—-");

                // Extra filter: undvik rader som innehåller vanliga förklarande ord
                $explanatoryWords = ['format:', 'exempel:', 'tips:', 'hur', 'varför', 'genom att', 'för att'];
                $isExplanatory = false;
                foreach ($explanatoryWords as $word) {
                    if (stripos($title, $word) !== false) {
                        $isExplanatory = true;
                        break;
                    }
                }

                if (!$isExplanatory && $title !== '') {
                    $topics[] = mb_strimwidth($title, 0, 120, '…', 'UTF-8');
                }
                continue;
            }

            // Om vi träffar normal text efter en rubrik, fortsätt att skippa
            if ($skipNextLines && !preg_match('/^\s*(?:[-*]|\d+\.)\s+/', $line)) {
                continue;
            }

            $skipNextLines = false;
        }

        // Deduplicera och normalisera
        $topics = collect($topics)
            ->map(fn($t) => preg_replace('/\s+/', ' ', $t))
            ->filter(fn($t) => strlen($t) > 5) // Minst 5 tecken för att vara ett rimligt ämne
            ->unique()
            ->values()
            ->all();

        // Begränsa antal ämnen
        return array_slice($topics, 0, 8);
    }

    public function render()
    {
        return view('livewire.content.weekly-planning', [
            'latest' => $this->latest,
        ]);
    }
}
