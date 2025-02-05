document.addEventListener('DOMContentLoaded', function() {
  const tableBody = document.getElementById('offres-table-body');

  // Charger les offres stockées
  let offres = JSON.parse(localStorage.getItem("offres")) || [];

  // Remplir le tableau avec les offres
  function afficherOffres() {
    tableBody.innerHTML = ""; // On vide le tableau avant de recharger les offres

    offres.forEach((offre, index) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${offre.titre}</td>
        <td>${offre.entreprise}</td>
        <td>${offre.remuneration}</td>
        <td>${offre.dateDebut}</td>
        <td>${offre.dateFin}</td>
        <td>
          <button class="btn-modifier" data-index="${index}">Modifier</button>
          <button class="btn-supprimer" data-index="${index}">Supprimer</button>
        </td>
      `;
      tableBody.appendChild(row);
    });

    // Gestion du clic sur "Modifier"
    document.querySelectorAll('.btn-modifier').forEach(btn => {
      btn.addEventListener('click', function() {
        const index = this.getAttribute("data-index");
        localStorage.setItem("offreAModifier", JSON.stringify(offres[index]));
        window.location.href = "modifier-offre.html";
      });
    });

    // Gestion du clic sur "Supprimer"
    document.querySelectorAll('.btn-supprimer').forEach(btn => {
      btn.addEventListener('click', function() {
        const index = this.getAttribute("data-index");

        // Confirmation avant suppression
        if (confirm("Voulez-vous vraiment supprimer cette offre ?")) {
          offres.splice(index, 1); // Supprime l'offre du tableau
          localStorage.setItem("offres", JSON.stringify(offres)); // Mettre à jour le localStorage
          afficherOffres(); // Rafraîchir l'affichage
        }
      });
    });
  }

  afficherOffres();
});
