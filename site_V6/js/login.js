// public/js/login.js
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = form.querySelector('input[type="email"]').value;
      const password = form.querySelector('input[type="password"]').value;
      if (password.length < 8) {
        alert("Le mot de passe doit contenir au moins 8 caractères");
      } else {
        alert("Connexion réussie pour " + email);
        window.location.href = "index.html";
      }
    });
  }
});
