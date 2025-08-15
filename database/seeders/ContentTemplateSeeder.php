<?php

namespace Database\Seeders;

use App\Models\ContentTemplate;
use Illuminate\Database\Seeder;

class ContentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        ContentTemplate::updateOrCreate(
            ['slug' => 'blog'],
            ['name' => 'Blogginlägg', 'provider' => 'auto', 'max_tokens' => 2200, 'temperature' => 0.7, 'visibility' => 'system']
        );
        ContentTemplate::updateOrCreate(
            ['slug' => 'campaign'],
            ['name' => 'Kampanjidéer', 'provider' => 'openai', 'max_tokens' => 1000, 'temperature' => 0.7, 'visibility' => 'system']
        );
        ContentTemplate::updateOrCreate(
            ['slug' => 'social'],
            ['name' => 'Sociala inlägg', 'provider' => 'openai', 'max_tokens' => 800, 'temperature' => 0.8, 'visibility' => 'system']
        );
    }
}
