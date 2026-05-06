<?php
// index.php – Zoznam kníh (READ + vyhľadávanie + filter)
require_once 'config/db.php'; // pripojenie k DB
$pageTitle = 'Knižnica';
require_once 'includes/header.php';

// --- Vyhľadávanie a filtrovanie ---
$search = trim($_GET['search'] ?? '');
$filterGenre = trim($_GET['genre'] ?? '');
$filterAuthor = intval($_GET['author'] ?? 0);//

// Zostavenie WHERE podmienky
$where = '1=1';
$params = [];
$types  = '';

if ($search !== '') {
    $where .= ' AND (b.title LIKE ? OR a.name LIKE ?)';
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $types   .= 'ss';
}
if ($filterGenre !== '') {
    $where .= ' AND b.genre = ?';
    $params[] = $filterGenre;
    $types   .= 's';
}
if ($filterAuthor > 0) {
    $where .= ' AND b.author_id = ?';
    $params[] = $filterAuthor;
    $types   .= 'i';
}

$sql = "SELECT b.*, a.name AS author_name
        FROM books b
        JOIN authors a ON b.author_id = a.id
        WHERE {$where}
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$books = $stmt->get_result();

// Štatistiky
$statsQ = $conn->query("SELECT COUNT(*) AS total,
                               ROUND(AVG(rating),1) AS avg_rating,
                               COUNT(DISTINCT author_id) AS authors_cnt
                        FROM books");
$stats = $statsQ->fetch_assoc();

// Žánre pre filter
$genresQ = $conn->query("SELECT DISTINCT genre FROM books WHERE genre IS NOT NULL ORDER BY genre");
$genres = $genresQ->fetch_all(MYSQLI_ASSOC);

// Autori pre filter
$authorsQ = $conn->query("SELECT id, name FROM authors ORDER BY name");
$authorsList = $authorsQ->fetch_all(MYSQLI_ASSOC);
?>

<h1 class="page-title">📚 Moja knižnica</h1>

<!-- Štatistiky -->
<div class="stats-strip">
    <div class="stat-box">
        <div class="stat-num"><?= $stats['total'] ?></div>
        <div class="stat-lbl">kníh celkom</div>
    </div>
    <div class="stat-box">
        <div class="stat-num"><?= $stats['avg_rating'] ?? '–' ?></div>
        <div class="stat-lbl">priem. hodnotenie</div>
    </div>
    <div class="stat-box">
        <div class="stat-num"><?= $stats['authors_cnt'] ?></div>
        <div class="stat-lbl">autorov</div>
    </div>
</div>

<!-- Vyhľadávanie a filter -->
<div class="search-wrap">
    <form method="GET" action="index.php" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label mb-1">🔍 Hľadať</label>
            <input type="text" name="search" class="form-control"
                   placeholder="Názov alebo autor…"
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1">Žáner</label>
            <select name="genre" class="form-select">
                <option value="">Všetky žánre</option>
                <?php foreach ($genres as $g): ?>
                    <option value="<?= htmlspecialchars($g['genre']) ?>"
                        <?= $filterGenre === $g['genre'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($g['genre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1">Autor</label>
            <select name="author" class="form-select">
                <option value="">Všetci autori</option>
                <?php foreach ($authorsList as $au): ?>
                    <option value="<?= $au['id'] ?>"
                        <?= $filterAuthor === $au['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($au['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-amber w-100">Hľadať</button>
            <a href="index.php" class="btn btn-outline-secondary">✕</a>
        </div>
    </form>
</div>

<!-- Tabuľka kníh -->
<?php if ($books->num_rows === 0): ?>
    <div class="alert alert-warning">Žiadne knihy nenájdené.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-books table-hover align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Názov</th>
            <th>Autor</th>
            <th>Žáner</th>
            <th>Rok</th>
            <th>Hodnotenie</th>
            <th class="text-center">Akcie</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1; while ($book = $books->fetch_assoc()): ?>
        <tr>
            <td class="text-muted-custom"><?= $i++ ?></td>
            <td>
                <a href="book_detail.php?id=<?= $book['id'] ?>"
                   class="fw-semibold text-decoration-none"
                   style="color:var(--navy)">
                   <?= htmlspecialchars($book['title']) ?>
                </a>
            </td>
            <td><?= htmlspecialchars($book['author_name']) ?></td>
            <td>
                <?php if ($book['genre']): ?>
                    <span class="badge-genre"><?= htmlspecialchars($book['genre']) ?></span>
                <?php else: ?>–<?php endif; ?>
            </td>
            <td><?= $book['year'] ?? '–' ?></td>
            <td>
                <?php if ($book['rating']): ?>
                    <span class="stars"><?= str_repeat('★', $book['rating']) ?><?= str_repeat('☆', 5 - $book['rating']) ?></span>
                <?php else: ?>–<?php endif; ?>
            </td>
            <td class="text-center">
                <a href="book_edit.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-navy me-1" title="Upraviť">✏️</a>
                <a href="book_delete.php?id=<?= $book['id'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   title="Vymazať"
                   onclick="return confirm('Naozaj vymazať knihu: <?= addslashes(htmlspecialchars($book['title'])) ?>?')">🗑</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
<?php endif; ?>

<div class="mt-3">
    <a href="book_add.php" class="btn btn-amber">+ Pridať novú knihu</a>
</div>

<?php require_once 'includes/footer.php'; ?>
