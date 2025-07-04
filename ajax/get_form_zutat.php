<form id="formZutat" class="border p-3 bg-light rounded">
    <h5>🧂 Neue Zutat anlegen</h5>

    <div class="mb-3">
        <label class="form-label">Name der Zutat</label>
        <input type="text" name="zutat_name" class="form-control" required>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">Kalorien (kcal / 100g)</label>
            <input type="number" name="kcal" class="form-control" step="0.1" min="0">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fett (g / 100g)</label>
            <input type="number" name="fett" class="form-control" step="0.1" min="0">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Eiweiß (g / 100g)</label>
            <input type="number" name="eiweiss" class="form-control" step="0.1" min="0">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Kohlenhydrate (g / 100g)</label>
            <input type="number" name="kohlenhydrate" class="form-control" step="0.1" min="0">
        </div>
    </div>

    <p class="small text-muted">⚠️ Alle Angaben beziehen sich auf 100 g der Zutat.</p>

    <button type="submit" class="btn btn-success">Zutat speichern</button>
</form>

<script>
document.getElementById('formZutat').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('add_zutat.php', {
        method: 'POST',
        body: formData
    }).then(res => res.text()).then(msg => {
        alert(msg);
        this.reset();
    }).catch(err => alert("Fehler: " + err));
});
</script>
