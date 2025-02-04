// gestion-etudiants.js

document.querySelectorAll("form").forEach(form => {
  form.addEventListener("submit", function(e) {
    e.preventDefault();
    showModal("Action sur l'étudiant effectuée avec succès !");
    this.reset();
  });
});
