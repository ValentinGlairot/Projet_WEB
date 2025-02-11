document.querySelector(".search-form").addEventListener("submit", function (e) {
    e.preventDefault();

    document.querySelectorAll(".notification.info").forEach(notification => notification.remove());

    const notification = document.createElement("div");
    notification.className = "notification info";
    notification.textContent = "🔍 Recherche d'offres en cours...";

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
});

document.querySelectorAll(".add-to-wishlist").forEach(button => {
    button.addEventListener("click", function () {
        const offerTitle = this.getAttribute("data-title").trim();

        if (!offerTitle) return;

        let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

        if (!wishlist.includes(offerTitle)) {
            wishlist.push(offerTitle);
            localStorage.setItem("wishlist", JSON.stringify(wishlist));

            showNotification("Ajouté à la wishlist !", "success", true);

            setTimeout(() => {
                window.location.href = `wishlist.html?highlight=${encodeURIComponent(offerTitle)}`;
            }, 1000);
        } else {
            showNotification("Cette offre est déjà dans votre wishlist.", "error");
        }
    });
});

