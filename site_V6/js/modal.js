// public/js/modal.js
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('custom-modal');
  const closeBtn = document.querySelector('.custom-modal-close');

  if (modal && closeBtn) {
    // Ferme le modal en cliquant sur le bouton
    closeBtn.addEventListener('click', function() {
      modal.style.display = 'none';
    });

    // Ferme le modal si on clique en dehors de son contenu
    window.addEventListener('click', function(e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  }
});
