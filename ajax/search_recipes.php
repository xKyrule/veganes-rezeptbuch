<?php
require_once '../inc/db.php';

$q = $_GET['q'] ?? '';

if($q == "entwurf"){
    // ORDER nach kategorie_id --> kategorie liste + priowahl muss noch erstellt werden dann soll nach Kategorien sortiert werden
    $stmt = $pdo->prepare("SELECT id, titel, kategorie_id, entwurf FROM rezepte WHERE entwurf ='1' ORDER BY kategorie_id");
    $stmt->execute();
    $rezepte = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rezepte) === 0) {
        echo '<div class="text-muted">Keine Entwürfe gefunden.</div>';
    } else {
        echo '<ul class="list-group">';
        foreach ($rezepte as $r) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<span>'.htmlspecialchars($r['titel']).'</span>';
            echo '<span class="badge bg-secondary">'.htmlspecialchars($r['kategorie_id']).'</span>';
            echo '<div>';
            echo '<button class="btn btn-sm btn-outline-primary btn-view" data-id="' . $r['id'] . '">Ansehen</button> ';
            echo '<button class="btn btn-sm btn-outline-secondary btn-edit" data-id="' . $r['id'] . '" data-entwurf="' . $r['entwurf'] . '">Bearbeiten</button>';
            echo '</div></li>';
        }
        echo '</ul>';
    }
}

elseif ($q == "lade_alle_rezepte" || strlen($q) == 0){
    // ORDER nach kategorie_id --> kategorie liste + priowahl muss noch erstellt werden dann soll nach Kategorien sortiert werden
    $stmt = $pdo->prepare("SELECT id, titel, kategorie_id, entwurf FROM rezepte ORDER BY kategorie_id ");
    $stmt->execute();
    $rezepte = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rezepte) === 0) {
        echo '<div class="text-muted">Keine Rezepte gefunden.</div>';
    } else {
        echo '<ul class="list-group">';
        foreach ($rezepte as $r) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<span>'.htmlspecialchars($r['titel']).'</span>';
            echo '<span class="badge bg-secondary">'.htmlspecialchars($r['kategorie_id']).'</span>';
            echo '<div>';
            echo '<button class="btn btn-sm btn-outline-primary btn-view" data-id="' . $r['id'] . '">Ansehen</button> ';
            echo '<button class="btn btn-sm btn-outline-secondary btn-edit" data-id="' . $r['id'] . '" data-entwurf="' . $r['entwurf'] . '">Bearbeiten</button>';



            echo '</div></li>';
        }
        echo '</ul>';
    }

}else{

    if (strlen($q) < 2) {
        echo '<div class="text-muted">Bitte mindestens 2 Zeichen eingeben.</div>';
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, titel, kategorie_id FROM rezepte WHERE titel LIKE ? ORDER BY titel");
    $stmt->execute(['%' . $q . '%']);
    $rezepte = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rezepte) === 0) {
        echo '<div class="text-muted">Keine Rezepte gefunden.</div>';
    } else {
        echo '<ul class="list-group">';
        foreach ($rezepte as $r) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<span>'.htmlspecialchars($r['titel']).'</span>';
            echo '<span class="badge bg-secondary">'.htmlspecialchars($r['kategorie_id']).'</span>';
            echo '<div>';
            echo '<button class="btn btn-sm btn-outline-primary btn-view" data-id="' . $r['id'] . '">Ansehen</button> ';
            echo '<button class="btn btn-sm btn-outline-secondary btn-edit" data-id="' . $r['id'] . '" data-entwurf="' . $r['entwurf'] . '">Bearbeiten</button>';

            echo '</div></li>';
        }
        echo '</ul>';
    }

}


