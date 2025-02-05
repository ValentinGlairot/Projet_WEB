document.addEventListener('DOMContentLoaded', function () {
  const candidaturesTable = document.querySelector(".styled-table tbody");

  // Récupérer les candidatures enregistrées
  let candidatures = JSON.parse(localStorage.getItem("candidatures")) || [];

  // Vider la table avant d'afficher les données
  candidaturesTable.innerHTML = "";

  if (candidatures.length === 0) {
    candidaturesTable.innerHTML = "<tr><td colspan='5' style='text-align:center;'>Aucune candidature en cours</td></tr>";
    return;
  }

  // Générer dynamiquement chaque ligne du tableau
  candidatures.forEach(candidature => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${candidature.entreprise}</td>
      <td>${candidature.titre}</td>
      <td>${candidature.date}</td>
      <td>${candidature.lettreMotivation.substring(0, 30)}...</td>
      <td><span class="status pending">En attente</span></td>
    `;
    candidaturesTable.appendChild(row);
  });
});
