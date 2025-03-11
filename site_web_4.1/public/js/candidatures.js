document.addEventListener("DOMContentLoaded", function () {
  // =====================================
  // Gestion des statuts des candidatures
  // =====================================
  const statusColors = {
    "Acceptée": "accepted",
    "En Attente": "pending",
    "Refusée": "refused"
  };

  // Charge les statuts sauvegardés dans le localStorage
  function loadStatuses() {
    const savedStatuses = JSON.parse(localStorage.getItem("candidatures")) || {};
    document.querySelectorAll("tbody tr").forEach(function (row) {
      // On utilise la 2ème cellule (index 1) comme clé, par exemple le nom de l'offre
      const key = row.cells[1].textContent.trim();
      const statusSpan = row.querySelector(".status");
      if (savedStatuses[key]) {
        statusSpan.textContent = savedStatuses[key];
        statusSpan.className = "status " + statusColors[savedStatuses[key]];
      }
    });
  }

  // Sauvegarde le statut d'une candidature dans le localStorage
  function saveStatus(key, status) {
    let savedStatuses = JSON.parse(localStorage.getItem("candidatures")) || {};
    savedStatuses[key] = status;
    localStorage.setItem("candidatures", JSON.stringify(savedStatuses));
  }

  // Ajoute l'écouteur pour le bouton "Changer" sur chaque ligne existante du tableau
  document.querySelectorAll("tbody tr").forEach(function (row) {
    const changeBtn = row.querySelector(".change-status");
    const statusSpan = row.querySelector(".status");
    if (changeBtn) {
      changeBtn.addEventListener("click", function (e) {
        document.querySelectorAll(".status-options").forEach(menu => menu.remove());

        // Crée le conteneur pour les options de statut
        const statusOptions = document.createElement("div");
        statusOptions.classList.add("status-options");

        Object.keys(statusColors).forEach(function (statusText) {
          const btn = document.createElement("button");
          btn.textContent = statusText;
          btn.classList.add("status-btn", statusColors[statusText]);
          btn.addEventListener("click", function () {
            statusSpan.textContent = statusText;
            statusSpan.className = "status " + statusColors[statusText];
            const key = row.cells[1].textContent.trim();
            saveStatus(key, statusText);
            statusOptions.remove();
          });
          statusOptions.appendChild(btn);
        });

        // Insère le menu dans la 5ème cellule (ou dans le parent du bouton si inexistant)
        if (row.cells[4]) {
          row.cells[4].appendChild(statusOptions);
        } else {
          changeBtn.parentElement.appendChild(statusOptions);
        }
        e.stopPropagation();
      });
    }
  });

  // Ferme le menu de statut si on clique en dehors
  document.addEventListener("click", function (e) {
    if (
      !e.target.classList.contains("change-status") &&
      !e.target.classList.contains("status-btn")
    ) {
      document.querySelectorAll(".status-options").forEach(menu => menu.remove());
    }
  });

  loadStatuses();

  // =====================================
  // Fonction utilitaire : Notifications
  // =====================================
  function showNotification(message, type = "info") {
    document.querySelectorAll(".notification").forEach(n => n.remove());
    const notification = document.createElement("div");
    notification.className = "notification " + type;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(function () {
      notification.remove();
    }, 3000);
  }

  // =====================================
  // Fonction utilitaire : Pop-up
  // =====================================
  function showPopup(contentHTML, onConfirm, onCancel) {
    const popup = document.createElement("div");
    popup.classList.add("popup-form");
    popup.innerHTML = contentHTML;
    document.body.appendChild(popup);
    popup.style.display = "block";

    const btnConfirm = popup.querySelector(".btn-confirm");
    const btnCancel = popup.querySelector(".btn-cancel");

    if (btnConfirm) {
      btnConfirm.addEventListener("click", function () {
        if (onConfirm) onConfirm(popup);
      });
    }
    if (btnCancel) {
      btnCancel.addEventListener("click", function () {
        if (onCancel) onCancel(popup);
        popup.remove();
      });
    }
  }

  // =====================================
  // Ajout d'une candidature via pop-up
  // =====================================
  const addButton = document.getElementById("add-candidature");
  const tableBody = document.querySelector("tbody");

  if (addButton && tableBody) {
    addButton.addEventListener("click", function () {
      const popupHTML = `
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
      showPopup(
        popupHTML,
        function (popup) {
          const entreprise = popup.querySelector("#entreprise").value.trim();
          const offre = popup.querySelector("#offre").value.trim();
          const date = popup.querySelector("#date").value;
          const lettre = popup.querySelector("#lettreMotivation").value.trim();

          if (!entreprise || !offre || !date || !lettre) {
            showNotification("⚠️ Veuillez remplir tous les champs.", "error");
            return;
          }

          // Création d'une nouvelle ligne dans le tableau
          const newRow = document.createElement("tr");
          newRow.innerHTML = `
              <td>${entreprise}</td>
              <td>${offre}</td>
              <td>${date}</td>
              <td>${lettre}</td>
              <td>
                <span class="status pending">En Attente</span>
                <button class="btn-action change-status">Changer</button>
              </td>
            `;
          tableBody.appendChild(newRow);
          showNotification("✅ Candidature ajoutée avec succès !", "success");
          popup.remove();

          // Ajoute l'écouteur sur le bouton "Changer" de la nouvelle ligne
          const changeBtn = newRow.querySelector(".change-status");
          const statusSpan = newRow.querySelector(".status");
          if (changeBtn) {
            changeBtn.addEventListener("click", function (e) {
              document.querySelectorAll(".status-options").forEach(menu => menu.remove());
              const statusOptions = document.createElement("div");
              statusOptions.classList.add("status-options");
              Object.keys(statusColors).forEach(function (statusText) {
                const btn = document.createElement("button");
                btn.textContent = statusText;
                btn.classList.add("status-btn", statusColors[statusText]);
                btn.addEventListener("click", function () {
                  statusSpan.textContent = statusText;
                  statusSpan.className = "status " + statusColors[statusText];
                  const key = newRow.cells[1].textContent.trim();
                  saveStatus(key, statusText);
                  statusOptions.remove();
                });
                statusOptions.appendChild(btn);
              });
              if (newRow.cells[4]) {
                newRow.cells[4].appendChild(statusOptions);
              } else {
                changeBtn.parentElement.appendChild(statusOptions);
              }
              e.stopPropagation();
            });
          }
        },
        function (popup) {
          popup.remove();
        }
      );
    });
  }

  // =====================================
  // Suppression d'une candidature
  // =====================================
  const deleteButton = document.getElementById("delete-candidature");
  if (deleteButton && tableBody) {
    deleteButton.addEventListener("click", function () {
      showNotification("🗑️ Cliquez sur une candidature pour la supprimer.", "info");

      tableBody.addEventListener(
        "click",
        function handler(e) {
          const row = e.target.closest("tr");
          if (row && row.parentNode === tableBody) {
            const popupHTML = `
                <h3>Voulez-vous vraiment supprimer cette candidature ?</h3>
                <div class="btn-container">
                  <button class="btn-confirm">Oui</button>
                  <button class="btn-cancel">Annuler</button>
                </div>
              `;
            showPopup(
              popupHTML,
              function (popup) {
                row.remove();
                showNotification("✅ Candidature supprimée avec succès", "success");
                popup.remove();
              },
              function (popup) {
                popup.remove();
              }
            );
            // On retire cet écouteur après le premier clic
            tableBody.removeEventListener("click", handler);
          }
        },
        { once: true }
      );
    });
  }
});
