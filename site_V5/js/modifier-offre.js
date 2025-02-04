// modifier-offre.js

document.querySelector("form").addEventListener("submit", function(e) {
  e.preventDefault();
  showModal("Offre modifiée avec succès !");
  this.reset();
});
