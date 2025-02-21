document.addEventListener("DOMContentLoaded", function () {
  // --- SECTION SUPPRESSION (V7) ---
  // On part du principe que la liste affichée se trouve dans un conteneur avec la classe ".wishlist-list"
  const wishlistList = document.querySelector(".wishlist-list");
  if (wishlistList) {
    wishlistList.addEventListener("click", function (e) {
      if (e.target.classList.contains("btn-remove")) {
        const wishlistId = e.target.getAttribute("data-wishlist-id");

        fetch("../api/remove_wishlist.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ wishlist_id: wishlistId }),
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Retire l’élément du DOM
              e.target.parentElement.remove();
              // Affichage de la notification de suppression (style V6)
              showNotification("ℹ️ Offre retirée de la wishlist.", "info");
            } else {
              showNotification("Erreur : " + data.message, "error");
            }
          })
          .catch(error => {
            console.error("Erreur lors de la suppression :", error);
            showNotification("Erreur lors de la suppression", "error");
          });
      }
    });
  }

  // --- FONCTIONNALITÉS COMMUNES (V6) ---

  /**
   * Affiche une notification personnalisée.
   *
   * @param {string} message - Le message à afficher.
   * @param {string} [type="info"] - Le type de notification ("success", "error", "info", …).
   * @param {boolean} [withButton=false] - Si true, ajoute un bouton pour accéder à la wishlist.
   */
  function showNotification(message, type = "info", withButton = false) {
    // Supprime d'éventuelles notifications existantes
    document.querySelectorAll(".notification").forEach(n => n.remove());

    const notification = document.createElement("div");
    notification.className = `notification ${type}`;

    // Ajout d'une icône pour les notifications de succès
    if (type === "success") {
      notification.innerHTML = `✅ ${message}`;
    } else {
      notification.textContent = message;
    }

    if (withButton) {
      const button = document.createElement("button");
      button.classList.add("btn-view-wishlist");
      button.textContent = "Voir la wishlist";
      button.addEventListener("click", function () {
        window.location.href = "wishlist.html";
      });

      notification.appendChild(document.createElement("br"));
      notification.appendChild(button);
    }

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.remove();
    }, 3000);
  }

  /**
   * Ajoute une offre à la wishlist via l'API.
   *
   * @param {string} offerTitle - Le titre de l'offre à ajouter.
   */
  function addToWishlist(offerTitle) {
    // On envoie la requête d'ajout à l'API (à adapter selon votre back-end)
    fetch("../api/add_wishlist.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ offer_title: offerTitle }),
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification("Ajouté à la wishlist !", "success", true);

          // Optionnel : mise à jour de l'affichage de la wishlist si l'élément existe dans le DOM
          if (wishlistList) {
            const li = document.createElement("li");
            li.textContent = offerTitle;
            li.classList.add("wishlist-item");
            li.dataset.title = offerTitle;

            // Le bouton de suppression doit utiliser la même classe et l'attribut data pour déclencher le code de suppression
            const removeBtn = document.createElement("button");
            removeBtn.textContent = "Retirer";
            removeBtn.classList.add("btn-remove");
            // Utilisation d'un identifiant renvoyé par l'API (ou sinon on peut utiliser le titre)
            removeBtn.setAttribute("data-wishlist-id", data.wishlist_id || offerTitle);

            li.appendChild(removeBtn);
            wishlistList.appendChild(li);
          }

          // Redirection avec surlignage de l'offre ajoutée
          setTimeout(() => {
            window.location.href = `wishlist.html?highlight=${encodeURIComponent(offerTitle)}`;
          }, 1000);
        } else {
          showNotification("⚠️ " + data.message, "error");
        }
      })
      .catch(error => {
        console.error("Erreur lors de l'ajout :", error);
        showNotification("Erreur lors de l'ajout à la wishlist.", "error");
      });
  }

  // --- Ajout via les boutons "Ajouter à la wishlist" ---
  document.querySelectorAll(".add-to-wishlist").forEach(button => {
    button.addEventListener("click", function () {
      const offerTitle = this.getAttribute("data-title").trim();
      addToWishlist(offerTitle);
    });
  });

  // --- Cas spécifique pour la page de détails d'offre ---
  const detailWishlistButton = document.querySelector(".btn");
  if (
    detailWishlistButton &&
    detailWishlistButton.textContent.includes("Ajouter à la wishlist")
  ) {
    detailWishlistButton.addEventListener("click", function () {
      const offerTitle = document.querySelector("h3").textContent.trim();
      addToWishlist(offerTitle);
    });
  }

  // --- SURBRILLANCE DE L'OFFRE AJOUTÉE ---
  const urlParams = new URLSearchParams(window.location.search);
  const highlightedOffer = urlParams.get("highlight");

  if (highlightedOffer) {
    setTimeout(() => {
      const offerElements = document.querySelectorAll(".wishlist-item");
      let found = false;
      offerElements.forEach(offer => {
        if (offer.dataset.title.trim() === highlightedOffer.trim()) {
          offer.classList.add("highlight-offer");
          found = true;
          setTimeout(() => {
            offer.classList.remove("highlight-offer");
            if (found) {
              window.history.replaceState({}, document.title, window.location.pathname);
            }
          }, 3000);
        }
      });
      if (!found) {
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    }, 500);
  }
});
