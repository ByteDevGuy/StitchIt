 function toggleSelectAll(master) {
    document.querySelectorAll('input[name="delete_indexes[]"]').forEach(cb => cb.checked = master.checked);
}
 
 // Toggle edit profile form visibility
function toggleEditForm() {
    const form = document.getElementById('editProfileForm');
    const editBtn = document.querySelector('.profile-left .btn');
            
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        editBtn.textContent = 'Cancel Edit';
    } else {
        form.style.display = 'none';
        editBtn.textContent = 'Edit Profile';
    }
}

// Select/Deselect all checkboxes
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('input[name="delete_indexes[]"]');
    checkboxes.forEach(cb => cb.checked = source.checked);
}

function openReviewModal(productId, productName, historyId) {
    document.getElementById("modal_product_id").value = productId;
    document.getElementById("modal_product_name").textContent = productName;
    document.getElementById("modal_history_id").value = historyId;
    document.getElementById("reviewModal").style.display = "flex";
}


function closeReviewModal() {
    document.getElementById("reviewModal").style.display = "none";
}

function showImageModal(src) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");

    modalImg.src = src;
    modal.style.display = "flex"; // Use flex for centering
}

function closeImageModal() {
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