document.addEventListener('DOMContentLoaded', function () {
  const loginBtn = document.getElementById("login-btn");
  const userMenu = document.getElementById("user-menu");

  // Vérifier la session
  fetch('/site_web/api/check-session.php')
      .then(response => response.json())
      .then(data => {
          console.log("Statut de connexion :", data); // Debug

          if (data.loggedIn) {
              // L'utilisateur est connecté
              userMenu.innerHTML = `
                  <div id="user-icon" class="dropdown">
                      <img src="/site_web/public/images/logo_deco.png" alt="Déconnexion" class="user-logo">
                      <div class="dropdown-menu">
                          <p>Bienvenue, <strong>${data.user.prenom}</strong></p>
                          <form action="/site_web/index.php?controller=utilisateur&action=logout" method="POST">
                              <button type="submit">Déconnexion</button>
                          </form>
                      </div>
                  </div>
              `;

              // Activer l'affichage du menu déroulant
              document.getElementById("user-icon").addEventListener("click", function () {
                  document.querySelector(".dropdown-menu").classList.toggle("show");
              });
          } else {
              // L'utilisateur n'est pas connecté
              userMenu.innerHTML = `<a href="/site_web/index.php?controller=utilisateur&action=connexion" id="login-btn" class="btn-login">Connexion</a>`;
          }
      })
      .catch(error => console.error("Erreur lors de la vérification de la session :", error));
});
