// public/js/gerer-entreprise.js
document.addEventListener('DOMContentLoaded', function() {
  // Initialiser localStorage entreprisesData si pas présent
  if(!localStorage.getItem('entreprisesData')) {
    localStorage.setItem('entreprisesData', JSON.stringify({}));
  }

  const createForm  = document.getElementById('create-entreprise-form');
  const updateForm  = document.getElementById('update-entreprise-form');
  const evalForm    = document.getElementById('eval-entreprise-form');
  const deleteForm  = document.getElementById('delete-entreprise-form');

  // CREER
  if(createForm) {
    createForm.addEventListener('submit', function(e) {
      e.preventDefault();
      let data = JSON.parse(localStorage.getItem('entreprisesData'));
      const nom = document.getElementById('nom-creation').value.trim();
      const desc = document.getElementById('desc-creation').value.trim();
      const email = document.getElementById('email-creation').value.trim();
      const tel = document.getElementById('tel-creation').value.trim();

      if(!nom) {
        alert("Nom d'entreprise requis.");
        return;
      }

      // On crée la fiche
      data[nom] = {
        desc: desc,
        email: email,
        tel: tel,
        nbStagiaires: 0,       // Par défaut 0
        evaluations: []        // Table de notes vide
      };

      localStorage.setItem('entreprisesData', JSON.stringify(data));
      alert("Entreprise créée avec succès !");
      createForm.reset();
    });
  }

  // MODIFIER
  if(updateForm) {
    updateForm.addEventListener('submit', function(e) {
      e.preventDefault();
      let data = JSON.parse(localStorage.getItem('entreprisesData'));

      const idModif = document.getElementById('id-modif').value.trim();
      const newNom  = document.getElementById('nom-modif').value.trim();
      const newDesc = document.getElementById('desc-modif').value.trim();
      const newEmail= document.getElementById('email-modif').value.trim();
      const newTel  = document.getElementById('tel-modif').value.trim();

      if(!data[idModif]) {
        alert("Entreprise introuvable : " + idModif);
        return;
      }

      // Si newNom existe, on fait un rename
      if(newNom) {
        // On clone l'ancienne entrée et supprime l'ancienne clé
        data[newNom] = data[idModif];
        delete data[idModif];

        // On continue la mise à jour sur data[newNom]
        if(newDesc)  data[newNom].desc  = newDesc;
        if(newEmail) data[newNom].email = newEmail;
        if(newTel)   data[newNom].tel   = newTel;
      } else {
        // On modifie juste
        if(newDesc)  data[idModif].desc  = newDesc;
        if(newEmail) data[idModif].email = newEmail;
        if(newTel)   data[idModif].tel   = newTel;
      }

      localStorage.setItem('entreprisesData', JSON.stringify(data));
      alert("Entreprise modifiée avec succès !");
      updateForm.reset();
    });
  }

  // EVALUER
  if(evalForm) {
    evalForm.addEventListener('submit', function(e) {
      e.preventDefault();
      let data = JSON.parse(localStorage.getItem('entreprisesData'));

      const idEval = document.getElementById('id-eval').value.trim();
      const note = parseInt(document.getElementById('note-eval').value, 10);

      if(!data[idEval]) {
        alert("Entreprise introuvable : " + idEval);
        return;
      }
      if(note < 1 || note > 5) {
        alert("La note doit être entre 1 et 5.");
        return;
      }

      data[idEval].evaluations.push(note);
      localStorage.setItem('entreprisesData', JSON.stringify(data));

      alert("Évaluation enregistrée pour " + idEval + " !");
      evalForm.reset();
    });
  }

  // SUPPRIMER
  if(deleteForm) {
    deleteForm.addEventListener('submit', function(e) {
      e.preventDefault();
      let data = JSON.parse(localStorage.getItem('entreprisesData'));

      const idSuppr = document.getElementById('id-suppr').value.trim();
      if(!data[idSuppr]) {
        alert("Entreprise introuvable : " + idSuppr);
        return;
      }

      delete data[idSuppr];
      localStorage.setItem('entreprisesData', JSON.stringify(data));

      alert("Entreprise supprimée avec succès !");
      deleteForm.reset();
    });
  }

});
