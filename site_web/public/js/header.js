document.addEventListener('DOMContentLoaded', function () {
  const loginBtn = document.getElementById("login-btn");
  const userIcon = document.getElementById("user-icon");
  const logoutBtn = document.getElementById("logout-btn");

  // Vérifier la session via PHP
  fetch('../api/check-session.php')
    .then(response => response.json())
    .then(data => {
      console.log("Statut de connexion :", data);

      if (data.logged_in) {
        if (loginBtn) loginBtn.style.display = "none";
        if (userIcon) userIcon.style.display = "block";
      } else {
        if (userIcon) userIcon.style.display = "none";
        if (loginBtn) loginBtn.style.display = "block";
      }
    })
    .catch(error => {
      console.error("Erreur lors de la vérification de la session :", error);
    });

  // Déconnexion
  if (logoutBtn) {
    logoutBtn.addEventListener("click", function () {
      fetch('../api/logout.php', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            window.location.reload();
          } else {
            console.error("Erreur lors de la déconnexion");
          }
        })
        .catch(error => {
          console.error("Erreur lors de la déconnexion :", error);
        });
    });
  }
});
