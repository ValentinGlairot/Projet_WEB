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

  // Récupération des formulaires
  const createForm = document.getElementById('create-entreprise-form');
  const updateForm = document.getElementById('update-entreprise-form');
  const deleteForm = document.getElementById('delete-entreprise-form');

  // CREER
  if (createForm) {
    createForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(createForm);
      fetch("../api/gerer-entreprise.php?action=create", {
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
  if (updateForm) {
    updateForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(updateForm);
      fetch("../api/gerer-entreprise.php?action=update", {
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
  if (deleteForm) {
    deleteForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(deleteForm);
      fetch("../api/gerer-entreprise.php?action=delete", {
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
