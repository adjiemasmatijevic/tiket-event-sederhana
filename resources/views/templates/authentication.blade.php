<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="/assets/images/logo.webp" type="image/png">
    <title>@yield('app_name') - @yield('title')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/app-light.css" id="lightTheme">
</head>

<body class="bg-light">
    <a href="{{ route('home') }}" class="btn btn-secondary m-3" style="position: absolute; top: 0; left: 0;">
        <i class="fa-solid fa-house"></i> Back to Home
    </a>

    <div class="d-flex align-items-center justify-content-center min-vh-100">
        @yield('content')
    </div>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const passwordInput = document.querySelector("#password");
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener("click", function() {
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                this.querySelector("i").classList.toggle("fa-eye");
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });
        }

        const togglePasswordC = document.querySelector("#togglePasswordC");
        const passwordConfirmationInput = document.querySelector("#password_confirmation");
        if (togglePasswordC && passwordConfirmationInput) {
            togglePasswordC.addEventListener("click", function() {
                const type = passwordConfirmationInput.getAttribute("type") === "password" ? "text" : "password";
                passwordConfirmationInput.setAttribute("type", type);
                this.querySelector("i").classList.toggle("fa-eye");
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });
        }

        const togglePasswordR = document.querySelector("#togglePasswordR");
        const passwordResetInput = document.querySelector("#NewPassword");
        if (togglePasswordR && passwordResetInput) {
            togglePasswordR.addEventListener("click", function() {
                const type = passwordResetInput.getAttribute("type") === "password" ? "text" : "password";
                passwordResetInput.setAttribute("type", type);
                this.querySelector("i").classList.toggle("fa-eye");
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });
        }

        const togglePasswordRC = document.querySelector("#togglePasswordRC");
        const passwordResetConfirmationInput = document.querySelector("#ConfirmPassword");
        if (togglePasswordRC && passwordResetConfirmationInput) {
            togglePasswordRC.addEventListener("click", function() {
                const type = passwordResetConfirmationInput.getAttribute("type") === "password" ? "text" : "password";
                passwordResetConfirmationInput.setAttribute("type", type);
                this.querySelector("i").classList.toggle("fa-eye");
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });
        }
    </script>
</body>

</html>