document.addEventListener('DOMContentLoaded', function () {
  function showNotification(message, type = "info") {
    document.querySelectorAll(".notification").forEach(notification => notification.remove());
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }

  // RECHERCHE
  const searchForm = document.getElementById('search-etudiant-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const email = document.getElementById('email-etudiant').value.trim();
      fetch(`../api/gestion-etudiants.php?action=search&email=${email}`)
        .then(response => response.json())
        .then(data => {
          document.getElementById('res-nom').textContent = data.nom || "Introuvable";
          document.getElementById('res-prenom').textContent = data.prenom || "-";
          document.getElementById('res-email').textContent = data.email || "-";
        })
        .catch(error => console.error("Erreur de recherche :", error));
    });
  }

  // CREER
  const createForm = document.getElementById('create-etudiant-form');
  if (createForm) {
    createForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(createForm);
      fetch("../api/gestion-etudiants.php?action=create", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          showNotification(data.message, data.success ? "success" : "error");
          if (data.success) {
            createForm.reset();
          }
        })
        .catch(error => {
          console.error("Erreur :", error);
          showNotification("Une erreur s'est produite.", "error");
        });
    });
  }

  // MODIFIER
  const updateForm = document.getElementById('update-etudiant-form');
  if (updateForm) {
    updateForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(updateForm);
      fetch("../api/gestion-etudiants.php?action=update", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          showNotification(data.message, data.success ? "success" : "error");
          if (data.success) {
            updateForm.reset();
          }
        })
        .catch(error => {
          console.error("Erreur :", error);
          showNotification("Une erreur s'est produite.", "error");
        });
    });
  }

  // SUPPRIMER
  const deleteForm = document.getElementById('delete-etudiant-form');
  if (deleteForm) {
    deleteForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(deleteForm);
      fetch("../api/gestion-etudiants.php?action=delete", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          showNotification(data.message, data.success ? "success" : "error");
          if (data.success) {
            deleteForm.reset();
          }
        })
        .catch(error => {
          console.error("Erreur :", error);
          showNotification("Une erreur s'est produite.", "error");
        });
    });
  }
});
