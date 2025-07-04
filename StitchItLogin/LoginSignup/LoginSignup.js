const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});

document.querySelector("form[action='register.php']").addEventListener("submit", function(e) {
  e.preventDefault(); // prevent default form submission

  const form = e.target;
  const formData = new FormData(form);

  fetch("register.php", {
    method: "POST",
    body: formData,
  })
  .then(async res => {
    const text = await res.text();
    console.log("Raw response:", text); // Debug: see what PHP returned

    try {
      const data = JSON.parse(text); // Try to parse it as JSON

      if (data.status === "taken" || data.status === "error") {
        showModal(data.message);
      } else if (data.status === "success") {
        showModal("Registered successfully!");
        setTimeout(() => {
          window.location.href = "../LoginSignup/LoginSignup.php";
        }, 1500);
      }
    } catch (e) {
      showModal("Server returned an invalid response.");
      console.error("Failed to parse JSON:", e);
    }
  })
  .catch((err) => {
    console.error("Fetch error:", err);
    showModal("Something went wrong.");
  });
});



// Optional: hide error message after 5 seconds
setTimeout(() => {
    const url = new URL(window.location);
    url.searchParams.delete('error');
    window.history.replaceState({}, document.title, url);
}, 1000);

function showModal(message) {
  document.getElementById("modal-message").innerText = message;
  document.getElementById("modal").style.display = "flex";
}

function closeModal() {
  document.getElementById("modal").style.display = "none";
}

// Auto show modal if URL has a query
window.onload = function () {
  const params = new URLSearchParams(window.location.search);
  if (params.has('error')) {
    showModal(params.get('error'));
  } else if (params.has('success')) {
    showModal(params.get('success'));
  }
  
  // Optional: Remove query after 5 seconds
  setTimeout(() => {
    const url = new URL(window.location);
    url.searchParams.delete('error');
    url.searchParams.delete('success');
    window.history.replaceState({}, document.title, url);
  }, 2000);
};