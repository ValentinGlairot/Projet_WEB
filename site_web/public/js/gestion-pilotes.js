document.addEventListener('DOMContentLoaded', function() {
  // Fonction utilitaire pour afficher une notification stylisée
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

  // GESTION DU BOUTON MODIFIER
  document.querySelectorAll('.btn-modifier').forEach(button => {
    button.addEventListener('click', function() {
      const piloteId = this.getAttribute("data-id");
      window.location.href = `modifier-pilote.php?id=${piloteId}`;
    });
  });

  // GESTION DU BOUTON SUPPRIMER
  document.querySelectorAll('.btn-supprimer').forEach(button => {
    button.addEventListener('click', function() {
      const piloteId = this.getAttribute("data-id");
      if (confirm("Voulez-vous vraiment supprimer ce pilote ?")) {
        fetch(`../api/gestion-pilotes.php?action=delete&id=${piloteId}`, {
          method: "DELETE"
        })
        .then(response => response.json())
        .then(data => {
          showNotification(data.message, data.success ? "success" : "error");
          if (data.success) {
            setTimeout(() => {
              location.reload();
            }, 1000);
          }
        })
        .catch(error => {
          console.error("Erreur de suppression :", error);
          showNotification("Une erreur s'est produite.", "error");
        });
      }
    });
  });

  // GESTION DU FORMULAIRE DE CREATION
  const createPiloteForm = document.getElementById("create-pilote-form");
  if (createPiloteForm) {
    createPiloteForm.addEventListener("submit", function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch("../api/gestion-pilotes.php?action=create", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        showNotification(data.message, data.success ? "success" : "error");
        if (data.success) {
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      })
      .catch(error => {
        console.error("Erreur lors de la création :", error);
        showNotification("Une erreur s'est produite.", "error");
      });
    });
  }
});
