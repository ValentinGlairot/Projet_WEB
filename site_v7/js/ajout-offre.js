document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // Récupérer les valeurs du formulaire
      const titre = document.getElementById("titre").value.trim();
      const description = document.getElementById("description").value.trim();
      const entreprise = document.getElementById("entreprise").value.trim();
      const remuneration = document.getElementById("remuneration").value.trim();
      const dateDebut = document.getElementById("date-debut").value;
      const dateFin = document.getElementById("date-fin").value;

      // Vérification simple
      if (!titre || !description || !entreprise || !remuneration || !dateDebut || !dateFin) {
        alert("Veuillez remplir tous les champs.");
        return;
      }

      // Récupérer les offres stockées
      let offres = JSON.parse(localStorage.getItem("offres")) || [];

      // Ajouter la nouvelle offre
      const nouvelleOffre = { titre, description, entreprise, remuneration, dateDebut, dateFin };
      offres.push(nouvelleOffre);
      localStorage.setItem("offres", JSON.stringify(offres));

      alert("Offre ajoutée avec succès !");
      form.reset();
      window.location.href = "offres.html"; // Rediriger vers la page des offres
    });
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const offre = JSON.parse(localStorage.getItem("offreSelectionnee"));

  if (!offre) {
    document.querySelector('.content').innerHTML = "<p>Offre introuvable.</p>";
    return;
  }

  document.getElementById('offre-titre').textContent = offre.titre;
  document.getElementById('offre-entreprise').textContent = offre.entreprise;
  document.getElementById('offre-description').textContent = offre.description;
  document.getElementById('offre-remuneration').textContent = offre.remuneration;
  document.getElementById('offre-date-debut').textContent = offre.dateDebut;
  document.getElementById('offre-date-fin').textContent = offre.dateFin;
});
