// wishlist.js

// Fonction pour charger la wishlist depuis le localStorage
function loadWishlist() {
  const wishlistContainer = document.querySelector("ul.wishlist-list");
  wishlistContainer.innerHTML = "";
  // Si la wishlist n'existe pas, on initialise avec des éléments par défaut
  let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [
    { id: "1", title: "Stage Développeur Web - Paris" },
    { id: "2", title: "Stage Marketing Digital - Lyon" }
  ];
  wishlist.forEach(item => {
    const li = document.createElement("li");
    li.setAttribute("data-id", item.id);
    li.textContent = item.title;
    const btn = document.createElement("button");
    btn.className = "btn";
    btn.textContent = "Retirer de la wishlist";
    btn.addEventListener("click", function(e) {
      e.preventDefault();
      removeWishlistItem(item.id);
      showModal("Offre retirée de la wishlist !");
    });
    li.appendChild(btn);
    wishlistContainer.appendChild(li);
  });
}

// Fonction pour supprimer un élément de la wishlist
function removeWishlistItem(id) {
  let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
  wishlist = wishlist.filter(item => item.id !== id);
  localStorage.setItem("wishlist", JSON.stringify(wishlist));
  loadWishlist();
}

// Au chargement de la page, on affiche la wishlist
document.addEventListener("DOMContentLoaded", function() {
  loadWishlist();
});
