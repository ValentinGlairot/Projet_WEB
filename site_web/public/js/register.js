document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("register-form");

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // Récupération des valeurs et suppression des espaces inutiles
      const nom = document.getElementById("nom").value.trim();
      const prenom = document.getElementById("prenom").value.trim();
      const email = document.getElementById("email").value.trim();
      const role = document.getElementById("role").value.trim();
      const password = document.getElementById("password").value.trim();
      const confirmPassword = document.getElementById("confirm-password").value.trim();

      // Vérification que tous les champs sont remplis
      if (!nom || !prenom || !email || !role || !password || !confirmPassword) {
        showNotification("⚠️ Veuillez remplir tous les champs.", "error");
        return;
      }

      // Vérification de la correspondance des mots de passe
      if (password !== confirmPassword) {
        showNotification("❌ Les mots de passe ne correspondent pas.", "error");
        return;
      }

      const data = { nom, prenom, email, role, password };

      fetch("../api/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification("✅ Compte créé avec succès !", "success");
            setTimeout(() => {
              window.location.href = "login.php";
            }, 2000);
          } else {
            showNotification("Erreur : " + data.message, "error");
          }
        })
        .catch(error => {
          console.error("Erreur lors de l'inscription :", error);
          showNotification("Une erreur est survenue.", "error");
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

  if (type === "error") {
    notification.style.position = "fixed";
    notification.style.top = "120px";
    notification.style.left = "35%";
    notification.style.transform = "translateX(-50%)";
    notification.style.zIndex = "1000";
  }

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.remove();
  }, 3000);
}
