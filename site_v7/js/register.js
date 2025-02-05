document.addEventListener("DOMContentLoaded", function () {
  const registerForm = document.getElementById("register-form");

  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Récupérer les valeurs du formulaire
    const nom = document.getElementById("nom").value.trim();
    const prenom = document.getElementById("prenom").value.trim();
    const email = document.getElementById("email").value.trim();
    const role = document.getElementById("role").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;
    const errorMessage = document.getElementById("password-error");

    // Vérifier si les mots de passe correspondent
    if (password !== confirmPassword) {
      errorMessage.style.display = "block";
      return;
    } else {
      errorMessage.style.display = "none";
    }

    // Charger la liste des utilisateurs depuis localStorage
    let users = JSON.parse(localStorage.getItem("users")) || [];

    // Vérifier si l'email existe déjà
    const existingUser = users.find(user => user.email === email);
    if (existingUser) {
      alert("Cet email est déjà utilisé. Veuillez en choisir un autre.");
      return;
    }

    // Ajouter le nouvel utilisateur
    const newUser = { nom, prenom, email, role, password };
    users.push(newUser);
    localStorage.setItem("users", JSON.stringify(users));

    // Message de confirmation et redirection
    alert("Compte créé avec succès !");
    window.location.href = "index.html";
  });
});
