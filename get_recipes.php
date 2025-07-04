<?php
require_once 'inc/db.php';

$stmt = $pdo->query("
    SELECT r.id, r.titel, r.anleitung, k.name AS kategorie
    FROM rezepte r
    LEFT JOIN kategorien k ON r.kategorie_id = k.id
    ORDER BY r.id DESC
    LIMIT 10
");

$rezepte = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
  <div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <h2>Alle Rezepte</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Kategorie</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rezepte as $rezept): ?>
                    <tr>
                        <td><?= htmlspecialchars($rezept['titel']) ?></td>
                        <td><?= htmlspecialchars($rezept['kategorie']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-edit" data-id="<?= $rezept['id'] ?>">Bearbeiten</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
    </div>
  </div>
</div>


