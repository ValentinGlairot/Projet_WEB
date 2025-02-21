document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById("contact-form");

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Récupération et vérification des champs obligatoires
            const nom = document.getElementById("nom").value.trim();
            const email = document.getElementById("email").value.trim();
            const message = document.getElementById("message").value.trim();

            if (!nom || !email || !message) {
                showNotification("⚠️ Veuillez remplir tous les champs.", "error");
                return;
            }

            showNotification("✅ Message envoyé avec succès !", "success");
            form.reset();
        });
    }
});

// Fonction utilitaire pour afficher une notification stylisée
function showNotification(message, type = "info") {
    // Supprime toutes les notifications existantes
    document.querySelectorAll(".notification").forEach(notification => notification.remove());

    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;

    // Pour les notifications d'erreur, on positionne le message en haut, centré, comme souhaité
    if (type === "error") {
        notification.style.position = "fixed";
        notification.style.top = "20px";
        notification.style.left = "50%";
        notification.style.transform = "translateX(-50%)";
        notification.style.zIndex = "1000";
    }

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
