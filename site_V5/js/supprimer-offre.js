// supprimer-offre.js

document.querySelector("form").addEventListener("submit", function(e) {
  e.preventDefault();
  showModal("Offre supprimée avec succès !");
  this.reset();
});
