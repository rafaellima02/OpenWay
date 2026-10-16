<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($tituloPagina ?? APP_NOME) ?> · <?= e(APP_NOME) ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
<?php foreach (($css ?? []) as $arquivoCss): ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= e($arquivoCss) ?>">
<?php endforeach; ?>
</head>
<body>
<main class="pagina">
