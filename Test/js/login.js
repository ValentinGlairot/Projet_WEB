document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

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

            showNotification("✅ Connexion réussie pour " + email, "success");

            setTimeout(() => {
                window.location.href = "index.html";
            }, 2000);

            form.reset();
        });
    }
});

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


