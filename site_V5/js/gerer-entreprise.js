// gerer-entreprise.js

document.querySelectorAll("form").forEach(form => {
  form.addEventListener("submit", function(e) {
    e.preventDefault();
    showModal("Action entreprise effectuée avec succès !");
    this.reset();
  });
});
