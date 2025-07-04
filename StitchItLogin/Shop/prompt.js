document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // prevent normal submit

        const formData = new FormData(form);

        fetch('AddToCart.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            const modal = document.getElementById("cart-modal");
            const message = document.getElementById("modal-message");
            message.textContent = data.message;

            modal.style.display = "flex";
        })
        .catch(() => {
            alert("Failed to add to cart.");
        });
    });
});

// Close modal after 2 seconds
setInterval(() => {
    const modal = document.getElementById("cart-modal");
    if (modal.style.display === "flex") {
        modal.style.display = "none";
    }
}, 2000);