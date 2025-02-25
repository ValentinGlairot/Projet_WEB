document.addEventListener('DOMContentLoaded', function() {

  // GESTION DU BOUTON MODIFIER
  document.querySelectorAll('.btn-modifier').forEach(button => {
    button.addEventListener('click', function() {
      const offreId = this.getAttribute("data-id");
      window.location.href = `modifier-offre.php?id=${offreId}`;
    });
  });

  // GESTION DU BOUTON SUPPRIMER
  document.querySelectorAll('.btn-supprimer').forEach(button => {
    button.addEventListener('click', function() {
      const offreId = this.getAttribute("data-id");

      if (confirm("Voulez-vous vraiment supprimer cette offre ?")) {
        fetch(`../api/gerer-offres.php?action=delete&id=${offreId}`, {
          method: "DELETE"
        })
        .then(response => response.json())
        .then(data => {
          alert(data.message);
          location.reload();
        })
        .catch(error => console.error("Erreur de suppression :", error));
      }
    });
  });

});
