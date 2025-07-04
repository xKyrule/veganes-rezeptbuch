<?php
require_once 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['zutat_name']);
    $kcal = isset($_POST['kcal']) ? floatval($_POST['kcal']) : null;
    $fett = isset($_POST['fett']) ? floatval($_POST['fett']) : null;
    $eiweiss = isset($_POST['eiweiss']) ? floatval($_POST['eiweiss']) : null;
    $kohlenhydrate = isset($_POST['kohlenhydrate']) ? floatval($_POST['kohlenhydrate']) : null;

    if (empty($name)) {
        echo 'Bitte gib einen Namen für die Zutat ein.';
        exit;
    }

    // Prüfen, ob Zutat schon existiert
    $stmt = $pdo->prepare("SELECT id FROM zutaten WHERE name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()) {
        echo 'Diese Zutat existiert bereits.';
        exit;
    }

    // Zutat mit Nährwerten einfügen
    $stmt = $pdo->prepare("INSERT INTO zutaten (name, kcal, fett, eiweiss, kohlenhydrate) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $kcal, $fett, $eiweiss, $kohlenhydrate])) {
        echo 'Zutat erfolgreich hinzugefügt.';
    } else {
        echo 'Fehler beim Hinzufügen der Zutat.';
    }
}
?>
