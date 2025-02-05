// public/js/entreprises.js
document.addEventListener('DOMContentLoaded', function() {
  const searchForm = document.querySelector('.search-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const input = searchForm.querySelector('input[type="text"]');
      alert("Recherche de l'entreprise: " + input.value);
    });
  }
});
