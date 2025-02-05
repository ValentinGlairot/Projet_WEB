document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');

  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // Récupération des valeurs
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;

      // Récupérer la liste des utilisateurs depuis localStorage
      let users = JSON.parse(localStorage.getItem("users")) || [];

      // Vérifier si l'utilisateur existe
      const user = users.find(user => user.email === email && user.password === password);

      if (user) {
        // Enregistrer l'utilisateur connecté
        localStorage.setItem("currentUser", JSON.stringify(user));

        alert("Connexion réussie !");
        window.location.href = "dashboard.html";
      } else {
        alert("Email ou mot de passe incorrect.");
      }
    });
  }
});
