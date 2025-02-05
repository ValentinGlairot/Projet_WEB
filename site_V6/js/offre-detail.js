// public/js/offre-detail.js
document.addEventListener('DOMContentLoaded', function() {
  // Bouton "Ajouter à la wishlist"
  const wishlistBtn = document.querySelector('button.btn');
  if (wishlistBtn) {
    wishlistBtn.addEventListener('click', function() {
      alert("Offre ajoutée à la wishlist !");
    });
  }

  // Formulaire de candidature
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Candidature envoyée !");
      form.reset();
    });
  }
});
