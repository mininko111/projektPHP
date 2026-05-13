<?php
// authors.php – Zoznam autorov (READ)
require_once 'config/db.php';
$pageTitle = 'Autori';
require_once 'includes/header.php';

$authorsQ = $conn->query(
    "SELECT a.*, COUNT(b.id) AS book_count,
            ROUND(AVG(b.rating), 1) AS avg_rating
     FROM authors a
     LEFT JOIN books b ON b.author_id = a.id
     GROUP BY a.id
     ORDER BY a.name"
);
$authors = $authorsQ->fetch_all(MYSQLI_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title mb-0">✍️ Autori</h1>
    <a href="author_add.php" class="btn btn-amber">+ Pridať autora</a>
</div>

<?php if (empty($authors)): ?>
    <div class="alert alert-warning">Zatiaľ nie sú pridaní žiadni autori.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-books table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Meno autora</th>
            <th>Krajina</th>
            <th>Rok nar.</th>
            <th>Kníh</th>
            <th>Priem. hodnotenie</th>
            <th class="text-center">Akcie</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1; foreach ($authors as $au): ?>
        <tr>
            <td class="text-muted-custom"><?= $i++ ?></td>
            <td>
                <a href="author_detail.php?id=<?= $au['id'] ?>"
                   class="fw-semibold text-decoration-none" style="color:var(--navy)">
                    <?= htmlspecialchars($au['name']) ?>
                </a>
            </td>
            <td><?= htmlspecialchars($au['country'] ?? '–') ?></td>
            <td><?= $au['born_year'] ?? '–' ?></td>
            <td>
                <span class="badge-genre"><?= $au['book_count'] ?></span>
            </td>
            <td>
                <?php if ($au['avg_rating']): ?>
                    <span class="stars">★</span> <?= $au['avg_rating'] ?>
                <?php else: ?>–<?php endif; ?>
            </td>
            <td class="text-center">
                <a href="author_edit.php?id=<?= $au['id'] ?>" class="btn btn-sm btn-outline-navy me-1">✏️</a>
                <a href="author_delete.php?id=<?= $au['id'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Vymazať autora <?= addslashes(htmlspecialchars($au['name'])) ?>? Vymažú sa aj všetky jeho knihy!')">🗑</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
