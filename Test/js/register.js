document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const nom = document.getElementById("nom").value.trim();
            const prenom = document.getElementById("prenom").value.trim();
            const email = document.getElementById("email").value.trim();
            const role = document.getElementById("role").value.trim();
            const password = document.getElementById("password").value.trim();
            const confirmPassword = document.getElementById("confirm-password").value.trim();

            if (!nom || !prenom || !email || !role || !password || !confirmPassword) {
                showNotification("⚠️ Veuillez remplir tous les champs.", "error");
                return;
            }

            if (password !== confirmPassword) {
                showNotification("❌ Les mots de passe ne correspondent pas.", "error");
                return;
            }

            showNotification("✅ Compte créé avec succès !", "success");

            setTimeout(() => {
                window.location.href = "index.html";
            }, 2000);

            form.reset();
        });
    }
});


function showNotification(message, type = "error") {
    document.querySelectorAll(".notification").forEach(notification => notification.remove());

    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
