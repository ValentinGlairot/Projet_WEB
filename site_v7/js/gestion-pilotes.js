// public/js/gestion-pilotes.js

document.addEventListener('DOMContentLoaded', function() {
  // On simule un stockage local : { "Dupont": { nom:"Dupont", prenom:"Jean" } }
  if(!localStorage.getItem('pilotesData')) {
    const sample = {
      "Doe": { nom: "Doe", prenom: "John" },
      "Smith": { nom: "Smith", prenom: "Alice" }
    };
    localStorage.setItem('pilotesData', JSON.stringify(sample));
  }
  let pilotesData = JSON.parse(localStorage.getItem('pilotesData'));

  // RECHERCHER
  const searchForm = document.getElementById('search-pilote-form');
  if(searchForm) {
    searchForm.addEventListener('submit', function(e){
      e.preventDefault();
      const nom = document.getElementById('nom-pilote').value.trim();
      const prenom = document.getElementById('prenom-pilote').value.trim();
      let result = null;

      // On cherche une correspondance sur le nom
      // (Ici, c'est un exemple simple : on ne gère pas les collisions)
      if(nom && pilotesData[nom]) {
        // On vérifie le prénom
        if(!prenom || pilotesData[nom].prenom === prenom) {
          result = pilotesData[nom];
        }
      }

      // Affichage
      document.getElementById('res-pilote-nom').textContent = result ? result.nom : "(Introuvable)";
      document.getElementById('res-pilote-prenom').textContent = result ? result.prenom : "---";
    });
  }

  // CREER
  const createForm = document.getElementById('create-pilote-form');
  if(createForm){
    createForm.addEventListener('submit', function(e){
      e.preventDefault();
      const nom = document.getElementById('nom-create-pilote').value.trim();
      const prenom = document.getElementById('prenom-create-pilote').value.trim();

      pilotesData[nom] = { nom: nom, prenom: prenom };
      localStorage.setItem('pilotesData', JSON.stringify(pilotesData));

      alert("Pilote créé avec succès !");
      createForm.reset();
    });
  }

  // MODIFIER
  const updateForm = document.getElementById('update-pilote-form');
  if(updateForm){
    updateForm.addEventListener('submit', function(e){
      e.preventDefault();
      const id = document.getElementById('id-modif-pilote').value.trim();
      const newNom = document.getElementById('nom-modif-pilote').value.trim();
      const newPrenom = document.getElementById('prenom-modif-pilote').value.trim();

      if(!pilotesData[id]) {
        alert("Pilote introuvable : " + id);
        return;
      }

      // On modifie
      const oldData = pilotesData[id];
      if(newNom) {
        // rename
        delete pilotesData[id];
        pilotesData[newNom] = { nom: newNom, prenom: (newPrenom || oldData.prenom) };
      } else {
        // on reste sur id
        if(newPrenom) oldData.prenom = newPrenom;
        pilotesData[id] = oldData;
      }

      localStorage.setItem('pilotesData', JSON.stringify(pilotesData));
      alert("Pilote modifié !");
      updateForm.reset();
    });
  }

  // SUPPRIMER
  const deleteForm = document.getElementById('delete-pilote-form');
  if(deleteForm){
    deleteForm.addEventListener('submit', function(e){
      e.preventDefault();
      const id = document.getElementById('id-suppr-pilote').value.trim();
      if(!pilotesData[id]) {
        alert("Pilote introuvable : " + id);
        return;
      }
      delete pilotesData[id];
      localStorage.setItem('pilotesData', JSON.stringify(pilotesData));
      alert("Pilote supprimé !");
      deleteForm.reset();
    });
  }
});
