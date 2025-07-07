<?php
require_once 'templates/header.php';
require_once 'inc/db.php';
?>

<div class="container my-4">
  <div id="buttonContainer" class="mb-3">
    <button id="btnAddRecipe" class="btn btn-primary me-2">➕ Neues Rezept</button>
    <button id="btnAddZutat" class="btn btn-secondary">🧂 Neue Zutat</button>

    <?php
      $sql = "SELECT COUNT(id) AS anzahl FROM rezepte WHERE entwurf = '1'";
      $stmt = $pdo->query($sql);
      $ergebnis = $stmt->fetch(PDO::FETCH_ASSOC);

      $entwurf_anzahl = $ergebnis['anzahl'];

    ?>

    <button id="btnDraft" class="btn btn-warning">&#9998 Entwürfe 
      <?php echo "($entwurf_anzahl)"; ?>
    </button>
  </div>

  <div id="searchContainer" class="mb-4">
    <h4>🔍 Rezept-Suche</h4>
    <input type="text" id="rezeptSuche" class="form-control" placeholder="Rezeptname oder Kategorie...">
    <div id="suchErgebnisse" class="mt-3"></div>
  </div>

  <div id="recipeFormContainer" class="mb-4"></div>
  <div id="zutatFormContainer" class="mb-4"></div>
</div>

<?php require_once 'templates/footer.php'; ?>