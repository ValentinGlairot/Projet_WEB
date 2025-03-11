document.addEventListener("DOMContentLoaded", function () {
  // --- SECTION SUPPRESSION ---
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

  // --- FONCTIONNALITÉS COMMUNES ---

  /**
   * Affiche une notification personnalisée.
   *
   * @param {string} message 
   * @param {string} [type="info"] 
   * @param {boolean} [withButton=false] 
   */
  function showNotification(message, type = "info", withButton = false) {
    document.querySelectorAll(".notification").forEach(n => n.remove());

    const notification = document.createElement("div");
    notification.className = `notification ${type}`;

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
   * @param {string} offerTitle 
   */
  function addToWishlist(offerTitle) {
    // On envoie la requête d'ajout à l'API 
    fetch("../api/add_wishlist.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ offer_title: offerTitle }),
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification("Ajouté à la wishlist !", "success", true);

          // Mise à jour de l'affichage de la wishlist si l'élément existe dans le DOM
          if (wishlistList) {
            const li = document.createElement("li");
            li.textContent = offerTitle;
            li.classList.add("wishlist-item");
            li.dataset.title = offerTitle;

            const removeBtn = document.createElement("button");
            removeBtn.textContent = "Retirer";
            removeBtn.classList.add("btn-remove");
            // Utilisation d'un identifiant renvoyé par l'API 
            removeBtn.setAttribute("data-wishlist-id", data.wishlist_id || offerTitle);

            li.appendChild(removeBtn);
            wishlistList.appendChild(li);
          }

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

  document.querySelectorAll(".add-to-wishlist").forEach(button => {
    button.addEventListener("click", function () {
      const offerTitle = this.getAttribute("data-title").trim();
      addToWishlist(offerTitle);
    });
  });

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
