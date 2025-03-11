document.addEventListener("DOMContentLoaded", function () {
  function showNotification(message, type = "info") {
    document.querySelectorAll(".notification").forEach(n => n.remove());
    const notification = document.createElement("div");
    notification.className = "notification " + type;
    notification.textContent = message;
    notification.style.position = "fixed";
    notification.style.top = "130px";
    notification.style.left = "50%";
    notification.style.transform = "translateX(-50%)";
    notification.style.zIndex = "1000";
    document.body.appendChild(notification);
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }

  const searchForm = document.querySelector(".search-form");
  const offresContainer = document.querySelector(".offers-container");

  if (searchForm && offresContainer) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault();

      showNotification("🔍 Recherche d'offres en cours...");

      const keyword = document.getElementById("motcle").value.trim().toLowerCase();

      // Appel à l'API pour récupérer les offres correspondantes
      fetch(`../api/get_offres.php?search=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(offres => {
          offresContainer.innerHTML = "";

          if (offres.length === 0) {
            offresContainer.innerHTML = "<p style='text-align: center; color: #777;'>Aucune offre correspondante.</p>";
            return;
          }

          offres.forEach(offre => {
            const offerCard = document.createElement("div");
            offerCard.classList.add("offer-card");

            offerCard.innerHTML = `
                <h4>${offre.titre} - ${offre.entreprise}</h4>
                <p><strong>Rémunération :</strong> ${offre.remuneration}€</p>
                <div class="offer-buttons">
                  <a href="offre-detail.php?id=${offre.id}" class="btn-voir">Voir</a>
                  <button class="btn btn-add-wishlist" data-title="${offre.titre}" data-id="${offre.id}">Ajouter à la wishlist</button>
                </div>
              `;
            offresContainer.appendChild(offerCard);
          });
        })
        .catch(error => console.error("Erreur lors du chargement des offres :", error));
    });
  }
});