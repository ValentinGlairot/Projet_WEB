// login.js

document.querySelector("form").addEventListener("submit", function(e) {
  e.preventDefault();
  showModal("Connexion réussie !");
  window.location.href = "index.html";
});
