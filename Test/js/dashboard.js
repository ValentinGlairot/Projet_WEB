document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".dashboard-cards .card");

    cards.forEach(card => {
        const valueElement = card.querySelector("p");

        let value = parseInt(valueElement.textContent, 10);
        const cardTitle = card.querySelector("h3").textContent.trim();

        const excludedTitles = [
            "Répartition par Compétences",
            "Durée des Stages",
            "Top Offres en Wishlist"
        ];

        if (!isNaN(value) && !excludedTitles.includes(cardTitle)) {
            const controls = document.createElement("div");
            controls.classList.add("card-controls");

            // Bouton d'augmentation
            const plusButton = document.createElement("button");
            plusButton.textContent = "+";
            plusButton.classList.add("btn-control", "btn-plus");
            plusButton.addEventListener("click", function () {
                value++;
                valueElement.textContent = value;
            });

            // Bouton de diminution
            const minusButton = document.createElement("button");
            minusButton.textContent = "−";
            minusButton.classList.add("btn-control", "btn-minus");
            minusButton.addEventListener("click", function () {
                if (value > 0) { // Empêche d'aller sous 0
                    value--;
                    valueElement.textContent = value;
                }
            });

            // Ajout des boutons au conteneur
            controls.appendChild(minusButton);
            controls.appendChild(plusButton);
            card.appendChild(controls);
        }
    });
});