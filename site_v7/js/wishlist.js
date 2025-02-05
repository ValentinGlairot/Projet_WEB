document.addEventListener('DOMContentLoaded', function() {
  const wishlistList = document.querySelector('.wishlist-list');

  // Charger la wishlist depuis le LocalStorage ou initialiser un tableau vide
  let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
  console.log("Wishlist chargée :", wishlist);

  // Vider la liste affichée
  wishlistList.innerHTML = '';

  // Générer dynamiquement la liste des offres sauvegardées
  wishlist.forEach((offer, index) => {
    const li = document.createElement('li');
    li.innerHTML = `
      <strong>${offer.titre}</strong> - ${offer.entreprise} 
      <button class="btn btn-remove" data-index="${index}">Retirer de la wishlist</button>
    `;
    wishlistList.appendChild(li);
  });

  // Gestion du clic sur "Retirer de la wishlist"
  wishlistList.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove')) {
      const index = e.target.getAttribute("data-index");

      // Supprimer l'offre de la liste en utilisant son index
      wishlist.splice(index, 1);
      localStorage.setItem('wishlist', JSON.stringify(wishlist));

      // Mettre à jour l'affichage
      e.target.parentElement.remove();
      alert("Offre retirée de la wishlist !");
      console.log("Wishlist après suppression :", wishlist);
    }
  });
});
