document.addEventListener("DOMContentLoaded", function () {
  const offersContainer = document.getElementById("offers-container");
  const noOffersMessage = document.getElementById("no-offers-message");

  // Charger les offres depuis le localStorage
  let offres = JSON.parse(localStorage.getItem("offres")) || [];

  // Vérifier si des offres existent
  if (offres.length === 0) {
    noOffersMessage.style.display = "block"; // Afficher le message si aucune offre n'existe
    return;
  }

  // Générer les cartes dynamiquement
  offres.forEach((offre) => {
    const offerCard = document.createElement("div");
    offerCard.classList.add("offer-card");

    offerCard.innerHTML = `
      <h4>${offre.titre} - ${offre.entreprise}</h4>
      <div class="offer-buttons">
        <a href="offre-detail.html?id=${offre.id}" class="btn-voir">Voir</a>
      </div>
    `;

    offersContainer.appendChild(offerCard);
  });
});
