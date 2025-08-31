<?php
/** @var string $title */
/** @var string $intro */
/** @var array<array{title:string, html:string}> $items */
/** @var string|null $cta_label */
/** @var string|null $cta_url */
/** @var string|null $company_name */
/** @var string|null $unsubscribe_text */
/** @var string|null $unsubscribe_url */
/** @var bool|null $isTest */
/** @var array|null $customImages */

// Set default values för variabler som kanske inte finns
$company_name = $company_name ?? 'Vårt företag';
$unsubscribe_text = $unsubscribe_text ?? 'Du kan avregistrera dig från vårt nyhetsbrev när som helst.';
$unsubscribe_url = $unsubscribe_url ?? '*|UNSUB|*';
$isTest = $isTest ?? false;
$customImages = $customImages ?? [];
?>
    <!doctype html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title><?= e($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;line-height:1.5;color:#111827}
        .container{max-width:640px;margin:0 auto;padding:24px}
        h1{font-size:24px;margin:0 0 16px}
        h2{font-size:18px;margin:24px 0 8px}
        p{margin:0 0 12px}
        .card{border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin:16px 0;background:#ffffff}
        .btn{display:inline-block;background:#111827;color:#ffffff;padding:10px 16px;border-radius:6px;text-decoration:none}
        .muted{color:#6b7280;font-size:12px;margin-top:16px}
        .test-indicator{background:#fef3c7;border:1px solid #f59e0b;color:#92400e;padding:12px;margin-bottom:16px;text-align:center;font-weight:600}
        .header{text-align:center;padding:20px;background:linear-gradient(135deg,#ea580c,#dc2626);color:white;margin-bottom:20px}
        .image-container{text-align:center;margin:20px 0}
        .custom-image{max-width:100%;height:auto;border-radius:8px}
        .unsubscribe-footer{margin-top:40px;padding-top:20px;border-top:1px solid #e5e7eb;text-align:center}
        .unsubscribe-link{color:#6b7280;text-decoration:underline;font-size:12px}
        .product-image{max-width:100%;height:auto;border-radius:8px;margin-bottom:16px}
    </style>
</head>
<body>
<div class="container">
    @if($isTest)
        <div class="test-indicator">
            🧪 TESTMEJL - Detta är en förhandsvisning av ditt nyhetsbrev
        </div>
    @endif

    <div class="header">
        <h1><?= e($title) ?></h1>
        <p><?= e($company_name) ?> • <?= date('j F Y') ?></p>
    </div>

    <p><?= $intro ?></p>

    <?php foreach($items as $it): ?>
    <div class="card">
        <h2><?= e($it['title']) ?></h2>
            <?= $it['html'] /* redan HTML-säkrat i jobben */ ?>
    </div>
    <?php endforeach; ?>

    <?php if (!empty($customImages)): ?>
        <?php foreach($customImages as $image): ?>
            <div class="image-container">
                <img src="<?= e($image['url']) ?>" alt="<?= e($image['name']) ?>" class="custom-image">
            </div>
            <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($cta_label) && !empty($cta_url)): ?>
    <p style="margin-top:24px;text-align:center">
        <a href="<?= e($cta_url) ?>" class="btn"><?= e($cta_label) ?></a>
    </p>
    <?php endif; ?>

        <!-- Avprenumerationslänk -->
    <div class="unsubscribe-footer">
        <p class="muted"><?= e($unsubscribe_text) ?></p>
        <p><a href="<?= e($unsubscribe_url) ?>" class="unsubscribe-link">Avprenumerera från nyhetsbrevet</a></p>
    </div>
</div>
</body>
</html>
