// public/js/gerer-entreprise.js
document.addEventListener('DOMContentLoaded', function() {
  // Pour chaque formulaire de la page, on gère la soumission
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Action réalisée avec succès !");
      form.reset();
    });
  });
});
