<?php
require_once '../inc/db.php';

$id = $_GET['id'] ?? null;
$edit = isset($_GET['edit']) && $_GET['edit'] == 1;
$entwurf = isset($_GET['entwurf']) && $_GET['entwurf'] == 1;
echo "TEST!";
echo "entwurf= ".$entwurf."<br>";

$rezept = [
  'titel' => '',
  'anleitung' => '',
];

$kategorienIds = [];
$zutaten = [];

if ($id) {

// Rezeptdaten laden
  $stmt = $pdo->prepare("SELECT * FROM rezepte WHERE id = ?");
  $stmt->execute([$id]);
  $rezept = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$rezept) {
      echo "<div class='alert alert-danger'>❌ Rezept nicht gefunden.</div>";
      exit;
  }

  // Zugehörige Kategorien laden
  $stmt = $pdo->prepare("SELECT kategorie_id FROM rezept_kategorien WHERE rezept_id = ?");
  $stmt->execute([$id]);
  $kategorienIds = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'kategorie_id');

  // Zutaten zum Rezept laden
  $stmt = $pdo->prepare("
      SELECT z.id, z.name, rz.menge
      FROM zutaten z
      JOIN rezept_zutat rz ON z.id = rz.zutat_id
      WHERE rz.rezept_id = ?
      ORDER BY z.name
  ");
  $stmt->execute([$id]);
  $zutaten = $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// Alle Kategorien laden
$alleKategorien = $pdo->query("SELECT id, name FROM kategorien ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Alle Zutaten laden
$alleZutaten = $pdo->query("SELECT id, name FROM zutaten ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$disabled = !$edit && $id ? "readonly" : "";
$selectDisabled = !$edit && $id ? "disabled" : "";
?>

<form class="border p-3 bg-light rounded" method="POST" action="add_recipe.php" id="recipeForm">
  <h5>
    <?php if (!$id): ?>
      📘 Neues Rezept anlegen
    <?php elseif ($entwurf): ?>
      📝 Entwurf bearbeiten
    <?php elseif ($edit): ?>
      📝 Rezept bearbeiten
    <?php else:?>
      👁️ Rezept ansehen
    <?php endif; ?>
  </h5>

  <div class="mb-3">
    <label class="form-label">Titel</label>
    <input type="text" class="form-control" name="titel"
           value="<?= htmlspecialchars($rezept['titel']) ?>" <?= $disabled ?> required>
  </div>

  <div class="mb-3">
    <label class="form-label">Kategorien</label>
    <div class="container px-0">
      <div class="row">
        <?php foreach ($alleKategorien as $kat): ?>
          <div class="col-auto">
            <div class="form-check">
              <input class="form-check-input" type="checkbox"
                     name="kategorien_ids[]"
                     value="<?= $kat['id'] ?>"
                     <?= in_array($kat['id'], $kategorienIds) ? 'checked' : '' ?>
                     <?= $selectDisabled ?>>
              <label class="form-check-label"><?= htmlspecialchars($kat['name']) ?></label>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

<div class="mb-3" id="zutatenListe" data-allzutaten='<?= json_encode($alleZutaten) ?>'>
  <label class="form-label">Zutaten</label>

  <?php if (count($zutaten) > 0): ?>
    <?php foreach ($zutaten as $z): ?>
      <div class="input-group mb-2">
        <select name="zutaten_ids[]" class="form-select" <?= $selectDisabled ?> required>
          <?php foreach ($alleZutaten as $az): ?>
            <option value="<?= $az['id'] ?>" <?= $az['id'] == $z['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($az['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="zutaten_mengen[]" class="form-control"
               placeholder="Menge" value="<?= htmlspecialchars($z['menge']) ?>" <?= $disabled ?> required>
        <?php if ($edit || !$id): ?>
          <button type="button" class="btn btn-danger btn-remove-zutat">✖️</button>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php elseif (!$id): ?>
    <div class="input-group mb-2">
      <select name="zutaten_ids[]" class="form-select" required>
        <?php foreach ($alleZutaten as $az): ?>
          <option value="<?= $az['id'] ?>"><?= htmlspecialchars($az['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text" name="zutaten_mengen[]" class="form-control" placeholder="Menge" required>
      <button type="button" class="btn btn-danger btn-remove-zutat">✖️</button>
    </div>
  <?php endif; ?>
</div>

<?php if ($edit || !$id): ?>
  <div class="mb-3">
    <button type="button" id="btnAddZutatForm" class="btn btn-outline-success btn-sm">
      ➕ Weitere Zutat
    </button>
  </div>
<?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Anleitung</label>
    <textarea class="form-control" name="anleitung" rows="4" <?= $disabled ?> required><?= htmlspecialchars($rezept['anleitung']) ?></textarea>
  </div>


  <?php

  if($entwurf == 1){

  }else{
    
  }

  ?>

  <?php if ($edit || !$id): ?>
    <button type="submit" class="btn btn-primary"><?= $id ? 'Speichern' : 'Anlegen' ?></button>
  <?php endif; ?>

  <!-- ENTWURF BUTTONS FEHLEN-->

  <input type="hidden" name="id" value="<?= $id ?>">

</form>

<style>
  select.form-select.error {
    border-color: red;
    box-shadow: 0 0 5px red;
  }
</style>

<script>
const form = document.getElementById('recipeForm');
const zutatenListe = document.getElementById('zutatenListe');

function checkDuplicateZutat(selectedSelect) {
    const allSelects = Array.from(form.querySelectorAll('select[name="zutaten_ids[]"]'));
    const selectedValues = allSelects
        .filter(s => s !== selectedSelect)
        .map(s => s.value);

    if (selectedValues.includes(selectedSelect.value)) {
        alert('Diese Zutat wurde bereits ausgewählt. Bitte wähle eine andere Zutat.');
        // Setze das aktuell ausgewählte Feld zurück
        selectedSelect.value = "";
        return true;
    }
    return false;
}

// Live-Prüfung beim Ändern einer Zutat
zutatenListe.addEventListener('change', function(e) {
    if (e.target.matches('select[name="zutaten_ids[]"]')) {
        checkDuplicateZutat(e.target);
    }
});

// Finale Prüfung beim Absenden
form.addEventListener('submit', function(e) {
    const allSelects = Array.from(form.querySelectorAll('select[name="zutaten_ids[]"]'));
    const seen = new Set();
    for (const select of allSelects) {
        if (seen.has(select.value)) {
            alert('Bitte keine doppelte Zutat auswählen.');
            e.preventDefault();
            return;
        }
        seen.add(select.value);
    }
});

</script>
