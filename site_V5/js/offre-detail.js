// offre-detail.js

// Bouton "Ajouter à la wishlist"
document.querySelector("button.btn").addEventListener("click", function(e) {
  e.preventDefault();
  showModal("Offre ajoutée à la wishlist !");
});

// Formulaire de candidature
document.querySelector("section.content form").addEventListener("submit", function(e) {
  e.preventDefault();
  showModal("Candidature envoyée avec succès !");
  this.reset();
});
