function openModal(base64Src) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = base64Src;
}
function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}

// Optional: Close modal when clicking outside the image box
window.addEventListener("click", function (event) {
    const modal = document.getElementById("imageModal");
    const imageBox = document.getElementById("modalImage");

    if (event.target === modal) {
        modal.style.display = "none";
    }
});