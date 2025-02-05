// public/js/supprimer-offre.js
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Offre supprimée avec succès !");
      form.reset();
      window.location.href = "offres.html";
    });
  }
});
