<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShelf <?= isset($pageTitle) ? '– ' . htmlspecialchars($pageTitle) : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <span class="brand-icon">📚</span> BookShelf
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>"
                       href="index.php">Knihy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'book_add.php' ? 'active' : '' ?>"
                       href="book_add.php">+ Pridať knihu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(basename($_SERVER['PHP_SELF']), 'author') !== false ? 'active' : '' ?>"
                       href="authors.php">Autori</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">

<?php if (isset($_GET['msg'])): ?>
    <?php
        $msgMap = [
            'book_added'    => ['success', '✓ Kniha bola úspešne pridaná.'],
            'book_updated'  => ['success', '✓ Kniha bola úspešne upravená.'],
            'book_deleted'  => ['warning', '🗑 Kniha bola vymazaná.'],
            'author_added'  => ['success', '✓ Autor bol úspešne pridaný.'],
            'author_updated'=> ['success', '✓ Autor bol úspešne upravený.'],
            'author_deleted'=> ['warning', '🗑 Autor bol vymazaný.'],
            'error'         => ['danger',  '✗ Nastala chyba. Skúste znova.'],
        ];
        $key = htmlspecialchars($_GET['msg']);
        [$type, $text] = $msgMap[$key] ?? ['info', $key];
    ?>
    <div class="alert alert-<?= $type ?> alert-dismissible fade show mb-4" role="alert">
        <?= $text ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
