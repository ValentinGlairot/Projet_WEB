document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('custom-modal');
  const closeBtn = document.querySelector('.custom-modal-close');

  if (modal && closeBtn) {
    closeBtn.addEventListener('click', function () {
      modal.style.display = 'none';
    });

    window.addEventListener('click', function (e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  }
});