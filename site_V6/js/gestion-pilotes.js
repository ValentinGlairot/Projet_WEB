// public/js/gestion-pilotes.js
document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Action sur pilote effectuée !");
      form.reset();
    });
  });
});
