<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'pagespeed' => [
        'key'      => env('PAGESPEED_API_KEY'),
        'strategy' => env('PAGESPEED_STRATEGY', 'desktop')
    ],

    'openai' => [
        'key'   => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    ],
    'anthropic' => [
        'key'     => env('ANTHROPIC_API_KEY'),
        'model'   => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-latest'),
        'version' => '2023-06-01',
    ],

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT_URI'),
        // önskade scopes för sidor och IG
        'scopes'        => [
            'public_profile',
            'pages_show_list',
            'pages_read_engagement',
            'pages_manage_posts',
            'pages_manage_metadata',
            'pages_read_user_content',
            'read_insights',
            'email',
            'business_management',
            'instagram_basic',
        ],
    ],

    'instagram' => [
        'client_id'     => env('INSTAGRAM_APP_ID'),
        'client_secret' => env('INSTAGRAM_APP_SECRET'),
        'redirect'      => env('INSTAGRAM_REDIRECT_URI'),
        // IG Basic Display/Graph – vi använder Graph via FB-login, scopes hanteras via facebook.scopes
    ],

    'mailchimp' => [
        'key'        => env('MAILCHIMP_API_KEY'),
        'default_dc' => env('MAILCHIMP_DC', null), // valfritt: datacenter (t.ex. us21); lämna tomt så parse:ar vi från key
    ],

    'serpapi' => [
        'key' => env('SERPAPI_API_KEY'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
        'scopes' => [
            'openid',
            'w_member_social',
            'profile',
            'email'
        ],
    ],

    'shopify' => [
        'client_id'     => env('SHOPIFY_CLIENT_ID', 'your_app_api_key'),
        'client_secret' => env('SHOPIFY_CLIENT_SECRET', 'your_app_api_secret'),
        // kommaseparerad lista med scopes, håll den tight
        'scopes'        => env('SHOPIFY_SCOPES', 'read_content,write_content'),
        // valfritt: sätt explicit callback, annars bygger vi dynamiskt
        'redirect'      => env('SHOPIFY_REDIRECT', null),
        // API-version kan användas av adapter om du vill
        'api_version'   => env('SHOPIFY_API_VERSION', '2024-10'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    ],
];
