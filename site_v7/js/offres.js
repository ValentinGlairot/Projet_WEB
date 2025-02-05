document.addEventListener('DOMContentLoaded', function () {
  // 1. Initialisation du stockage local des offres
  if (!localStorage.getItem('offresData')) {
    const sampleOffres = {
      "offre1": { nbPostulants: 2 },
      "offre2": { nbPostulants: 5 },
      "offre3": { nbPostulants: 1 }
    };
    localStorage.setItem('offresData', JSON.stringify(sampleOffres));
  }

  let offresData = JSON.parse(localStorage.getItem('offresData')) || {};
  let offres = JSON.parse(localStorage.getItem("offres")) || [];
  const offresContainer = document.querySelector('.offers-container');

  // Vider l'affichage avant de charger les offres
  offresContainer.innerHTML = "";

  // Vérifier s'il y a des offres à afficher
  if (offres.length === 0) {
    offresContainer.innerHTML = "<p>Aucune offre disponible.</p>";
  } else {
    offres.forEach((offre, index) => {
      const offreId = `offre${index + 1}`;
      const nbPostulants = offresData[offreId] ? offresData[offreId].nbPostulants : 0;

      const offerCard = document.createElement("div");
      offerCard.classList.add("offer-card");
      offerCard.setAttribute("data-offre-id", offreId);

      offerCard.innerHTML = `
        <h4>${offre.titre}</h4>
        <p><strong>Entreprise :</strong> ${offre.entreprise}</p>
        <p><strong>Rémunération :</strong> ${offre.remuneration}</p>
        <p><strong>Début :</strong> ${offre.dateDebut} | <strong>Fin :</strong> ${offre.dateFin}</p>
        <p><strong>Nombre de postulants :</strong> <span class="nb-postulants">${nbPostulants}</span></p>
        <div class="offer-buttons">
          <a href="offre-detail.html" class="btn-voir" data-index="${index}">Voir</a>
          <button class="btn-add-wishlist" data-index="${index}">Ajouter à la wishlist</button>
        </div>
      `;

      offresContainer.appendChild(offerCard);
    });

    // Gestion du clic sur "Voir" pour afficher les détails de l'offre
    document.querySelectorAll(".btn-voir").forEach(btn => {
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        const index = this.getAttribute("data-index");
        localStorage.setItem("offre_actuelle", JSON.stringify(offres[index]));
        window.location.href = "offre-detail.html";
      });
    });

    // Gestion de l'ajout en wishlist
    document.querySelectorAll(".btn-add-wishlist").forEach(button => {
      button.addEventListener("click", function () {
        const index = this.getAttribute("data-index");
        const offre = offres[index];

        let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

        // Vérifier si l'offre est déjà dans la wishlist
        const exists = wishlist.some(item => item.titre === offre.titre);
        if (!exists) {
          wishlist.push(offre);
          localStorage.setItem("wishlist", JSON.stringify(wishlist));
          alert("Offre ajoutée à la wishlist !");
        } else {
          alert("Cette offre est déjà dans votre wishlist.");
        }
      });
    });
  }

  // 3. Mettre à jour le nombre de postulants affiché
  document.querySelectorAll(".offer-card").forEach(card => {
    const offreId = card.getAttribute("data-offre-id");
    const nbSpan = card.querySelector(".nb-postulants");
    if (offresData[offreId]) {
      nbSpan.textContent = offresData[offreId].nbPostulants;
    } else {
      nbSpan.textContent = "0";
    }
  });

  // 4. Gestion de la recherche par mot-clé
  document.querySelector(".search-form")?.addEventListener("submit", function (e) {
    e.preventDefault();
    const keyword = document.getElementById("motcle").value.trim().toLowerCase();
    const offers = document.querySelectorAll(".offer-card");

    offers.forEach(offer => {
      const title = offer.querySelector("h4").innerText.toLowerCase();
      offer.style.display = title.includes(keyword) ? "block" : "none";
    });
  });
});
