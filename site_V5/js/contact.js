// contact.js

document.querySelector("form").addEventListener("submit", function(e) {
  e.preventDefault();
  showModal("Votre message a été envoyé !");
  this.reset();
});
