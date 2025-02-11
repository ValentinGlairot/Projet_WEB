document.addEventListener("DOMContentLoaded", function () {
    const wishlistKey = "wishlist";
    let wishlist = JSON.parse(localStorage.getItem(wishlistKey)) || [];

    /** ============================
     *  ENREGISTRER / CHARGER LA WISHLIST
     ==============================*/
    function saveWishlist() {
        localStorage.setItem(wishlistKey, JSON.stringify(wishlist));
    }

    function isInWishlist(offerTitle) {
        return wishlist.includes(offerTitle);
    }

    /** ============================
     *  AFFICHER UNE NOTIFICATION
     ==============================*/
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


    /** ============================
     *  AJOUTER UNE OFFRE À LA WISHLIST
     ==============================*/
    function addToWishlist(offerTitle) {
        if (!isInWishlist(offerTitle)) {
            wishlist.push(offerTitle);
            saveWishlist();
            showNotification("Ajouté à la wishlist !", "success", true);

            // Redirection avec surlignage
            setTimeout(() => {
                window.location.href = `wishlist.html?highlight=${encodeURIComponent(offerTitle)}`;
            }, 1000);

            updateWishlistDisplay();
        } else {
            showNotification("⚠️ Cette offre est déjà dans votre wishlist.", "error");
        }
    }

    /** ============================
     *  SUPPRIMER UNE OFFRE DE LA WISHLIST
     ==============================*/
    function removeFromWishlist(offerTitle) {
        wishlist = wishlist.filter(item => item !== offerTitle);
        saveWishlist();
        updateWishlistDisplay();
        showNotification("ℹ️ Offre retirée de la wishlist.", "info");
    }

    /** ============================
     *  METTRE À JOUR L'AFFICHAGE DE LA WISHLIST
     ==============================*/
    function updateWishlistDisplay() {
        const wishlistList = document.getElementById("wishlist-list");
        let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

        if (wishlistList) {
            wishlistList.innerHTML = "";
            wishlist.forEach(offer => {
                const li = document.createElement("li");
                li.classList.add("wishlist-item");
                li.dataset.title = offer;
                const spanText = document.createElement("span");
                spanText.textContent = offer;

                const removeBtn = document.createElement("button");
                removeBtn.textContent = "Retirer";
                removeBtn.classList.add("btn-remove");
                removeBtn.addEventListener("click", function () {
                    removeFromWishlist(offer);
                });

                li.appendChild(spanText);
                li.appendChild(removeBtn);
                wishlistList.appendChild(li);
            });
        }
    }


    /** ============================
     *  AJOUTER UNE OFFRE VIA LES BOUTONS
     ==============================*/
    document.querySelectorAll(".add-to-wishlist").forEach(button => {
        button.addEventListener("click", function () {
            const offerTitle = this.getAttribute("data-title").trim();
            addToWishlist(offerTitle);
        });
    });

    // Pour les pages de détails d'offres
    const detailWishlistButton = document.querySelector(".btn");
    if (detailWishlistButton && detailWishlistButton.textContent.includes("Ajouter à la wishlist")) {
        detailWishlistButton.addEventListener("click", function () {
            const offerTitle = document.querySelector("h3").textContent.trim();
            addToWishlist(offerTitle);
        });
    }

    updateWishlistDisplay();

});

/** ============================
    *  SURBRILLANCE DE L'OFFRE AJOUTÉE
    ==============================*/

document.addEventListener("DOMContentLoaded", function () {
    const highlightedOffer = localStorage.getItem("highlightedOffer");

    if (highlightedOffer) {
        setTimeout(() => {
            const offerElements = document.querySelectorAll(".wishlist-item");

            document.querySelectorAll(".wishlist-item").forEach(item => {
                item.classList.remove("highlight-offer");
            });

            let found = false;
            offerElements.forEach(offer => {
                if (offer.dataset.title.trim() === highlightedOffer.trim()) {
                    offer.classList.add("highlight-offer");
                    found = true;

                    setTimeout(() => {
                        offer.classList.remove("highlight-offer");
                        localStorage.removeItem("highlightedOffer");
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }, 3000);
                }
            });

            if (!found) {
                window.history.replaceState({}, document.title, window.location.pathname);
                localStorage.removeItem("highlightedOffer");
            }
        }, 500);
    } else {
        document.querySelectorAll(".wishlist-item").forEach(item => {
            item.classList.remove("highlight-offer");
        });
    }
});
