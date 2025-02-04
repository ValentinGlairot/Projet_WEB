// ajout-offre.js

document.querySelector("form").addEventListener("submit", function(e) {
  e.preventDefault();
  showModal("Offre publiée avec succès !");
  this.reset();
});
