// public/js/wishlist.js
document.addEventListener('DOMContentLoaded', function() {
  const buttons = document.querySelectorAll('.wishlist-list button');
  buttons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Offre retirée de la wishlist !");
      // Optionnel : retirer l’élément du DOM
      const listItem = this.parentElement;
      listItem.parentElement.removeChild(listItem);
    });
  });
});
