<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn / SignUp</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="LoginSignup.css">
</head>
<body>
        <div class="container">
            <div class="form-box login">
                <form action="Login.php" method="POST">
                    <?php
                    if (!empty($_GET['error'])) {
                        echo "<div class='error-message'>" . htmlspecialchars($_GET['error']) . "</div>";
                    }
                    ?>
                    <h1>Login</h1>
                    <div class="input-box">
                        <input type="text" placeholder="Username" name="username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input type="password" placeholder="Password" name="password" required>
                        <i class='bx bxs-lock-alt' ></i>
                    </div>
                    <div class="forgot-link">
                        <a href="">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn">Login</button>
                </form>
            </div>
            
            <div class="form-box register">
                <form action="register.php" method="POST">
                    <h1>Registration</h1>

                    <div class="input-box">
                        <input type="text" placeholder="First Name" name="fn" required>
                        <!--<i class='bx bxs-user'></i>-->
                    </div>

                    <div class="input-box">
                        <input type="text" placeholder="Last Name" name="ln" required>
                        <!--<i class='bx bxs-user'></i>-->
                    </div>

                    <div class="input-box">
                        <input type="text" placeholder="Username" name="username" required>
                        <i class='bx bxs-user'></i>
                    </div>

                    <div class="input-box">
                        <input type="email" placeholder="Email" name="email" required>
                        <i class='bx bxs-envelope'></i>
                    </div>

                    <div class="input-box">
                        <input type="password" placeholder="Password" name="passcode" required>
                        <i class='bx bxs-lock-alt'></i>
                    </div>

                    <button type="submit" class="btn">Register</button>
                </form>
            </div>

            <div class="toggle-box">
                <div class="toggle-panel toggle-left">
                    <h1>Hello, Welcome!</h1>
                    <p>Don't have an account?</p>
                    <button class="btn register-btn">Register</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Welcome Back!</h1>
                    <p>Already have an account?</p>
                    <button class="btn login-btn">Login</button>
                </div>
            </div>
        </div>


    <!-- Modal Structure -->
        <div id="modal" class="modal" style="display: none;">
            <div class="modal-content">
                <p id="modal-message"></p>
                <button onclick="closeModal()">OK</button>
            </div>
        </div>
<script src="LoginSignup.js"></script>

</body>
</html>


        <!--<div class="form-box register">
                <form action="">
                    <h1>Registration</h1>
                    <div class="input-box">
                        <input type="text" placeholder="Username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input type="email" placeholder="Email" required>
                        <i class='bx bxs-envelope' ></i>
                    </div>
                    <div class="input-box">
                        <input type="password" placeholder="Password" required>
                        <i class='bx bxs-lock-alt' ></i>
                    </div>
                    <button type="submit" class="btn">Register</button>
                </form>
            </div>-->