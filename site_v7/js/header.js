document.addEventListener('DOMContentLoaded', function () {
  const loginBtn = document.getElementById("login-btn");
  const userIcon = document.getElementById("user-icon");
  const dropdownMenu = document.querySelector(".dropdown-menu");
  const logoutBtn = document.getElementById("logout-btn");

  // Vérifier si un utilisateur est connecté
  const currentUser = JSON.parse(localStorage.getItem("currentUser"));

  if (currentUser) {
    loginBtn.classList.add("hidden"); // Cacher le bouton "Connexion"
    userIcon.classList.remove("hidden"); // Afficher l'icône utilisateur
  } else {
    userIcon.classList.add("hidden"); // Cacher l'icône si pas connecté
  }

  // Afficher le menu lors du survol/clic
  userIcon.addEventListener("mouseenter", function () {
    dropdownMenu.style.display = "block";
  });

  // Cacher le menu uniquement si la souris quitte la zone
  userIcon.addEventListener("mouseleave", function (event) {
    setTimeout(() => {
      if (!dropdownMenu.matches(":hover") && !userIcon.matches(":hover")) {
        dropdownMenu.style.display = "none";
      }
    }, 200);
  });

  dropdownMenu.addEventListener("mouseleave", function () {
    dropdownMenu.style.display = "none";
  });

  // Déconnexion
  logoutBtn.addEventListener("click", function () {
    localStorage.removeItem("currentUser"); // Supprimer l'utilisateur connecté
    window.location.reload(); // Rafraîchir la page
  });
});
