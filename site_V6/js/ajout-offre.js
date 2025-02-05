// public/js/ajout-offre.js
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      // Ici, vous pouvez ajouter des validations spécifiques au formulaire.
      alert("Offre ajoutée avec succès !");
      form.reset();
      window.location.href = "offres.html";
    });
  }
});
