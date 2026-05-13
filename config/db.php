<?php
// config/db.php – pripojenie k databáze cez mysqli
// Upravte prihlasovacie údaje podľa svojho prostredia.

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // zmeňte podľa svojho MySQL používateľa
define('DB_PASS', 'root');           // zmeňte podľa svojho hesla
define('DB_NAME', 'bookshelf');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;padding:2rem;color:#c0392b;">
        <h2>⚠️ Chyba pripojenia k databáze</h2>
        <p>' . htmlspecialchars($conn->connect_error) . '</p>
        <p>Skontrolujte údaje v <code>config/db.php</code> a uistite sa, že ste spustili <code>setup.sql</code>.</p>
    </div>');
}

$conn->set_charset('utf8mb4');
