document.addEventListener("DOMContentLoaded", function () {
    const statusColors = {
        "Acceptée": "accepted",
        "En Attente": "pending",
        "Refusée": "refused"
    };

    // Charger les statuts depuis le localStorage
    function loadStatuses() {
        const savedStatuses = JSON.parse(localStorage.getItem("candidatures")) || {};
        document.querySelectorAll("tbody tr").forEach(row => {
            const title = row.cells[1].textContent.trim();
            const statusSpan = row.querySelector(".status");

            if (savedStatuses[title]) {
                statusSpan.textContent = savedStatuses[title];
                statusSpan.className = "status " + statusColors[savedStatuses[title]];
            }
        });
    }

    // Sauvegarder le statut sélectionné
    function saveStatus(title, status) {
        let savedStatuses = JSON.parse(localStorage.getItem("candidatures")) || {};
        savedStatuses[title] = status;
        localStorage.setItem("candidatures", JSON.stringify(savedStatuses));
    }

    document.querySelectorAll("tbody tr").forEach(row => {
        const statusSpan = row.querySelector(".status");
        const changeBtn = row.querySelector(".change-status");

        changeBtn.addEventListener("click", function () {
            // Supprime les anciens menus avant d'en afficher un nouveau
            document.querySelectorAll(".status-options").forEach(menu => menu.remove());

            const statusOptions = document.createElement("div");
            statusOptions.classList.add("status-options");

            Object.keys(statusColors).forEach(status => {
                const statusBtn = document.createElement("button");
                statusBtn.textContent = status;
                statusBtn.classList.add("status-btn", statusColors[status]);

                statusBtn.addEventListener("click", function () {
                    statusSpan.textContent = status;
                    statusSpan.className = "status " + statusColors[status];

                    const title = row.cells[1].textContent.trim();
                    saveStatus(title, status);

                    statusOptions.remove(); // Ferme le menu après sélection
                });

                statusOptions.appendChild(statusBtn);
            });

            row.cells[4].appendChild(statusOptions); // Ajoute le menu dans la cellule statut
        });
    });

    loadStatuses(); // Charge les statuts au démarrage

    // Fermer le menu de sélection si on clique ailleurs
    document.addEventListener("click", function (event) {
        if (!event.target.classList.contains("change-status") && !event.target.classList.contains("status-btn")) {
            document.querySelectorAll(".status-options").forEach(menu => menu.remove());
        }
    });
});

function setupStatusChange(row) {
    const statusSpan = row.querySelector(".status");
    const changeBtn = row.querySelector(".change-status");

    changeBtn.addEventListener("click", function () {
        document.querySelectorAll(".status-options").forEach(menu => menu.remove());

        const statusOptions = document.createElement("div");
        statusOptions.classList.add("status-options");

        const statuses = {
            "Acceptée": "accepted",
            "En Attente": "pending",
            "Refusée": "refused"
        };

        Object.keys(statuses).forEach(status => {
            const statusBtn = document.createElement("button");
            statusBtn.textContent = status;
            statusBtn.classList.add("status-btn", statuses[status]);

            statusBtn.addEventListener("click", function () {
                statusSpan.textContent = status;
                statusSpan.className = "status " + statuses[status];
                statusOptions.remove();
            });

            statusOptions.appendChild(statusBtn);
        });

        row.cells[4].appendChild(statusOptions);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.querySelector("tbody");
    const addButton = document.getElementById("add-candidature");

    function showNotification(message, type = "info") {
        const notification = document.createElement("div");
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    function showAddPopup() {
        let popup = document.createElement("div");
        popup.classList.add("popup-form");

        popup.innerHTML = `
                <h3>Ajouter une Candidature</h3>
                <input type="text" id="entreprise" placeholder="Nom de l'entreprise">
                <input type="text" id="offre" placeholder="Nom de l'offre">
                <input type="date" id="date">
                <input type="text" id="lettreMotivation" placeholder="Lettre de motivation (brève)">
                <div class="btn-container">
                    <button class="btn-confirm">Ajouter</button>
                    <button class="btn-cancel">Annuler</button>
                </div>
            `;

        document.body.appendChild(popup);
        popup.style.display = "block";

        popup.querySelector(".btn-confirm").addEventListener("click", function () {
            const entreprise = document.getElementById("entreprise").value.trim();
            const offre = document.getElementById("offre").value.trim();
            const date = document.getElementById("date").value;
            const lettreMotivation = document.getElementById("lettreMotivation").value.trim();

            if (!entreprise || !offre || !date || !lettreMotivation) {
                showNotification("⚠️ Veuillez remplir tous les champs.", "error");
                return;
            }

            const newRow = document.createElement("tr");
            newRow.innerHTML = `
                    <td>${entreprise}</td>
                    <td>${offre}</td>
                    <td>${date}</td>
                    <td>${lettreMotivation}</td>
                    <td>
                        <span class="status pending">En attente</span>
                        <button class="btn-action change-status">Changer</button>
                    </td>
                `;

            tableBody.appendChild(newRow);
            showNotification("✅ Candidature ajoutée avec succès !", "success");
            popup.remove();
        });

        popup.querySelector(".btn-cancel").addEventListener("click", function () {
            popup.remove();
        });
    }
    addButton.addEventListener("click", showAddPopup);
});

/** ================================
*  SUPPRESSION D'UNE CANDIDATURE
================================ */
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.querySelector("tbody");
    const deleteButton = document.getElementById("delete-candidature");
    let selectedRow = null;

    function showNotification(message, type = "info") {
        const notification = document.createElement("div");
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    function showConfirmationPopup() {
        let popup = document.createElement("div");
        popup.classList.add("popup-form");

        popup.innerHTML = `
            <h3>Voulez-vous vraiment supprimer cette candidature ?</h3>
            <div class="btn-container">
                <button class="btn-confirm">Oui</button>
                <button class="btn-cancel">Annuler</button>
            </div>
        `;

        document.body.appendChild(popup);
        popup.style.display = "block";

        popup.querySelector(".btn-confirm").addEventListener("click", function () {
            if (selectedRow) {
                selectedRow.remove();
                selectedRow = null;
                showNotification("✅ Candidature supprimée avec succès", "success");
            }
            popup.remove();
        });

        popup.querySelector(".btn-cancel").addEventListener("click", function () {
            popup.remove();
        });
    }

    deleteButton.addEventListener("click", function () {
        showNotification("🗑️ Cliquez sur une candidature pour la supprimer.", "info");

        tableBody.addEventListener(
            "click",
            function (event) {
                const row = event.target.closest("tr");
                if (row && row.parentNode === tableBody) {
                    selectedRow = row;
                    showConfirmationPopup();
                }
            },
            { once: true }
        );
    });
});




