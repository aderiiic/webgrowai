<?php
/** @var string $title */
/** @var string $intro */
/** @var array<array{title:string, html:string}> $items */
/** @var string|null $cta_label */
/** @var string|null $cta_url */
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
    </style>
</head>
<body>
<div class="container">
    <h1><?= e($title) ?></h1>
    <p><?= e($intro) ?></p>

    <?php foreach($items as $it): ?>
    <div class="card">
        <h2><?= e($it['title']) ?></h2>
            <?= $it['html'] /* redan HTML-säkrat i jobben */ ?>
    </div>
    <?php endforeach; ?>

    <?php if (!empty($cta_label) && !empty($cta_url)): ?>
    <p style="margin-top:24px;">
        <a href="<?= e($cta_url) ?>" class="btn"><?= e($cta_label) ?></a>
    </p>
    <?php endif; ?>

    <p class="muted">
        Du får detta nyhetsbrev eftersom du prenumererar. Hantering av avprenumeration sköts av Mailchimp.
    </p>
</div>
</body>
</html>
