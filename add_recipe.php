<?php
require_once 'inc/db.php';

// Formulardaten abholen
$titel = $_POST['titel'] ?? '';
$anleitung = $_POST['anleitung'] ?? '';
$kategorien = $_POST['kategorien_ids'] ?? [];
$zutaten_ids = $_POST['zutaten_ids'] ?? [];
$zutaten_mengen = $_POST['zutaten_mengen'] ?? [];
$rezeptId = $_POST['id'] ?? null;

try {
    if ($rezeptId) {
        // Rezept aktualisieren
        $stmt = $pdo->prepare("UPDATE rezepte SET titel = ?, anleitung = ? WHERE id = ?");
        $stmt->execute([$titel, $anleitung, $rezeptId]);

        // Alte Kategorien löschen und neu einfügen
        $pdo->prepare("DELETE FROM rezept_kategorien WHERE rezept_id = ?")->execute([$rezeptId]);
        $pdo->prepare("DELETE FROM rezept_zutat WHERE rezept_id = ?")->execute([$rezeptId]);
    } else {
        // Neues Rezept einfügen
        $stmt = $pdo->prepare("INSERT INTO rezepte (titel, anleitung) VALUES (?, ?)");
        $stmt->execute([$titel, $anleitung]);
        $rezeptId = $pdo->lastInsertId();
    }

    // Kategorien einfügen
    if (!empty($kategorien)) {
        $stmt = $pdo->prepare("INSERT INTO rezept_kategorien (rezept_id, kategorie_id) VALUES (?, ?)");
        foreach ($kategorien as $katId) {
            $stmt->execute([$rezeptId, $katId]);
        }
    }

    // Zutaten einfügen
    if (!empty($zutaten_ids) && !empty($zutaten_mengen)) {
        $stmt = $pdo->prepare("INSERT INTO rezept_zutat (rezept_id, zutat_id, menge) VALUES (?, ?, ?)");
        foreach ($zutaten_ids as $index => $zutatId) {
            $menge = $zutaten_mengen[$index] ?? '';
            if (!empty($zutatId) && trim($menge) !== '') {
                $stmt->execute([$rezeptId, $zutatId, $menge]);
            }
        }
    }

    echo 'Rezept erfolgreich gespeichert.';
} catch (Exception $e) {
    echo 'Fehler beim Speichern des Rezepts: ' . $e->getMessage();
}
