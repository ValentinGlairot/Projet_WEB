document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            showNotification("✅ Offre supprimée avec succès !", "success");

            setTimeout(() => {
                form.reset();
                window.location.href = "offres.html";
            }, 2000);
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
