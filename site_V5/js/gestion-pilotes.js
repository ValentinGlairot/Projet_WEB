// gestion-pilotes.js

document.querySelectorAll("form").forEach(form => {
  form.addEventListener("submit", function(e) {
    e.preventDefault();
    showModal("Action sur le pilote effectuée avec succès !");
    this.reset();
  });
});
