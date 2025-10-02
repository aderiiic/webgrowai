<?php

namespace App\Services;

use App\Models\Integration;
use App\Models\WpIntegration;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Crypt;

class WordPressClient
{
    private Client $client;
    private string $baseUrl;
    private string $username;

    public function __construct(string $baseUrl, string $username, string $appPassword)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->username = $username;
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth'     => [$username, $appPassword],
            'timeout'  => 20,
        ]);
    }

    /**
     * Bakåtkompatibel fabrik som bygger klient från WpIntegration.
     */
    public static function for(WpIntegration $integration): self
    {
        return new self(
            $integration->wp_url,
            $integration->wp_username,
            Crypt::decryptString($integration->wp_app_password)
        );
    }

    /**
     * Ny fabrik: bygger klient från Integration (provider=wordpress) där credentials är ett JSON/array-fält.
     * Stödjer nycklarna: wp_url|url, wp_username|username, wp_app_password|app_password.
     * Kastar RuntimeException om något saknas.
     */
    public static function fromIntegration(Integration $integration): self
    {
        $creds = (array) ($integration->credentials ?? []);

        $baseUrl = rtrim((string)($creds['wp_url'] ?? $creds['url'] ?? ''), '/');
        $username = (string)($creds['wp_username'] ?? $creds['username'] ?? '');
        $rawPass  = (string)($creds['wp_app_password'] ?? $creds['app_password'] ?? '');

        if ($baseUrl === '' || $username === '' || $rawPass === '') {
            throw new \RuntimeException('Ogiltiga WordPress-uppgifter (url/användare/lösen saknas).');
        }

        try {
            $appPassword = Crypt::decryptString($rawPass);
        } catch (\Throwable) {
            $appPassword = $rawPass;
        }

        return new self($baseUrl, $username, $appPassword);
    }

    public function getMe(): array
    {
        $res = $this->client->get('/wp-json/wp/v2/users/me', ['query' => ['context' => 'edit']]);
        return json_decode((string) $res->getBody(), true);
    }

    public function getPosts(array $params = []): array
    {
        $query = array_merge(['per_page' => 10, 'status' => 'any', 'orderby' => 'date', 'order' => 'desc'], $params);
        $res = $this->client->get('/wp-json/wp/v2/posts', ['query' => $query]);
        return json_decode((string) $res->getBody(), true);
    }

    public function createPost(array $payload): array
    {
        try {
            $res = $this->client->post('/wp-json/wp/v2/posts', ['json' => $payload]);
            return json_decode((string) $res->getBody(), true);
        } catch (ClientException $e) {
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            throw new \RuntimeException('WP createPost misslyckades: '.$body, $e->getCode(), $e);
        }
    }

    public function updatePost(int $id, array $payload): array
    {
        try {
            $res = $this->client->post("/wp-json/wp/v2/posts/{$id}", ['json' => $payload]);
            return json_decode((string) $res->getBody(), true);
        } catch (ClientException $e) {
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            throw new \RuntimeException('WP updatePost misslyckades: '.$body, $e->getCode(), $e);
        }
    }

    public function getPost(int $id): array
    {
        $res = $this->client->get("/wp-json/wp/v2/posts/{$id}");
        return json_decode((string) $res->getBody(), true);
    }

    public function getPages(array $params = []): array
    {
        $query = array_merge(['per_page' => 10, 'status' => 'publish', 'orderby' => 'date', 'order' => 'desc'], $params);
        $res = $this->client->get('/wp-json/wp/v2/pages', ['query' => $query]);
        return json_decode((string) $res->getBody(), true);
    }

    public function getPage(int $id): array
    {
        $res = $this->client->get("/wp-json/wp/v2/pages/{$id}");
        return json_decode((string) $res->getBody(), true);
    }

    public function updatePage(int $id, array $payload): array
    {
        try {
            $res = $this->client->post("/wp-json/wp/v2/pages/{$id}", ['json' => $payload]);
            return json_decode((string) $res->getBody(), true);
        } catch (ClientException $e) {
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            throw new \RuntimeException('WP updatePage misslyckades: '.$body, $e->getCode(), $e);
        }
    }

    public function uploadMedia(string $bytes, string $filename, string $mime = 'image/png'): array
    {
        try {
            $res = $this->client->post('/wp-json/wp/v2/media', [
                'timeout'         => 120,
                'connect_timeout' => 10,
                'expect'          => false,
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $bytes,
                        'filename' => $filename,
                        'headers'  => ['Content-Type' => $mime],
                    ],
                ],
            ]);

            $response = json_decode((string) $res->getBody(), true);

            if (!$response || !isset($response['id'])) {
                throw new \RuntimeException('WordPress media upload returnerade ogiltig respons: ' . $res->getBody());
            }

            return $response;

        } catch (ClientException $e) {
            $body = (string) ($e->getResponse()?->getBody() ?? '');
            $statusCode = $e->getCode();

            $error = json_decode($body, true);
            $errorMessage = $error['message'] ?? $body;

            throw new \RuntimeException("WordPress media upload misslyckades (HTTP {$statusCode}): {$errorMessage}", $statusCode, $e);
        }
    }

    public function testConnection(): array
    {
        try {
            $res = $this->client->get('/wp-json/wp/v2/users/me', ['query' => ['context' => 'edit']]);
            if ($res->getStatusCode() >= 200 && $res->getStatusCode() < 300) {
                $user = json_decode((string)$res->getBody(), true);
                return ['ok' => true, 'status' => $res->getStatusCode(), 'user' => $user];
            }
            return $this->mapWpError($res->getStatusCode(), (string)$res->getBody());
        } catch (ClientException $e) {
            $status = $e->getResponse()?->getStatusCode() ?? 0;
            $body   = (string)($e->getResponse()?->getBody() ?? '');
            return $this->mapWpError($status, $body);
        } catch (\Throwable $e) {
            \Log::warning('[WP] Test connection exception', [
                'url' => $this->baseUrl,
                'user' => $this->username,
                'error' => $e->getMessage(),
            ]);
            return [
                'ok' => false,
                'code' => 'network_error',
                'status' => 0,
                'message' => 'Nätverksfel eller timeout vid anslutning till WordPress.',
                'hint' => 'Kontrollera att sajten svarar och att brandvägg/CDN inte blockerar REST API.',
            ];
        }
    }

    private function mapWpError(int $status, string $rawBody): array
    {
        $json = json_decode($rawBody, true);
        $wpCode = is_array($json) ? ($json['code'] ?? null) : null;

        $map = [
            'incorrect_password' => ['auth_invalid', 'Felaktigt applikationslösenord.'],
            'invalid_username'   => ['auth_invalid', 'Felaktigt användarnamn.'],
            'rest_not_logged_in' => ['auth_required', 'Inloggning krävs för denna åtgärd.'],
            'rest_cannot_view_user' => ['insufficient_permissions', 'Kontot saknar rättigheter.'],
            'rest_forbidden'     => ['forbidden', 'Åtkomst blockerad (forbidden) på WordPress.'],
        ];

        [$code, $msg] = $map[$wpCode] ?? match (true) {
            $status === 401 => ['auth_invalid', 'Autentisering misslyckades mot WordPress.'],
            $status === 403 => ['forbidden', 'Åtkomst nekad av WordPress.'],
            $status === 404 => ['not_found', 'REST-endpoint saknas (404).'],
            $status === 429 => ['rate_limited', 'WordPress rate limit – försök igen senare.'],
            $status >= 500  => ['wp_error', 'Fel på WordPress-sidan (serverfel).'],
            default         => ['unknown', 'Okänt fel vid anrop till WordPress.'],
        };

        $hint = match ($code) {
            'auth_invalid' => 'Kontrollera URL, användarnamn och applikationslösenord (utan mellanslag) och spara igen.',
            'auth_required' => 'Säkerställ att applikationslösenord används och kontot har rätt roll.',
            'insufficient_permissions' => 'Kontot behöver minst författar- eller redaktörsroll.',
            'forbidden' => 'Se över säkerhetsplugin/brandvägg (Wordfence/Cloudflare) som kan blockera REST API.',
            'not_found' => 'Kontrollera att WordPress REST API är aktiverat.',
            'rate_limited' => 'För många förfrågningar – vänta och försök igen.',
            'wp_error' => 'Kontrollera WordPress-felsloggar (teman/plugin kan orsaka fel).',
            default => 'Verifiera inställningarna och försök igen.',
        };

        \Log::info('[WP] API error', [
            'url' => $this->baseUrl,
            'user' => $this->username,
            'status' => $status,
            'wp_code' => $wpCode,
            'body_preview' => mb_substr($rawBody, 0, 400),
        ]);

        return [
            'ok' => false,
            'code' => $code,
            'status' => $status,
            'wp_code' => $wpCode,
            'message' => $msg,
            'hint' => $hint,
        ];
    }
}
