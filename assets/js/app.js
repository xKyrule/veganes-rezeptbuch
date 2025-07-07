document.addEventListener('DOMContentLoaded', () => {
  const recipeContainer = document.getElementById('recipeFormContainer');
  const zutatContainer = document.getElementById('zutatFormContainer');
  const btnAddRecipe = document.getElementById('btnAddRecipe');
  const btnAddZutat = document.getElementById('btnAddZutat');
  const searchContainer = document.getElementById('searchContainer');
  const inputSuche = document.getElementById('rezeptSuche');
  const ergebnisseContainer = document.getElementById('suchErgebnisse');
  const btnDraft = document.getElementById('btnDraft');

  //btnDraftEdit
  btnDraft.addEventListener('click', () => {
    fetch('ajax/search_recipes.php?q=entwurf')
      .then(res => res.text())
      .then(html => {
        ergebnisseContainer.innerHTML = html;

        // Buttons "Ansehen" und "Bearbeiten"
        ergebnisseContainer.querySelectorAll('.btn-view').forEach(btn => {
          btn.addEventListener('click', () => {
            loadForm(btn.dataset.id, false, btn.dataset.entwurf);
          });
        });
        ergebnisseContainer.querySelectorAll('.btn-edit').forEach(btn => {
          btn.addEventListener('click', () => {
            loadForm(btn.dataset.id, true, btn.dataset.entwurf);
        });
      });
    });

  });


  fetch('ajax/search_recipes.php?q=lade_alle_rezepte')
    .then(res => res.text())
    .then(html => {
      ergebnisseContainer.innerHTML = html;

      // Buttons "Ansehen" und "Bearbeiten"
      ergebnisseContainer.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => {
          loadForm(btn.dataset.id, false, btn.dataset.entwurf);
        });
      });
      ergebnisseContainer.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
          loadForm(btn.dataset.id, true, btn.dataset.entwurf);
      });
    });
  });

  function showRecipeForm() {
    if (searchContainer) searchContainer.style.display = 'none';
    if (recipeContainer) recipeContainer.style.display = 'block';
    if (zutatContainer) zutatContainer.style.display = 'none';
  }

  function showZutatForm() {
    if (searchContainer) searchContainer.style.display = 'none';
    if (recipeContainer) recipeContainer.style.display = 'none';
    if (zutatContainer) zutatContainer.style.display = 'block';
  }

  function showSearch() {
    if (searchContainer) searchContainer.style.display = 'block';
    if (recipeContainer) recipeContainer.style.display = 'none';
    if (zutatContainer) zutatContainer.style.display = 'none';
  }

  // Zutaten hinzufügen (für das Rezeptformular)
  function setupAddZutatButton() {
    const btnAddZutatForm = document.getElementById('btnAddZutatForm');
    if (!btnAddZutatForm) return;

    btnAddZutatForm.addEventListener('click', () => {
      const zutatenListe = document.getElementById('zutatenListe');
      if (!zutatenListe) return;

      // hole die Zutatenliste aus dem data-Attribut, um Optionen zu generieren
      const alleZutaten = JSON.parse(zutatenListe.dataset.allzutaten);

      const div = document.createElement('div');
      div.className = 'input-group mb-2';

      const select = document.createElement('select');
      select.name = 'zutaten_ids[]';
      select.className = 'form-select';
      select.required = true;

      alleZutaten.forEach(zutat => {
        const option = document.createElement('option');
        option.value = zutat.id;
        option.textContent = zutat.name;
        select.appendChild(option);
      });

      const inputMenge = document.createElement('input');
      inputMenge.type = 'text';
      inputMenge.name = 'zutaten_mengen[]';
      inputMenge.placeholder = 'Menge';
      inputMenge.className = 'form-control';
      inputMenge.required = true;

      const btnRemove = document.createElement('button');
      btnRemove.type = 'button';
      btnRemove.className = 'btn btn-danger btn-remove-zutat';
      btnRemove.textContent = '✖️';

      btnRemove.addEventListener('click', () => {
        div.remove();
      });

      div.appendChild(select);
      div.appendChild(inputMenge);
      div.appendChild(btnRemove);

      zutatenListe.appendChild(div);
    });
  }

  // Entfernen-Buttons (delegation)
  function setupRemoveZutatButtons() {
    document.addEventListener('click', e => {
      if (e.target.classList.contains('btn-remove-zutat')) {
        e.target.closest('.input-group').remove();
      }
    });
  }

  // Rezept-Formular Submit per AJAX
  function setupRecipeFormSubmit() {
    const form = document.getElementById('recipeForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      fetch('add_recipe.php', {
        method: 'POST',
        body: formData
      })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          form.reset();
          recipeContainer.innerHTML = '';
          showSearch();
        })
        .catch(() => {
          alert('Fehler beim Speichern des Rezepts.');
        });
    });
  }

  // Rezept-Formular laden (neu)
  btnAddRecipe.addEventListener('click', () => {
    fetch('./ajax/get_form_recipe.php')
      .then(res => res.text())
      .then(html => {
        recipeContainer.innerHTML = html;
        zutatContainer.innerHTML = '';
        setupAddZutatButton();
        setupRemoveZutatButtons();
        setupRecipeFormSubmit();

        const btnBack = document.createElement('button');
        btnBack.className = 'btn btn-outline-danger mt-2 btnBack';
        btnBack.textContent = '⬅️ Zurück';
        btnBack.onclick = () => {
          recipeContainer.innerHTML = '';
          showSearch();
        };
        recipeContainer.appendChild(btnBack);

        showRecipeForm();
      });
  });

    // Entwurf-Formular laden (neu)
  //   btnDraftEdit.addEventListener('click', () => {
  //     fetch('./ajax/get_form_recipe.php?draft=1')
  //       .then(res => res.text())
  //       .then(html => {
  //         recipeContainer.innerHTML = html;
  //         zutatContainer.innerHTML = '';
  //         setupAddZutatButton();
  //         setupRemoveZutatButtons();
  //         setupRecipeFormSubmit();

  //         const btnBack = document.createElement('button');
  //         btnBack.className = 'btn btn-outline-danger mt-2 btnBack';
  //         btnBack.textContent = '⬅️ Zurück';
  //         btnBack.onclick = () => {
  //           recipeContainer.innerHTML = '';
  //           showSearch();
  //         };
  //         recipeContainer.appendChild(btnBack);

  //         showRecipeForm();
  //     });
  // });

  // Zutaten-Formular laden (für neues Zutat hinzufügen)
  btnAddZutat.addEventListener('click', () => {
    fetch('./ajax/get_form_zutat.php')
      .then(res => res.text())
      .then(html => {
        zutatContainer.innerHTML = html;
        recipeContainer.innerHTML = '';

        // Form-Submit per AJAX einrichten (wie bei dir)
        const form = document.getElementById('formZutat');
        if (form) {
          form.addEventListener('submit', e => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('./add_zutat.php', {
              method: 'POST',
              body: formData,
            })
              .then(res => res.text())
              .then(msg => {
                alert(msg);
                form.reset();
                zutatContainer.innerHTML = '';
                showSearch();
                // Optional: Zutatenliste neu laden
              })
              .catch(err => alert('Fehler: ' + err));
          });
        }

        const btnBack = document.createElement('button');
        btnBack.className = 'btn btn-outline-danger mt-2 btnBack';
        btnBack.textContent = '⬅️ Zurück';
        btnBack.onclick = () => {
          zutatContainer.innerHTML = '';
          showSearch();
        };
        zutatContainer.appendChild(btnBack);

        showZutatForm();
      });
  });

  // Sucheingabe & Ergebnisse
  if (inputSuche && ergebnisseContainer) {
    inputSuche.addEventListener('keyup', () => {
      const query = inputSuche.value.trim();
      fetch('ajax/search_recipes.php?q=' + encodeURIComponent(query))
        .then(res => res.text())
        .then(html => {
          ergebnisseContainer.innerHTML = html;

          // Buttons "Ansehen" und "Bearbeiten"
          ergebnisseContainer.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', () => {
              loadForm(btn.dataset.id, false, btn.dataset.entwurf);
            });
          });
          ergebnisseContainer.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
              loadForm(btn.dataset.id, true, btn.dataset.entwurf);
            });
          });
        });
    });
  }

  // Rezept laden (View/Edit)
  function loadForm(id, editable = false, entwurf = false) {

    // alert(entwurf);
    fetch('ajax/get_form_recipe.php?id=' + id + '&edit=' + (editable ? 1 : 0) +'&entwurf='+entwurf)
      .then(res => res.text())
      .then(html => {
        recipeContainer.innerHTML = html;
        setupAddZutatButton();
        setupRemoveZutatButtons();
        setupRecipeFormSubmit();

        const btnBack = document.createElement('button');
        btnBack.className = 'btn btn-outline-danger mt-3 btnBack';
        btnBack.textContent = '⬅️ Zurück';
        btnBack.onclick = () => {
          recipeContainer.innerHTML = '';
          showSearch();
        };
        recipeContainer.appendChild(btnBack);

        showRecipeForm();
      });
  }

  // Init
  showSearch();
});
