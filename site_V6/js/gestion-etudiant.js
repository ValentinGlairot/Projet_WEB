// public/js/gestion-etudiants.js
document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Action sur étudiant effectuée !");
      form.reset();
    });
  });
});
