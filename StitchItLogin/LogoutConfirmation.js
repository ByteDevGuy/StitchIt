function confirmLogout(event) {
    event.preventDefault(); // Prevent the default link behavior

    const confirmed = confirm("Are you sure you want to log out?");
    if (confirmed) {
      window.location.href = "LoginSignup/logout.php"; // Proceed to logout
    }
  }