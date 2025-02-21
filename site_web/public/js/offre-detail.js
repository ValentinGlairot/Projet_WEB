document.addEventListener("DOMContentLoaded", function () {
  // --------------------------------------------------
  // Fonction utilitaire : Affichage des notifications
  // --------------------------------------------------
  function showNotification(message, type = "info") {
    // Supprime d'éventuelles notifications existantes
    document.querySelectorAll(".notification").forEach(n => n.remove());
    const notification = document.createElement("div");
    notification.className = "notification " + type;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }

  // --------------------------------------------------
  // Gestion de l'ajout à la wishlist
  // --------------------------------------------------
  // Pour la page offre-detail, le bouton possède la classe "add-to-wishlist" et un attribut "data-title"
  const wishlistButton = document.querySelector(".add-to-wishlist");
  if (wishlistButton) {
    wishlistButton.addEventListener("click", function () {
      const offerTitle = this.getAttribute("data-title").trim();
      if (!offerTitle) return;

      // Récupère la wishlist depuis le localStorage ou initialise un tableau vide
      let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

      if (!wishlist.includes(offerTitle)) {
        wishlist.push(offerTitle);
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
        showNotification("Ajouté à la wishlist !", "success");
        // Redirection après 1 seconde vers la page wishlist en surlignant l'offre ajoutée
        setTimeout(() => {
          window.location.href = `wishlist.php?highlight=${encodeURIComponent(offerTitle)}`;
        }, 1000);
      } else {
        showNotification("Cette offre est déjà dans votre wishlist.", "error");
      }
    });
  }

  // --------------------------------------------------
  // Gestion de l'affichage du fichier CV (si présent)
  // --------------------------------------------------
  const fileInput = document.getElementById("cv");
  const fileContainer = document.getElementById("cv-container");
  if (fileInput && fileContainer) {
    function updateFileDisplay(file) {
      // Réinitialise le conteneur
      fileContainer.innerHTML = "";
      if (file) {
        // Masque le champ fichier
        fileInput.style.display = "none";
        // Crée un élément pour afficher le nom du fichier
        const fileName = document.createElement("span");
        fileName.textContent = file.name;
        fileName.classList.add("file-name");
        // Crée le bouton de suppression
        const removeBtn = document.createElement("button");
        removeBtn.textContent = "❌";
        removeBtn.classList.add("btn-remove");
        removeBtn.addEventListener("click", function () {
          fileInput.value = "";             // Réinitialise le champ fichier
          fileContainer.innerHTML = "";       // Efface l'affichage
          fileInput.style.display = "block";  // Réaffiche le champ fichier
        });
        // Ajoute le nom du fichier et le bouton au conteneur
        fileContainer.appendChild(fileName);
        fileContainer.appendChild(removeBtn);
      }
    }
    fileInput.addEventListener("change", function (event) {
      if (event.target.files.length > 0) {
        updateFileDisplay(event.target.files[0]);
      }
    });
  }

  // --------------------------------------------------
  // Gestion de la soumission du formulaire "Postuler"
  // --------------------------------------------------
  const postulerForm = document.getElementById("postuler-form");
  if (postulerForm) {
    postulerForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(postulerForm);

      fetch("../api/postuler.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification("Candidature envoyée avec succès !", "success");
            // Redirection vers la page des candidatures après 1 seconde
            setTimeout(() => {
              window.location.href = "candidatures.php";
            }, 1000);
          } else {
            showNotification("Erreur : " + data.message, "error");
          }
        })
        .catch(error => {
          console.error("Erreur lors de la soumission :", error);
          showNotification("Erreur lors de la soumission", "error");
        });
    });
  }
});
