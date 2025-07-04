function openModal(base64Src) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = base64Src;
}
function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}