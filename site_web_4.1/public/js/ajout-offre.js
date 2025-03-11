document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("add-offer-form");

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // Récupération des valeurs du formulaire
      const titre = document.getElementById("titre").value.trim();
      const description = document.getElementById("description").value.trim();
      const entreprise = document.getElementById("entreprise").value.trim();
      const remuneration = document.getElementById("remuneration").value.trim();
      const dateDebut = document.getElementById("date-debut").value;
      const dateFin = document.getElementById("date-fin").value;

      // Vérification des champs obligatoires
      if (!titre || !description || !entreprise || !remuneration || !dateDebut || !dateFin) {
        showNotification("⚠️ Veuillez remplir tous les champs.", "error");
        return;
      }

      // Récupération des offres existantes depuis le localStorage
      let offres = JSON.parse(localStorage.getItem("offres")) || [];

      // Création et stockage de la nouvelle offre
      const nouvelleOffre = { titre, description, entreprise, remuneration, dateDebut, dateFin };
      offres.push(nouvelleOffre);
      localStorage.setItem("offres", JSON.stringify(offres));

      showNotification("✅ Offre ajoutée avec succès !", "success");
      form.reset();

      setTimeout(() => {
        window.location.href = "offres.html";
      }, 2000);
    });
  }
});

function showNotification(message, type = "info") {
  document.querySelectorAll(".notification").forEach(n => n.remove());

  const notification = document.createElement("div");
  notification.className = `notification ${type}`;
  notification.textContent = message;

  if (type === "error") {
    notification.style.top = "20px";
  }

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.remove();
  }, 3000);
}
