<?php

namespace App\Services;

use GuzzleHttp\Client;

class ImageGenerator
{
    public function __construct(
        private ?string $apiKey = null,
        private string $model = 'gpt-image-1'
    ) {
        $this->apiKey = $this->apiKey ?: config('services.openai.key');
        if (!$this->apiKey) {
            throw new \RuntimeException('OpenAI API-nyckel saknas.');
        }
    }

    // Genererar bildbytes. Hanterar både b64- och url-svar.
    public function generate(string $prompt, string $size = '1024x1024'): string
    {
        $http = new Client(['base_uri' => 'https://api.openai.com/v1/', 'timeout' => 60]);

        $res = $http->post('images/generations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'  => $this->model,
                'prompt' => $prompt,
                'size'   => $size,
                // response_format utelämnas för kompatibilitet
            ],
        ]);

        $data = json_decode((string) $res->getBody(), true);
        // Nya svar kan innehålla url eller b64_json
        $first = $data['data'][0] ?? null;
        if (!$first) {
            throw new \RuntimeException('Tomt bildsvar från OpenAI.');
        }

        if (!empty($first['b64_json'])) {
            return base64_decode($first['b64_json']);
        }

        if (!empty($first['url'])) {
            // Hämta bytes från den signerade URL:en
            $img = (new Client(['timeout' => 60]))->get($first['url']);
            return (string) $img->getBody();
        }

        throw new \RuntimeException('Okänt bildsvarsformat från OpenAI.');
    }

    // Instagram behöver JPEG – konvertera från PNG/bytes till JPEG
    public function generateJpeg(string $prompt, string $size = '1024x1024', int $quality = 90): string
    {
        $png = $this->generate($prompt, $size);
        return $this->pngToJpeg($png, $quality);
    }

    private function pngToJpeg(string $pngBytes, int $quality = 90): string
    {
        $im = imagecreatefromstring($pngBytes);
        if (!$im) {
            throw new \RuntimeException('Kunde inte läsa bildbytes för JPEG-konvertering.');
        }
        $w = imagesx($im);
        $h = imagesy($im);
        $bg = imagecreatetruecolor($w, $h);
        $white = imagecolorallocate($bg, 255, 255, 255);
        imagefilledrectangle($bg, 0, 0, $w, $h, $white);
        imagecopy($bg, $im, 0, 0, 0, 0, $w, $h);

        ob_start();
        imagejpeg($bg, null, $quality);
        $jpeg = ob_get_clean();

        imagedestroy($im);
        imagedestroy($bg);

        if ($jpeg === false) {
            throw new \RuntimeException('Kunde inte konvertera till JPEG.');
        }
        return $jpeg;
    }
}
