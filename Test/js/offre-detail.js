document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            if (document.querySelector(".form-notification")) {
                return;
            }

            const notification = document.createElement("div");
            notification.className = "form-notification";
            notification.textContent = "✅ Candidature envoyée avec succès !";
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
            setTimeout(() => {
                form.reset();
            }, 500);
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("cv");
    const fileContainer = document.getElementById("cv-container");

    function updateFileDisplay(file) {
        fileContainer.innerHTML = "";

        if (file) {
            fileInput.style.display = "none";

            const fileName = document.createElement("span");
            fileName.textContent = file.name;
            fileName.classList.add("file-name");

            // Création du bouton de suppression
            const removeBtn = document.createElement("button");
            removeBtn.textContent = "❌";
            removeBtn.classList.add("btn-remove");
            removeBtn.addEventListener("click", function () {
                fileInput.value = "";
                fileContainer.innerHTML = "";
                fileInput.style.display = "block";
            });

            fileContainer.appendChild(fileName);
            fileContainer.appendChild(removeBtn);
        }
    }

    fileInput.addEventListener("change", function (event) {
        if (event.target.files.length > 0) {
            updateFileDisplay(event.target.files[0]);
        }
    });
});

const wishlistButton = document.querySelector(".btn-add-to-wishlist");

if (wishlistButton) {
    wishlistButton.addEventListener("click", function () {
        const offerTitle = document.querySelector("h3").textContent.trim();

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
}




