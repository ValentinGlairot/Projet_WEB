document.addEventListener('DOMContentLoaded', function () {
  const offre = JSON.parse(localStorage.getItem("offre_actuelle"));

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

  // Gérer l'ajout à la wishlist
  const wishlistBtn = document.querySelector(".btn");
  if (wishlistBtn) {
    wishlistBtn.addEventListener("click", function () {
      let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

      const offreExiste = wishlist.some(o => o.titre === offre.titre);
      if (!offreExiste) {
        wishlist.push({
          titre: offre.titre,
          entreprise: offre.entreprise,
          remuneration: offre.remuneration,
          dateDebut: offre.dateDebut,
          dateFin: offre.dateFin
        });
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
        alert("Offre ajoutée à la wishlist !");
      } else {
        alert("Cette offre est déjà dans votre wishlist.");
      }
    });
  }

  // Gestion de la candidature
  const candidatureForm = document.querySelector("form");
  if (candidatureForm) {
    candidatureForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const nom = document.getElementById("nom").value.trim();
      const email = document.getElementById("email").value.trim();
      const cv = document.getElementById("cv").files[0];
      const lettre = document.getElementById("lettre").value.trim();

      if (!cv) {
        alert("Veuillez ajouter un fichier de CV.");
        return;
      }

      let candidatures = JSON.parse(localStorage.getItem("candidatures")) || [];

      // Enregistrer correctement la candidature
      candidatures.push({
        entreprise: offre.entreprise,
        titre: offre.titre,
        date: new Date().toISOString().split("T")[0], // Date actuelle
        lettreMotivation: lettre
      });

      localStorage.setItem("candidatures", JSON.stringify(candidatures));
      alert("Candidature envoyée avec succès !");
      window.location.href = "candidatures.html"; // Redirige vers la page de candidatures
    });
  }
});
