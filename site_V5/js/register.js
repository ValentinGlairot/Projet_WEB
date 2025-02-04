// register.js

document.querySelector("form").addEventListener("submit", function(e) {
  e.preventDefault();
  showModal("Compte créé avec succès !");
  window.location.href = "index.html";
});
