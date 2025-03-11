document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("login-form");

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      if (!email || !password) {
        showNotification("⚠️ Veuillez remplir tous les champs.", "error");
        return;
      }
      if (password.length < 8) {
        showNotification("⚠️ Le mot de passe doit contenir au moins 8 caractères.", "error");
        return;
      }

      fetch("../api/traitement-login.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
        .then(response => response.text())
        .then(text => {
          let data;
          try {
            data = JSON.parse(text);
          } catch (error) {
            console.error("Erreur de parsing JSON :", error);
            showNotification("Erreur : Réponse invalide du serveur !", "error");
            return;
          }

          if (data.success) {
            showNotification("✅ Connexion réussie pour " + email, "success");
            setTimeout(() => {
              window.location.href = "dashboard.php";
            }, 2000);
          } else {
            showNotification("Erreur de connexion : " + data.message, "error");
          }
        })
        .catch(error => {
          console.error("Erreur AJAX :", error);
          showNotification("Une erreur est survenue lors de la connexion.", "error");
        });

      form.reset();
    });
  }
});

function showNotification(message, type = "info") {
  document.querySelectorAll(".notification").forEach(n => n.remove());
  const notification = document.createElement("div");
  notification.className = `notification ${type}`;
  notification.textContent = message;
  document.body.appendChild(notification);
  setTimeout(() => {
    notification.remove();
  }, 3000);
}
