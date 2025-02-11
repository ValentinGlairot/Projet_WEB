document.querySelector(".search-form").addEventListener("submit", function (e) {
    e.preventDefault();

    document.querySelectorAll(".notification.info").forEach(notification => notification.remove());

    const notification = document.createElement("div");
    notification.className = "notification info";
    notification.textContent = "🔍 Recherche d'entreprise en cours...";

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
});