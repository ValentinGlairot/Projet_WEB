document.addEventListener("DOMContentLoaded", function () {
    fetch("../api/get_dashboard_data.php")
        .then(response => response.json())
        .then(data => {
            // Vérifier si les données sont correctes
            if (!data.success) {
                console.error("Erreur lors du chargement des statistiques.");
                return;
            }

            // Mise à jour des cartes avec les valeurs de la BDD
            document.querySelector(".card:nth-child(1) p").textContent = data.offres_creees || 0;
            document.querySelector(".card:nth-child(2) p").textContent = data.candidatures_recues || 0;
            document.querySelector(".card:nth-child(3) p").textContent = data.entreprises_partenaire || 0;

            // Mise à jour des statistiques des offres
            const statsContainer = document.querySelector(".statistics .dashboard-cards");
            statsContainer.innerHTML = `
                <div class="card">
                    <h4>Répartition par Compétences</h4>
                    <p>${data.stats_competences || "Aucune donnée"}</p>
                </div>
                <div class="card">
                    <h4>Durée des Stages</h4>
                    <p>${data.stats_duree || "Aucune donnée"}</p>
                </div>
                <div class="card">
                    <h4>Top Offres en Wishlist</h4>
                    <p>${data.stats_wishlist || "Aucune donnée"}</p>
                </div>
            `;
        })
        .catch(error => console.error("Erreur lors du chargement des données du tableau de bord :", error));
});
