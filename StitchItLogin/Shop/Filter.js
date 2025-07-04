document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".filter-buttons a");
  const products = document.querySelectorAll(".product");
  let activeCategory = "";   // empty = “no filter”

  buttons.forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();                 // stop the normal link jump
      const cat = btn.dataset.category;   // the category on this button

      // Toggle logic
      activeCategory = (activeCategory === cat) ? "" : cat;

      // Update button styles
      buttons.forEach(b =>
        b.classList.toggle("selected", activeCategory && b.dataset.category === activeCategory)
      );

      // Show / hide products
      products.forEach(prod => {
        const match = !activeCategory || prod.dataset.category === activeCategory;
        prod.style.display = match ? "" : "none";
      });
    });
  });
});