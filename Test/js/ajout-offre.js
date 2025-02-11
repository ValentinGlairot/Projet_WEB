document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const titre = document.getElementById("titre").value.trim();
            const description = document.getElementById("description").value.trim();
            const ville = document.getElementById("ville").value.trim();

            if (!titre || !description || !ville) {
                showNotification("⚠️ Veuillez remplir tous les champs.", "error");
                return;
            }

            showNotification("✅ Offre publiée avec succès !", "success");
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
