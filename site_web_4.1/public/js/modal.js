// modal.js

document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('custom-modal');
  const modalMessage = document.getElementById('modal-message');
  const modalClose = document.querySelector('.custom-modal-close');

  // Fonction pour ouvrir la modal avec un message
  function openModal(message) {
    modalMessage.innerText = message;
    modal.style.display = 'block';
  }

  // Fonction pour fermer la modal
  function closeModal() {
    modal.style.display = 'none';
  }

  // Fermer la modal en cliquant sur le "X"
  modalClose.addEventListener('click', closeModal);

  // Fermer la modal si l'utilisateur clique en dehors du contenu
  window.addEventListener('click', function (event) {
    if (event.target === modal) {
      closeModal();
    }
  });

  window.openModal = openModal;
});
