// public/js/contact.js
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      // Ajoutez ici la validation des champs si nécessaire
      alert("Votre message a été envoyé !");
      form.reset();
    });
  }
});
