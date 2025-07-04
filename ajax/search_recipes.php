<?php
require_once '../inc/db.php';

$q = $_GET['q'] ?? '';

if (strlen($q) < 2) {
    echo '<div class="text-muted">Bitte mindestens 2 Zeichen eingeben.</div>';
    exit;
}

$stmt = $pdo->prepare("SELECT id, titel FROM rezepte WHERE titel LIKE ? ORDER BY titel LIMIT 10");
$stmt->execute(['%' . $q . '%']);
$rezepte = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rezepte) === 0) {
    echo '<div class="text-muted">Keine Rezepte gefunden.</div>';
} else {
    echo '<ul class="list-group">';
    foreach ($rezepte as $r) {
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
        echo htmlspecialchars($r['titel']);
        echo '<div>';
        echo '<button class="btn btn-sm btn-outline-primary btn-view" data-id="' . $r['id'] . '">Ansehen</button> ';
        echo '<button class="btn btn-sm btn-outline-secondary btn-edit" data-id="' . $r['id'] . '">Bearbeiten</button>';
        echo '</div></li>';
    }
    echo '</ul>';
}
