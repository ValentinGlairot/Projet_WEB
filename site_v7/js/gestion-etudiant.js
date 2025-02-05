// public/js/gestion-etudiants.js

document.addEventListener('DOMContentLoaded', function() {
  // On stocke les étudiants de façon fictive :
  // { "etudiant1@example.com": { nom: "Doe", prenom: "John", email: "...", nbCandidatures: 2, nbWishlist: 3 } }
  
  if(!localStorage.getItem('etudiantsData')) {
    const sample = {
      "john.doe@example.com": {
        nom: "Doe",
        prenom: "John",
        email: "john.doe@example.com",
        nbCandidatures: 2,
        nbWishlist: 1
      },
      "jane.smith@example.com": {
        nom: "Smith",
        prenom: "Jane",
        email: "jane.smith@example.com",
        nbCandidatures: 0,
        nbWishlist: 2
      }
    };
    localStorage.setItem('etudiantsData', JSON.stringify(sample));
  }
  
  let dataEtudiants = JSON.parse(localStorage.getItem('etudiantsData'));

  // 1) RECHERCHER
  const searchForm = document.getElementById('search-etudiant-form');
  if(searchForm) {
    searchForm.addEventListener('submit', function(e){
      e.preventDefault();
      const nom  = document.getElementById('nom-etudiant').value.trim().toLowerCase();
      const prenom = document.getElementById('prenom-etudiant').value.trim().toLowerCase();
      const email  = document.getElementById('email-etudiant').value.trim().toLowerCase();

      // On parcourt dataEtudiants pour trouver un match (simplifié)
      let found = null;
      for(const key in dataEtudiants) {
        const etu = dataEtudiants[key];
        // Vérif simple : on match si la saisie n'est pas vide et correspond aux champs
        if(email && etu.email.toLowerCase() === email) {
          found = etu;
          break;
        } else if(nom && prenom) {
          if(etu.nom.toLowerCase() === nom && etu.prenom.toLowerCase() === prenom) {
            found = etu;
            break;
          }
        }
      }

      // Affichage
      document.getElementById('res-nom').textContent = found ? found.nom : "(introuvable)";
      document.getElementById('res-prenom').textContent = found ? found.prenom : "-";
      document.getElementById('res-email').textContent = found ? found.email : "-";
    });
  }

  // 2) CREER
  const createForm = document.getElementById('create-etudiant-form');
  if(createForm) {
    createForm.addEventListener('submit', function(e){
      e.preventDefault();
      const nom = document.getElementById('nom-create-etudiant').value.trim();
      const prenom = document.getElementById('prenom-create-etudiant').value.trim();
      const email = document.getElementById('email-create-etudiant').value.trim().toLowerCase();

      if(!email) {
        alert("Email requis.");
        return;
      }

      dataEtudiants[email] = {
        nom: nom,
        prenom: prenom,
        email: email,
        nbCandidatures: 0,
        nbWishlist: 0
      };
      localStorage.setItem('etudiantsData', JSON.stringify(dataEtudiants));

      alert("Étudiant créé avec succès !");
      createForm.reset();
    });
  }

  // 3) MODIFIER
  const updateForm = document.getElementById('update-etudiant-form');
  if(updateForm) {
    updateForm.addEventListener('submit', function(e){
      e.preventDefault();
      const id = document.getElementById('id-modif-etudiant').value.trim().toLowerCase();
      const newNom = document.getElementById('nom-modif-etudiant').value.trim();
      const newPrenom = document.getElementById('prenom-modif-etudiant').value.trim();
      const newEmail = document.getElementById('email-modif-etudiant').value.trim().toLowerCase();

      if(!dataEtudiants[id]) {
        alert("Étudiant introuvable avec l'id/email : " + id);
        return;
      }

      // On met à jour
      let etu = dataEtudiants[id];
      if(newNom) etu.nom = newNom;
      if(newPrenom) etu.prenom = newPrenom;

      // Si on change l'email => c'est un "rename" de la clé
      if(newEmail && newEmail !== id) {
        etu.email = newEmail;
        dataEtudiants[newEmail] = etu;
        delete dataEtudiants[id];
      }

      localStorage.setItem('etudiantsData', JSON.stringify(dataEtudiants));
      alert("Étudiant modifié avec succès !");
      updateForm.reset();
    });
  }

  // 4) SUPPRIMER
  const deleteForm = document.getElementById('delete-etudiant-form');
  if(deleteForm) {
    deleteForm.addEventListener('submit', function(e){
      e.preventDefault();
      const id = document.getElementById('id-suppr-etudiant').value.trim().toLowerCase();
      if(!dataEtudiants[id]) {
        alert("Étudiant introuvable : " + id);
        return;
      }
      delete dataEtudiants[id];
      localStorage.setItem('etudiantsData', JSON.stringify(dataEtudiants));
      alert("Étudiant supprimé !");
      deleteForm.reset();
    });
  }

  // 5) STATISTIQUES (SF21) => “état de la recherche de stage”
  const statForm = document.getElementById('stat-etudiant-form');
  if(statForm) {
    statForm.addEventListener('submit', function(e){
      e.preventDefault();
      const id = document.getElementById('id-stat-etudiant').value.trim().toLowerCase();
      const statNom = document.getElementById('stat-nom');
      const statPrenom = document.getElementById('stat-prenom');
      const statEmail = document.getElementById('stat-email');
      const statCandidatures = document.getElementById('stat-candidatures');
      const statWishlist = document.getElementById('stat-wishlist');

      if(dataEtudiants[id]) {
        const etu = dataEtudiants[id];
        statNom.textContent = etu.nom;
        statPrenom.textContent = etu.prenom;
        statEmail.textContent = etu.email;
        statCandidatures.textContent = etu.nbCandidatures || 0;
        statWishlist.textContent = etu.nbWishlist || 0;
      } else {
        statNom.textContent = "(inconnu)";
        statPrenom.textContent = "-";
        statEmail.textContent = "-";
        statCandidatures.textContent = "0";
        statWishlist.textContent = "0";
      }
    });
  }

});
