// modal.js

function showModal(message) {
  const modal = document.getElementById("custom-modal");
  const modalMessage = document.getElementById("modal-message");
  modalMessage.textContent = message;
  modal.style.display = "block";
}

document.addEventListener("DOMContentLoaded", function() {
  const closeBtn = document.querySelector(".custom-modal-close");
  if (closeBtn) {
    closeBtn.addEventListener("click", function() {
      document.getElementById("custom-modal").style.display = "none";
    });
  }
  // Ferme le modal si l'utilisateur clique en dehors du contenu
  window.addEventListener("click", function(e) {
    const modal = document.getElementById("custom-modal");
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});
