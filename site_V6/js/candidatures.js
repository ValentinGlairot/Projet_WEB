// public/js/candidatures.js
document.addEventListener('DOMContentLoaded', function() {
  // Exemple de gestion simple de pagination
  const paginationLinks = document.querySelectorAll('.pagination a');
  paginationLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      alert("Page " + this.textContent + " sélectionnée");
    });
  });
});
