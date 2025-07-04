  // Get modal elements
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");
  const closeBtn = document.querySelector(".modal .close");

  // When any image is clicked
  document.querySelectorAll(".clickable-image").forEach(img => {
    img.addEventListener("click", function () {
      modal.style.display = "block";
      modalImg.src = this.src;
    });
  });

  // Close modal
  closeBtn.onclick = function () {
    modal.style.display = "none";
  };

  // Close modal when clicking outside the image
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };