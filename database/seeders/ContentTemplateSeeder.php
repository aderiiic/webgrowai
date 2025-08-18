<?php

namespace Database\Seeders;

use App\Models\ContentTemplate;
use Illuminate\Database\Seeder;

class ContentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Blogg
        ContentTemplate::updateOrCreate(
            ['slug' => 'blog'],
            [
                'name'        => 'Blogginlägg',
                'provider'    => 'auto',
                'max_tokens'  => 2200,
                'temperature' => 0.7,
                'visibility'  => 'system',
            ]
        );

        // Kampanjidéer
        ContentTemplate::updateOrCreate(
            ['slug' => 'campaign'],
            [
                'name'        => 'Kampanjidéer',
                'provider'    => 'openai',
                'max_tokens'  => 1000,
                'temperature' => 0.7,
                'visibility'  => 'system',
            ]
        );

        // Generisk social (kan tas bort om du vill)
        ContentTemplate::updateOrCreate(
            ['slug' => 'social'],
            [
                'name'        => 'Sociala inlägg (generisk)',
                'provider'    => 'openai',
                'max_tokens'  => 800,
                'temperature' => 0.8,
                'visibility'  => 'system',
            ]
        );

        // Social: Facebook
        ContentTemplate::updateOrCreate(
            ['slug' => 'social-facebook'],
            [
                'name'        => 'Socialt inlägg – Facebook',
                'provider'    => 'openai',
                'max_tokens'  => 700,
                'temperature' => 0.8,
                'visibility'  => 'system',
            ]
        );

        // Social: Instagram
        ContentTemplate::updateOrCreate(
            ['slug' => 'social-instagram'],
            [
                'name'        => 'Socialt inlägg – Instagram',
                'provider'    => 'openai',
                'max_tokens'  => 700,
                'temperature' => 0.8,
                'visibility'  => 'system',
            ]
        );

        // Social: LinkedIn
        ContentTemplate::updateOrCreate(
            ['slug' => 'social-linkedin'],
            [
                'name'        => 'Socialt inlägg – LinkedIn',
                'provider'    => 'openai',
                'max_tokens'  => 900,
                'temperature' => 0.7,
                'visibility'  => 'system',
            ]
        );
    }
}
