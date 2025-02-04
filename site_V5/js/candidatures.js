// candidatures.js

document.querySelectorAll(".pagination a").forEach(link => {
  link.addEventListener("click", function(e) {
    e.preventDefault();
    showModal("Page " + this.textContent + " sélectionnée");
  });
});
